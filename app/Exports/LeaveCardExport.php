<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use App\Models\OfficeDivisions;
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
    protected $year;

    public function __construct($leaveApplicationId, $year)
    {
        $this->leaveApplicationId = $leaveApplicationId;
        $this->year = (int)$year;
    }

    private function setBoldLabelWithValue($sheet, $cell, $label, $value)
    {
        $richText = new RichText();
    
        $boldLabel = $richText->createTextRun($label);
        $boldLabel->getFont()->setBold(true)->setName('Arial');
        $boldValue = $richText->createTextRun($value);
        $boldValue->getFont()->setBold(true)->setName('Arial');
        $sheet->setCellValue($cell, $richText);
    }

    private function getMonthlyLeaveDetails($userId, $month, $leaveType)
    {
        // Get all approved leaves with pay for the specified type
        $leaves = LeaveApplication::where('user_id', $userId)
            ->where('type_of_leave', $leaveType)
            ->where('status', 'Approved')
            ->where('remarks', 'With Pay')
            ->whereNotNull('approved_dates')
            ->get();

        if ($leaves->isEmpty()) {
            return [
                'dates' => '',
                'total_days' => 0
            ];
        }

        $dates = [];
        $totalDays = 0;

        foreach ($leaves as $leave) {
            $approvedDatesString = trim($leave->approved_dates, '[]"');
            $approvedDates = array_map('trim', explode(',', $approvedDatesString));

            foreach ($approvedDates as $date) {
                $date = trim($date, '"\'');
                
                try {
                    $leaveDate = Carbon::parse($date);
                    
                    if ($leaveDate->year == $this->year && $leaveDate->month == $month) {
                        $dates[] = $leaveDate->format('d');
                        $totalDays += 1;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        if (empty($dates)) {
            return [
                'dates' => '',
                'total_days' => 0
            ];
        }

        sort($dates, SORT_NUMERIC);
        $monthName = Carbon::create()->month($month)->format('M');
        $datesString = count($dates) > 0 ? $monthName . '. ' . implode(', ', $dates) : '';

        return [
            'dates' => $datesString,
            'total_days' => $totalDays
        ];
    }

    private $lateTimeMatrix = [
        1 => 0.002, 2 => 0.004, 3 => 0.006, 4 => 0.008, 5 => 0.010,
        6 => 0.012, 7 => 0.015, 8 => 0.017, 9 => 0.019, 10 => 0.021,
        11 => 0.023, 12 => 0.025, 13 => 0.027, 14 => 0.029, 15 => 0.031,
        16 => 0.033, 17 => 0.035, 18 => 0.037, 19 => 0.040, 20 => 0.042,
        21 => 0.044, 22 => 0.046, 23 => 0.048, 24 => 0.050, 25 => 0.052,
        26 => 0.054, 27 => 0.056, 28 => 0.058, 29 => 0.060, 30 => 0.062,
        31 => 0.065, 32 => 0.067, 33 => 0.069, 34 => 0.071, 35 => 0.073,
        36 => 0.075, 37 => 0.077, 38 => 0.079, 39 => 0.081, 40 => 0.083,
        41 => 0.085, 42 => 0.087, 43 => 0.090, 44 => 0.092, 45 => 0.094,
        46 => 0.096, 47 => 0.098, 48 => 0.100, 49 => 0.102, 50 => 0.104,
        51 => 0.106, 52 => 0.108, 53 => 0.110, 54 => 0.112, 55 => 0.115,
        56 => 0.117, 57 => 0.119, 58 => 0.121, 59 => 0.123, 60 => 0.125
    ];

    private function getMonthlyLateDetails($userId, $month)
    {
        $lateRecord = LeaveCreditsCalculation::where('user_id', $userId)
            ->where('month', (string)$month)
            ->where('year', (string)$this->year)
            ->first();

        if (!$lateRecord || !$lateRecord->late_time) {
            return null;
        }

        // Parse the time string (HH:mm format)
        list($hours, $minutes) = explode(':', $lateRecord->late_time);
        $hours = (int)$hours;
        $minutes = (int)$minutes;

        // Calculate total deduction
        $deduction = 0;
        
        // Add hours deduction (0.125 per hour)
        if ($hours > 0) {
            $deduction += ($hours * 0.125);
        }

        // Add minutes deduction from matrix
        if ($minutes > 0) {
            $deduction += $this->lateTimeMatrix[$minutes];
        }

        return [
            'time' => $lateRecord->late_time,
            'deduction' => round($deduction, 3)
        ];
    }

    private function getMonthlyEarnedCredits($userId, $month)
    {
        $creditRecord = LeaveCreditsCalculation::where('user_id', $userId)
            ->where('month', (string)$month)
            ->where('year', (string)$this->year)
            ->first();

        return $creditRecord ? $creditRecord->leave_credits_earned : null;
    }

    private function formatLeaveDetails($leaveDetails, $leaveType, $month)
    {
        if (empty($leaveDetails['dates'])) {
            return '';
        }

        $monthName = Carbon::create()->month($month)->format('M');
        $dates = explode(', ', substr($leaveDetails['dates'], strlen($monthName . '. ')));
        return $leaveType . ' - ' . $monthName . '. ' . implode(', ', $dates);
    }

    public function export(): StreamedResponse
    {
        $user = Auth::user();
        
        // Fetching user data
        $position = Positions::find($user->position_id)->position ?? 'N/A';
        $department = OfficeDivisions::find($user->office_division_id)->office_division ?? 'N/A';
        $dateHired = UserData::where('user_id', $user->id)->value('date_hired');

        if ($dateHired) {
            $formattedDateHired = Carbon::createFromFormat('Y-m-d', $dateHired)->format('m/d/Y');
        } else {
            $formattedDateHired = 'N/A';
        }

        $templatePath = storage_path('app/public/leave_template/LeaveLedgerTemplate.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $richText = new RichText();
        $yearText = $richText->createTextRun("FOR THE YEAR " . $this->year);
        $yearText->getFont()->setBold(true);
        $yearText->getFont()->setName('Arial');
        $sheet->setCellValue('K5', $richText);

        // Fill in the basic user information
        $this->setBoldLabelWithValue($sheet, 'B7', 'NAME: ', $user->name ?? 'N/A');
        $this->setBoldLabelWithValue($sheet, 'B8', 'DATE APPOINTED: ', $formattedDateHired ?? 'N/A');
        $this->setBoldLabelWithValue($sheet, 'N7', 'POSITION: ', $position ?? 'N/A');
        $this->setBoldLabelWithValue($sheet, 'N8', 'DEPARTMENT: ', $department ?? 'N/A');

        $previousYear = $this->year - 1;
        $previousDecBalance = MonthlyCredits::where('user_id', $user->id)
            ->where('year', $previousYear)
            ->where('month', 12)
            ->first();

        // Set the "Leave balance as of" text in D15
        $richText = new RichText();
        $balanceText = $richText->createTextRun("Leave balance as of Dec {$previousYear}");
        $balanceText->getFont()->setBold(true);
        $balanceText->getFont()->setName('Arial');
        $sheet->setCellValue('D15', $richText);

        // Set the balances
        if ($previousDecBalance) {
            $sheet->setCellValue('L15', $previousDecBalance->vl_latest_credits);
            $sheet->setCellValue('P15', $previousDecBalance->sl_latest_credits);
        } else {
            $sheet->setCellValue('L15', 0);
            $sheet->setCellValue('P15', 0);
        }

        // Get current balance from L15
        $currentVLBalance  = floatval($sheet->getCell('L15')->getValue());
        $currentSLBalance = floatval($sheet->getCell('P15')->getValue());

        // Array of months
        $months = [
            1 => 'JANUARY',
            2 => 'FEBRUARY',
            3 => 'MARCH',
            4 => 'APRIL',
            5 => 'MAY',
            6 => 'JUNE',
            7 => 'JULY',
            8 => 'AUGUST',
            9 => 'SEPTEMBER',
            10 => 'OCTOBER',
            11 => 'NOVEMBER',
            12 => 'DECEMBER'
        ];

        // Set month label and process January (Row 17)
        // $sheet->setCellValue('B17', 'JANUARY');
        // $januaryLeaves = $this->getMonthlyLeaveDetails($user->id, 1);
        
        // if (!empty($januaryLeaves['dates'])) {
        //     $sheet->setCellValue('D17', $januaryLeaves['dates']);
        // }
        
        // if ($januaryLeaves['total_days'] > 0) {
        //     $sheet->setCellValue('K17', $januaryLeaves['total_days']);
        //     $newBalance = $currentBalance - $januaryLeaves['total_days'];
        //     $sheet->setCellValue('L17', $newBalance);
        // } else {
        //     $sheet->setCellValue('L17', $currentBalance); // Maintain previous balance if no leaves
        // }

        // Process January (Row 17)
        $currentRow = 17;
        $sheet->setCellValue('B' . $currentRow, 'JAUNARY');
        
        // Process all months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = $months[$month];
            $hasEntries = false;

            // First row: Process leaves (VL and SL)
            $vlLeaves = $this->getMonthlyLeaveDetails($user->id, $month, 'Vacation Leave');
            $slLeaves = $this->getMonthlyLeaveDetails($user->id, $month, 'Sick Leave');
            
            if (!empty($vlLeaves['dates']) || !empty($slLeaves['dates'])) {
                $sheet->setCellValue('B' . $currentRow, $monthName);
                
                // Format the dates string with VL/SL indicators
                $datesString = '';
                
                if (!empty($vlLeaves['dates'])) {
                    $datesString = $this->formatLeaveDetails($vlLeaves, 'VL', $month);
                }
                
                if (!empty($slLeaves['dates'])) {
                    $slDatesString = $this->formatLeaveDetails($slLeaves, 'SL', $month);
                    if (!empty($datesString)) {
                        $datesString .= ' & ' . $slDatesString;
                    } else {
                        $datesString = $slDatesString;
                    }
                }
                
                $sheet->setCellValue('D' . $currentRow, $datesString);
                
                // Handle VL balance with per-row excess
                if ($vlLeaves['total_days'] > 0) {
                    $sheet->setCellValue('K' . $currentRow, $vlLeaves['total_days']);
                    $newVLBalance = $currentVLBalance - $vlLeaves['total_days'];
                    
                    if ($newVLBalance < 0) {
                        $sheet->setCellValue('M' . $currentRow, round(abs($newVLBalance), 3));
                        $currentVLBalance = 0;
                    } else {
                        $currentVLBalance = $newVLBalance;
                    }
                    
                    $sheet->setCellValue('L' . $currentRow, round($currentVLBalance, 3));
                }
                
                // Handle SL balance with per-row excess
                if ($slLeaves['total_days'] > 0) {
                    $sheet->setCellValue('O' . $currentRow, $slLeaves['total_days']);
                    $newSLBalance = $currentSLBalance - $slLeaves['total_days'];
                    
                    if ($newSLBalance < 0) {
                        $sheet->setCellValue('Q' . $currentRow, round(abs($newSLBalance), 3));
                        $currentSLBalance = 0;
                    } else {
                        $currentSLBalance = $newSLBalance;
                    }
                    
                    $sheet->setCellValue('P' . $currentRow, round($currentSLBalance, 3));
                }
                
                $currentRow++;
                $hasEntries = true;
            }

            // Second row: Process undertime/lates (VL only)
            $monthLates = $this->getMonthlyLateDetails($user->id, $month);
            if ($monthLates) {
                $sheet->setCellValue('B' . $currentRow, $monthName);
                $sheet->setCellValue('D' . $currentRow, 'Undertime/Late');
                $sheet->setCellValue('K' . $currentRow, $monthLates['deduction']);
                
                $newVLBalance = $currentVLBalance - $monthLates['deduction'];
                if ($newVLBalance < 0) {
                    $sheet->setCellValue('M' . $currentRow, round(abs($newVLBalance), 3));
                    $currentVLBalance = 0;
                } else {
                    $currentVLBalance = $newVLBalance;
                }
                
                $sheet->setCellValue('L' . $currentRow, round($currentVLBalance, 3));
                $currentRow++;
                $hasEntries = true;
            }

            // Third row: Process earned credits (affects both VL and SL)
            $earnedCredits = $this->getMonthlyEarnedCredits($user->id, $month);
            if ($earnedCredits !== null) {
                $sheet->setCellValue('B' . $currentRow, $monthName);
                
                // Add earned credits to both VL and SL
                $sheet->setCellValue('J' . $currentRow, $earnedCredits); // VL earned
                $currentVLBalance += $earnedCredits;
                $sheet->setCellValue('L' . $currentRow, round($currentVLBalance, 3));
                
                $sheet->setCellValue('N' . $currentRow, $earnedCredits); // SL earned
                $currentSLBalance += $earnedCredits;
                $sheet->setCellValue('P' . $currentRow, round($currentSLBalance, 3));
                
                $currentRow++;
                $hasEntries = true;
            }

            // Add a blank row between months if there were any entries
            if ($hasEntries && $month < 12) {
                $currentRow++;
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="LeaveLedger_' . $this->year . '.xlsx"',
        ]);
    }

    private function calculateBalanceBroughtForward($userId, Carbon $startDate)
    {
        // Get previous year's December balance
        $previousYear = $this->year - 1;
        $previousDecBalance = MonthlyCredits::where('user_id', $userId)
            ->where('year', $previousYear)
            ->where('month', 12)
            ->first();

        if ($previousDecBalance) {
            return [
                'vl' => $previousDecBalance->vl_latest_credits,
                'sl' => $previousDecBalance->sl_latest_credits,
                'found_date' => $startDate->format('Y-m-d')
            ];
        }

        return [
            'vl' => 0,
            'sl' => 0,
            'found_date' => null
        ];
    }
}