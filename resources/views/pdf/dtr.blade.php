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
        .header {
            text-align: center;
            margin-bottom: 8px;
        }
        h2 {
            font-size: 14px;
            margin: 5px 0;
        }
        p {
            font-size: 11px;
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            position: relative;
            z-index: 1;
        }
        th, td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
            font-size: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
        .watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.1;
            background-image: url('{{ public_path('images/nycwatermark.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        .generation-time {
            position: fixed;
            bottom: 5px;
            left: 5px;
            font-size: 7px;
            z-index: 2;
        }
        .form-number {
            position: fixed;
            top: 5px;
            left: 5px;
            font-size: 8px;
            z-index: 2;
        }
        .weekend {
            background-color: #f8f8f8;
        }
        .holiday {
            background-color: #ffe6e6;
        }
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 9px;
            border: 1px solid black;
            padding: 5px;
            background-color: #f9f9f9;
        }
        .summary-column {
            flex: 1;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }
        .summary-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .summary-value {
            text-align: right;
            min-width: 30px;
        }
        .signature-section {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid black;
            margin-top: 20px;
            font-weight: bold;
        }
        .signature-title {
            font-size: 8px;
        }
        .compact-table th, .compact-table td {
            padding: 1px;
        }
        .remark-column {
            max-width: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="form-number">Civil Service Form No.48</div>
    <div class="watermark"></div>

    @foreach($dtrsWithSummary as $employeeName => $data)
        <div class="header">
            <h2>{{ $employeeName }}</h2>
            <p>{{ Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
        </div>



        <!-- DTR Table - Optimized to fit more days -->
        <table class="compact-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>AM In</th>
                    <th>AM Out</th>
                    <th>PM In</th>
                    <th>PM Out</th>
                    <th>Hours</th>
                    <th>Late</th>
                    <th>OT</th>
                    <th>Arr.</th>
                    <th class="remark-column">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['dtrs'] as $dtr)
                    @php
                        $hasTimeEntries = $dtr->morning_in || $dtr->morning_out || $dtr->afternoon_in || $dtr->afternoon_out;
                        $dayOfWeek = $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('D') : '';
                        $isWeekend = in_array($dayOfWeek, ['Sat', 'Sun']);
                        $isHoliday = strpos($dtr->effective_remarks, 'Holiday') !== false;
                    @endphp
                    <tr class="{{ $isWeekend ? 'weekend' : '' }} {{ $isHoliday ? 'holiday' : '' }}">
                        <td>{{ $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('d') : '' }}</td>
                        <td>{{ $dayOfWeek }}</td>
                        <td>{{ $dtr->morning_in && $dtr->morning_in != '00:00' ? $dtr->morning_in : '' }}</td>
                        <td>{{ $dtr->morning_out && $dtr->morning_out != '00:00' ? $dtr->morning_out : '' }}</td>
                        <td>{{ $dtr->afternoon_in && $dtr->afternoon_in != '00:00' ? $dtr->afternoon_in : '' }}</td>
                        <td>{{ $dtr->afternoon_out && $dtr->afternoon_out != '00:00' ? $dtr->afternoon_out : '' }}</td>
                        <td>{{ $hasTimeEntries && $dtr->total_hours_rendered && $dtr->total_hours_rendered != '00:00' ? $dtr->total_hours_rendered : '' }}</td>
                        <td>{{ $hasTimeEntries && $dtr->late ? $dtr->late : '' }}</td>
                        <td>{{ $dtr->overtime && $dtr->overtime != '00:00' ? $dtr->overtime : '' }}</td>
                        <td>{{ $dtr->location === 'Onsite' ? '' : ($dtr->location === 'Work From Home' ? 'WFH' : $dtr->location) }}</td>
                        <td class="remark-column" title="{{ $dtr->effective_remarks !== 'Present' ? $dtr->effective_remarks : '' }}">
                            {{ $dtr->effective_remarks !== 'Present' ? $dtr->effective_remarks : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Summary Section - Moved to top for better visibility -->
        <div class="summary-section">
            <div class="summary-column">
                <div class="summary-item">
                    <span class="summary-label">Days Worked:</span>
                    <span class="summary-value">{{ $data['summary']['days_worked'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Tardiness (hrs):</span>
                    <span class="summary-value">{{ $data['summary']['tardiness'] }}</span>
                </div>
            </div>
            <div class="summary-column">
                <div class="summary-item">
                    <span class="summary-label">Absences:</span>
                    <span class="summary-value">{{ $data['summary']['absences'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Leave Days:</span>
                    <span class="summary-value">{{ $data['summary']['leave_days'] }}</span>
                </div>
            </div>
            <div class="summary-column">
                <div class="summary-item">
                    <span class="summary-label">Overtime (hrs):</span>
                    <span class="summary-value">{{ $data['summary']['overtime'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Holidays:</span>
                    <span class="summary-value">{{ $data['summary']['holidays'] }}</span>
                </div>
            </div>
        </div>

        <div style="font-size: 8px; margin-top: 10px;">
            <em>I hereby certify upon my honor that the entries on this time record, which were made daily at the time of arrival at and departure from the office, are a true and correct report of hours of work performed.</em>
        </div>

        <div class="signature-section">
            <div class="signature-block">
                @if($eSignaturePath)
                    <img src="{{ storage_path('app/public/' . $eSignaturePath) }}"
                         style="width: 80px; height: auto; margin-bottom: -15px;">
                @endif
                <div class="signature-line">{{ $employeeName }}</div>
                <div class="signature-title">Employee</div>
            </div>

            <div class="signature-block">
                <div class="signature-line">{{ $data['dtrs']->first()->sign_name ?? '' }}</div>
                <div class="signature-title">{{ $data['dtrs']->first()->sign_pos ?? '' }}</div>
                <div class="signature-title">Verified as to the prescribed office hours</div>
            </div>
        </div>

        <div class="generation-time">
            Generated on: {{ now()->format('F d, Y H:i:s') }}
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
