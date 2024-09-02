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
            padding: 20px;
            position: relative;
        }
        h2, p {
            text-align: center;
            margin: 5px 0;
        }
        h2 { font-size: 14px; }
        p { font-size: 12px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }
        th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
            font-size: 10px;
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
            bottom: 10px;
            left: 10px;
            font-size: 8px;
            z-index: 2;
        }
        .form-number {
            position: fixed;
            top: 10px;
            left: 10px;
            font-size: 10px;
            z-index: 2;
        }
        .note-section {
            margin-top: 20px;
            font-size: 10px;
            text-align: left;
            position: relative;
            z-index: 1;
        }
        .note-section strong {
            display: block;
            margin-top: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="form-number">Civil Service Form No.48</div>

    <div class="watermark"></div>

    @foreach($dtrs as $employeeName => $employeeDtrs)
        @if($employeeDtrs->isNotEmpty())
            <h2>{{ $employeeName }}</h2>
            <p>{{ Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>AM In</th>
                        <th>AM Out</th>
                        <th>PM In</th>
                        <th>PM Out</th>
                        <th>Total Hours</th>
                        <th>Late/Undertime</th>
                        <th>Day</th>
                        <th>Arrangement</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employeeDtrs as $dtr)
                        @if($dtr)
                            <tr>
                                <td>{{ $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('d') : '--' }}</td>
                                <td>{{ $dtr->morning_in ?? '--:--' }}</td>
                                <td>{{ $dtr->morning_out ?? '--:--' }}</td>
                                <td>{{ $dtr->afternoon_in ?? '--:--' }}</td>
                                <td>{{ $dtr->afternoon_out ?? '--:--' }}</td>
                                <td>{{ $dtr->total_hours_rendered }}</td>
                                <td>{{ $dtr->late }}</td>
                                <td>{{ $dtr->date ? Carbon\Carbon::parse($dtr->date)->format('D') : '--' }}</td>
                                <td>{{ $dtr->location }}</td>
                                <td>{{ $dtr->remarks }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div class="note-section">
                ABSENCES AND UNDERTIME<br>
                I hereby certify upon my honor that the entries on this time record, which were made daily at the time of arrival at and departure from the office, are a true and correct report of hours of work performed.
                <br><br><br><br>
                <div style="position: relative; margin-top: 10px; min-height: 60px;">
                    @if($eSignaturePath)
                        <img src="{{ storage_path('app/public/' . $eSignaturePath) }}"
                             style="width: 90px; height: auto; position: absolute; top: -5px; left: 0; z-index: 1;">
                    @endif
                    <div style="position: relative; z-index: 2; padding-top: 20px;">
                        <strong style="text-decoration: underline; z-index: 2;">{{ $employeeName }}</strong>
                        Employee
                    </div>
                </div>
                {{-- <strong style="text-decoration: underline;">{{ $employeeName }}</strong> --}}
                <br><br><br>

                Verified and found correct as to the prescribed office hours.
                <div style="position: relative; margin-top: 10px; min-height: 60px;">
                    {{-- @if($eSignaturePath)
                        <img src="{{ storage_path('app/public/' . $eSignaturePath) }}"
                             style="width: 90px; height: auto; position: absolute; top: -5px; left: 0; z-index: 1;">
                    @endif --}}
                    <div style="position: relative; z-index: 2; padding-top: 20px;">
                        <strong style="text-decoration: underline; z-index: 2;">{{ $signatoryName }}</strong>
                        Executive Director And COO
                    </div>
                </div>
            </div>

            <div class="generation-time">
                Generated on: {{ now()->format('F d, Y H:i:s') }}
            </div>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endif
    @endforeach
</body>
</html>
