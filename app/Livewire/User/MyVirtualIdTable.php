<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Models\OfficeDivisions;
use App\Models\Positions;
use App\Models\ESignature;

class MyVirtualIdTable extends Component
{
    public $office_or_department;
    public $profilePhotoPath;
    public $eSignaturePath;
    public $empCodeFormatted;
    public $showDropdown = false;

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

        // Get e-signature path
        $eSignature = ESignature::where('user_id', $user->id)->first();
        $this->eSignaturePath = $eSignature ? 'storage/' . $eSignature->file_path : null;

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
        
        // Get position directly using query
        $position = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'N/A';

        // Format the date_of_birth
        $formattedDateOfBirth = $userData->date_of_birth 
            ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
            : 'N/A';

        // Create a formatted string of user data
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

        // Get position directly
        $position = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'No position assigned';

        $formattedDateOfBirth = $userData->date_of_birth 
            ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
            : 'N/A';

        $this->eSignaturePath = explode('/',$this->eSignaturePath);
        $this->eSignaturePath = $this->eSignaturePath[1] . '/' . $this->eSignaturePath[2];

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
            'eSignaturePath' => $this->eSignaturePath,
        ]);
    }
}