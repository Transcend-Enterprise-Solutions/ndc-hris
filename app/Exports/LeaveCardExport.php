<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use App\Models\Positions;
use App\Models\UserData;
use App\Models\OfficeDivisionUnits;
use App\Models\MonetizationRequest;
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

        // Define the mapping of appointment types to their full descriptions
        $appointmentMap = [
            'plantilla' => 'Plantilla',
            'cos' => 'Contract of Service',
            'ct' => 'Co-Terminus',
            'pa' => 'Presidential Appointee',
        ];

        $appointmentType = explode(',', $appointment)[0];
        $appointmentDisplay = $appointmentMap[$appointmentType] ?? 'N/A';

        $vl_balance_brought_forward = LeaveCredits::where('user_id', $user->id)->value('vlbalance_brought_forward') ?? 0;
        $sl_balance_brought_forward = LeaveCredits::where('user_id', $user->id)->value('slbalance_brought_forward') ?? 0;

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

        // $sheet->setCellValue('K11', 'Balance Brought Forward');
        // $sheet->setCellValue('N11', $vl_balance_brought_forward);
        $sheet->setCellValue('R11', $sl_balance_brought_forward);

        // Insert "Particulars" based on the selected months
        $startDate = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();

        $rowIndex = 11;
        // $firstMonthProcessed = false;
        $currentBalance = $vl_balance_brought_forward;

        // Set Balance Brought Forward
        $sheet->setCellValue('K' . $rowIndex, 'Balance Brought Forward');
        $sheet->setCellValue('N' . $rowIndex, $currentBalance);

        $rowIndex++;

        for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date->addMonth()) {
            $month = intval($date->format('n'));
            $year = $date->format('Y');
        
            $leaveCredits = LeaveCreditsCalculation::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->value('leave_credits_earned');
        
            $leaveCredits = $leaveCredits !== null ? $leaveCredits : 0;
        
            $sheet->setCellValue('K' . $rowIndex, $date->format('F Y')); 
        
            $sheet->setCellValue('L' . $rowIndex, $leaveCredits);
            $sheet->setCellValue('P' . $rowIndex, $leaveCredits);

            // if (!$firstMonthProcessed) {
            //     $sumValue = $leaveCredits + $vl_balance_brought_forward; // Sum of leave credits and balance brought forward
            //     $sheet->setCellValue('N' . $rowIndex, $sumValue); // Store the sum in column N
            //     $firstMonthProcessed = true; // Set the flag to true after processing the first month
            // } else {
            //     // If not the first month, just set the value in N column to the leave credits
            //     $sheet->setCellValue('N' . $rowIndex, $leaveCredits);
            // }

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

            $currentBalance += $leaveCredits;
            $sheet->setCellValue('N' . $rowIndex, $currentBalance);

            $rowIndex++;

            // if ($lateTime && $lateTime !== '00:00') {
            //     $rowIndex++;
            //     $sheet->setCellValue('K' . $rowIndex, 'Lates/Undertime');
                
            //     list($hours, $minutes) = explode(':', $lateTime);
            //     $totalMinutes = (intval($hours) * 60) + intval($minutes);
                
            //     $days = floor($totalMinutes / (8 * 60));
            //     $remainingMinutes = $totalMinutes % (8 * 60);
            //     $hours = floor($remainingMinutes / 60);
            //     $minutes = $remainingMinutes % 60;
                
            //     if ($days > 0) {
            //         $sheet->setCellValue('C' . $rowIndex, $days);
            //     }
            //     if ($hours > 0 || $days > 0) {
            //         $sheet->setCellValue('D' . $rowIndex, $hours);
            //     }
            //     $sheet->setCellValue('E' . $rowIndex, $minutes);

            //     $sheet->setCellValue('A' . $rowIndex, $totalVLDays);
            //     $sheet->setCellValue('B' . $rowIndex, $totalSickLeaveDays);

            //     $totalCreditsEarned = LeaveCreditsCalculation::where('user_id', $user->id)
            //         ->where('month', $month)
            //         ->where('year', $year)
            //         ->value('total_credits_earned') ?? 0;

            //     $sheet->setCellValue('M' . $rowIndex, $totalCreditsEarned);
            //     $subtracted = $sumValue - $totalCreditsEarned;
            //     $sheet->setCellValue('N' . $rowIndex, $subtracted);
            // }

            if ($lateTime && $lateTime !== '00:00') {
                $sheet->setCellValue('K' . $rowIndex, 'Lates/Undertime');
                
                list($hours, $minutes) = explode(':', $lateTime);
                $totalMinutes = (intval($hours) * 60) + intval($minutes);
                
                $days = floor($totalMinutes / (8 * 60));
                $remainingMinutes = $totalMinutes % (8 * 60);
                $hours = floor($remainingMinutes / 60);
                $minutes = $remainingMinutes % 60;
                
                // Store Lates/Undertime details in C, D, E columns
                if ($days > 0) {
                    $sheet->setCellValue('C' . $rowIndex, $days);
                }
                if ($hours > 0 || $days > 0) {
                    $sheet->setCellValue('D' . $rowIndex, $hours);
                }
                $sheet->setCellValue('E' . $rowIndex, $minutes);
    
                // Calculate Lates/Undertime in days
                $lateTimeDays = $totalMinutes / (8 * 60);
                $sheet->setCellValue('M' . $rowIndex, $lateTimeDays);
    
                // Subtract late time from current balance
                $currentBalance -= $lateTimeDays;
                $sheet->setCellValue('N' . $rowIndex, $currentBalance);
    
                $rowIndex++;
            }
        
            $rowIndex++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="LeaveCard.xlsx"',
        ]);
    }
}
