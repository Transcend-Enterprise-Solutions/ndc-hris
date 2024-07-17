<div class="w-full">
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-full sm:w-4/5 bg-white rounded-2xl p-3 shadow dark:bg-gray-800">

            <div class="pt-4 pb-4">
                <h1 class="text-3xl font-bold text-center text-slate-800 dark:text-white">PERSONAL DATA SHEET</h1>
            </div>

            <style>
                @media (max-width: 1024px){
                    .custom-d{
                        display: block;
                    }
                }

                @media (max-width: 768px){
                    .m-scrollable{
                        width: 100%;
                        overflow-x: scroll;
                    }
                }

                @media (min-width:1024px){
                    .custom-p{
                        padding-bottom: 14px !important;
                    }
                }
            </style>

            <div class="overflow-hidden text-sm pb-3">

                {{-- Employee's Data --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">I. PERSONAL INFORMATION
                    <i class="fas fa-edit float-right pt-1 cursor-pointer" wire:click="toggleEditPersonalInfo"></i>
                </div>
                <div>

                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Surname</p>
                                <p class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-white">{{ $userData->surname }}</p>
                            </div>
                    
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Firstname</p>
                                <p class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-white">{{ $userData->first_name }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 dark:bg-slate-700 bg-gray-50">Middlename</p>
                                <p class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-white">{{ $userData->middle_name }}</p>
                            </div>
                    
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Name Extension</p>
                                <p class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-white">{{ $userData->name_extension }}</p>
                            </div>
                        </div>

                    </div>
                    
                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Date of Birth</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ \Carbon\Carbon::parse($userData->date_of_birth)->format('F d, Y') }}
                                </p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Place of Birth</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->place_of_birth }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Sex at Birth</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->sex }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Civil Status</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->civil_status }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Citizenship</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->citizenship }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Height</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->height }}m</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Weight</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->weight }}kg</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Bloodtype</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->blood_type }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 px-1 w-3/6 bg-gray-50 dark:bg-slate-700  py-2.5">Permanent Address</p>
                                <p class="custom-p w-full border border-gray-200 dark:border-slate-600 px-1 py-2.5 dark:text-white">
                                    {{ $userData->p_house_street }} <br>
                                    {{ $userData->permanent_selectedBarangay }} {{ $userData->permanent_selectedCity }} <br>
                                    {{ $userData->permanent_selectedProvince }}, Philippines <br>
                                    {{ $userData->permanent_selectedZipcode }}
                                </p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 px-1 w-3/6 bg-gray-50 dark:bg-slate-700  py-2.5">Residential Address</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 px-1 py-2.5 dark:text-white">
                                    {{ $userData->r_house_street }} <br>
                                    {{ $userData->residential_selectedBarangay }} {{ $userData->residential_selectedCity }} <br>
                                    {{ $userData->residential_selectedProvince }}, Philippines <br>
                                    {{ $userData->residential_selectedZipcode }}
                                </p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Tel No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->tel_number }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Mobile No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->mobile_number }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                             <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Email</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="custom-d flex w-full">

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">GSIS ID No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->gsis }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Pag-Ibig ID No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->pagibig }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">PhilHealth ID No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->philhealth }}</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/4 block">
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">SSS No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->sss }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">TIN No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->tin }}</p>
                            </div>
                            <div class="flex w-full sm:w-auto">
                                <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Agency Employee No.</p>
                                <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userData->agency_employee_no }}</p>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- Family Background --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">II. FAMILY BACKGROUND
                    <i class="fas fa-edit float-right pt-1"></i>
                </div>
                <div>
                    {{-- Spouse --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border border-gray-200 dark:border-slate-600 p-1 w-full bg-gray-200 font-bold dark:bg-slate-700 dark:text-white">Spouse</p>
                    </div>

                    @foreach ($userSpouse as $spouse)
                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Surname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->surname }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Firstname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Middlename</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->middle_name }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Name Extension</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->name_extension }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Date of Birth</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ \Carbon\Carbon::parse($spouse->birth_date)->format('F d, Y') }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Occupation</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->occupation }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Employer</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->employer }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Tel. No.</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->tel_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-4/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 sm:w-1/5 bg-gray-50 dark:bg-slate-700">Business Address</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $spouse->business_address }}</p>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach

                    {{-- Father --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border border-gray-200 dark:border-slate-600 p-1 w-full bg-gray-200 font-bold dark:bg-slate-700 dark:text-white">Father</p>
                    </div>

                    <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Surname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userFather->surname }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Firstname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userFather->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Middlename</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userFather->middle_name }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Name Extension</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userFather->name_extension }}</p>
                                </div>
                            </div>

                    </div>

                    {{-- Mother's Maiden Name --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border border-gray-200 dark:border-slate-600 p-1 w-full bg-gray-200 font-bold dark:bg-slate-700 dark:text-white">Mother's Maiden Name</p>
                    </div>

                    <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Surname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userMother->surname }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Firstname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userMother->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Middlename</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userMother->middle_name }}</p>
                                </div>
                        
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Name Extension</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $userMother->name_extension }}</p>
                                </div>
                            </div>

                    </div>

                    {{-- Children --}}
                    <div class="flex w-full sm:w-auto">
                        <p class="border border-gray-200 dark:border-slate-600 p-1 w-full bg-gray-200 font-bold dark:bg-slate-700 dark:text-white">Children</p>
                    </div>

                    @foreach ($userChildren as $child)
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Fullname</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $child->childs_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Date of Birth</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ \Carbon\Carbon::parse($child->childs_birth_date)->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                                                   
                        </div>
                    @endforeach

                </div>

                {{-- Educational Background --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">III. EDUCATIONAL BACKGROUND
                    <i class="fas fa-edit float-right pt-1"></i>
                </div>
                <div>
                    @foreach ($educBackground as $educ)
                        <div class="flex w-full sm:w-auto">
                            <p class="border border-gray-200 dark:border-slate-600 p-1 w-1/7 bg-gray-200 font-bold dark:bg-slate-700 dark:text-white">Level</p>
                            <p class="w-full border border-gray-200 dark:border-slate-600 p-1 font-bold uppercase dark:text-white">{{ $educ->level }}</p>
                        </div>
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Name of School</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $educ->name_of_school }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Period of Attendance</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">
                                        From: {{ $educ->from }} <br>
                                        To: {{ $educ->to }}
                                    </p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Scholarship/Academic Honors Received</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $educ->award }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Basic Education/<br>Degree/Course</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $educ->basic_educ_degree_course }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Highest Level/<br>Units Earned</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $educ->highest_level_unit_earned }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">Year Graduated</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-white">{{ $educ->year_graduated }}</p>
                                </div>
                            </div>
                                                
                        </div>
                    @endforeach
                </div>

                {{-- Civil Service Eligibility --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">IV. CIVIL SERVICE ELIGIBILITY
                    <i class="fas fa-edit float-right pt-1"></i>
                </div>
                <div class="m-scrollable">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-slate-700">
                                <th class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">Eligibility</th>
                                <th class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">Rating</th>
                                <th class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">Date of Examination/Confernment</th>
                                <th class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">Place of Examination/Confernment</th>
                                <th class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">License Number</th>
                                <th class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">Date of Validity</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($eligibility as $elig)
                                <tr class="dark:text-white">
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{ $elig->eligibility }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{ $elig->rating }}%</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{ \Carbon\Carbon::parse($elig->date)->format('F d, Y') }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{ $elig->place_of_exam }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{ $elig->license }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{ \Carbon\Carbon::parse($elig->date_of_validity)->format('F d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Work Experience --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">V. WORK EXPERIENCE
                    <i class="fas fa-edit float-right pt-1"></i>
                </div>
                <div class="m-scrollable">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-slate-700">
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase w-1/5"  width="20%">
                                    <div class="block w-full">
                                        <div class=" flex justify-center w-full">
                                            INCLUSIVE DATES
                                        </div>
                                        <div class="flex w-full">
                                            <div class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                From
                                            </div>
                                            <div class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                To
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">Position Title</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">Department/Agency/Office/Company</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">Monthly Salary</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">Status of Appointment</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">GOV'T SERVICE</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($workExperience as $exp)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $exp->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $exp->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $exp->position }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $exp->department }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ '₱ ' . number_format($exp->monthly_salary, 2) }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $exp->status_of_appointment }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $exp->gov_service ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Voluntary Work --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">VI. VOLUNTARY WORK
                    <i class="fas fa-edit float-right pt-1"></i>
                </div>
                <div class="m-scrollable">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-slate-700">
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"  width="20%">Name of Organization</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">Address of Organization</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase w-1/5">
                                    <div class="block w-full">
                                        <div class=" flex justify-center w-full">
                                            INCLUSIVE DATES
                                        </div>
                                        <div class="flex w-full">
                                            <div class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                From
                                            </div>
                                            <div class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                To
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">Number of Hours</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"  width="20%">Position/Nature of Work</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($voluntaryWorks as $voluntary)
                                <tr>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $voluntary->org_name }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $voluntary->org_address }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $voluntary->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $voluntary->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-sm text-left">{{ $voluntary->no_of_hours }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-sm text-left">{{ $voluntary->position_nature }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Learning and Development --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">VII. LEARNING AND DEVELOPMENT
                    <i class="fas fa-edit float-right pt-1"></i>
                </div>
                <div class="m-scrollable">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-slate-700">
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase"  width="20%">Title of Training</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase w-1/5">
                                    <div class="block w-full">
                                        <div class=" flex justify-center w-full">
                                            INCLUSIVE DATES
                                        </div>
                                        <div class="flex w-full">
                                            <div class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                From
                                            </div>
                                            <div class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                To
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase">Number of Hours</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase">Type of LD</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase"  width="20%">Conducted/Sponsored By</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($lds as $ld)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $ld->title }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $ld->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $ld->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $ld->no_of_hours }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $ld->type_of_ld }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-white text-left">{{ $ld->conducted_by }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Other Information --}}
                <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">VIII. OTHER INFORMATION</div>
                <div class="m-scrollable">

                    {{-- SKILLS --}}
                    <div class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-700 bg-gray-200 dark:bg-slate-700">
                        <p class="p-1 w-full font-bold">SKILLS</p>
                        <i class="fas fa-edit float-right pt-2 pr-1.5"></i>
                    </div>
                    <div class="custom-d flex w-full border-2 border-gray-200 dark:border-slate-700">
                        <div class="flex w-full sm:w-auto dark:text-white">
                            @foreach ($skills as $skill)
                                <p class="p-1"> • {{ $skill->skill }} </p>
                            @endforeach
                        </div>
                    </div>

                    {{-- Hobbies --}}
                    <div class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-700 bg-gray-200 dark:bg-slate-700">
                        <p class="p-1 w-full font-bold">HOBBIES</p>
                        <i class="fas fa-edit float-right pt-2 pr-1.5"></i>
                    </div>
                    <div class="custom-d flex w-full border-2 border-gray-200 dark:border-slate-700">
                        <div class="flex w-full sm:w-auto dark:text-white">
                            @foreach ($hobbies as $hobby)
                                <p class="p-1"> • {{ $hobby->hobby }} </p>
                            @endforeach
                        </div>
                    </div>

                    {{-- NON-ACADEMIC DISTINCTIONS / RECOGNITION --}}
                    <div class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-700 bg-gray-200 dark:bg-slate-700">
                        <p class="p-1 w-full font-bold">NON-ACADEMIC DISTINCTIONS / RECOGNITION</p>
                        <i class="fas fa-edit float-right pt-2 pr-1.5"></i>
                    </div>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-slate-700">
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase" width="20%">Award</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase">Association/ Organization Name</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase"  width="20%">Date Received</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($non_acads_distinctions as $non_acads_distinction)
                                <tr class="dark:text-white">
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $non_acads_distinction->award }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $non_acads_distinction->ass_org_name }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $non_acads_distinction->date_received }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- MEMBERSHIP IN ASSOCIATION/ORGANIZATION --}}
                    <div class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-700 bg-gray-200 dark:bg-slate-700">
                        <p class="p-1 w-full font-bold">MEMBERSHIP IN ASSOCIATION/ORGANIZATION</p>
                        <i class="fas fa-edit float-right pt-2 pr-1.5"></i>
                    </div>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-slate-700">
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase">Association/Organization Name</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase"  width="20%">Position</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($assOrgMemberships as $assOrgMembership)
                                <tr class="dark:text-white">
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $assOrgMembership->ass_org_name }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $assOrgMembership->position }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Character References --}}
                    <div class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-700 bg-gray-200 dark:bg-slate-700">
                        <p class="p-1 w-full font-bold">CHARACTER REFERENCES</p>
                        <i class="fas fa-edit float-right pt-2 pr-1.5"></i>
                    </div>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-slate-700">
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase"  width="20%">Fullname</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase">Address</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase">Tel Number</th>
                                <th class="p-1 border-2 border-gray-200 dark:border-slate-700 font-medium text-left uppercase"  width="20%">Mobile Number</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach($references as $reference)
                                <tr class="dark:text-white">
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $reference->firstname }} {{ $reference->middle_initial ? $reference->middle_initial . '.' : '' }} {{ $reference->surname }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $reference->address }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $reference->tel_number }}</td>
                                    <td class="p-1 border-2 border-gray-200 dark:border-slate-700 text-left">{{ $reference->mobile_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

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
                <div class="bg-gray-400 dark:bg-slate-700 p-2 text-white flex justify-center rounded-b-lg">
                    <button class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap" wire:click='exportPDS' wire:loading.attr='disabled'>
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


    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="personalInfo">
        <div class="p-4">
            <div class="bg-slate-800 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Edit Personal Information
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='savePersonalInfo'>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                        <input type="text" id="surname" wire:model='surname' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('surname') 
                            <span class="text-red-500 text-sm">The surname is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Firstname</label>
                        <input type="text" id="first_name" wire:model='first_name' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('first_name') 
                            <span class="text-red-500 text-sm">The firstname is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middlename</label>
                        <input type="text" id="middle_name" wire:model='middle_name' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('middle_name') 
                            <span class="text-red-500 text-sm">The middlename is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="name_extension" class="block text-sm font-medium text-gray-700">Name Extension</label>
                        <input type="text" id="name_extension" wire:model='name_extension' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('name_extension') 
                            <span class="text-red-500 text-sm">The name extension is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" id="date_of_birth" wire:model='date_of_birth' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('date_of_birth') 
                            <span class="text-red-500 text-sm">The date of birth is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700">Place of Birth</label>
                        <input type="text" id="place_of_birth" wire:model='place_of_birth' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('place_of_birth') 
                            <span class="text-red-500 text-sm">The place of birth is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex at Birth</label>
                        <input type="text" id="sex" wire:model='sex' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('sex') 
                            <span class="text-red-500 text-sm">The sex is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="civil_status" class="block text-sm font-medium text-gray-700">Civil Status</label>
                        <select wire:model='civil_status' class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value=""></option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('civil_status') 
                            <span class="text-red-500 text-sm">The civil status is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="citizenship" class="block text-sm font-medium text-gray-700">Citizenship</label>
                        <input type="text" id="citizenship" wire:model='citizenship' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('citizenship') 
                            <span class="text-red-500 text-sm">The citizenship is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="height" class="block text-sm font-medium text-gray-700">Height</label>
                        <input type="number" step="0.01" id="height" wire:model='height' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('height') 
                            <span class="text-red-500 text-sm">The height is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="weight" class="block text-sm font-medium text-gray-700">Weight</label>
                        <input type="number" step="0.01" id="weight" wire:model='weight' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('weight') 
                            <span class="text-red-500 text-sm">The weight is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="blood_type" class="block text-sm font-medium text-gray-700">Bloodtype</label>
                        <input type="text" id="blood_type" wire:model='blood_type' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('blood_type') 
                            <span class="text-red-500 text-sm">The bloodtype is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="tel_number" class="block text-sm font-medium text-gray-700">Tel Number</label>
                        <input type="text" id="tel_number" wire:model='tel_number' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('tel_number') 
                            <span class="text-red-500 text-sm">The telephone number is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                        <input type="text" id="mobile_number" wire:model='mobile_number' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('mobile_number') 
                            <span class="text-red-500 text-sm">The mobile number is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" wire:model='email' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('email') 
                            <span class="text-red-500 text-sm">The email is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="gsis" class="block text-sm font-medium text-gray-700">GSIS ID No.</label>
                        <input type="text" id="gsis" wire:model='gsis' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('gsis') 
                            <span class="text-red-500 text-sm">The GSIS ID No. is required!</span> 
                        @enderror
                    </div>                    

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">SSS ID No.</label>
                        <input type="text" id="first_name" wire:model='sss' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('sss')
                            <span class="text-red-500 text-sm">The SSS ID No. is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Pag-Ibig ID No.</label>
                        <input type="text" id="first_name" wire:model='pagibig' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('pagibig') 
                            <span class="text-red-500 text-sm">The Pag-IBIG ID No. is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">PhilHealth ID No.</label>
                        <input type="text" id="first_name" wire:model='philhealth' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('philhealth') 
                            <span class="text-red-500 text-sm">The Philhealth No. is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">TIN ID No.</label>
                        <input type="text" id="tin" wire:model='tin' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('tin') 
                            <span class="text-red-500 text-sm">The TIN ID No. is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-2 sm:col-span-2">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Agency Employee No.</label>
                        <input type="text" id="agency_employee_no" wire:model='agency_employee_no' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('agency_employee_no') 
                            <span class="text-red-500 text-sm">The Agency Employee No. is required!</span> 
                        @enderror
                    </div>
                    
                    <fieldset class="col-span-2 sm:col-span-2 border border-gray-300 p-4 rounded-lg overflow-hidden w-full">
                        <legend class="px-2"> Permanent Address </legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_province" class="block text-sm font-medium text-gray-700">Province</label>
                                <select class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        wire:model='p_province' id="p_province" name="p_province" required>
                                    @if ($pprovinces)
                                        <option value="{{ $p_province }}" style="opacity: .6;">{{ $p_province }}</option>
                                        @foreach ($pprovinces->sortBy('province_description') as $province)
                                            <option value="{{ $province->province_description }}">
                                                {{ $province->province_description }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Select a region</option>
                                    @endif
                                </select>
                                @error('p_province') 
                                    <span class="text-red-500 text-sm">The province is required!</span> 
                                @enderror
                            </div>
                    
                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_city" class="block text-sm font-medium text-gray-700">City</label>
                                <select class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        wire:model='p_city' id="p_city" name="p_city" required>
                                    @if ($pcities)
                                        <option value="{{ $p_city }}" style="opacity: .6;">{{ $p_city }}</option>
                                        @foreach ($pcities->sortBy('city_municipality_description') as $city_municipality)
                                            <option value="{{ $city_municipality->city_municipality_description }}">
                                                {{ $city_municipality->city_municipality_description }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Select a city/municipality</option>
                                    @endif
                                </select>
                                @error('p_city') 
                                    <span class="text-red-500 text-sm">The city is required!</span> 
                                @enderror
                            </div>
                    
                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_barangay" class="block text-sm font-medium text-gray-700">Barangay</label>
                                <select class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        wire:model='p_barangay' id="p_barangay" name="p_barangay" required>
                                    @if ($pbarangays)
                                        <option value="{{ $p_barangay }}" style="opacity: .6;">{{ $p_barangay }}</option>
                                        @foreach ($pbarangays->sortBy('barangay_description') as $barangay)
                                            <option value="{{ $barangay->barangay_description }}">
                                                {{ $barangay->barangay_description }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Select a barangay</option>
                                    @endif
                                </select>
                                @error('p_barangay') 
                                    <span class="text-red-500 text-sm">The barangay is required!</span> 
                                @enderror
                            </div>
                    
                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_zipcode" class="block text-sm font-medium text-gray-700">Zip Code</label>
                                <input type="number" id="p_zipcode" wire:model='p_zipcode' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('p_zipcode') 
                                    <span class="text-red-500 text-sm">The Zip Code is required!</span> 
                                @enderror
                            </div>
                    
                            <div class="col-span-2 sm:col-span-2">
                                <label for="p_house_street" class="block text-sm font-medium text-gray-700">House | Street | Subdivision</label>
                                <input type="text" id="p_house_street" wire:model='p_house_street' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('p_house_street') 
                                    <span class="text-red-500 text-sm">The House/Street/Subdivision is required!</span> 
                                @enderror
                            </div>
                    
                        </div>
                    </fieldset>

                    <fieldset class="col-span-2 sm:col-span-2 border border-gray-300 p-4 rounded-lg overflow-hidden w-full">
                        <legend class="px-2"> Residential Address </legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="surname" class="block text-sm font-medium text-gray-700">Province</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    wire:model.live="r_province" id="r_province"
                                    name="r_province" required>
                                    @if ($pprovinces)
                                        <option value="{{ $r_province }}" style="opacity: .6;">{{ $r_province }}</option>
                                        @foreach ($pprovinces->sortBy('province_description') as $province)
                                            <option value="{{ $province->province_description }}">
                                                {{ $province->province_description }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Select a region</option>
                                    @endif
                                </select>
                                @error('r_province') 
                                    <span class="text-red-500 text-sm">The province is required!</span> 
                                @enderror
                            </div>
        
                            <div class="col-span-2 sm:col-span-1">
                                <label for="surname" class="block text-sm font-medium text-gray-700">City</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    wire:model.live="r_city" id="r_city"
                                    name="r_city" required>
                                    @if ($rcities)
                                        <option value="{{ $r_city }}" style="opacity: .6;">{{ $r_city }}</option>
                                        @foreach ($rcities->sortBy('city_municipality_description') as $city_municipality)
                                            <option value="{{ $city_municipality->city_municipality_description }}">
                                                {{ $city_municipality->city_municipality_description }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Select a city/municipality</option>
                                    @endif
                                </select>
                                @error('r_city') 
                                    <span class="text-red-500 text-sm">The city is required!</span> 
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="surname" class="block text-sm font-medium text-gray-700">Barangay</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    wire:model.live="r_barangay" id="r_barangay"
                                    name="r_barangay" required>
                                    @if ($rbarangays)
                                        <option value="{{ $r_barangay }}" style="opacity: .6;">{{ $r_barangay }}</option>
                                        @foreach ($rbarangays->sortBy('barangay_description') as $barangay)
                                            <option value="{{ $barangay->barangay_description }}">
                                                {{ $barangay->barangay_description }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Select a barangay</option>
                                    @endif
                                </select>
                                @error('r_barangay') 
                                    <span class="text-red-500 text-sm">The barangay is required</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Zip Code</label>
                                <input type="number" id="first_name" wire:model='r_zipcode' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('r_zipcode') 
                                    <span class="text-red-500 text-sm">The Zip Code is required!</span> 
                                @enderror
                            </div>
        
                            <div class="col-span-2 sm:col-span-2">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">House | Street | Subdivision</label>
                                <input type="text" id="first_name" wire:model='r_house_street' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('r_house_street') 
                                    <span class="text-red-500 text-sm">The House/Street/Subdivision is required!</span> 
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 sm:col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Save
                        </button>
                        <button @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    
</div>
