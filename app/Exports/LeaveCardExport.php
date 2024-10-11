<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use App\Models\Positions;
use App\Models\UserData;
use App\Models\OfficeDivisionUnits;
use App\Models\MonetizationRequest;
use App\Models\MonthlyCredits;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

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

    private function setBoldLabelWithValue($sheet, $cell, $label, $value)
    {
        $richText = new RichText();
        
        // Add bold label
        $boldPart = $richText->createTextRun($label);
        $boldPart->getFont()->setBold(true);
        
        // Add value
        $richText->createText($value);
        
        // Set the rich text to the cell
        $sheet->setCellValue($cell, $richText);
    }

    public function export(): StreamedResponse
    {
        $leaveApplication = LeaveApplication::with('user')->findOrFail($this->leaveApplicationId);
        $user = $leaveApplication->user;

        // Fetching user data
        $user = $leaveApplication->user;
        $position = Positions::find($user->position_id)->position ?? 'N/A';
        $civilStatus = UserData::where('user_id', $user->id)->value('civil_status') ?? 'N/A';
        $gsis = UserData::where('user_id', $user->id)->value('gsis') ?? 'N/A';
        $tin = UserData::where('user_id', $user->id)->value('tin') ?? 'N/A';
        $officeDivisionId = $user->unit_id;
        $unit = OfficeDivisionUnits::where('id', $officeDivisionId)->value('unit') ?? 'N/A';
        $appointment = UserData::where('user_id', $user->id)->value('appointment') ?? 'N/A';
        $dateHired = UserData::where('user_id', $user->id)->value('date_hired');

        if ($dateHired) {
            $formattedDateHired = \Carbon\Carbon::createFromFormat('Y-m-d', $dateHired)->format('m/d/Y');
        } else {
            $formattedDateHired = 'N/A'; // If no date_hired is found
        }

        $appointmentMap = [
            'plantilla' => 'Plantilla',
            'cos' => 'Contract of Service',
            'ct' => 'Co-Terminus',
            'pa' => 'Presidential Appointee',
        ];

        $appointmentType = explode(',', $appointment)[0];
        $appointmentDisplay = $appointmentMap[$appointmentType] ?? 'N/A';

        // $startDateCarbon = Carbon::createFromFormat('Y-m', $this->startDate);
        // $balanceData = $this->calculateBalanceBroughtForward($user->id, $startDateCarbon);
        $startDateCarbon = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
        $endDateCarbon = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();
        
        $balanceData = $this->calculateBalanceBroughtForward($user->id, $startDateCarbon, $endDateCarbon);
        // $sl_balance_brought_forward = LeaveCredits::where('user_id', $user->id)->value('slbalance_brought_forward') ?? 0;

        // Summing approved leave days within the selected date range
        $approvedStatuses = ['Approved', 'Approved by HR', 'Approved by Supervisor'];
        $leaveTypes = ['Vacation Leave', 'Sick Leave'];

        $approvedLeaves = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', $approvedStatuses)
            ->whereBetween('updated_at', [
                Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth(),
                Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth()
            ])
            ->sum('approved_days') ?? 0;

        // Load the template
        $templatePath = storage_path('app/public/leave_template/LEAVE-CARD.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $this->setBoldLabelWithValue($sheet, 'A3', 'Name: ', $user->name);
        $this->setBoldLabelWithValue($sheet, 'A4', 'Position: ', $position);
        $this->setBoldLabelWithValue($sheet, 'L3', 'Civil Status: ', $civilStatus);
        $this->setBoldLabelWithValue($sheet, 'R3', 'GSIS Policy No: ', $gsis);
        $this->setBoldLabelWithValue($sheet, 'R4', 'TIN No: ', $tin);
        $this->setBoldLabelWithValue($sheet, 'L5', 'Unit: ', $unit);
        $this->setBoldLabelWithValue($sheet, 'A5', 'Status: ', $appointmentDisplay);
        $this->setBoldLabelWithValue($sheet, 'L4', 'Entrance to Duty: ', $formattedDateHired);

        // Insert "Particulars" based on the selected months
        $startDate = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();

        $rowIndex = 11;
        // $firstMonthProcessed = false;
        // $currentBalance = $balances['vl'];
        // $currentBalanceSl = $balances['sl'];
        $currentBalance = $balanceData['vl'];
        $currentBalanceSl = $balanceData['sl'];    

        // if ($balanceData['found_date']) {
        //     $startDateCarbon = Carbon::parse($balanceData['found_date'])->startOfMonth();
        // }

        // Set Balance Brought Forward
        $sheet->setCellValue('K' . $rowIndex, 'Balance Brought Forward');
        $sheet->setCellValue('N' . $rowIndex, $currentBalance);
        $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);

        $rowIndex++;

        // $processStartDate = $balanceData['found_date'] 
        //     ? Carbon::parse($balanceData['found_date'])->startOfMonth() 
        //     : $startDateCarbon;

        // $dataFound = false;
        $firstDataFound = false;
        for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date->addMonth()) {
            $month = intval($date->format('n'));
            $year = $date->format('Y');
        
            $leaveCredits = LeaveCreditsCalculation::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->value('leave_credits_earned');

            $monthlyCredit = MonthlyCredits::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
        
            $leaveCredits = $leaveCredits !== null ? $leaveCredits : 0;
        
            $sheet->setCellValue('K' . $rowIndex, $date->format('F Y')); 

            if (!$firstDataFound && $leaveCredits > 0) {
                $firstDataFound = true;
                // Update the Balance Brought Forward with the first non-zero data
                $sheet->setCellValue('N11', $currentBalance);
                $sheet->setCellValue('R11', $currentBalanceSl);
            }
        
            if ($firstDataFound) {
            $sheet->setCellValue('L' . $rowIndex, $leaveCredits);
            $sheet->setCellValue('P' . $rowIndex, $leaveCredits);

            $currentBalance += $leaveCredits;
            $sheet->setCellValue('N' . $rowIndex, $currentBalance);
            $currentBalanceSl += $leaveCredits;
            $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);
        } else {
            // For months before first data, display 0 or empty cells
            $sheet->setCellValue('L' . $rowIndex, 0);
            $sheet->setCellValue('P' . $rowIndex, 0);
            $sheet->setCellValue('N' . $rowIndex, 0);
            $sheet->setCellValue('R' . $rowIndex, 0);
        }
            $lateTime = LeaveCreditsCalculation::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->value('late_time');

            $totalVLDays = 0;
            $approvedLeaves = LeaveApplication::where('user_id', $user->id)
                ->where('status', 'Approved')
                ->where('remarks', 'With Pay')
                ->get();
            
            foreach ($approvedLeaves as $leave) {
                // Explode the approved_dates to get individual dates
                $approvedDates = explode(',', $leave->approved_dates);
                $leaveTypes = explode(',', $leave->type_of_leave);
                
                // Check if "Vacation Leave" is in the type_of_leave array
                if (in_array('Vacation Leave', $leaveTypes)) {
                    foreach ($approvedDates as $approvedDate) {
                        $approvedDate = Carbon::parse($approvedDate)->startOfDay();
                        
                        // Check if the approved date falls within the current month range
                        if ($approvedDate->between(
                            Carbon::createFromDate($year, $month, 1)->startOfMonth(),
                            Carbon::createFromDate($year, $month, 1)->endOfMonth()
                        )) {
                            // Sum the approved_days for the matching dates
                            $totalVLDays += $leave->approved_days;
                        }
                    }
                }
            }

            $totalSickLeaveDays = 0;
            foreach ($approvedLeaves as $leave) {
                // Explode the approved_dates to get individual dates
                $approvedDates = explode(',', $leave->approved_dates);
                $leaveTypes = explode(',', $leave->type_of_leave);
                
                // Check if "Sick Leave" is in the type_of_leave array
                if (in_array('Sick Leave', $leaveTypes)) {
                    foreach ($approvedDates as $approvedDate) {
                        $approvedDate = Carbon::parse($approvedDate)->startOfDay();
                        
                        // Check if the approved date falls within the current month range
                        if ($approvedDate->between(
                            Carbon::createFromDate($year, $month, 1)->startOfMonth(),
                            Carbon::createFromDate($year, $month, 1)->endOfMonth()
                        )) {
                            // Sum the approved_days for the matching dates
                            $totalSickLeaveDays += $leave->approved_days;
                        }
                    }
                }
            }

            

            $rowIndex++;

            if ($totalVLDays > 0 || $totalSickLeaveDays > 0 || ($lateTime && $lateTime !== '00:00')) {
                $dataFound = true;
                $sheet->setCellValue('K' . $rowIndex, 'Lates/Undertime');
                
                // Record VL days in column A
                if ($totalVLDays > 0) {
                    $sheet->setCellValue('A' . $rowIndex, $totalVLDays);
                }
                
                // Record SL days in column B
                if ($totalSickLeaveDays > 0) {
                    $sheet->setCellValue('B' . $rowIndex, $totalSickLeaveDays);
                }

                // Process late time if exists
                $lateTimeDays = 0;
                if ($lateTime && $lateTime !== '00:00') {
                    list($hours, $minutes) = explode(':', $lateTime);
                    $totalMinutes = (intval($hours) * 60) + intval($minutes);
                    
                    $days = floor($totalMinutes / (8 * 60));
                    $remainingMinutes = $totalMinutes % (8 * 60);
                    $hours = floor($remainingMinutes / 60);
                    $minutes = $remainingMinutes % 60;

                    // Record late time in columns C, D, E
                    if ($days > 0) {
                        $sheet->setCellValue('C' . $rowIndex, $days);
                    }
                    if ($hours > 0) {
                        $sheet->setCellValue('D' . $rowIndex, $hours);
                    }
                    if ($minutes > 0) {
                        $sheet->setCellValue('E' . $rowIndex, $minutes);
                    }

                    $lateTimeDays = $totalMinutes / (8 * 60);
                }

                // Calculate total deductions for VL (A + C + D + E columns)
                $totalDeductionVL = $totalVLDays + $lateTimeDays;
                $sheet->setCellValue('M' . $rowIndex, $totalDeductionVL);
                
                // Record SL deductions
                $sheet->setCellValue('Q' . $rowIndex, $totalSickLeaveDays);

                // Update balances after all deductions
                $currentBalance -= $totalDeductionVL;
                $currentBalanceSl -= $totalSickLeaveDays;

                // Record new balances
                $sheet->setCellValue('N' . $rowIndex, $currentBalance);
                $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);

                $rowIndex++;
            }
            // if ($dataFound) {
            //     break;
            // }
        
            // $rowIndex++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="LeaveCard.xlsx"',
        ]);
    }

    // private function calculateBalanceBroughtForward($userId, Carbon $startDate)
    // {
    //     $monthlyCredit = MonthlyCredits::where('user_id', $userId)
    //         ->where('month', $startDate->month)
    //         ->where('year', $startDate->year)
    //         ->first();

    //         if ($monthlyCredit) {
    //             return [
    //                 'vl' => $monthlyCredit->vl_latest_credits,
    //                 'sl' => $monthlyCredit->sl_latest_credits
    //             ];
    //         }

    //     // If no monthly credit is found, return 0 or throw an exception
    //     // depending on how you want to handle this case
    //     // throw new \Exception("No monthly credit found for the selected start date.");
    //     return ['vl' => 0, 'sl' => 0];
    // }
    private function calculateBalanceBroughtForward($userId, Carbon $startDate, Carbon $endDate)
    {
        for ($currentDate = $startDate->copy(); $currentDate->lte($endDate); $currentDate->addMonth()) {
            $monthlyCredit = MonthlyCredits::where('user_id', $userId)
                ->where('month', $currentDate->month)
                ->where('year', $currentDate->year)
                ->first();

            if ($monthlyCredit && ($monthlyCredit->vl_latest_credits > 0 || $monthlyCredit->sl_latest_credits > 0)) {
                return [
                    'vl' => $monthlyCredit->vl_latest_credits,
                    'sl' => $monthlyCredit->sl_latest_credits,
                    'found_date' => $currentDate->format('Y-m-d')
                ];
            }
        }

        // If no monthly credit is found in the entire date range, return 0 for both VL and SL
        return [
            'vl' => 0,
            'sl' => 0,
            'found_date' => null
        ];
    }
}
