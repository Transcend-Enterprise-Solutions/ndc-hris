<div class="w-full">
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-full sm:w-4/5 bg-white rounded-lg p-3 shadow dark:bg-gray-800">

            <div class="pt-4 pb-4">
                <h1 class="text-3xl font-bold text-center text-black dark:text-white">PERSONAL DATA SHEET</h1>
            </div>

            <style>
                @media (max-width: 1024px){
                    .custom-d{
                        display: block;
                    }
                }

                @media (min-width:1024px){
                    .custom-p{
                        padding-bottom: 12px !important;
                    }
                }
            </style>

            <div class="overflow-hidden pb-3">

                {{-- Employee's Data --}}
                <div class="bg-blue-500 p-2 text-white font-bold">I. PERSONAL INFORMATION</div>
                <div>

                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Surname</p>
                                <p class="w-full border p-1">{{ $userData->surname }}</p>
                            </div>
                    
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Firstname</p>
                                <p class="w-full border p-1">{{ $userData->first_name }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Middlename</p>
                                <p class="w-full border p-1">{{ $userData->middle_name }}</p>
                            </div>
                    
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Name Extension</p>
                                <p class="w-full border p-1">{{ $userData->name_extension }}</p>
                            </div>
                        </div>

                    </div>
                    
                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Date of Birth</p>
                                <p class="w-full border p-1">{{ \Carbon\Carbon::parse($userData->date_of_birth)->format('F d, Y') }}
                                </p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Place of Birth</p>
                                <p class="w-full border p-1">{{ $userData->place_of_birth }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Sex at Birth</p>
                                <p class="w-full border p-1">{{ $userData->sex }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Civil Status</p>
                                <p class="w-full border p-1">{{ $userData->civil_status }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Citizenship</p>
                                <p class="w-full border p-1">{{ $userData->citizenship }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Height</p>
                                <p class="w-full border p-1">{{ $userData->height }}m</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Weight</p>
                                <p class="w-full border p-1">{{ $userData->weight }}kg</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Bloodtype</p>
                                <p class="w-full border p-1">{{ $userData->blood_type }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border px-1 w-3/6 bg-gray-50  py-2.5">Permanent Address</p>
                                <p class="custom-p w-full border px-1 py-2.5">
                                    {{ $userData->p_house_street }} <br>
                                    {{ $userData->permanent_selectedBarangay }} {{ $userData->permanent_selectedCity }} <br>
                                    {{ $userData->permanent_selectedProvince }} <br>
                                    {{ $userData->permanent_selectedZipcode }}
                                </p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border px-1 w-3/6 bg-gray-50  py-2.5">Residential Address</p>
                                <p class="w-full border px-1 py-2.5">
                                    {{ $userData->r_house_street }} <br>
                                    {{ $userData->residential_selectedBarangay }} {{ $userData->residential_selectedCity }} <br>
                                    {{ $userData->residential_selectedProvince }} <br>
                                    {{ $userData->residential_selectedZipcode }}
                                </p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Tel No.</p>
                                <p class="w-full border p-1">{{ $userData->tel_number }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Mobile No.</p>
                                <p class="w-full border p-1">{{ $userData->mobile_number }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                             <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Email</p>
                                <p class="w-full border p-1">{{ $userData->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">GSIS ID No.</p>
                                <p class="w-full border p-1">{{ $userData->gsis }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">Pag-Ibig ID No.</p>
                                <p class="w-full border p-1">{{ $userData->pagibig }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">PhilHealth ID No.</p>
                                <p class="w-full border p-1">{{ $userData->philhealth }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">SSS No.</p>
                                <p class="w-full border p-1">{{ $userData->sss }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/6 bg-gray-50">TIN No.</p>
                                <p class="w-full border p-1">{{ $userData->tin }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-3/5 bg-gray-50">Agency Employee No.</p>
                                <p class="w-full border p-1">{{ $userData->agency_employee_no }}</p>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- Family Background --}}
                <div class="bg-blue-500 p-2 text-white font-bold">II. FAMILY BACKGROUND</div>
                <div>
                    {{-- Spouse --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">Spouse</p>
                    </div>

                    @foreach ($userSpouse as $spouse)
                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Surname</p>
                                    <p class="w-full border p-1">{{ $spouse->surname }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Firstname</p>
                                    <p class="w-full border p-1">{{ $spouse->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Middlename</p>
                                    <p class="w-full border p-1">{{ $spouse->middle_name }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Name Extension</p>
                                    <p class="w-full border p-1">{{ $spouse->name_extension }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Date of Birth</p>
                                    <p class="w-full border p-1">{{ $spouse->birth_date }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Occupation</p>
                                    <p class="w-full border p-1">{{ $spouse->occupation }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Employer</p>
                                    <p class="w-full border p-1">{{ $spouse->employer }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Tel. No.</p>
                                    <p class="w-full border p-1">{{ $spouse->tel_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-4/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-1/5 bg-gray-50">Business Address</p>
                                    <p class="w-full border p-1">{{ $spouse->business_address }}</p>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach

                    {{-- Father --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">Father</p>
                    </div>

                    <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Surname</p>
                                    <p class="w-full border p-1">{{ $userFather->surname }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Firstname</p>
                                    <p class="w-full border p-1">{{ $userFather->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Middlename</p>
                                    <p class="w-full border p-1">{{ $userFather->middle_name }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Name Extension</p>
                                    <p class="w-full border p-1">{{ $userFather->name_extension }}</p>
                                </div>
                            </div>

                    </div>

                    {{-- Mother's Maiden Name --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">Mother's Maiden Name</p>
                    </div>

                    <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Surname</p>
                                    <p class="w-full border p-1">{{ $userMother->surname }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Firstname</p>
                                    <p class="w-full border p-1">{{ $userMother->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Middlename</p>
                                    <p class="w-full border p-1">{{ $userMother->middle_name }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Name Extension</p>
                                    <p class="w-full border p-1">{{ $userMother->name_extension }}</p>
                                </div>
                            </div>

                    </div>

                    {{-- Children --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">Children</p>
                    </div>

                    @foreach ($userChildren as $child)
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Fullname</p>
                                    <p class="w-full border p-1">{{ $child->childs_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Date of Birth</p>
                                    <p class="w-full border p-1">{{ \Carbon\Carbon::parse($child->childs_birth_date)->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                                                   
                        </div>
                    @endforeach

                </div>

                {{-- Educational Background --}}
                <div class="bg-blue-500 p-2 text-white font-bold">III. EDUCATIONAL BACKGROUND</div>
                <div>
                    @foreach ($educBackground as $educ)
                        <div class="flex w-full sm:w-auto">
                            <p class="border p-1 w-1/7 bg-gray-200 font-bold">Level</p>
                            <p class="w-full border p-1 font-bold uppercase">{{ $educ->level }}</p>
                        </div>
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Name of School</p>
                                    <p class="w-full border p-1">{{ $educ->name_of_school }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-3/6 bg-gray-50">Period of Attendance</p>
                                    <p class="w-full border p-1">
                                        From: {{ $educ->from }} <br>
                                        To: {{ $educ->to }}
                                    </p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-5/6 bg-gray-50">Basic Education/Degree/Course</p>
                                    <p class="w-full border p-1">{{ $educ->basic_educ_degree_course }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p class="border p-1 w-5/6 bg-gray-50">Highest Level/Units Earned</p>
                                    <p class="w-full border p-1">{{ $educ->highest_level_unit_earned }}</p>
                                </div>
                            </div>
                                                
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="flex w-full sm:w-auto">
                                <p class="border p-1 w-4/6 sm:w-3/6 bg-gray-50">Scholarship/Academic Honors Received</p>
                                <p class="w-full border p-1">{{ $educ->award }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Civil Service Eligibility --}}
                <div class="bg-blue-500 p-2 text-white font-bold">IV. CIVIL SERVICE ELIGIBILITY</div>
                <div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Eligibility</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Rating</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Date of Examination/Confernment</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Place of Examination/Confernment</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">License Number</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Date of Validity</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($eligibility as $elig)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left">{{ $elig->eligibility }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $elig->rating }}%</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ \Carbon\Carbon::parse($elig->date)->format('F d, Y') }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $elig->place_of_exam }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $elig->license }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ \Carbon\Carbon::parse($elig->date_of_validity)->format('F d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Work Experience --}}
                <div class="bg-blue-500 p-2 text-white font-bold">V. WORK EXPERIENCE</div>
                <div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase w-1/5"  width="20%">
                                    <div class="block w-full">
                                        <div class=" flex justify-center w-full">
                                            INCLUSIVE DATES
                                        </div>
                                        <div class="flex w-full">
                                            <div class="flex justify-center border border-gray-300 p-1 w-2/4">
                                                From
                                            </div>
                                            <div class="flex justify-center border border-gray-300 p-1 w-2/4">
                                                To
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Position Title</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Department/Agency/Office/Company</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Monthly Salary</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Status of Appointment</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">GOV'T SERVICE</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($workExperience as $exp)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $exp->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $exp->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $exp->position }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $exp->department }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ '₱ ' . number_format($exp->monthly_salary, 2) }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $exp->status_of_appointment }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $exp->gov_service ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Voluntary Work --}}
                <div class="bg-blue-500 p-2 text-white font-bold">VI. VOLUNTARY WORK</div>
                <div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Name of Organization</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Address of Organization</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase w-1/5">
                                    <div class="block w-full">
                                        <div class=" flex justify-center w-full">
                                            INCLUSIVE DATES
                                        </div>
                                        <div class="flex w-full">
                                            <div class="flex justify-center border border-gray-300 p-1 w-2/4">
                                                From
                                            </div>
                                            <div class="flex justify-center border border-gray-300 p-1 w-2/4">
                                                To
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Number of Hours</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Position/Nature of Work</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($voluntaryWorks as $voluntary)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left">{{ $voluntary->org_name }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $voluntary->org_address }}</td>
                                    <td class="p-1 border-2 text-sm text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $voluntary->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $voluntary->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $voluntary->no_of_hours }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $voluntary->position_nature }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Learning and Development --}}
                <div class="bg-blue-500 p-2 text-white font-bold">VII. LEARNING AND DEVELOPMENT</div>
                <div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Title of Training</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase w-1/5">
                                    <div class="block w-full">
                                        <div class=" flex justify-center w-full">
                                            INCLUSIVE DATES
                                        </div>
                                        <div class="flex w-full">
                                            <div class="flex justify-center border border-gray-300 p-1 w-2/4">
                                                From
                                            </div>
                                            <div class="flex justify-center border border-gray-300 p-1 w-2/4">
                                                To
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Number of Hours</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Type of LD</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Conducted/Sponsored By</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($lds as $ld)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left">{{ $ld->title }}</td>
                                    <td class="p-1 border-2 text-sm text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $ld->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $ld->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $ld->no_of_hours }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $ld->type_of_ld }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $ld->conducted_by }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Other Information --}}
                <div class="bg-blue-500 p-2 text-white font-bold">VIII. OTHER INFORMATION</div>
                <div>

                    {{-- SKILLS --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">SKILLS</p>
                    </div>
                    <div class="custom-d flex w-full">
                        <div class="flex w-full sm:w-auto">
                            @foreach ($skills as $skill)
                                <p class="p-1"> • {{ $skill->skill }} </p>
                            @endforeach
                        </div>
                    </div>

                    {{-- Hobbies --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">Hobbies</p>
                    </div>
                    <div class="custom-d flex w-full">
                        <div class="flex w-full sm:w-auto">
                            @foreach ($hobbies as $hobby)
                                <p class="p-1"> • {{ $hobby->hobby }} </p>
                            @endforeach
                        </div>
                    </div>

                    {{-- NON-ACADEMIC DISTINCTIONS / RECOGNITION --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">NON-ACADEMIC DISTINCTIONS / RECOGNITION</p>
                    </div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase" width="20%">Award</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Association/Organization Name</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Date Received</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($non_acads_distinctions as $non_acads_distinction)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left">{{ $non_acads_distinction->award }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $non_acads_distinction->ass_org_name }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $non_acads_distinction->date_received }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- MEMBERSHIP IN ASSOCIATION/ORGANIZATION --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">MEMBERSHIP IN ASSOCIATION/ORGANIZATION</p>
                    </div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Association/Organization Name</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Position</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($assOrgMemberships as $assOrgMembership)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left">{{ $assOrgMembership->ass_org_name }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $assOrgMembership->position }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Character References --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border p-1 w-full bg-gray-200 font-bold">CHARACTER REFERENCES</p>
                    </div>
                    <table class="w-full">
                        <thead class="text-neutral-500 dark:text-neutral-200">
                            <tr class="text-neutral-800 dark:text-neutral-200 bg-gray-200">
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Fullname</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Address</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase">Tel Number</th>
                                <th class="p-1 border-2 border-gray-300 text-sm font-medium text-left uppercase"  width="20%">Mobile Number</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($references as $reference)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 text-sm text-left">{{ $reference->firstname }} {{ $reference->middle_initial ? $reference->middle_initial . '.' : '' }} {{ $reference->surname }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $reference->address }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $reference->tel_number }}</td>
                                    <td class="p-1 border-2 text-sm text-left">{{ $reference->mobile_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                {{-- End --}}
                <div class="bg-blue-500 p-2 text-white flex justify-center"></div>


                {{-- Footer --}}
                <style>
                    @-webkit-keyframes spinner-border {
                        to {
                            transform: rotate(360deg);
                        }
                    }

                    @keyframes spinner-border {
                        to {
                            transform: rotate(360deg);
                        }
                    }

                    .spinner-border {
                        display: inline-block;
                        width: 1rem;
                        height: 1rem;
                        vertical-align: text-bottom;
                        border: 2px solid currentColor;
                        border-right-color: transparent;
                        border-radius: 50%;
                        -webkit-animation: spinner-border .75s linear infinite;
                        animation: spinner-border .75s linear infinite;
                        color: white;
                    }
                </style>
                <div class="bg-white p-2 mt-3 text-white flex justify-center">
                    <button class="btn bg-emerald-500 hover:bg-emerald-600 text-white whitespace-nowrap" wire:click='exportPDS' wire:loading.attr='disabled'>
                        <div wire:loading wire:target="exportPDS">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                        <i class="bi bi-file-earmark-arrow-down" wire:loading.remove></i>&nbsp&nbspExport
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
