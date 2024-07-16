<!DOCTYPE html>
<html>
<head>
    <title>Volunteers' PDF</title>
    <style>
        .table .border-top{
            border-top: 1px solid gray;
        }

        .border{
            border: 1px solid gray;
        }

        .spacer{
            height: 20px;
        }

        .border-r{
            border-right: 1px solid darkgray;
        }

        .border-l{
            border-left: 1px solid darkgray;
        }

        .border-b{
            border-bottom: 1px solid darkgray;
        }

        .border-t{
            border-top: 1px solid darkgray;
        }

        .data{
            background: #eeeeee;
            height: 100%;
            width: 35%;
            padding: auto 5px;
        }

        .value{
            padding: auto 5px;
            height: 100%;
            width: 65%;
        }

        .flex{
            display: flex;
        }

        .row{
            height: 30px;
            width: 100%;
        }

        .block{
            display: block;
        }

        .col-1{
            width: 50%;
            height: 100%;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
            {{-- Header --}}
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th width="25%">
                            <center><img src="images/nyc-logo.png" width="80"></center>
                        </th>
                        <th width="50%">
                            <center><h2>PERSONAL DATA SHEET</h2></center>
                        </th>
                        <th width="25%">
                            <center><img src="images/hris-logo.png" width="80"></center>
                        </th>
                    </tr>
                </thead>
            </table>

            <div class="spacer"></div>

            {{-- Personal Information --}}
            <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">I. PERSONAL INFORMATION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->surname }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Middlename</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->middle_name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Firstname</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->first_name }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name Extension</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->name_extension }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Birth</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ \Carbon\Carbon::parse($pds['userData']->date_of_birth)->format('F d, Y') }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Place of Birth</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->place_of_birth }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Sex at Birth</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->sex }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Civil Status</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->civil_status }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Citizenship</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->citizenship }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Bloodtype</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->blood_type }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Height</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->height }}m</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Weight</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->weight }}kg</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Email</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->email }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Tel No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->tel_number }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Mobile No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->mobile_number }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">GSIS No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->gsis }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Pag-Ibig No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->pagibig }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">SSS No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->sss }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">PhilHealth</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->philhealth }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">TIN No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->tin }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Agency Employee No.</td>
                        <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->agency_employee_no }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Permanent Address</td>
                        <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">
                            {{ $pds['userData']->p_house_street }}
                            {{ $pds['userData']->permanent_selectedBarangay }}
                            {{ $pds['userData']->permanent_selectedCity }},
                            {{ $pds['userData']->permanent_selectedProvince }}
                            {{ $pds['userData']->permanent_selectedZipcode }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Residential Address</td>
                        <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">
                            {{ $pds['userData']->r_house_street }}
                            {{ $pds['userData']->residential_selectedBarangay }}
                            {{ $pds['userData']->residential_selectedCity }},
                            {{ $pds['userData']->residential_selectedProvince }}
                            {{ $pds['userData']->residential_selectedZipcode }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Family Background --}}
            <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">II. FAMILY BACKGROUND</th>
                    </tr>
                </thead>

                {{-- Spouse --}}
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">SPOUSE</td>
                    </tr>
                </tbody>

                <tbody>
                    @foreach ($pds['userSpouse'] as $spouse)
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->surname }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Middlename</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->middle_name }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Firstname</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->first_name }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name Extension</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->name_extension }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Birth</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->birth_date }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Occupation</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->occupation }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Employer</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->employer }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Tel No.</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->tel_number }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Business Address</td>
                            <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">{{ $spouse->business_address }}</td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Father --}}
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">FATHER</td>
                    </tr>
                </tbody>

                <tbody>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userFather']->surname }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Middlename</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userFather']->middle_name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Firstname</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userFather']->first_name }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name Extension</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userFather']->name_extension }}</td>
                    </tr>
                </tbody>

                {{-- Mother --}}
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">MOTHER</td>
                    </tr>
                </tbody>

                <tbody>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userMother']->surname }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Middlename</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userMother']->middle_name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Firstname</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userMother']->first_name }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name Extension</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userMother']->name_extension }}</td>
                    </tr>
                </tbody>

                {{-- Page Break --}}
                <tbody>
                    <tr>
                        <td colspan="4" style="background: darkgray; padding: 5px;"></td>
                    </tr>
                </tbody>

                <div class="page-break"></div>

                {{-- Children --}}
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">CHILDREN</td>
                    </tr>
                </tbody>

                <tbody>
                    @foreach ($pds['userChildren'] as $child)
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $child->childs_name }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Birth</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ \Carbon\Carbon::parse($child->childs_birth_date)->format('F d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

             {{-- Educational Background --}}
             <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">III. EDUCATIONAL BACKGROUND</th>
                    </tr>
                </thead>

                @foreach ($pds['educBackground'] as $educ)
                    <tbody>
                        <tr>
                            <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px; text-transform: uppercase;">{{ $educ->level }}</td>
                        </tr>
                    </tbody>

                    <tbody>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name of School</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $educ->name_of_school }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Basic Education/Degree/Course</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $educ->basic_educ_degree_course }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Perion of Attendance</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">
                                From: {{ $educ->from }} <br>
                                <hr style="height: 1px; background: darkgray; color: darkgray; border: none;">
                                To: {{ $educ->to }}
                            </td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Year Graduated</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $educ->year_graduated }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Highest Level/Units Earned</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $educ->highest_level_unit_earned }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Scholarship/Academic Honors Received</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $educ->award }}</td>
                        </tr>
                    </tbody>
                @endforeach

            </table>

             {{-- Civil Service Eligibility --}}
             <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">IV. CIVIL SERVICE ELIGIBILITY</th>
                    </tr>
                </thead>

                @foreach ($pds['eligibility'] as $eligibility)
                    <tbody>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">ELIGIBILITY</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $eligibility->eligibility }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Rating</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $eligibility->rating }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Examination/ Confernment</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ \Carbon\Carbon::parse($eligibility->date)->format('F d, Y') }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Place of Examination/ Confernment</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $eligibility->place_of_exam }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">License Number</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $eligibility->license }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Validity</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ \Carbon\Carbon::parse($eligibility->date_of_validity)->format('F d, Y') }}</td>
                        </tr>
                    </tbody>
                    @if (!$loop->last)
                        <tbody>
                            <tr>
                                <td colspan="4" style="background: #eeeeee; padding: 5px;"></td>
                            </tr>
                        </tbody>
                    @endif
                @endforeach

            </table>

             {{-- Work Experience --}}
             <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">V. WORK EXPERIENCE</th>
                    </tr>
                </thead>

                @foreach ($pds['workExperience'] as $exp)
                    <tbody>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Inclusive Dates</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">
                                From: {{ \Carbon\Carbon::parse($exp->start_date)->format('F d, Y') }} <br>
                                <hr style="height: 1px; background: darkgray; color: darkgray; border: none;">
                                To: {{ \Carbon\Carbon::parse($exp->end_date)->format('F d, Y') }}
                            </td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Position</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $exp->position }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Department/Agency/ Office/Company</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $exp->department }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Monthly Salary</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ 'Php ' . number_format($exp->monthly_salary, 2)  }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Status of Appointment</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $exp->status_of_appointment }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Gov't Service</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $exp->gov_service ? 'Yes' : 'No' }}</td>
                        </tr>
                    </tbody>
                    @if (!$loop->last)
                        <tbody>
                            <tr>
                                <td colspan="4" style="background: #eeeeee; padding: 5px;"></td>
                            </tr>
                        </tbody>
                    @endif
                @endforeach

            </table>

             {{-- Voluntary Work --}}
             <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">VI. VOLUNTARY WORK</th>
                    </tr>
                </thead>

                @foreach ($pds['voluntaryWorks'] as $voluntary)
                    <tbody>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name of Organization</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $voluntary->org_name }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Address of Organization</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $voluntary->org_address }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px; text-align: center;">Inclusive Dates</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">From</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">
                                {{ \Carbon\Carbon::parse($voluntary->start_date)->format('F d, Y') }}
                            </td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">To</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">
                                {{ \Carbon\Carbon::parse($voluntary->end_date)->format('F d, Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Number of Hours</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $voluntary->no_of_hours }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Position/Nature of Work</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $voluntary->position_nature }}</td>
                        </tr>
                    </tbody>
                    @if (!$loop->last)
                        <tbody>
                            <tr>
                                <td colspan="4" style="background: #eeeeee; padding: 5px;"></td>
                            </tr>
                        </tbody>
                    @endif
                @endforeach

            </table>

             {{-- LEARNING AND DEVELOPMENT --}}
             <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th colspan="4" style="padding: 5px; text-align: left;">VII. LEARNING AND DEVELOPMENT</th>
                    </tr>
                </thead>

                @foreach ($pds['lds'] as $ld)
                    <tbody>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Title of Training</td>
                            <td colspan="3" style=" border: 1px solid darkgray; padding: 5px;">{{ $ld->title }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Inclusive Date</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">
                                From: {{ $ld->start_date }} <br>
                                <hr style="height: 1px; background: darkgray; color: darkgray; border: none;">
                                To: {{ $ld->end_date }}
                            </td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Number of Hours</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $ld->no_of_hours }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Type of LD</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $ld->type_of_ld }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Conducted/ Sponsored By</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $ld->conducted_by }}</td>
                        </tr>
                    </tbody>
                    @if (!$loop->last)
                        <tbody>
                            <tr>
                                <td colspan="4" style="background: #eeeeee; padding: 5px;"></td>
                            </tr>
                        </tbody>
                    @endif
                @endforeach

            </table>

             {{--  OTHER INFORMATION --}}
             <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <thead>
                    <tr style="background: darkgray; color: white;">
                        <th style="padding: 5px; text-align: left;">VIII.  OTHER INFORMATION</th>
                    </tr>
                </thead>

                {{-- Skills --}}
                <tbody>
                    <tr>
                        <td style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">SKILLS</td>
                    </tr>
                </tbody>

                <tbody>
                    <tr>
                        <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">
                            @foreach ($pds['skills'] as $skill)
                            • {{ $skill->skill }} 
                            @endforeach
                        </td>
                    </tr>
                </tbody>

                {{-- Hobbies --}}
                <tbody>
                    <tr>
                        <td style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">HOBBIES</td>
                    </tr>
                </tbody>

                <tbody>
                    <tr>
                        <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">
                            @foreach ($pds['hobbies'] as $hobby)
                            • {{ $hobby->hobby }} 
                            @endforeach
                        </td>
                    </tr>
                </tbody>

            </table>

            {{-- NON-ACADEMIC DISTINCTIONS / RECOGNITION --}}
            <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">NON-ACADEMIC DISTINCTIONS / RECOGNITION</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Award</td>
                        <td colspan="2" style="border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Association/Organization name</td>
                        <td style="width: 30%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Date Received</td>
                    </tr>
                </tbody>
                <tbody>
                    @foreach ($pds['non_acads_distinctions'] as $non_acads_distinction)
                        <tr>
                            <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">{{ $non_acads_distinction->award }}</td>
                            <td colspan="2" style="border: 1px solid darkgray; padding: 5px;">{{ $non_acads_distinction->ass_org_name }}</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">
                                {{ \Carbon\Carbon::parse($non_acads_distinction->date_received)->format('F d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- MEMBERSHIP IN ASSOCIATION/ORGANIZATION --}}
            <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">MEMBERSHIP IN ASSOCIATION/ORGANIZATION</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="3" style="border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Association/Organization name</td>
                        <td style="width: 30%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Position</td>
                    </tr>
                </tbody>
                <tbody>
                    @foreach ($pds['assOrgMemberships'] as $assOrgMembership)
                        <tr>
                            <td colspan="3" style="width: 20%; border: 1px solid darkgray; padding: 5px;">{{ $assOrgMembership->ass_org_name }}</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $assOrgMembership->position }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- CHARACTER REFERENCES --}}
            <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">CHARACTER REFERENCES</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td style="width: 20%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Fullname</td>
                        <td style="width: 40%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Address</td>
                        <td style="width: 20%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Tel No.</td>
                        <td style="width: 20%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Mobile No.</td>
                    </tr>
                </tbody>
                <tbody>
                    @foreach ($pds['references'] as $reference)
                        <tr>
                            <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">
                                {{ $reference->firstname }} {{ $reference->middle_initial ? $reference->middle_initial . '.' : '' }} {{ $reference->surname }}
                            </td>
                            <td style="width: 40%; border: 1px solid darkgray; padding: 5px;">{{ $reference->address }}</td>
                            <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">{{ $reference->tel_number }}</td>
                            <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">{{ $reference->mobile_number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="spacer"></div>
            <div class="spacer"></div>

            {{-- Signature --}}
            <table style="width: 100%; border: 1px solid darkgray; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 20%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Signature</td>
                        <td style="width: 40%; border: 1px solid darkgray; padding: 5px;"></td>
                        <td style="width: 20%; border: 1px solid darkgray; background: #eeeeee; padding: 5px;">Date</td>
                        <td style="width: 20%; border: 1px solid darkgray; padding: 5px;">{{ \Carbon\Carbon::now()->format('F d, Y') }}
                        </td>
                    </tr>
                </tbody>
            </table>
</body>
</html>