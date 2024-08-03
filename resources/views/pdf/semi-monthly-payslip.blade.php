<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .container { width: 100%; max-width: 396px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 4px; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Employee Payslip</h2>
        <p><strong>Pay Period:</strong> {{ $payslip->start_date }} to {{ $payslip->end_date }}</p>
        
        <table>
            <tr>
                <th>Employee Name</th>
                <td>{{ $payslip->name }}</td>
            </tr>
            <tr>
                <th>Employee Number</th>
                <td>{{ $payslip->employee_number }}</td>
            </tr>
            <tr>
                <th>Position</th>
                <td>{{ $payslip->position }}</td>
            </tr>
            <tr>
                <th>Salary Grade</th>
                <td>{{ $payslip->salary_grade }}</td>
            </tr>
        </table>

        <h3 class="mt-2">Earnings</h3>
        <table>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
            <tr>
                <td>Basic Salary ({{ $payslip->no_of_days_covered }} days)</td>
                <td class="text-right">{{ number_format($payslip->gross_salary, 2) }}</td>
            </tr>
            @if($payslip->regular_holidays > 0)
            <tr>
                <td>Regular Holiday Pay ({{ $payslip->regular_holidays }} days)</td>
                <td class="text-right">{{ number_format($payslip->regular_holidays_amount, 2) }}</td>
            </tr>
            @endif
            @if($payslip->special_holidays > 0)
            <tr>
                <td>Special Holiday Pay ({{ $payslip->special_holidays }} days)</td>
                <td class="text-right">{{ number_format($payslip->special_holidays_amount, 2) }}</td>
            </tr>
            @endif
            @if($payslip->leave_days_withpay > 0)
            <tr>
                <td>Leave with Pay ({{ $payslip->leave_days_withpay }} days)</td>
                <td class="text-right">{{ number_format($payslip->leave_payment, 2) }}</td>
            </tr>
            @endif
        </table>

        <h3 class="mt-2">Deductions</h3>
        <table>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
            @if($payslip->absences_days > 0)
            <tr>
                <td>Absences ({{ $payslip->absences_days }} days)</td>
                <td class="text-right">{{ number_format($payslip->absences_amount, 2) }}</td>
            </tr>
            @endif
            @if($payslip->late_undertime_hours > 0 || $payslip->late_undertime_mins > 0)
            <tr>
                <td>Late/Undertime ({{ $payslip->late_undertime_hours }}h {{ $payslip->late_undertime_mins }}m)</td>
                <td class="text-right">{{ number_format($payslip->late_undertime_hours_amount + $payslip->late_undertime_mins_amount, 2) }}</td>
            </tr>
            @endif
            @if($payslip->leave_days_withoutpay > 0)
            <tr>
                <td>Leave without Pay ({{ $payslip->leave_days_withoutpay }} days)</td>
                <td class="text-right">{{ number_format($payslip->leave_days_withoutpay_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>Withholding Tax</td>
                <td class="text-right">{{ number_format($payslip->withholding_tax, 2) }}</td>
            </tr>
            <tr>
                <td>NYCEMPC</td>
                <td class="text-right">{{ number_format($payslip->nycempc, 2) }}</td>
            </tr>
        </table>

        <h3 class="mt-2">Summary</h3>
        <table>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
            <tr>
                <td>Gross Salary</td>
                <td class="text-right">{{ number_format($payslip->gross_salary, 2) }}</td>
            </tr>
            <tr>
                <td>Total Deductions</td>
                <td class="text-right">{{ number_format($payslip->total_deductions, 2) }}</td>
            </tr>
            <tr>
                <th>Net Amount Due</th>
                <th class="text-right">{{ number_format($payslip->net_amount_due, 2) }}</th>
            </tr>
        </table>
    </div>
</body>
</html>