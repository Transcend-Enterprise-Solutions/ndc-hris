<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use App\Models\LeaveCredits;
use App\Models\Positions;
use App\Models\UserData;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveCardExport
{
    protected $leaveApplicationId;

    public function __construct($leaveApplicationId)
    {
        $this->leaveApplicationId = $leaveApplicationId;
    }

    public function export(): StreamedResponse
    {
        $leaveApplication = LeaveApplication::with('user')->findOrFail($this->leaveApplicationId);

        $position = Positions::find($leaveApplication->user->position_id)->position ?? 'N/A';
        $civilStatus = UserData::where('user_id', $leaveApplication->user->id)->value('civil_status') ?? 'N/A';
        $gsis = UserData::where('user_id', $leaveApplication->user->id)->value('gsis') ?? 'N/A';
        $tin = UserData::where('user_id', $leaveApplication->user->id)->value('tin') ?? 'N/A';

        $vlClaimableCredits = LeaveCredits::where('user_id', $leaveApplication->user->id)->value('vl_claimable_credits') ?? 0;

        $templatePath = storage_path('app/public/leave_template/LEAVE-CARD.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();

        $nameText = new RichText();
        $boldName = $nameText->createTextRun('Name:');
        $boldName->getFont()->setBold(true);
        $nameText->createText(' ' . $leaveApplication->user->name);

        $positionText = new RichText();
        $boldPosition = $positionText->createTextRun('Position:');
        $boldPosition->getFont()->setBold(true);
        $positionText->createText(' ' . $position);

        $civilStatusText = new RichText();
        $boldCivilStatus = $civilStatusText->createTextRun('Civil Status:');
        $boldCivilStatus->getFont()->setBold(true);
        $civilStatusText->createText(' ' . $civilStatus);

        $gsisText = new RichText();
        $boldGsis = $gsisText->createTextRun('GSIS Policy No:');
        $boldGsis->getFont()->setBold(true);
        $gsisText->createText(' ' . $gsis);

        $tinText = new RichText();
        $boldTin = $tinText->createTextRun('TIN No:');
        $boldTin->getFont()->setBold(true);
        $tinText->createText(' ' . $tin);

        $sheet->setCellValue('A3', $nameText);
        $sheet->setCellValue('A4', $positionText);
        $sheet->setCellValue('L3', $civilStatusText);
        $sheet->setCellValue('R3', $gsisText);
        $sheet->setCellValue('R4', $tinText);

        // Set vl_claimable_credits into cell N11
        $sheet->setCellValue('N11', $vlClaimableCredits);

        $fileName = 'LeaveCard-' . $leaveApplication->id . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
