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
use Illuminate\Support\Facades\Auth;

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
        $boldPart = $richText->createTextRun($label);
        $boldPart->getFont()->setBold(true);
        $richText->createText($value);
        $sheet->setCellValue($cell, $richText);
    }

    // public function export(): StreamedResponse
    // {
    //     $leaveApplication = LeaveApplication::with('user')->findOrFail($this->leaveApplicationId);
    //     $user = $leaveApplication->user;

    //     // Fetching user data
    //     $user = $leaveApplication->user;
    //     $position = Positions::find($user->position_id)->position ?? 'N/A';
    //     $civilStatus = UserData::where('user_id', $user->id)->value('civil_status') ?? 'N/A';
    //     $gsis = UserData::where('user_id', $user->id)->value('gsis') ?? 'N/A';
    //     $tin = UserData::where('user_id', $user->id)->value('tin') ?? 'N/A';
    //     $officeDivisionId = $user->unit_id;
    //     $unit = OfficeDivisionUnits::where('id', $officeDivisionId)->value('unit') ?? 'N/A';
    //     $appointment = UserData::where('user_id', $user->id)->value('appointment') ?? 'N/A';
    //     $dateHired = UserData::where('user_id', $user->id)->value('date_hired');

    //     if ($dateHired) {
    //         $formattedDateHired = \Carbon\Carbon::createFromFormat('Y-m-d', $dateHired)->format('m/d/Y');
    //     } else {
    //         $formattedDateHired = 'N/A';
    //     }

    //     $appointmentMap = [
    //         'plantilla' => 'Plantilla',
    //         'cos' => 'Contract of Service',
    //         'ct' => 'Co-Terminus',
    //         'pa' => 'Presidential Appointee',
    //     ];

    //     $appointmentType = explode(',', $appointment)[0];
    //     $appointmentDisplay = $appointmentMap[$appointmentType] ?? 'N/A';

    //     $startDateCarbon = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
    //     $endDateCarbon = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();
        
    //     $balanceData = $this->calculateBalanceBroughtForward($user->id, $startDateCarbon, $endDateCarbon);

    //     $approvedStatuses = ['Approved', 'Approved by HR', 'Approved by Supervisor'];
    //     $leaveTypes = ['Vacation Leave', 'Sick Leave'];

    //     $approvedLeaves = LeaveApplication::where('user_id', $user->id)
    //         ->whereIn('status', $approvedStatuses)
    //         ->whereBetween('updated_at', [
    //             Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth(),
    //             Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth()
    //         ])
    //         ->sum('approved_days') ?? 0;

    //     $templatePath = storage_path('app/public/leave_template/LEAVE-CARD.xlsx');
    //     $spreadsheet = IOFactory::load($templatePath);
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $this->setBoldLabelWithValue($sheet, 'A3', 'Name: ', $user->name);
    //     $this->setBoldLabelWithValue($sheet, 'A4', 'Position: ', $position);
    //     $this->setBoldLabelWithValue($sheet, 'L3', 'Civil Status: ', $civilStatus);
    //     $this->setBoldLabelWithValue($sheet, 'R3', 'GSIS Policy No: ', $gsis);
    //     $this->setBoldLabelWithValue($sheet, 'R4', 'TIN No: ', $tin);
    //     $this->setBoldLabelWithValue($sheet, 'L5', 'Unit: ', $unit);
    //     $this->setBoldLabelWithValue($sheet, 'A5', 'Status: ', $appointmentDisplay);
    //     $this->setBoldLabelWithValue($sheet, 'L4', 'Entrance to Duty: ', $formattedDateHired);

    //     $startDate = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
    //     $endDate = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();

    //     $rowIndex = 11;
    //     $currentBalance = $balanceData['vl'];
    //     $currentBalanceSl = $balanceData['sl'];    

    //     $sheet->setCellValue('K' . $rowIndex, 'Balance Brought Forward');
    //     $sheet->setCellValue('N' . $rowIndex, $currentBalance);
    //     $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);

    //     $rowIndex++;

    //     $firstDataFound = false;
    //     for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date->addMonth()) {
    //         $month = intval($date->format('n'));
    //         $year = $date->format('Y');
        
    //         $leaveCredits = LeaveCreditsCalculation::where('user_id', $user->id)
    //             ->where('month', $month)
    //             ->where('year', $year)
    //             ->value('leave_credits_earned');

    //         $monthlyCredit = MonthlyCredits::where('user_id', $user->id)
    //             ->where('month', $month)
    //             ->where('year', $year)
    //             ->first();
        
    //         $leaveCredits = $leaveCredits !== null ? $leaveCredits : 0;
        
    //         $sheet->setCellValue('K' . $rowIndex, $date->format('F Y')); 

    //         if (!$firstDataFound && $leaveCredits > 0) {
    //             $firstDataFound = true;
    //             $sheet->setCellValue('N11', $currentBalance);
    //             $sheet->setCellValue('R11', $currentBalanceSl);
    //         }
        
    //         if ($firstDataFound) {
    //         $sheet->setCellValue('L' . $rowIndex, $leaveCredits);
    //         $sheet->setCellValue('P' . $rowIndex, $leaveCredits);

    //         $currentBalance += $leaveCredits;
    //         $sheet->setCellValue('N' . $rowIndex, $currentBalance);
    //         $currentBalanceSl += $leaveCredits;
    //         $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);
    //     } else {
    //         $sheet->setCellValue('L' . $rowIndex, 0);
    //         $sheet->setCellValue('P' . $rowIndex, 0);
    //         $sheet->setCellValue('N' . $rowIndex, 0);
    //         $sheet->setCellValue('R' . $rowIndex, 0);
    //     }
    //         $lateTime = LeaveCreditsCalculation::where('user_id', $user->id)
    //             ->where('month', $month)
    //             ->where('year', $year)
    //             ->value('late_time');

    //         $totalVLDays = 0;
    //         $approvedLeaves = LeaveApplication::where('user_id', $user->id)
    //             ->where('status', 'Approved')
    //             ->where('remarks', 'With Pay')
    //             ->get();
            
    //         foreach ($approvedLeaves as $leave) {
    //             $approvedDates = explode(',', $leave->date_of_filing);
    //             $leaveTypes = explode(',', $leave->type_of_leave);
                
    //             if (in_array('Vacation Leave', $leaveTypes)) {
    //                 foreach ($approvedDates as $approvedDate) {
    //                     $approvedDate = Carbon::parse($approvedDate)->startOfDay();
                        
    //                     if ($approvedDate->between(
    //                         Carbon::createFromDate($year, $month, 1)->startOfMonth(),
    //                         Carbon::createFromDate($year, $month, 1)->endOfMonth()
    //                     )) {
    //                         $totalVLDays += $leave->approved_days;
    //                     }
    //                 }
    //             }
    //         }

    //         $totalSickLeaveDays = 0;
    //         foreach ($approvedLeaves as $leave) {
    //             $approvedDates = explode(',', $leave->date_of_filing);
    //             $leaveTypes = explode(',', $leave->type_of_leave);
                
    //             if (in_array('Sick Leave', $leaveTypes)) {
    //                 foreach ($approvedDates as $approvedDate) {
    //                     $approvedDate = Carbon::parse($approvedDate)->startOfDay();
                        
    //                     if ($approvedDate->between(
    //                         Carbon::createFromDate($year, $month, 1)->startOfMonth(),
    //                         Carbon::createFromDate($year, $month, 1)->endOfMonth()
    //                     )) {
    //                         $totalSickLeaveDays += $leave->approved_days;
    //                     }
    //                 }
    //             }
    //         }

    //         $rowIndex++;

    //         if ($totalVLDays > 0 || $totalSickLeaveDays > 0) {
    //             $sheet->setCellValue('K' . $rowIndex, 'Total Leave');
                
    //             if ($totalVLDays > 0) {
    //                 $sheet->setCellValue('A' . $rowIndex, $totalVLDays);
    //                 $sheet->setCellValue('M' . $rowIndex, $totalVLDays);
    //                 $currentBalance -= $totalVLDays;
    //                 $sheet->setCellValue('N' . $rowIndex, $currentBalance);
    //             }
                
    //             if ($totalSickLeaveDays > 0) {
    //                 $sheet->setCellValue('B' . $rowIndex, $totalSickLeaveDays);
    //                 $sheet->setCellValue('Q' . $rowIndex, $totalSickLeaveDays);
    //                 $currentBalanceSl -= $totalSickLeaveDays;
    //                 $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);
    //             }
                
    //             $rowIndex++;
    //         }

    //         if ($lateTime && $lateTime !== '00:00') {
    //             $sheet->setCellValue('K' . $rowIndex, 'Lates/Undertime');
                
    //             list($hours, $minutes) = explode(':', $lateTime);
    //             $totalMinutes = (intval($hours) * 60) + intval($minutes);
                
    //             $days = floor($totalMinutes / (8 * 60));
    //             $remainingMinutes = $totalMinutes % (8 * 60);
    //             $hours = floor($remainingMinutes / 60);
    //             $minutes = $remainingMinutes % 60;
        
    //             if ($days > 0) {
    //                 $sheet->setCellValue('C' . $rowIndex, $days);
    //             }
    //             if ($hours > 0) {
    //                 $sheet->setCellValue('D' . $rowIndex, $hours);
    //             }
    //             if ($minutes > 0) {
    //                 $sheet->setCellValue('E' . $rowIndex, $minutes);
    //             }
        
    //             $lateTimeDays = $totalMinutes / (8 * 60);
    //             $sheet->setCellValue('M' . $rowIndex, $lateTimeDays);
    //             $currentBalance -= $lateTimeDays;
    //             $sheet->setCellValue('N' . $rowIndex, $currentBalance);
        
    //             $rowIndex++;
    //         }

    //     }

    //     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     return new StreamedResponse(function () use ($writer) {
    //         $writer->save('php://output');
    //     }, 200, [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //         'Content-Disposition' => 'attachment; filename="LeaveCard.xlsx"',
    //     ]);
    // }

    public function export(): StreamedResponse
    {
        // Get the user directly instead of through leave application
        $user = Auth::user();

        // Fetching user data (this part remains unchanged)
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
            $formattedDateHired = 'N/A';
        }

        $appointmentMap = [
            'plantilla' => 'Plantilla',
            'cos' => 'Contract of Service',
            'ct' => 'Co-Terminus',
            'pa' => 'Presidential Appointee',
        ];

        $appointmentType = explode(',', $appointment)[0];
        $appointmentDisplay = $appointmentMap[$appointmentType] ?? 'N/A';

        $startDateCarbon = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
        $endDateCarbon = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();
        
        $balanceData = $this->calculateBalanceBroughtForward($user->id, $startDateCarbon, $endDateCarbon);

        $templatePath = storage_path('app/public/leave_template/LEAVE-CARD.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Fill in the basic user information
        $this->setBoldLabelWithValue($sheet, 'A3', 'Name: ', $user->name);
        $this->setBoldLabelWithValue($sheet, 'A4', 'Position: ', $position);
        $this->setBoldLabelWithValue($sheet, 'L3', 'Civil Status: ', $civilStatus);
        $this->setBoldLabelWithValue($sheet, 'R3', 'GSIS Policy No: ', $gsis);
        $this->setBoldLabelWithValue($sheet, 'R4', 'TIN No: ', $tin);
        $this->setBoldLabelWithValue($sheet, 'L5', 'Unit: ', $unit);
        $this->setBoldLabelWithValue($sheet, 'A5', 'Status: ', $appointmentDisplay);
        $this->setBoldLabelWithValue($sheet, 'L4', 'Entrance to Duty: ', $formattedDateHired);

        $startDate = Carbon::createFromFormat('Y-m', $this->startDate)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $this->endDate)->endOfMonth();

        $rowIndex = 11;
        $currentBalance = $balanceData['vl'];
        $currentBalanceSl = $balanceData['sl'];    

        $sheet->setCellValue('K' . $rowIndex, 'Balance Brought Forward');
        $sheet->setCellValue('N' . $rowIndex, $currentBalance);
        $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);

        $rowIndex++;

        $firstDataFound = false;
        for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date->addMonth()) {
            $month = intval($date->format('n'));
            $year = $date->format('Y');
        
            // Get leave credits calculation
            $leaveCredits = LeaveCreditsCalculation::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->value('leave_credits_earned') ?? 0;

            $monthlyCredit = MonthlyCredits::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
        
            $sheet->setCellValue('K' . $rowIndex, $date->format('F Y')); 

            // Process leave credits
            if (!$firstDataFound && $leaveCredits > 0) {
                $firstDataFound = true;
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
                $sheet->setCellValue('L' . $rowIndex, 0);
                $sheet->setCellValue('P' . $rowIndex, 0);
                $sheet->setCellValue('N' . $rowIndex, 0);
                $sheet->setCellValue('R' . $rowIndex, 0);
            }

            // Get late time
            $lateTime = LeaveCreditsCalculation::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->value('late_time');

            // Only process approved leaves if they exist
            $totalVLDays = 0;
            $totalSickLeaveDays = 0;
            
            $approvedLeaves = LeaveApplication::where('user_id', $user->id)
                ->where('status', 'Approved')
                ->where('remarks', 'With Pay')
                ->get();
            
            if ($approvedLeaves->isNotEmpty()) {
                foreach ($approvedLeaves as $leave) {
                    $approvedDates = explode(',', $leave->date_of_filing);
                    $leaveTypes = explode(',', $leave->type_of_leave);
                    
                    if (in_array('Vacation Leave', $leaveTypes)) {
                        foreach ($approvedDates as $approvedDate) {
                            $approvedDate = Carbon::parse($approvedDate)->startOfDay();
                            
                            if ($approvedDate->between(
                                Carbon::createFromDate($year, $month, 1)->startOfMonth(),
                                Carbon::createFromDate($year, $month, 1)->endOfMonth()
                            )) {
                                $totalVLDays += $leave->approved_days;
                            }
                        }
                    }

                    if (in_array('Sick Leave', $leaveTypes)) {
                        foreach ($approvedDates as $approvedDate) {
                            $approvedDate = Carbon::parse($approvedDate)->startOfDay();
                            
                            if ($approvedDate->between(
                                Carbon::createFromDate($year, $month, 1)->startOfMonth(),
                                Carbon::createFromDate($year, $month, 1)->endOfMonth()
                            )) {
                                $totalSickLeaveDays += $leave->approved_days;
                            }
                        }
                    }
                }
            }

            $rowIndex++;

            // Only add leave information if there are actual leaves
            if ($totalVLDays > 0 || $totalSickLeaveDays > 0) {
                $sheet->setCellValue('K' . $rowIndex, 'Total Leave');
                
                if ($totalVLDays > 0) {
                    $sheet->setCellValue('A' . $rowIndex, $totalVLDays);
                    $sheet->setCellValue('M' . $rowIndex, $totalVLDays);
                    $currentBalance -= $totalVLDays;
                    $sheet->setCellValue('N' . $rowIndex, $currentBalance);
                }
                
                if ($totalSickLeaveDays > 0) {
                    $sheet->setCellValue('B' . $rowIndex, $totalSickLeaveDays);
                    $sheet->setCellValue('Q' . $rowIndex, $totalSickLeaveDays);
                    $currentBalanceSl -= $totalSickLeaveDays;
                    $sheet->setCellValue('R' . $rowIndex, $currentBalanceSl);
                }
                
                $rowIndex++;
            }

            // Process late time if it exists
            if ($lateTime && $lateTime !== '00:00') {
                $sheet->setCellValue('K' . $rowIndex, 'Lates/Undertime');
                
                list($hours, $minutes) = explode(':', $lateTime);
                $totalMinutes = (intval($hours) * 60) + intval($minutes);
                
                $days = floor($totalMinutes / (8 * 60));
                $remainingMinutes = $totalMinutes % (8 * 60);
                $hours = floor($remainingMinutes / 60);
                $minutes = $remainingMinutes % 60;
        
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
                $sheet->setCellValue('M' . $rowIndex, $lateTimeDays);
                $currentBalance -= $lateTimeDays;
                $sheet->setCellValue('N' . $rowIndex, $currentBalance);
        
                $rowIndex++;
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="LeaveCard.xlsx"',
        ]);
    }

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

        return [
            'vl' => 0,
            'sl' => 0,
            'found_date' => null
        ];
    }
}
