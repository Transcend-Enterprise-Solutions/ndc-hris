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
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->surname }}</td>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Middlename</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->middle_name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Firstname</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->first_name }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name Extension</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->name_extension }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Birth</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ \Carbon\Carbon::parse($pds['userData']->date_of_birth)->format('F d, Y') }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Place of Birth</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->place_of_birth }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Sex at Birth</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->sex }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Civil Status</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->civil_status }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Citizenship</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->citizenship }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Bloodtype</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->blood_type }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Height</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->height }}m</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Weight</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->weight }}kg</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Email</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->email }}</td>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Tel No.</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->tel_number }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Mobile No.</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->mobile_number }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">GSIS No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->gsis }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Pag-Ibig No.</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->pagibig }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">SSS No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->sss }}</td>
                    </tr>
                    <tr>
                        <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">PhilHealth</td>
                        <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->philhealth }}</td>
                        <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">TIN No.</td>
                        <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->tin }}</td>
                    </tr>
                    <tr>
                        <td style="width: 25%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Agency Employee No.</td>
                        <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">{{ $pds['userData']->agency_employee_no }}</td>
                    </tr>
                    <tr>
                        <td style="width: 25%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Permanent Address</td>
                        <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">
                            {{ $pds['userData']->p_house_street }}
                            {{ $pds['userData']->permanent_selectedBarangay }}
                            {{ $pds['userData']->permanent_selectedCity }}
                            {{ $pds['userData']->permanent_selectedProvince }}
                            {{ $pds['userData']->permanent_selectedRegion }}
                            {{ $pds['userData']->permanent_selectedZipcode }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Residential Address</td>
                        <td colspan="3" style="border: 1px solid darkgray; padding: 5px;">
                            {{ $pds['userData']->r_house_street }}
                            {{ $pds['userData']->residential_selectedBarangay }}
                            {{ $pds['userData']->residential_selectedCity }}
                            {{ $pds['userData']->residential_selectedProvince }}
                            {{ $pds['userData']->residential_selectedRegion }}
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

                <tbody>
                    <tr>
                        <td colspan="4" style="background: #eeeeee; border: 1px solid darkgray; padding: 5px;">SPOUSE</td>
                    </tr>
                </tbody>

                <tbody>
                    @foreach ($pds['userSpouse'] as $spouse)
                        <tr>
                            <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Surname</td>
                            <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->surname }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Middlename</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->middle_name }}</td>
                        </tr>
                        <tr>
                            <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Firstname</td>
                            <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->first_name }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Name Extension</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->name_extension }}</td>
                        </tr>
                        <tr>
                            <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Date of Birth</td>
                            <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->birth_date }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Occupation</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->occupation }}</td>
                        </tr>
                        <tr>
                            <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Employer</td>
                            <td style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->employer }}</td>
                            <td style="width: 20%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Tel No.</td>
                            <td style="width: 30%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->tel_number }}</td>
                        </tr>
                        <tr>
                            <td style="width: 15%; background: #eeeeee; border: 1px solid darkgray; padding: 5px;">Business Address</td>
                            <td colspan="3" style="width: 35%; border: 1px solid darkgray; padding: 5px;">{{ $spouse->business_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
</body>
</html>