<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use App\Models\Positions;
use App\Models\UserData;
use App\Models\MonetizationRequest;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveCardExport
{
    protected $leaveApplicationId;
    protected $startDate;
    protected $endDate;

    public function __construct($leaveApplicationId, $startDate, $endDate)
    {
        $this->leaveApplicationId = $leaveApplicationId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function export(): StreamedResponse
    {
        $leaveApplication = LeaveApplication::with('user')->findOrFail($this->leaveApplicationId);

        // Fetching user-specific data
        $user = $leaveApplication->user;
        $position = Positions::find($user->position_id)->position ?? 'N/A';
        $civilStatus = UserData::where('user_id', $user->id)->value('civil_status') ?? 'N/A';
        $gsis = UserData::where('user_id', $user->id)->value('gsis') ?? 'N/A';
        $tin = UserData::where('user_id', $user->id)->value('tin') ?? 'N/A';

        $vlTotalCredits = LeaveCredits::where('user_id', $user->id)->value('vl_claimable_credits') ?? 0;
        $slTotalCredits = LeaveCredits::where('user_id', $user->id)->value('sl_claimable_credits') ?? 0;

        // Calculate the total approved monetization requests for the current month
        $vlMonetizedCredits = MonetizationRequest::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('vl_credits_requested') ?? 0;

        $slMonetizedCredits = MonetizationRequest::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('sl_credits_requested') ?? 0;

        // Extract year and month from startDate and endDate
        list($startYear, $startMonth) = explode('-', $this->startDate);
        list($endYear, $endMonth) = explode('-', $this->endDate);

        // Ensure month is numeric and properly padded
        $startMonth = (int) $startMonth;
        $endMonth = (int) $endMonth;

        // Calculate total earned credits within the specified date range
        $totalEarnedCredits = LeaveCreditsCalculation::where('user_id', $user->id)
            ->whereBetween('year', [$startYear, $endYear])
            ->where(function ($query) use ($startMonth, $endMonth, $startYear, $endYear) {
                if ($startYear == $endYear) {
                    $query->whereBetween('month', [$startMonth, $endMonth]);
                } else {
                    $query->where(function ($query) use ($startMonth, $endMonth, $startYear, $endYear) {
                        $query->where(function ($query) use ($startMonth) {
                            $query->where('year', $startYear)
                                ->where('month', '>=', $startMonth);
                        })
                        ->orWhere(function ($query) use ($endMonth, $endYear) {
                            $query->where('year', $endYear)
                                ->where('month', '<=', $endMonth);
                        });
                    });
                }
            })
            ->sum('leave_credits_earned') ?? 0;

        // With Pay
        $leaveTypes = ['Vacation Leave'];
        $approvedStatuses = ['Approved', 'Approved by HR', 'Approved by Supervisor'];

        $startDate = \Carbon\Carbon::createFromFormat('Y-m', $this->startDate);
        $endDate = \Carbon\Carbon::createFromFormat('Y-m', $this->endDate);

        $vacationLeaveWithPay = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', $approvedStatuses)
            ->whereIn('type_of_leave', $leaveTypes)
            ->where('remarks', 'With Pay')
            ->whereBetween('updated_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereYear('updated_at', '>=', $startDate->year)
                    ->whereMonth('updated_at', '>=', $startDate->month);

                if ($startDate->year != $endDate->year || $startDate->month != $endDate->month) {
                    $query->orWhereYear('updated_at', '<=', $endDate->year)
                        ->whereMonth('updated_at', '<=', $endDate->month);
                }
            })
            ->sum('approved_days') ?? 0;

        $sickLeaveType = ['Sick Leave'];
        $sickLeaveWithPay  = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', $approvedStatuses)
            ->whereIn('type_of_leave', $sickLeaveType)
            ->where('remarks', 'With Pay')
            ->whereBetween('updated_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereYear('updated_at', '>=', $startDate->year)
                    ->whereMonth('updated_at', '>=', $startDate->month);

                if ($startDate->year != $endDate->year || $startDate->month != $endDate->month) {
                    $query->orWhereYear('updated_at', '<=', $endDate->year)
                        ->whereMonth('updated_at', '<=', $endDate->month);
                }
            })
            ->sum('approved_days') ?? 0;

        // Without Pay
        $vacationLeaveWithoutPay = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', $approvedStatuses)
            ->whereIn('type_of_leave', $leaveTypes)
            ->where('remarks', 'Without Pay')
            ->whereBetween('updated_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereYear('updated_at', '>=', $startDate->year)
                    ->whereMonth('updated_at', '>=', $startDate->month);

                if ($startDate->year != $endDate->year || $startDate->month != $endDate->month) {
                    $query->orWhereYear('updated_at', '<=', $endDate->year)
                        ->whereMonth('updated_at', '<=', $endDate->month);
                }
            })
            ->sum('approved_days') ?? 0;

        $sickLeaveWithoutPay = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', $approvedStatuses)
            ->whereIn('type_of_leave', $sickLeaveType)
            ->where('remarks', 'Without Pay')
            ->whereBetween('updated_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereYear('updated_at', '>=', $startDate->year)
                    ->whereMonth('updated_at', '>=', $startDate->month);

                if ($startDate->year != $endDate->year || $startDate->month != $endDate->month) {
                    $query->orWhereYear('updated_at', '<=', $endDate->year)
                        ->whereMonth('updated_at', '<=', $endDate->month);
                }
            })
            ->sum('approved_days') ?? 0;

        // Load the template and populate cells
        $templatePath = storage_path('app/public/leave_template/LEAVE-CARD.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Create and set rich text for specific cells
        $nameText = new RichText();
        $boldName = $nameText->createTextRun('Name:');
        $boldName->getFont()->setBold(true);
        $nameText->createText(' ' . $user->name);

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

        // Populate the cells with user data
        $sheet->setCellValue('A3', $nameText);
        $sheet->setCellValue('A4', $positionText);
        $sheet->setCellValue('L3', $civilStatusText);
        $sheet->setCellValue('R3', $gsisText);
        $sheet->setCellValue('R4', $tinText);

        // Populate other required cells
        $sheet->setCellValue('N11', $vlTotalCredits);
        $sheet->setCellValue('R11', $slTotalCredits);
        $sheet->setCellValue('K11', 'Balance Brought Forward');
        $sheet->setCellValue('K12', '50% Leave Monetization');
        $sheet->setCellValue('M12', $vlMonetizedCredits);
        $sheet->setCellValue('Q12', $slMonetizedCredits);
        $sheet->setCellValue('L13', $totalEarnedCredits);

        $startDateFormatted = \Carbon\Carbon::createFromFormat('Y-m', $this->startDate)->format('M Y');
        $endDateFormatted = \Carbon\Carbon::createFromFormat('Y-m', $this->endDate)->format('M Y');
        $dateRange = "{$startDateFormatted} - {$endDateFormatted}";

        $sheet->setCellValue('K13', $dateRange);
        $sheet->setCellValue('A14', $vacationLeaveWithPay);
        $sheet->setCellValue('F14', $vacationLeaveWithoutPay);
        $sheet->setCellValue('B15', $sickLeaveWithPay);
        $sheet->setCellValue('G15', $sickLeaveWithoutPay);

        // Set the filename
        $fileName = 'LeaveCard-' . $leaveApplication->id . '.xlsx';

        // Stream the response
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
