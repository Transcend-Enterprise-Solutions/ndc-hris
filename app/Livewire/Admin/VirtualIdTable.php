<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\IdSignatory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\OfficeDivisions;
use App\Models\Positions;
use App\Models\ESignature;
use Livewire\WithFileUploads;

class VirtualIdTable extends Component
{
    use WithFileUploads;

    public $showSignatoryModal = false;
    public $signatoryName;
    public $signatoryPositionId;
    public $signatoryOfficeDivisionId;
    public $tempSignatureUrl;
    public $signatorySignature;
    
    public $officeDivisions = [];
    public $positions = [];
    
    public $employees = [];
    public $selectedEmployeeId = null;
    public $idType = 'virtual';
    public $showDropdown = false;
    public $searchTerm = '';
    public $showEmployeeDropdown = false;
    
    // Employee information fields
    public $name;
    public $emp_code;
    public $office_or_department;
    public $profilePhotoUrl;
    public $eSignatureUrl;
    public $emergencyContactName;
    public $emergencyContactNumber;
    
    // Signatory information
    public $signatoryPosition;
    public $signatoryOfficeDivision;
    public $signatorySignatureUrl;
    
    public $qrCodeData = '';
    public $defaultSignatory;

    public function mount()
    {
        $this->officeDivisions = OfficeDivisions::all();
        $this->positions = Positions::all();
        $this->loadDefaultSignatory();
        $this->loadEmployees();
    }

    protected function loadDefaultSignatory()
    {
        $this->defaultSignatory = IdSignatory::where('is_default', true)->first();
        
        if ($this->defaultSignatory) {
            $this->signatoryName = $this->defaultSignatory->name;
            $this->signatoryPositionId = $this->defaultSignatory->position_id;
            $this->signatoryOfficeDivisionId = $this->defaultSignatory->office_division_id;
            
            $this->tempSignatureUrl = $this->defaultSignatory->signature_path 
                ? route('signatory-signature.file', ['filename' => $this->defaultSignatory->signature_path])
                : null;
                
            // Load position and office division names
            $this->signatoryPosition = Positions::find($this->signatoryPositionId)?->position;
            $this->signatoryOfficeDivision = OfficeDivisions::find($this->signatoryOfficeDivisionId)?->office_division;
        }
    }

    public function loadEmployees()
    {
        $this->employees = User::with(['userData', 'officeDivision'])
            ->where('user_role', 'emp')
            ->when($this->searchTerm, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->searchTerm.'%')
                      ->orWhere('emp_code', 'like', '%'.$this->searchTerm.'%');
                });
            })
            ->orderBy('name')
            ->get();
    }

    public function updatedSearchTerm()
    {
        $this->loadEmployees();
    }

    public function selectEmployee($employeeId)
    {
        $this->selectedEmployeeId = $employeeId;
        $this->searchTerm = '';
        $this->showEmployeeDropdown = false;
        $this->updateEmployeeData();
    }

    public function switchIdType()
    {
        $this->idType = $this->idType === 'virtual' ? 'arta' : 'virtual';
        $this->updateEmployeeData();
    }
    
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }
    
    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    protected function updateEmployeeData()
    {
        if (!$this->selectedEmployeeId) return;

        $user = User::with('userData')->find($this->selectedEmployeeId);
        if (!$user) return;

        $userData = $user->userData;
        
        $formattedName = strtoupper(
            ($userData->first_name ?? '') . ' ' .
            (isset($userData->middle_name) ? substr($userData->middle_name, 0, 1) . '.' : '') . ' ' .
            ($userData->surname ?? '')
        );

        // Get office/department
        $officeDivision = OfficeDivisions::find($user->office_division_id);
        $this->office_or_department = $officeDivision ? $officeDivision->office_division : 'N/A';

        // Format employee code
        $this->emp_code = $this->formatEmpCode($user->emp_code);

        // Get profile photo and signature
        $eSignature = ESignature::where('user_id', $user->id)->first();
        $this->profilePhotoUrl = $eSignature?->profile_photo_path 
            ? route('profile-photo.file', ['filename' => $eSignature->profile_photo_path])
            : null;
        $this->eSignatureUrl = $eSignature?->file_path 
            ? route('signature.file', ['filename' => $eSignature->file_path])
            : null;
        
        $this->emergencyContactName = $eSignature?->emergency_contact_name ?? 'N/A';
        $this->emergencyContactNumber = $eSignature?->emergency_contact_number ?? 'N/A';

        // Set name and prepare QR code data
        $this->name = $formattedName;
        $this->prepareQrCodeData();
    }

    private function formatEmpCode($empCode)
    {
        if (strlen($empCode) >= 8) {
            return substr($empCode, 0, 4) . '-' . substr($empCode, 4, 4);
        }
        return $empCode;
    }

    private function prepareQrCodeData()
    {
        $this->qrCodeData = 'https://www.ndc.gov.ph/';
    }
    
    public function getQrCodeHtml()
    {
        if (empty($this->qrCodeData)) {
            return '';
        }
        
        return QrCode::size(100)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->margin(2)
            ->generate($this->qrCodeData);
    }

    public function openSignatoryModal()
    {
        $this->showSignatoryModal = true;
    }

    public function updatedSignatorySignature()
    {
        $this->validate([
            'signatorySignature' => 'image|max:2048',
        ]);
        
        $this->tempSignatureUrl = $this->signatorySignature->temporaryUrl();
    }

