<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTR Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            position: relative;
            font-size: 11px;
        }
        .form-number {
            position: absolute;
            top: 5px;
            left: 5px;
            font-size: 10px;
            color: #333;
        }
        .header-section {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo-container {
            margin-bottom: 5px;
            text-align: center;
        }
        .logo {
            width: 1.0in;
            height: 0.6in;
            margin-bottom: 2px;
        }
        .org-title {
            font-size: 14px;
            font-weight: bold;
            margin: 2px 0;
        }
        .org-subtitle {
            font-size: 13px;
            margin: 2px 0;
        }
        .dtr-title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .employee-name-container {
            margin-top: 15px;
            margin-bottom: 10px;
            text-align: left;
            font-size: 13px;
        }
        .employee-name-label {
            font-weight: bold;
            display: inline-block;
            margin-right: 5px;
        }
        .employee-name {
            display: inline-block;
        }
        .month-header {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            font-size: 13px;
            border: 1px solid #000;
            padding: 3px;
            background-color: #f2f2f2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .weekend {
            background-color: #f8f8f8;
        }
        .total-summary {
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            margin-top: 10px;
            border: 1px solid #000;
            padding: 5px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 2px;
        }
        .summary-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .certification {
            margin-top: 15px;
            font-size: 11px;
            text-align: center;
            font-style: italic;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid black;
            margin-top: 25px;
            font-weight: bold;
            font-size: 12px;
        }
        .signature-title {
            font-size: 11px;
            margin-top: 3px;
        }
        .remarks-column {
            width: 120px;
        }
        .timestamp {
            position: absolute;
            bottom: 5px;
            left: 5px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    @foreach($dtrsWithSummary as $employeeName => $data)
        <div class="form-number">Civil Service Form No.48</div>

        <div class="header-section">
            <div class="logo-container" style="display: flex; align-items: center;">
                <img src="{{ public_path('images/ndc-logo-transparent.png') }}" alt="NDC Logo" class="logo" style="margin-right: 20px;">
                <img src="{{ public_path('images/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas Logo" class="logo" style="position: relative; top: -6px;">
            </div>

            <div class="org-title">Republic of the Philippines</div>
            <div class="org-subtitle">National Development Company</div>
            <div class="dtr-title">DAILY TIME RECORD</div>
        </div>

        <div class="employee-name-container">
            <span class="employee-name-label">Name:</span>
            <span class="employee-name">{{ $employeeName }}</span><br>
            <span class="employee-name-label">Position:</span>
            <span class="employee-name">{{ $userPosition }}</span><br>
            <span class="employee-name-label">Department:</span>
            <span class="employee-name">{{ $userDepartment }}</span>
        </div>

        <div class="month-header">
            FOR THE MONTH OF {{ Carbon\Carbon::parse($startDate)->format('F Y') }}
        </div>

        <!-- DTR Table -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Day</th>
                    <th colspan="2">A.M.</th>
                    <th colspan="2">P.M.</th>
                    <th rowspan="2">Late</th>
                    <th rowspan="2">UT</th>
                    <th rowspan="2">OT</th>
                    <th rowspan="2" class="remarks-column">REMARKS</th>
                </tr>
                <tr>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['dtrs'] as $dtr)
                    @php
                        $hasTimeEntries = $dtr->effective_morning_in || $dtr->effective_morning_out ||
                                         $dtr->effective_afternoon_in || $dtr->effective_afternoon_out;
                        $dayOfWeek = $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('D') : '';
                        $dayNum = $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('j') : '';
                        $isWeekend = in_array($dayOfWeek, ['Sat', 'Sun']);
                    @endphp
                    <tr class="{{ $isWeekend ? 'weekend' : '' }}">
                        <td>{{ $dayNum }} {{ $dayOfWeek }}</td>
                        <td>{{ $dtr->effective_morning_in && $dtr->effective_morning_in != '00:00' ? $dtr->effective_morning_in : '--:--' }}</td>
                        <td>{{ $dtr->effective_morning_out && $dtr->effective_morning_out != '00:00' ? $dtr->effective_morning_out : '--:--' }}</td>
                        <td>{{ $dtr->effective_afternoon_in && $dtr->effective_afternoon_in != '00:00' ? $dtr->effective_afternoon_in : '--:--' }}</td>
                        <td>{{ $dtr->effective_afternoon_out && $dtr->effective_afternoon_out != '00:00' ? $dtr->effective_afternoon_out : '--:--' }}</td>
                        <td>{{ $hasTimeEntries && $dtr->effective_late ? $dtr->effective_late : '--:--' }}</td>
                        <td>{{ $hasTimeEntries && $dtr->effective_ut ? $dtr->effective_ut : '--:--' }}</td>
                        <td>{{ $dtr->effective_overtime && $dtr->effective_overtime != '00:00' ? $dtr->effective_overtime : '--:--' }}</td>
                        <td>{{ $dtr->effective_remarks !== 'Present' ? $dtr->effective_remarks : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-summary" style="text-align: left; margin-left: 3px; margin-top: 8px; margin-bottom: 3px;">TOTAL SUMMARY</div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 3px; font-size: 11px;">
            <tr>
                <!-- First Row -->
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>Days Worked : </strong> {{ $data['summary']['days_worked'] }}
                </td>
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>Late : </strong> {{ $data['summary']['late'] }}
                </td>
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>Leave : </strong> {{ $data['summary']['leave_days'] }}
                </td>
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>OT : </strong> {{ $data['summary']['overtime'] }}
                </td>
            </tr>
            <tr>
                <!-- Second Row -->
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>Absences : </strong> {{ $data['summary']['absences'] }}
                </td>
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>UT : </strong> {{ $data['summary']['undertime'] }}
                </td>
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>Holiday : </strong> {{ $data['summary']['holidays'] }}
                </td>
                <td style="border: none; padding: 1px 3px; text-align: left;">
                    <strong>Total Tardiness : </strong> {{ $data['summary']['tardiness'] }}
                </td>
            </tr>
        </table>

        <div class="certification">
            I CERTIFY on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.
        </div>

        <div class="signature-section">
            <div class="signature-block">
                @if($eSignaturePath)
                    <img src="{{ storage_path('app/public/' . $eSignaturePath) }}"
                         style="width: 80px; height: auto; margin-bottom: -5px;">
                @endif
                <div class="signature-line">{{ $employeeName }}</div>
                <div class="signature-title">Employee's Signature</div>
            </div>

            <div class="signature-block">
                <div class="signature-line">{{ $data['dtrs']->first()->sign_name ?? '' }}</div>
                <div class="signature-title">{{ $data['dtrs']->first()->sign_pos ?? '' }}</div>
                <div class="signature-title">Verified as to the prescribed office hours</div>
            </div>
        </div>

        <div class="timestamp">Generated on: {{ now()->format('F d, Y H:i:s') }}</div>

        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
