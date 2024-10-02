<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 20px}
        .container { width: 100%; max-width: 430px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 10px; }
        td { font-size: 9px; text-align: left; line-height: 10px; }
        tr { padding: 2 auto; width: 100%;}
        .data { font-size: 9px; font-weight: 600;}
        .dots {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .currency { font-family: DejaVu Sans; sans-serif; text-align: right !important; }

        .hidden{
            display: none;
        }

        .bold{
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
        .italic{
            font-style: italic;
        }
    </style>
</head>
<body>

    @php
        $formatCurrency = function($value) {
            if($value == null){
                return '0.00';
            }
            return number_format((float)$value, 2, '.', ',');
        };

        $halfSalary = $payslip['rate_per_month'] / 2;
        $perDay = $payslip['rate_per_month'] / 22;
        $perHour = $perDay / 8;
        $perMinute = $perHour / 60;
        $lateUndertimeAbsencesAmount = $payslip['absences_amount'] + $payslip['late_undertime_hours_amount'] + $payslip['late_undertime_mins_amount'];
        $lateUndertimeAbsencesAmount2 = $payslip2['absences_amount'] + $payslip2['late_undertime_hours_amount'] + $payslip2['late_undertime_mins_amount'];
        $totalNetPay = $payslip['net_amount_due'] + $payslip2['net_amount_due'];
    @endphp

    <div class="container" style="border: solid 2px black; padding: 10px; margin-left: 100px; margin-top: -20px">
         {{-- Header --}}
        <div style="display:flex; margin-bottom: 30px;">
            <center style="display:flex;">
                <img src="images/nyc-logo.png" width="35" style="margin-bottom: 2px">
                <img src="images/bagong-pilipinas-logo.png" width="35" style="margin-bottom: 5px">
                <img src="images/payslip-header.png" width="200">
            </center>
        </div>

        <table>
            <tbody>
                <tr>
                    <td width="20%" class="bold">Office/Division:</td>
                    <td>{{ $payslip['office_division'] }}</td>
                </tr>
                <tr>
                    <td width="20%" class="bold">Pay Period:</td>
                    <td>
                        {{ $monthPaylipFor ?: '' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td width="20%" class="bold">Employee's Name:</td>
                    <td width="30%">{{ $payslip['name'] }}</td>
                    <td></td>
                    <td width="20%" class="bold">Acct. No: </td>
                    <td width="15%"></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="20%" class="bold">Position:</td>
                    <td width="30%">{{ $payslip['position'] ?: '' }}</td>
                    <td></td>
                    <td width="20%" class="bold">Employee No: </td>
                    <td width="15%">{{ $payslip['employee_number'] ?: '' }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        
        <center><p class="data" style="margin: 6px 0;">***EARNINGS***</p></center>

        <table>
            <tbody>
                <tr>
                    <td class="bold">Monthly Salary
                    </td>
                    <td class="currency bold" width="30%">{{ $payslip['rate_per_month'] ? $formatCurrency($payslip['rate_per_month']) : '-' }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">1/2 Month Salary........................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($halfSalary) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">Per Day......................................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($perDay) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">Per Hour.....................................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($perHour) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">Per Minute..................................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($perMinute) }}</td>
                </tr>
            </tbody>
        </table>

        <center><p style="margin: 6px 0;"></p></center>

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td width="50%">
                            <div style="width: 100%">
                                <center><p class="data bold" style="color: red">{{ $payslipFor ?: '' }}</p></center>
                            </div>
                        </td>
                        <td width="50%">
                            <div style="width: 100%">
                                <table style="border: 1px solid black; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #DDEBF7; border: 1px solid black;">
                                            <th class="bold data text-center" style="border: 1px solid black; padding: 5px;">Days Rendered</th>
                                            <th class="bold data text-center" style="border: 1px solid black; padding: 5px;">Daily Rate</th>
                                            <th class="bold data text-center" style="border: 1px solid black; padding: 5px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="border: 1px solid black;">
                                            <td class="text-center" style="border: 1px solid black; padding: 5px;">{{ $payslip['no_of_days_covered'] ?: '' }}</td>
                                            <td class="text-center" style="border: 1px solid black; padding: 5px;">{{ $formatCurrency($perDay) }}</td>
                                            <td class="text-center" style="border: 1px solid black; padding: 5px;">{{ $formatCurrency($payslip['gross_salary']) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        <center><p style="margin: 6px 0;"></p></center>

        <table>
            <tbody>
                <tr>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="30%" class="dots">Less: Late, Undertime & Absences</td>
                    <td width="20%" class="currency" width="30%">{{ $formatCurrency($lateUndertimeAbsencesAmount) }}</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="30%" class="dots">Total Earnings (1st Cut-off) =</td>
                    <td width="20%" class="currency bold" width="30%" style="border-bottom: solid 2px black">{{ $formatCurrency($payslip['gross_salary_less']) }}</td>
                </tr>
            </tbody>
        </table>

        <center><p class="data" style="margin: 6px 0;">***DEDUCTIONS***</p></center>

        <table>
            <tbody>
                <tr>
                    <td class="dots" style="padding-left: 15px">Withholding Tax..........................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($payslip['withholding_tax']) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">NYCEMPC..................................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($payslip['nycempc']) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">Other deductions.........................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($payslip['other_deductions']) }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td width="25%"></td>
                    <td width="30%"></td>
                    <td width="30%" class="dots" style="text-align: right; padding-right: 10px">Total Deductions</td>
                    <td width="15%" class="currency" width="30%">{{ $formatCurrency($payslip['total_deductions']) }}</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="30%" class="dots bold italic" style="text-align: right">NET PAY</td>
                    <td width="30%" class="bold italic" style="text-align: right; padding-right: 10px">{{ $payslipFor ?: '' }}</td>
                    <td width="15%" class="currency bold italic" width="30%" style="border-bottom: solid 2px black; background: #FCE4D6">{{ $formatCurrency($payslip['net_amount_due']) }}</td>
                </tr>
            </tbody>
        </table>
        
        {{-- Second Cut-off --------------------------------------------------------------------------------------------------------------- --}}

        <center><p style="margin: 10px 0 10px -10px; border-bottom: solid 5px #D9D9D9; width: 105%;"></p></center>

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td width="50%">
                        <div style="width: 100%">
                            <center><p class="data bold" style="color: red">{{ $payslipFor2 ?: '' }}</p></center>
                        </div>
                    </td>
                    <td width="50%">
                        <div style="width: 100%">
                            <table style="border: 1px solid black; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #DDEBF7; border: 1px solid black;">
                                        <th class="bold data text-center" style="border: 1px solid black; padding: 5px;">Days Rendered</th>
                                        <th class="bold data text-center" style="border: 1px solid black; padding: 5px;">Daily Rate</th>
                                        <th class="bold data text-center" style="border: 1px solid black; padding: 5px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border: 1px solid black;">
                                        <td class="text-center" style="border: 1px solid black; padding: 5px;">{{ $payslip2['no_of_days_covered'] ?: '' }}</td>
                                        <td class="text-center" style="border: 1px solid black; padding: 5px;">{{ $formatCurrency($perDay) }}</td>
                                        <td class="text-center" style="border: 1px solid black; padding: 5px;">{{ $formatCurrency($payslip2['gross_salary']) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <center><p style="margin: 6px 0;"></p></center>

        <table>
            <tbody>
                <tr>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="30%" class="dots">Less: Late, Undertime & Absences</td>
                    <td width="20%" class="currency" width="30%">{{ $formatCurrency($lateUndertimeAbsencesAmount2) }}</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="30%" class="dots">Total Earnings (2nd Cut-off) =</td>
                    <td width="20%" class="currency bold" width="30%" style="border-bottom: solid 2px black">{{ $formatCurrency($payslip2['gross_salary_less']) }}</td>
                </tr>
            </tbody>
        </table>

        <center><p class="data" style="margin: 6px 0;">***DEDUCTIONS***</p></center>

        <table>
            <tbody>
                <tr>
                    <td class="dots" style="padding-left: 15px">Withholding Tax..........................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($payslip2['withholding_tax']) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">NYCEMPC..................................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($payslip2['nycempc']) }}</td>
                </tr>
                <tr>
                    <td class="dots" style="padding-left: 15px">Other deductions.........................................................................
                    </td>
                    <td class="currency" width="30%">{{ $formatCurrency($payslip2['other_deductions']) }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td width="25%"></td>
                    <td width="30%"></td>
                    <td width="30%" class="dots" style="text-align: right; padding-right: 10px">Total Deductions</td>
                    <td width="15%" class="currency" width="30%">{{ $formatCurrency($payslip2['total_deductions']) }}</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="30%" class="dots bold italic" style="text-align: right">NET PAY</td>
                    <td width="30%" class="bold italic" style="text-align: right; padding-right: 10px">{{ $payslipFor2 ?: '' }}</td>
                    <td width="15%" class="currency bold italic" width="30%" style="border-bottom: solid 2px black; background: #FCE4D6">{{ $formatCurrency($payslip2['net_amount_due']) }}</td>
                </tr>
            </tbody>
        </table>
        
        <center><p style="margin: 10px 0;"></p></center>

        <table>
            <tbody>
                <tr>
                    <td width="25%"></td>
                    <td width="30%" style="text-align: right">NET PAY</td>
                    <td width="30%" style="text-align: right; padding-right: 10px">{{ $payslipFor ?: '' }}</td>
                    <td width="15%" class="currency" width="30%">{{ $formatCurrency($payslip['net_amount_due']) }}</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="30%" style="text-align: right">NET PAY</td>
                    <td width="30%" style="text-align: right; padding-right: 10px">{{ $payslipFor2 ?: '' }}</td>
                    <td width="15%" class="currency" width="30%">{{ $formatCurrency($payslip2['net_amount_due']) }}</td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="30%" class="dots bold" style="text-align: right">TOTAL NET PAY</td>
                    <td width="30%" class="bold" style="text-align: right; padding-right: 10px">{{ $monthPaylipFor ?: '' }}</td>
                    <td width="15%" class="currency bold italic" width="30%" style="border-bottom: solid 2px black; background: #F4B084; font-size: 10px">{{ $formatCurrency($totalNetPay) }}</td>
                </tr>
            </tbody>
        </table>
        
        <center><p style="margin: 15px 0;"></p></center>

        <table>
            <tbody>
                <tr>
                    <td width="60%" style="padding-bottom: 10px;">Prepared By:</td>
                    <td width="40%" style="padding-bottom: 10px;">Noted By:</td>
                </tr>
                <tr>
                    <td width="60%">
                        @if($preparedBySignaturePath)
                            <img src="{{ $preparedBySignaturePath ?: '' }}" alt="" style="height: 50px; margin-bottom: -20px;">
                        @endif
                    </td>
                    <td width="40%">
                        @if($signatoriesSignaturePath)
                            <img src="{{ $signatoriesSignaturePath ?: '' }}" alt="" style="height: 50px; margin-bottom: -20px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="60%" style="font-weight: bold">{{ $preparedBy ? $preparedBy->name : 'XXXXXXXXXX' }}</td>
                    <td width="40%" style="font-weight: bold">{{ $signatories ? $signatories->name : 'XXXXXXXXXX' }}</td>
                </tr>
                <tr>
                    <td width="60%" style="font-style: italic">{{ $preparedBy ? $preparedBy->position : 'Position' }}</td>
                    <td width="40%" style="font-style: italic">{{ $signatories ? $signatories->position : 'Position' }}</td>
                </tr>
            </tbody>
        </table>

    </div>

    
</body>
</html>