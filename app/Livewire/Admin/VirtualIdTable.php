<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\OfficeDivisions;
// use App\Models\Positions;
use App\Models\ESignature;

class VirtualIdTable extends Component
{
    public $employees = [];
    public $selectedEmployeeId = null;
    public $idType = 'virtual';
    public $showDropdown = false;
    
    // Employee information fields
    public $name;
    public $emp_code;
    public $office_or_department;
    // public $position;
    public $profilePhotoUrl;
    public $eSignatureUrl;
    public $emergencyContactName;
    public $emergencyContactNumber;
    
    // Instead of storing the QR code HTML, store just the data that will go into the QR code
    public $qrCodeData = '';

    public function mount()
    {
        $this->employees = User::with('userData')
                                ->where('user_role', 'emp')    
                                ->get();
    }

    public function selectEmployee($employeeId)
    {
        $this->selectedEmployeeId = $employeeId;
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

        // Get office/department
        $officeDivision = OfficeDivisions::find($user->office_division_id);
        $this->office_or_department = $officeDivision ? $officeDivision->office_division : 'N/A';

        // Get position
        // $this->position = Positions::find($user->position_id)->position ?? 'No position assigned';

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
        $this->name = $user->name;
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
        if ($this->idType === 'arta') {
            $this->qrCodeData = sprintf(
                "ARTA ID\nName: %s\nDepartment: %s\nID: %s",
                $this->name,
                // $this->position,
                $this->office_or_department,
                $this->emp_code
            );
        } else {
            $this->qrCodeData = sprintf(
                "Name: %s\nEmployee Code: %s\nDepartment: %s",
                $this->name,
                $this->emp_code,
                // $this->position,
                $this->office_or_department
            );
        }
    }
    
    // Generate QR code on-the-fly when needed
    public function getQrCodeHtml()
    {
        if (empty($this->qrCodeData)) {
            return '';
        }
        
        return QrCode::size($this->idType === 'arta' ? 100 : 100)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->margin(2)
            ->generate($this->qrCodeData);
    }

    public function render()
    {
        return view('livewire.admin.virtual-id-table');
    }
}