public function saveSignatoryDetails()
{
    $this->validate([
        'signatoryName' => 'required|string|max:255',
        'signatoryPositionId' => 'required|exists:positions,id',
        'signatoryOfficeDivisionId' => 'required|exists:office_divisions,id',
        'signatorySignature' => 'nullable|image|max:2048',
    ]);

    try {
        // Debug: Log input values
        \Log::info('Attempting to save signatory:', [
            'name' => $this->signatoryName,
            'position_id' => $this->signatoryPositionId,
            'office_division_id' => $this->signatoryOfficeDivisionId,
            'has_signature' => !empty($this->signatorySignature)
        ]);

        // First, unset any existing default signatory
        IdSignatory::where('is_default', true)->update(['is_default' => false]);
        
        $signatoryData = [
            'name' => $this->signatoryName,
            'position_id' => $this->signatoryPositionId,
            'office_division_id' => $this->signatoryOfficeDivisionId,
            'is_default' => true,
        ];

        // Handle signature upload
        if ($this->signatorySignature) {
            // Delete old signature if exists
            if ($this->defaultSignatory && $this->defaultSignatory->signature_path) {
                Storage::delete('public/signatory-signatures/' . $this->defaultSignatory->signature_path);
            }

            $filename = 'signatory_' . time() . '.' . $this->signatorySignature->extension();
            $path = $this->signatorySignature->storeAs('public/signatory-signatures', $filename);
            $signatoryData['signature_path'] = $filename;
            
            \Log::info('Signature stored at:', ['path' => $path]);
        }

        // Save the signatory
        $this->defaultSignatory = IdSignatory::updateOrCreate(
            ['id' => $this->defaultSignatory->id ?? null],
            $signatoryData
        );

        // Refresh the displayed data
        $this->loadDefaultSignatory();
        
        session()->flash('message', 'Signatory details saved successfully!');
        $this->showSignatoryModal = false;
        
    } catch (\Exception $e) {
        \Log::error('Failed to save signatory:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        session()->flash('error', 'Error saving signatory details. Please check logs.');
    }
}
    public function getSignatorySignatureUrl()
    {
        return $this->defaultSignatory && $this->defaultSignatory->signature_path 
            ? route('signatory-signature.file', ['filename' => $this->defaultSignatory->signature_path])
            : null;
    }

    public function render()
    {
        return view('livewire.admin.virtual-id-table');
    }
}