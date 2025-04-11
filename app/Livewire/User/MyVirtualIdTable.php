<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Models\OfficeDivisions;
use App\Models\Positions;
use App\Models\ESignature;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class MyVirtualIdTable extends Component
{
    use WithFileUploads;

    public $office_or_department;
    public $profilePhotoPath;
    public $eSignatureFilename; // Changed from eSignaturePath to be more explicit
    public $empCodeFormatted;
    public $showDropdown = false;
    public $showSignatureModal = false;
    public $signatureFile;

    public function toggleUploadSignature()
    {
        $this->showSignatureModal = true;
    }

    public function saveSignature()
    {
        $this->validate([
            'signatureFile' => 'required|image|mimes:png,jpg,jpeg|max:1024',
        ]);
    
        $user = Auth::user();
        
        // Delete old signature
        $oldSignature = ESignature::where('user_id', $user->id)->first();
        if ($oldSignature) {
            Storage::disk('public')->delete('signatures/'.$oldSignature->file_path);
            $oldSignature->delete();
        }
    
        // Generate safe filename
        $extension = $this->signatureFile->extension();
        $filename = 'sig_'.$user->id.'_'.time().'.'.$extension;
        
        // Store file
        $this->signatureFile->storeAs('signatures', $filename, 'public');
        
        // Save to database
        ESignature::create([
            'user_id' => $user->id,
            'file_path' => $filename,
        ]);
    
        // Update component
        $this->eSignatureFilename = $filename;
        $this->showSignatureModal = false;
        $this->signatureFile = null;
        
        $this->dispatch('signature-uploaded');
    }

    public function getESignatureUrlProperty()
    {
        if (!$this->eSignatureFilename) return null;
        return route('signature.file', ['filename' => $this->eSignatureFilename]);
    }

    public function getSignatureExistsProperty()
    {
        if (!$this->eSignatureFilename) return false;
        
        return Storage::disk('public')->exists('signatures/'.$this->eSignatureFilename);
    }

    public function resetVariables()
    {
        $this->reset('signatureFile');
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function mount()
    {
        $user = Auth::user();
        $userData = $user->userData;
    
        // Get office or department
        $officeDivision = OfficeDivisions::find($user->office_division_id);
        $this->office_or_department = $officeDivision ? $officeDivision->office_division : 'N/A';
    
        // Get profile photo path
        $this->profilePhotoPath = $user->profile_photo_path;
    
        // Get e-signature filename only
        $eSignature = ESignature::where('user_id', $user->id)->first();
        $this->eSignatureFilename = $eSignature ? $eSignature->file_path : null;
    
        // Format employee code
        $this->empCodeFormatted = $this->formatEmpCode($user->emp_code);
    }

    private function formatEmpCode($empCode)
    {
        if (strlen($empCode) >= 8) {
            return substr($empCode, 0, 4) . '-' . substr($empCode, 4, 4);
        }
        return $empCode;
    }

    public function getQrCodeProperty()
    {
        $user = Auth::user();
        $userData = $user->userData;
        
        $position = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'N/A';

        $formattedDateOfBirth = $userData->date_of_birth 
            ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
            : 'N/A';

        $formattedData = sprintf(
            "Name: %s\nEmployee Code: %s\nDate of Birth: %s\nPlace of Birth: %s\nSex: %s\nCivil Status: %s\nBlood Type: %s\nPosition: %s",
            $user->name,
            $user->emp_code,
            $formattedDateOfBirth,
            $userData->place_of_birth ?? 'N/A',
            $userData->sex ?? 'N/A',
            $userData->civil_status ?? 'N/A',
            strtoupper($userData->blood_type ?? 'N/A'),
            $position
        );

        return QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->margin(2)
            ->generate($formattedData);
    }

    public function render()
    {
        $user = Auth::user();
        $userData = $user->userData;
    
        $position = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'No position assigned';
    
        $formattedDateOfBirth = $userData->date_of_birth 
            ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
            : 'N/A';
    
        return view('livewire.user.my-virtual-id-table', [
            'name' => $user->name,
            'emp_code' => $this->empCodeFormatted,
            'profilePhotoPath' => $this->profilePhotoPath,
            'dateOfBirth' => $formattedDateOfBirth,
            'placeOfBirth' => $userData->place_of_birth ?? null,
            'sex' => $userData->sex ?? null,
            'civilStatus' => $userData->civil_status ?? null,
            'bloodType' => $userData->blood_type ?? null,
            'qrCode' => $this->qrCode,
            'position' => $position,
            'office_or_department' => $this->office_or_department,
            'eSignatureUrl' => $this->eSignatureUrl, // Use computed property
            'signatureExists' => $this->signatureExists, // Use computed property
        ]);
    }
}