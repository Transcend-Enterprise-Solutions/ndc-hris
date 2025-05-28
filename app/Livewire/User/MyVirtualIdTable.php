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
    public $eSignatureFilename;
    public $empCodeFormatted;
    public $showDropdown = false;
    public $showSignatureModal = false;
    public $signatureFile;
    public $showProfilePhotoModal = false;
    public $profilePhoto;
    public $idType = 'virtual'; // 'virtual' or 'arta'
    public $showEmergencyContactModal = false;
    public $emergencyContactName;
    public $emergencyContactNumber;

    public function toggleEmergencyContactModal()
    {
        $this->showEmergencyContactModal = !$this->showEmergencyContactModal;
        
        // Load existing values if available
        $eSignature = ESignature::where('user_id', Auth::id())->first();
        if ($eSignature) {
            $this->emergencyContactName = $eSignature->emergency_contact_name;
            $this->emergencyContactNumber = $eSignature->emergency_contact_number;
        }
    }

    public function saveEmergencyContact()
    {
        $this->validate([
            'emergencyContactName' => 'required|string|max:255',
            'emergencyContactNumber' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $eSignature = ESignature::firstOrNew(['user_id' => $user->id]);
        $eSignature->emergency_contact_name = $this->emergencyContactName;
        $eSignature->emergency_contact_number = $this->emergencyContactNumber;
        $eSignature->save();

        $this->showEmergencyContactModal = false;
        $this->dispatch('emergency-contact-saved');
    }

    public function toggleUploadProfilePhoto()
    {
        $this->showProfilePhotoModal = true;
    }

    public function toggleUploadSignature()
    {
        $this->showSignatureModal = true;
    }

    public function switchIdType()
    {
        $this->idType = $this->idType === 'virtual' ? 'arta' : 'virtual';
    }

    public function saveProfilePhoto()
    {
        $this->validate([
            'profilePhoto' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        
        $user = Auth::user();
        $eSignature = ESignature::firstOrNew(['user_id' => $user->id]);
        
        // Delete old photo if exists
        if ($eSignature->profile_photo_path) {
            Storage::disk('public')->delete('profile-photos/'.$eSignature->profile_photo_path);
        }
        
        // Generate filename and store
        $filename = 'profile_'.$user->id.'_'.time().'.'.$this->profilePhoto->extension();
        $this->profilePhoto->storeAs('profile-photos', $filename, 'public');
        
        // Store just the filename (not full path)
        $eSignature->profile_photo_path = $filename;
        $eSignature->save();
        
        // Update the component property with just the filename
        $this->profilePhotoPath = $filename;
        $this->showProfilePhotoModal = false;
        $this->profilePhoto = null;
        
        $this->dispatch('profile-photo-uploaded');
    }

    public function saveSignature()
    {
        $this->validate([
            'signatureFile' => 'required|image|mimes:png,jpg,jpeg|max:1024',
        ]);
    
        $user = Auth::user();
        
        $oldSignature = ESignature::where('user_id', $user->id)->first();
        if ($oldSignature) {
            Storage::disk('public')->delete('signatures/'.$oldSignature->file_path);
            $oldSignature->delete();
        }
    
        $filename = 'sig_'.$user->id.'_'.time().'.'.$this->signatureFile->extension();
        $this->signatureFile->storeAs('signatures', $filename, 'public');
        
        ESignature::create([
            'user_id' => $user->id,
            'file_path' => $filename,
        ]);
    
        $this->eSignatureFilename = $filename;
        $this->showSignatureModal = false;
        $this->signatureFile = null;
        
        $this->dispatch('signature-uploaded');
    }

    public function getEmergencyContactNameProperty()
    {
        $eSignature = ESignature::where('user_id', Auth::id())->first();
        return $eSignature?->emergency_contact_name;
    }

    public function getEmergencyContactNumberProperty()
    {
        $eSignature = ESignature::where('user_id', Auth::id())->first();
        return $eSignature?->emergency_contact_number;
    }

    public function getProfilePhotoUrlProperty()
    {
        if (!$this->profilePhotoPath) return null;
        return route('profile-photo.file', ['filename' => basename($this->profilePhotoPath)]);
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
        $this->reset(['signatureFile', 'profilePhoto']);
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

        $officeDivision = OfficeDivisions::find($user->office_division_id);
        $this->office_or_department = $officeDivision ? $officeDivision->office_division : 'N/A';

        $eSignature = ESignature::where('user_id', $user->id)->first();
        
        // Store just the filename (not full path) for profile photos
        $this->profilePhotoPath = $eSignature?->profile_photo_path;
        
        $this->eSignatureFilename = $eSignature?->file_path;
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
        // Simply generate a QR code that links to the NDC website
        return QrCode::size($this->idType === 'arta' ? 100 : 100)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->margin(2)
            ->generate('https://www.ndc.gov.ph/');
    }

    public function render()
    {
        $user = Auth::user();
        $userData = $user->userData;

        // Format the name: FIRSTNAME M. SURNAME
        $formattedName = strtoupper(
            ($userData->first_name ?? '') . ' ' .
            (isset($userData->middle_name) ? substr($userData->middle_name, 0, 1) . '.' : '') . ' ' .
            ($userData->surname ?? '')
        );

        $position = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'No position assigned';

        return view('livewire.user.my-virtual-id-table', [
            'name' => $formattedName, // Updated to use formatted name
            'emp_code' => $this->empCodeFormatted,
            'profilePhotoPath' => $this->profilePhotoPath,
            'dateOfBirth' => $userData->date_of_birth 
                ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
                : 'N/A',
            'placeOfBirth' => $userData->place_of_birth ?? null,
            'sex' => $userData->sex ?? null,
            'civilStatus' => $userData->civil_status ?? null,
            'bloodType' => $userData->blood_type ?? null,
            'qrCode' => $this->qrCode,
            'position' => $position,
            'office_or_department' => $this->office_or_department,
            'eSignatureUrl' => $this->eSignatureUrl,
            'signatureExists' => $this->signatureExists,
            'idType' => $this->idType,
        ]);
    }
}