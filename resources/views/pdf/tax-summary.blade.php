<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tax Annualization</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        p {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            margin: 0pt;
        }

        .bold{
            font-weight: bold;
        }

        .normal{
            font-weight: 400;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }

        .td-left{
            text-align: left;
            padding-left: 3px;
        }

        .td-right{
            text-align: right;
            padding-right: 5px;
        }

        body{
            font-family: Arial, sans-serif;
            font-size: 8.5px;
        }

        @page {
            margin: 8mm 10mm !important;
        }

        .td-head{
            border: 1px solid black;
            vertical-align: bottom;
            padding-top: 10px;
            text-align: center;
        }

        table{
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <p class="bold">NATIONAL DEVELOPMENT CORPORATION</p>
    <p class="bold">Employee: <span class="normal">{{ $employee->surname . ', ' . $employee->first_name . ($employee->middle_name ? ', ' . $employee->middle_name : '') . ($employee->name_extension ? ', ' . $employee->name_extension : '') }}</span></p>
    <center style="margin-top: -80px">
        <p class="bold">TIN: <span class="normal">{{ $employee->tin }}</span></p>
    </center>
    <p class="bold">Date hired: <span class="normal">{{ \Carbon\Carbon::parse($employee->date_hired)->format('d-F-y') }}</span></p>
    <p class="bold">Position: <span class="normal">{{ $employee->position }}</span></p>



    <table style="margin-top: 15px">
        <tbody>
            <tr>
                <td class="td-head" width='44.5'>PAYROLL<br>PERIOD</td>
                <td class="td-head" width='100'>BASIC</td>
                <td class="td-head" width='44.5'>PERA</td>
                <td class="td-head" width='44.5'>Tardy/Absence<br>Overtime</td>
                <td class="td-head" width='44.5'></td>
                <td class="td-head" width='44.5'>Gross<br>Pay</td>
                <td class="td-head" width='44.5'>GSIS</td>
                <td class="td-head" width='44.5'>PHIC</td>
                <td class="td-head" width='44.5'>HDMF</td>
                <td class="td-head" width='44.5'>Provident Fund</td>
                <td class="td-head" width='44.5'>
                    Loans <br>
                    <div style="height: 1px; width: 100%; background-color: black;"></div>
                    GSIS MPL
                </td>
                <td class="td-head" width='44.5'>TOTAL<br>DEDUCTION</td>
                <td class="td-head" width='44.5'>SALARY<br>BEFORE TAX</td>
                <td class="td-head" width='44.5'>W/HOLDING<br>TAX</td>
                <td class="td-head" width='44.5'>NET PAY</td>
            </tr>
            @if($monthlyTax)
                @php
                    $allMonths = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $date = \Carbon\Carbon::createFromDate(null, $i, 1);
                        $allMonths[$date->format('n')] = [
                            'month_name' => $date->format('F'),
                            'data' => null
                        ];
                    }
                    
                    if($monthlyTax) {
                        foreach ($monthlyTax as $tax) {
                            $monthNumber = \Carbon\Carbon::parse($tax->start_date)->format('n');
                            $allMonths[$monthNumber]['data'] = $tax;
                        }
                    }
                    $formatCurrency = function($value) {
                        if($value == 0 || $value == null){
                            return "";
                        }
                        return number_format((float)$value, 2, '.', ',');
                    };
                    $formatEmpty = function($value) {
                        return $value ? $value : "-";
                    };
                @endphp
                @foreach ($allMonths as $monthData)
                    <tr>
                        <td class="td-left">{{ $monthData['month_name'] }}</td>
                        @if($monthData['data'])
                            <td class="td-right">{{ $formatCurrency($monthData['data']->rate_per_month) }}</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->personal_economic_relief_allowance) }}</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->gross_amount) }}</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->additional_gsis_premium) }}</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->total_deduction) }}</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->net_amount_received + $monthData['data']->total_deduction) }}</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->w_holding_tax) }}</td>
                            <td class="td-right">{{ $formatCurrency($monthData['data']->net_amount_received) }}</td>
                        @else
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                            <td class="td-right">-</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</body>
</html>