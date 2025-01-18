<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MyVirtualIdTable extends Component
{

    // public function downloadPdf()
    // {
    //     $user = Auth::user();
    //     $userData = $user->userData;

    //     $position = DB::table('positions')
    //         ->where('id', $user->position_id)
    //         ->value('position') ?? 'No position assigned';

    //     $formattedDateOfBirth = $userData->date_of_birth 
    //         ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
    //         : 'N/A';

    //     // Get QR code
    //     $qrCode = $this->qrCode;

    //     // Create PDF in landscape orientation
    //     $pdf = PDF::loadView('pdf.virtual-id', [
    //         'name' => $user->name,
    //         'emp_code' => $user->emp_code,
    //         'profilePhotoPath' => $user->profile_photo_path 
    //             ? public_path('storage/' . $user->profile_photo_path) 
    //             : public_path('default-avatar.png'),
    //         'dateOfBirth' => $formattedDateOfBirth,
    //         'placeOfBirth' => $userData->place_of_birth ?? null,
    //         'sex' => $userData->sex ?? null,
    //         'civilStatus' => $userData->civil_status ?? null,
    //         'bloodType' => $userData->blood_type ?? null,
    //         'position' => $position,
    //         'qrCode' => $qrCode
    //     ]); // Set the paper size to A4 in landscape orientation

    //     return response()->streamDownload(
    //         fn () => print($pdf->output()),
    //         "virtual-id.pdf"
    //     );
    // }
    public function downloadPdf()
    {
        $user = Auth::user();
        $userData = $user->userData;

        $position = DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'No position assigned';

        $formattedDateOfBirth = $userData->date_of_birth 
            ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
            : 'N/A';

        // Get QR code as base64 string
        $qrCode = base64_encode($this->qrCode);

        // Create PDF in landscape orientation
        $pdf = PDF::loadView('pdf.virtual-id', [
            'name' => $user->name,
            'emp_code' => $user->emp_code,
            'profilePhotoPath' => $user->profile_photo_path 
                ? public_path('storage/' . $user->profile_photo_path) 
                : public_path('default-avatar.png'),
            'dateOfBirth' => $formattedDateOfBirth,
            'placeOfBirth' => $userData->place_of_birth ?? null,
            'sex' => $userData->sex ?? null,
            'civilStatus' => $userData->civil_status ?? null,
            'bloodType' => $userData->blood_type ?? null,
            'position' => $position,
            'qrCode' => $qrCode  // Pass the base64-encoded QR code
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "MyVirtualID.pdf"
        );
    }


    public function getQrCodeProperty()
    {
        $user = Auth::user();
        $userData = $user->userData;
        
        // Get position directly using query
        $position = \DB::table('positions')
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
        $position = \DB::table('positions')
            ->where('id', $user->position_id)
            ->value('position') ?? 'No position assigned';

        $formattedDateOfBirth = $userData->date_of_birth 
            ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F j, Y') 
            : 'N/A';

        return view('livewire.user.my-virtual-id-table', [
            'name' => $user->name,
            'emp_code' => $user->emp_code,
            'profilePhotoPath' => $user->profile_photo_path,
            'dateOfBirth' => $formattedDateOfBirth,
            'placeOfBirth' => $userData->place_of_birth ?? null,
            'sex' => $userData->sex ?? null,
            'civilStatus' => $userData->civil_status ?? null,
            'bloodType' => $userData->blood_type ?? null,
            'qrCode' => $this->qrCode,
            'position' => $position,
        ]);
    }
}