<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class PayrollExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    protected $payrolls;
    protected $rowNumber = 0;

    public function __construct($payrolls)
    {
        // Convert to a collection if it's not already one
        $this->payrolls = $payrolls instanceof Collection ? $payrolls : collect($payrolls);
    }

    public function collection()
    {
        return $this->payrolls;
    }

    public function map($payroll): array
    {
        $this->rowNumber++;

        // Adjust this based on whether $payroll is an object or an array
        $getData = function($key) use ($payroll) {
            return is_array($payroll) ? ($payroll[$key] ?? '') : ($payroll->$key ?? '');
        };

        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $zeroCheck = function($value) {
            if ($value == 0 || $value == null) {
                return "-";
            }
            return $value;
        };

        return [
            $this->rowNumber,
            $getData('name'),
            $getData('employee_number'),
            $getData('position'),
            $getData('salary_grade'),
            $formatCurrency($getData('daily_salary_rate')),
            $zeroCheck($getData('no_of_days_covered')),
            $zeroCheck($getData('regular_holidays')),
            $formatCurrency($getData('regular_holidays_amount')),
            $zeroCheck($getData('special_holidays')),
            $formatCurrency($getData('special_holidays_amount')),
            $zeroCheck($getData('leave_days_withpay')),
            $formatCurrency($getData('leave_payment')),
            $formatCurrency($getData('gross_salary')),
            $zeroCheck($getData('leave_days_withoutpay')),
            $formatCurrency($getData('leave_days_withoutpay_amount')),
            $zeroCheck($getData('absences_days')),
            $formatCurrency($getData('absences_amount')),
            $zeroCheck($getData('late_undertime_hours')),
            $formatCurrency($getData('late_undertime_hours_amount')),
            $zeroCheck($getData('late_undertime_mins')),
            $formatCurrency($getData('late_undertime_mins_amount')),
            $formatCurrency($getData('gross_salary_less')),
            $formatCurrency($getData('withholding_tax')),
            $formatCurrency($getData('nycempc')),
            $formatCurrency($getData('total_deductions')),
            $getData('net_amount_due') == 0 ? '₱ 0.00' : $formatCurrency($getData('net_amount_due')),
        ];
    }

    public function headings(): array
    {
        return [
            'NO.',
            'NAME',
            'ID NUMBER',
            'POSITION',
            'SALARY GRADE',
            'DAILY SALARY RATE',
            'NO. OF DAYS COVERED',
            'REGULAR HOLIDAY/S',
            'REGULAR HOLIDAY/S (AMOUNT)',
            'SPECIAL HOLIDAY/S',
            'SPECIAL HOLIDAY/S (AMOUNT)',
            'LEAVE WITH PAY',
            'LEAVE WITH PAY (AMOUNT)',
            'GROSS SALARY',
            'LEAVE WITHOUT PAY',
            'LEAVE WITHOUT PAY (AMOUNT)',
            'ABSENCES (DAYS)',
            'ABSENCES (DAYS AMOUNT)',
            'LATE&UNDERTIME (HOURS)',
            'LATE&UNDERTIME (HOURS AMOUNT)',
            'LATE&UNDERTIME (MINS.)',
            'LATE&UNDERTIME (MINS. AMOUNT)',
            'GROSS SALARY LESS (ABSENCES/LATES/UNDERTIME)',
            'WITHHOLDING TAX',
            'NYCEMP',
            'TOTAL DEDUCTIONS',
            'NET AMOUNT DUE',
        ];
    }
}
