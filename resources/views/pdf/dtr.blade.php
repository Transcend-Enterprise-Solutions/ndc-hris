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
            font-size: 9px;
        }
        .form-number {
            position: absolute;
            top: 5px;
            left: 5px;
            font-size: 8px;
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
            font-size: 12px;
            font-weight: bold;
            margin: 2px 0;
        }
        .org-subtitle {
            font-size: 11px;
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
            font-size: 12px;
        }
        .employee-name-label {
            font-weight: bold;
            display: inline-block;
            margin-right: 5px;
        }
        .employee-name {
            font-weight: bold;
            display: inline-block;
        }
        .month-header {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            font-size: 11px;
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
            padding: 3px;
            text-align: center;
            font-size: 9px;
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
            font-size: 10px;
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
            font-size: 9px;
            padding: 2px;
        }
        .summary-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .certification {
            margin-top: 15px;
            font-size: 9px;
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
            font-size: 10px;
        }
        .signature-title {
            font-size: 9px;
            margin-top: 3px;
        }
        .remarks-column {
            width: 120px;
        }
        .timestamp {
            position: absolute;
            bottom: 5px;
            left: 5px;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    @foreach($dtrsWithSummary as $employeeName => $data)
        <div class="form-number">Civil Service Form No.48</div>

        <div class="header-section">
            <div class="logo-container">
                <img src="{{ public_path('images/ndc-logo-transparent.png') }}" alt="NDC Logo" class="logo">
            </div>
            <div class="org-title">Republic of the Philippines</div>
            <div class="org-subtitle">National Development Company</div>
            <div class="dtr-title">DAILY TIME RECORD</div>
        </div>

        <div class="employee-name-container">
            <span class="employee-name-label">Name:</span>
            <span class="employee-name">{{ $employeeName }}</span>
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
                    <th>Arrival</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Departure</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['dtrs'] as $dtr)
                    @php
                        $hasTimeEntries = $dtr->morning_in || $dtr->morning_out || $dtr->afternoon_in || $dtr->afternoon_out;
                        $dayOfWeek = $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('D') : '';
                        $dayNum = $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('j') : '';
                        $isWeekend = in_array($dayOfWeek, ['Sat', 'Sun']);
                    @endphp
                    <tr class="{{ $isWeekend ? 'weekend' : '' }}">
                        <td>{{ $dayNum }} {{ $dayOfWeek }}</td>
                        <td>{{ $dtr->morning_in && $dtr->morning_in != '00:00' ? $dtr->morning_in : '' }}</td>
                        <td>{{ $dtr->morning_out && $dtr->morning_out != '00:00' ? $dtr->morning_out : '' }}</td>
                        <td>{{ $dtr->afternoon_in && $dtr->afternoon_in != '00:00' ? $dtr->afternoon_in : '' }}</td>
                        <td>{{ $dtr->afternoon_out && $dtr->afternoon_out != '00:00' ? $dtr->afternoon_out : '' }}</td>
                        <td>{{ $hasTimeEntries && $dtr->late ? $dtr->late : '' }}</td>
                        <td>{{ $hasTimeEntries && $dtr->ut ? $dtr->ut : '' }}</td>
                        <td>{{ $dtr->overtime && $dtr->overtime != '00:00' ? $dtr->overtime : '' }}</td>
                        <td>{{ $dtr->effective_remarks !== 'Present' ? $dtr->effective_remarks : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-summary">TOTAL SUMMARY</div>

        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Days Worked (DW):</span>
                <span>{{ $data['summary']['days_worked'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Late:</span>
                <span>{{ $data['summary']['late'] ?? '0' }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Leave:</span>
                <span>{{ $data['summary']['leave_days'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Overtime:</span>
                <span>{{ $data['summary']['overtime'] }}</span>
            </div>

            <div class="summary-item">
                <span class="summary-label">Absences:</span>
                <span>{{ $data['summary']['absences'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Undertime:</span>
                <span>{{ $data['summary']['undertime'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Holiday:</span>
                <span>{{ $data['summary']['holidays'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Hrs Worked:</span>
                <span>{{ isset($data['summary']['total_hours']) ? $data['summary']['total_hours'] : '0' }}</span>
            </div>
        </div>

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
