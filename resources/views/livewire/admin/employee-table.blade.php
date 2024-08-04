<div class="w-full">
    <div class="w-full">

        {{-- Personal Data Sheet --}}
        @if($personalDataSheetOpen && $selectedUser)
        <div class="flex justify-center w-full">
            <div class="overflow-x-auto w-full bg-white rounded-2xl p-3 shadow dark:bg-gray-800">

                <div class="pt-4 pb-4">
                    <h1 class="text-3xl font-bold text-center text-slate-800 dark:text-white">PERSONAL DATA SHEET</h1>
                </div>

                <style>
                    @media (max-width: 1024px) {
                        .custom-d {
                            display: block;
                        }
                    }

                    @media (max-width: 768px) {
                        .m-scrollable {
                            width: 100%;
                            overflow-x: scroll;
                        }
                    }

                    @media (min-width:1024px) {
                        .custom-p {
                            padding-bottom: 14px !important;
                        }
                    }

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

                <div class="overflow-hidden text-sm pb-3">

                    {{-- Employee's Data --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">
                        I. PERSONAL INFORMATION</div>
                    <div>

                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Surname</p>
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                        {{ $userData->surname }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Firstname</p>
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                        {{ $userData->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 dark:bg-slate-700 bg-gray-50">
                                        Middlename</p>
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                        {{ $userData->middle_name }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Name Extension</p>
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                        {{ $userData->name_extension }}</p>
                                </div>
                            </div>

                        </div>

                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Date of Birth</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($userData->date_of_birth)->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Place of Birth</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->place_of_birth }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Sex at Birth</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->sex }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Civil Status</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->civil_status }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Citizenship</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->citizenship }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Height</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->height }}m</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Weight</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->weight }}kg</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Bloodtype</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->blood_type }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 px-1 w-3/6 bg-gray-50 dark:bg-slate-700  py-2.5">
                                        Permanent Address</p>
                                    <p
                                        class="custom-p w-full border border-gray-200 dark:border-slate-600 px-1 py-2.5 dark:text-gray-200">
                                        {{ $userData->p_house_street }} <br>
                                        {{ $userData->permanent_selectedBarangay }} {{ $userData->permanent_selectedCity
                                        }} <br>
                                        {{ $userData->permanent_selectedProvince }}, Philippines <br>
                                        {{ $userData->permanent_selectedZipcode }}
                                    </p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 px-1 w-3/6 bg-gray-50 dark:bg-slate-700  py-2.5">
                                        Residential Address</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 px-1 py-2.5 dark:text-gray-200">
                                        {{ $userData->r_house_street }} <br>
                                        {{ $userData->residential_selectedBarangay }} {{
                                        $userData->residential_selectedCity }} <br>
                                        {{ $userData->residential_selectedProvince }}, Philippines <br>
                                        {{ $userData->residential_selectedZipcode }}
                                    </p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Tel No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->tel_number }}</p>
                                </div>
                            </div>

                        </div>

                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Mobile No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->mobile_number }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Email</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        GSIS ID No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->gsis }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Pag-Ibig ID No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->pagibig }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        PhilHealth ID No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->philhealth }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        SSS No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->sss }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        TIN No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->tin }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Agency Employee No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->agency_employee_no }}</p>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Family Background --}}
                    <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">II. FAMILY
                        BACKGROUND
                    </div>
                    <div>
                        {{-- Spouse --}}
                        <div
                            class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                            <p class="p-1 w-full font-bold dark:text-gray-200">Spouse</p>
                        </div>

                        @if($userSpouse)
                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Surname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->surname }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Firstname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Middlename</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->middle_name }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Name Extension</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->name_extension }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Date of Birth</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($userSpouse->birth_date)->format('F d, Y') }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Occupation</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->occupation }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Employer</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->employer }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Tel. No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->tel_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="custom-d flex w-full">
                            <div class="w-full sm:w-4/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 sm:w-1/5 bg-gray-50 dark:bg-slate-700">
                                        Business Address</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userSpouse->business_address }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Father --}}
                        <div
                            class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                            <p class="p-1 w-full font-bold dark:text-gray-200">Father</p>
                        </div>

                        @if($userFather)
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Surname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userFather->surname }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Firstname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userFather->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Middlename</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userFather->middle_name }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Name Extension</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userFather->name_extension }}</p>
                                </div>
                            </div>

                        </div>
                        @endif

                        {{-- Mother's Maiden Name --}}
                        <div
                            class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                            <p class="p-1 w-full font-bold dark:text-gray-200">Mother's Maiden Name</p>
                        </div>

                        @if($userMother)
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Surname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userMother->surname }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Firstname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userMother->first_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Middlename</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userMother->middle_name }}</p>
                                </div>

                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Name Extension</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userMother->name_extension }}</p>
                                </div>
                            </div>

                        </div>
                        @endif

                        {{-- Children --}}
                        <div
                            class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                            <p class="p-1 w-full font-bold dark:text-gray-200">Children</p>
                        </div>

                        @if($userChildren)
                        @foreach ($userChildren as $child)
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Fullname</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $child->childs_name }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Date of Birth</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($child->childs_birth_date)->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>

                        </div>
                        @endforeach
                        @endif

                    </div>

                    {{-- Educational Background --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $educBackground && $educBackground->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
                        III. EDUCATIONAL BACKGROUND
                    </div>
                    <div>
                        @foreach ($educBackground as $educ)
                        <div class="flex w-full sm:w-auto">
                            <p
                                class="border border-gray-200 dark:border-slate-600 p-1 w-1/7 bg-gray-200 font-bold dark:bg-slate-700 dark:text-gray-200">
                                Level</p>
                            <p
                                class="w-full border border-gray-200 dark:border-slate-600 p-1 font-bold uppercase dark:text-gray-200">
                                {{ $educ->level }}>
                            </p>
                        </div>
                        <div class="custom-d flex w-full">

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Name of School</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $educ->name_of_school }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Period of Attendance</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        From: {{ $educ->from }} <br>
                                        To: {{ $educ->to }}
                                    </p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Scholarship/Academic Honors Received</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $educ->award }}</p>
                                </div>
                            </div>

                            <div class="w-full sm:w-2/4 block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Basic Education/<br>Degree/Course</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $educ->basic_educ_degree_course }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Highest Level/<br>Units Earned</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $educ->highest_level_unit_earned }}</p>
                                </div>
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Year Graduated</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $educ->year_graduated }}</p>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>

                    {{-- Civil Service Eligibility --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $eligibility && $eligibility->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
                        IV. CIVIL SERVICE ELIGIBILITY
                    </div>

                    @if ($eligibility && $eligibility->isNotEmpty())
                    <div class="m-scrollable">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th
                                        class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">
                                        Eligibility</th>
                                    <th
                                        class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">
                                        Rating</th>
                                    <th
                                        class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">
                                        Date of Examination/Confernment</th>
                                    <th
                                        class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">
                                        Place of Examination/Confernment</th>
                                    <th
                                        class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">
                                        License Number</th>
                                    <th width="20%"
                                        class="p-1 font-medium text-left uppercase border-2 border-gray-200 dark:border-slate-600">
                                        Date of Validity</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($eligibility as $elig)
                                <tr class="dark:text-gray-200">
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{
                                        $elig->eligibility }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{
                                        $elig->rating }}%</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{
                                        \Carbon\Carbon::parse($elig->date)->format('F d, Y') }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{
                                        $elig->place_of_exam }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{
                                        $elig->license }}</td>
                                    <td class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">{{
                                        \Carbon\Carbon::parse($elig->date_of_validity)->format('F d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- Work Experience --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $workExperience && $workExperience->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
                        V. WORK EXPERIENCE
                    </div>

                    @if ($workExperience && $workExperience->isNotEmpty())
                    <div class="m-scrollable">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase w-1/5"
                                        width="20%">
                                        <div class="block w-full">
                                            <div class=" flex justify-center w-full">
                                                INCLUSIVE DATES
                                            </div>
                                            <div class="flex w-full">
                                                <div
                                                    class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                    From
                                                </div>
                                                <div
                                                    class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                    To
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Position Title</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Department/Agency/Office/Company</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Monthly Salary</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Status of Appointment</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        GOV'T SERVICE</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($workExperience as $exp)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $exp->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $exp->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $exp->position }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $exp->department }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ '₱ ' . number_format($exp->monthly_salary, 2) }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $exp->status_of_appointment }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $exp->gov_service ? 'Yes' : 'No' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- Voluntary Work --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $voluntaryWorks && $voluntaryWorks->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
                        VI. VOLUNTARY WORK
                    </div>

                    @if ($voluntaryWorks && $voluntaryWorks->isNotEmpty())
                    <div class="m-scrollable">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Name of Organization</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Address of Organization</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase w-1/5">
                                        <div class="block w-full">
                                            <div class=" flex justify-center w-full">
                                                INCLUSIVE DATES
                                            </div>
                                            <div class="flex w-full">
                                                <div
                                                    class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                    From
                                                </div>
                                                <div
                                                    class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                    To
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Number of Hours</th>
                                    <th class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Position/Nature of Work</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($voluntaryWorks as $voluntary)
                                <tr>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $voluntary->org_name }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $voluntary->org_address }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $voluntary->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $voluntary->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-sm text-left">
                                        {{ $voluntary->no_of_hours }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-sm text-left">
                                        {{ $voluntary->position_nature }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- Learning and Development --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $lds && $lds->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
                        VII. LEARNING AND DEVELOPMENT
                    </div>

                    @if ($lds && $lds->isNotEmpty())
                    <div class="m-scrollable">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase"
                                        width="20%">Title of Training</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase w-1/5">
                                        <div class="block w-full">
                                            <div class=" flex justify-center w-full">
                                                INCLUSIVE DATES
                                            </div>
                                            <div class="flex w-full">
                                                <div
                                                    class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                    From
                                                </div>
                                                <div
                                                    class="flex justify-center border border-gray-200 dark:border-slate-600 p-1 w-2/4">
                                                    To
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase">
                                        Number of Hours</th>
                                    <th
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase">
                                        Type of LD</th>
                                    <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase"
                                        width="20%">Conducted/Sponsored By</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($lds as $ld)
                                <tr class="text-neutral-800 dark:text-neutral-200">
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $ld->title }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                        <div class="flex w-full">
                                            <div class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                {{ $ld->start_date }}
                                            </div>
                                            <div class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                {{ $ld->end_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $ld->no_of_hours }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $ld->type_of_ld }}</td>
                                    <td
                                        class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                        {{ $ld->conducted_by }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- Other Information --}}
                    <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">VIII.
                        OTHER INFORMATION</div>

                    <div class="m-scrollable">

                        {{-- SKILLS --}}
                        <div
                            class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <p class="p-1 w-full font-bold">SKILLS</p>
                        </div>

                        <div class="custom-d flex w-full border-r-2 border-l-2 border-gray-200 dark:border-slate-600">
                            <div class="flex w-full sm:w-auto dark:text-gray-200">
                                @foreach ($skills as $skill)
                                <p class="p-1"> • {{ $skill->skill }} </p>
                                @endforeach
                            </div>
                        </div>

                        {{-- Hobbies --}}
                        <div
                            class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <p class="p-1 w-full font-bold">HOBBIES</p>
                        </div>

                        <div class="custom-d flex w-full border-r-2 border-l-2 border-gray-200 dark:border-slate-600">
                            <div class="flex w-full sm:w-auto dark:text-gray-200">
                                @foreach ($hobbies as $hobby)
                                <p class="p-1"> • {{ $hobby->hobby }} </p>
                                @endforeach
                            </div>
                        </div>

                        {{-- NON-ACADEMIC DISTINCTIONS / RECOGNITION --}}
                        <div
                            class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <p class="p-1 w-full font-bold">NON-ACADEMIC DISTINCTIONS / RECOGNITION</p>
                        </div>

                        @if ($non_acads_distinctions && $non_acads_distinctions->isNotEmpty())
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Award</th>
                                    <th
                                        class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Association/ Organization Name</th>
                                    <th class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Date Received</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($non_acads_distinctions as $non_acads_distinction)
                                <tr class="dark:text-gray-200">
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $non_acads_distinction->award }}</td>
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $non_acads_distinction->ass_org_name }}</td>
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ \Carbon\Carbon::parse($non_acads_distinction->date_received)->format('F d,
                                        Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                        {{-- MEMBERSHIP IN ASSOCIATION/ORGANIZATION --}}
                        <div
                            class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <p class="p-1 w-full font-bold">MEMBERSHIP IN ASSOCIATION/ORGANIZATION</p>
                        </div>

                        @if ($assOrgMemberships && $assOrgMemberships->isNotEmpty())
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th
                                        class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Association/Organization Name</th>
                                    <th class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Position</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($assOrgMemberships as $assOrgMembership)
                                <tr class="dark:text-gray-200">
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $assOrgMembership->ass_org_name }}</td>
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $assOrgMembership->position }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                        {{-- Character References --}}
                        <div
                            class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <p class="p-1 w-full font-bold">CHARACTER REFERENCES</p>
                        </div>

                        @if ($references && $references->isNotEmpty())
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-slate-700">
                                    <th class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Fullname</th>
                                    <th
                                        class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Address</th>
                                    <th
                                        class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                        Tel Number</th>
                                    <th class="p-1 border-r-2 border-l-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase"
                                        width="20%">Mobile Number</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($references as $reference)
                                <tr class="dark:text-gray-200">
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $reference->firstname }} {{ $reference->middle_initial ?
                                        $reference->middle_initial . '.' : '' }} {{ $reference->surname }}</td>
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $reference->address }}</td>
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $reference->tel_number }}</td>
                                    <td
                                        class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                        {{ $reference->mobile_number }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-400 dark:bg-slate-700 p-2 text-white flex justify-center rounded-b-lg">
                        <button
                            class="btn bg-emerald-400 dark:bg-emerald-500 hover:bg-emerald-600 text-white whitespace-nowrap mx-2"
                            wire:click='exportPDS' wire:loading.attr='disabled'>
                            <div wire:loading wire:target="exportPDS" style="margin-right: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            <i class="bi bi-file-earmark-arrow-down"></i>&nbsp&nbspExport
                        </button>
                        <button wire:click="closePersonalDataSheet"
                            class="btn bg-emerald-400 dark:bg-emerald-500 hover:bg-emerald-600 text-white dark:text-white whitespace-nowrap mx-2">Close</button>
                    </div>

                </div>

            </div>
        </div>
        @else
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Employees List
                    </h1>
                </div>

                <style>
                    .scrollbar-thin1::-webkit-scrollbar {
                        width: 5px;
                    }

                    .scrollbar-thin1::-webkit-scrollbar-thumb {
                        background-color: #1a1a1a4b;
                        /* cursor: grab; */
                        border-radius: 0 50px 50px 0;
                    }

                    .scrollbar-thin1::-webkit-scrollbar-track {
                        background-color: #ffffff23;
                        border-radius: 0 50px 50px 0;
                    }
                </style>

                <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">


                    <div class="relative inline-block text-left">
                        <input type="search" id="search" wire:model.live="search" placeholder="Enter employee name"
                            class="py-2 px-3 block w-full shadow-sm text-sm font-medium border-gray-400
                            wire:text-neutral-800 dark: text-neutral-200 mb-4 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                    </div>


                    <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">

                        <!-- Filter Dropdown -->
                        <div class="w-full sm:w-auto">
                            <button wire:click="toggleDropdownFilter"
                                class="mt-4 sm:mt-0 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                    justify-center px-2 py-1.5 text-sm font-medium tracking-wide
                                    text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                    rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none w-full sm:w-fit" type="button">
                                Filter by
                                <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                            </button>
                            @if($dropdownForFilter)
                            <div class="absolute z-20 w-64 p-3 border border-gray-400
                                        bg-white rounded-lg shadow-2xl dark:bg-gray-700
                                        overflow-x-hidden scrollbar-thin1" style="height: fit-content">

                                <!-- Provinces Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownProvince" class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                 hover:bg-gray-200 dark:hover:bg-slate-600 focus:outline-none"
                                        type="button">
                                        Filter by Province
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if($dropdownForProvinceOpen)
                                    <div
                                        class="absolute z-20 w-full p-3 bg-gray-50 rounded-lg border border-gray-400
                                                    shadow-md dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Province</h6>
                                        <ul class="space-y-2 text-sm">
                                            @foreach($provinces as $province)
                                            <li class="flex items-center">
                                                <input id="province-{{ $province->province_description }}" type="radio"
                                                    wire:model.live="selectedProvince"
                                                    value="{{ $province->province_description }}"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="province-{{ $province->province_description }}"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">{{
                                                    $province->province_description
                                                    }}</label>
                                            </li>
                                            @endforeach
                                            <li class="flex items-center">
                                                <input id="any-province" type="radio" wire:model.live="selectedProvince"
                                                    value=""
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="any-province"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Any</label>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <!-- Cities Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownCity" class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600"
                                        type="button">
                                        Filter by City
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if($dropdownForCityOpen)
                                    <div
                                        class="absolute z-20 w-full p-3 bg-gray-50 rounded-lg border border-gray-400
                                                    shadow-md dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">City</h6>
                                        <ul class="space-y-2 text-sm">
                                            @if($cities)
                                            @foreach($cities as $city)
                                            <li class="flex items-center">
                                                <input id="city-{{ $city->city_municipality_description }}" type="radio"
                                                    wire:model.live="selectedCity"
                                                    value="{{ $city->city_municipality_description }}"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="city-{{ $city->city_municipality_description }}"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">{{
                                                    $city->city_municipality_description
                                                    }}</label>
                                            </li>
                                            @endforeach
                                            @endif
                                            <li class="flex items-center">
                                                <input id="any-city" type="radio" wire:model.live="selectedCity"
                                                    value=""
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="any-city"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Any</label>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <!-- Barangay Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownBarangay" class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600"
                                        type="button">
                                        Filter by Barangay
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if($dropdownForBarangayOpen)
                                    <div
                                        class="absolute z-20 w-full p-3 bg-gray-50 rounded-lg border border-gray-400
                                                    shadow-md dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Barangay</h6>
                                        <ul class="space-y-2 text-sm">
                                            @if($barangays)
                                            @foreach($barangays as $barangay)
                                            <li class="flex items-center">
                                                <input id="barangay-{{ $barangay->barangay_description }}" type="radio"
                                                    wire:model.live="selectedBarangay"
                                                    value="{{ $barangay->barangay_description }}"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="barangay-{{ $barangay->barangay_description }}"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">{{
                                                    $barangay->barangay_description
                                                    }}</label>
                                            </li>
                                            @endforeach
                                            @endif
                                            <li class="flex items-center">
                                                <input id="any-barangay" type="radio" wire:model.live="selectedBarangay"
                                                    value=""
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="any-barangay"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Any</label>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <!-- Civil Status Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownCivilStatus" class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600"
                                        type="button">
                                        Filter by Civil Status
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if($dropdownForCivilStatusOpen)
                                    <div
                                        class="absolute z-20 w-full p-3 bg-gray-50 rounded-lg border border-gray-400
                                                    shadow-md dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Civil Status
                                        </h6>
                                        <ul class="space-y-2 text-sm">
                                            <li class="flex items-center">
                                                <input id="single" type="radio" wire:model.live="civil_status"
                                                    value="Single"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="single"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Single</label>
                                            </li>
                                            <li class="flex items-center">
                                                <input id="married" type="radio" wire:model.live="civil_status"
                                                    value="Married"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="married"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Married</label>
                                            </li>
                                            <li class="flex items-center">
                                                <input id="widowed" type="radio" wire:model.live="civil_status"
                                                    value="Widowed"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="widowed"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Widowed</label>
                                            </li>
                                            <li class="flex items-center">
                                                <input id="separated" type="radio" wire:model.live="civil_status"
                                                    value="Separated"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="separated"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Separated</label>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <!-- Sex Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownSex" class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600"
                                        type="button">
                                        Filter by Sex
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if($dropdownForSexOpen)
                                    <div
                                        class="absolute z-20 w-full p-3 bg-gray-50 rounded-lg border border-gray-400
                                                    shadow-md dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Sex</h6>
                                        <ul class="space-y-2 text-sm">
                                            <li class="flex items-center">
                                                <input id="default" type="radio" wire:model.live="sex" value=""
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="default"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Default</label>
                                            </li>
                                            <li class="flex items-center">
                                                <input id="male" type="radio" wire:model.live="sex" value="Male"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="male"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Male</label>
                                            </li>
                                            <li class="flex items-center">
                                                <input id="female" type="radio" wire:model.live="sex" value="Female"
                                                    class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                <label for="female"
                                                    class="ml-2 text-gray-900 dark:text-gray-300">Female</label>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="w-full sm:w-auto">
                            <button wire:click="toggleDropdown" class="mt-4 sm:mt-0 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                    justify-center px-2 py-1.5 text-sm font-medium tracking-wide
                                    text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                    rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                                type="button">
                                Filter Column
                                <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                            </button>
                            @if($dropdownForCategoryOpen)
                            <div class="absolute z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg
                                        shadow-2xl dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Category</h6>
                                <ul class="space-y-2 text-sm">
                                    {{-- <li class="flex items-center">
                                        <input id="name" type="checkbox" wire:model="filters.name" class="h-4 w-4">
                                        <label for="name" class="ml-2 text-gray-900 dark:text-gray-300">Name</label>
                                    </li> --}}
                                    <li class="flex items-center">
                                        <input id="date_of_birth" type="checkbox"
                                            wire:model.live="filters.date_of_birth" class="h-4 w-4">
                                        <label for="date_of_birth" class="ml-2 text-gray-900 dark:text-gray-300">Birth
                                            Date</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="place_of_birth" type="checkbox"
                                            wire:model.live="filters.place_of_birth" class="h-4 w-4">
                                        <label for="place_of_birth" class="ml-2 text-gray-900 dark:text-gray-300">Birth
                                            Place</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="sex" type="checkbox" wire:model.live="filters.sex" class="h-4 w-4">
                                        <label for="sex" class="ml-2 text-gray-900 dark:text-gray-300">Sex</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="citizenship" type="checkbox" wire:model.live="filters.citizenship"
                                            class="h-4 w-4">
                                        <label for="citizenship"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Citizenship</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="civil_status" type="checkbox" wire:model.live="filters.civil_status"
                                            class="h-4 w-4">
                                        <label for="civil_status" class="ml-2 text-gray-900 dark:text-gray-300">Civil
                                            Status</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="height" type="checkbox" wire:model.live="filters.height"
                                            class="h-4 w-4">
                                        <label for="height" class="ml-2 text-gray-900 dark:text-gray-300">Height</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="weight" type="checkbox" wire:model.live="filters.weight"
                                            class="h-4 w-4">
                                        <label for="weight" class="ml-2 text-gray-900 dark:text-gray-300">Weight</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="blood_type" type="checkbox" wire:model.live="filters.blood_type"
                                            class="h-4 w-4">
                                        <label for="blood_type" class="ml-2 text-gray-900 dark:text-gray-300">Blood
                                            Type</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gsis" type="checkbox" wire:model.live="filters.gsis" class="h-4 w-4">
                                        <label for="gsis" class="ml-2 text-gray-900 dark:text-gray-300">GSIS ID
                                            No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="pagibig" type="checkbox" wire:model.live="filters.pagibig"
                                            class="h-4 w-4">
                                        <label for="pagibig" class="ml-2 text-gray-900 dark:text-gray-300">PAGIBIG ID
                                            No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="philhealth" type="checkbox" wire:model.live="filters.philhealth"
                                            class="h-4 w-4">
                                        <label for="philhealth" class="ml-2 text-gray-900 dark:text-gray-300">PhilHealth
                                            ID
                                            No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="sss" type="checkbox" wire:model.live="filters.sss" class="h-4 w-4">
                                        <label for="sss" class="ml-2 text-gray-900 dark:text-gray-300">SSS No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="tin" type="checkbox" wire:model.live="filters.tin" class="h-4 w-4">
                                        <label for="tin" class="ml-2 text-gray-900 dark:text-gray-300">TIN No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="agency_employee_no" type="checkbox"
                                            wire:model.live="filters.agency_employee_no" class="h-4 w-4">
                                        <label for="agency_employee_no"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Agency
                                            Employee No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="permanent_selectedProvince" type="checkbox"
                                            wire:model.live="filters.permanent_selectedProvince" class="h-4 w-4">
                                        <label for="permanent_selectedProvince"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Permanent Address
                                            (Province)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="permanent_selectedCity" type="checkbox"
                                            wire:model.live="filters.permanent_selectedCity" class="h-4 w-4">
                                        <label for="permanent_selectedCity"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Permanent
                                            Address (City)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="permanent_selectedBarangay" type="checkbox"
                                            wire:model.live="filters.permanent_selectedBarangay" class="h-4 w-4">
                                        <label for="permanent_selectedBarangay"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Permanent Address
                                            (Barangay)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="p_house_street" type="checkbox"
                                            wire:model.live="filters.p_house_street" class="h-4 w-4">
                                        <label for="p_house_street"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Permanent
                                            Address
                                            (Street)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="permanent_selectedZipcode" type="checkbox"
                                            wire:model.live="filters.permanent_selectedZipcode" class="h-4 w-4">
                                        <label for="permanent_selectedZipcode"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Permanent Address
                                            (Zip Code)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="residential_selectedProvince" type="checkbox"
                                            wire:model.live="filters.residential_selectedProvince" class="h-4 w-4">
                                        <label for="residential_selectedProvince"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Residential Address
                                            (Province)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="residential_selectedCity" type="checkbox"
                                            wire:model.live="filters.residential_selectedCity" class="h-4 w-4">
                                        <label for="residential_selectedCity"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Residential
                                            Address (City)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="residential_selectedBarangay" type="checkbox"
                                            wire:model.live="filters.residential_selectedBarangay" class="h-4 w-4">
                                        <label for="residential_selectedBarangay"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Residential Address
                                            (Barangay)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="p_house_street" type="checkbox"
                                            wire:model.live="filters.p_house_street" class="h-4 w-4">
                                        <label for="p_house_street"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Residential
                                            Address
                                            (Street)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="residential_selectedZipcode" type="checkbox"
                                            wire:model.live="filters.residential_selectedZipcode" class="h-4 w-4">
                                        <label for="residential_selectedZipcode"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Residential Address
                                            (Zip Code)</label>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>

                        <!-- Export to Excel -->
                        <div class="w-full sm:w-auto">
                            <button wire:click="exportUsers" class="mt-4 sm:mt-0 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                    justify-center px-4 py-1.5 text-sm font-medium tracking-wide
                                    text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                    rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                                type="button">
                                <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                                <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                            </button>
                        </div>

                    </div>

                </div>

                <!-- Table -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto">
                        <div class="inline-block w-full py-2 align-middle">
                            <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Name
                                                </th>
                                                @if($filters['date_of_birth'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Birth
                                                    Date</th>
                                                @endif
                                                @if($filters['place_of_birth'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Birth
                                                    Place</th>
                                                @endif
                                                @if($filters['sex'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Sex
                                                </th>
                                                @endif
                                                @if($filters['citizenship'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Citizenship</th>
                                                @endif
                                                @if($filters['civil_status'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Civil
                                                    Status</th>
                                                @endif
                                                @if($filters['height'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Height
                                                </th>
                                                @endif
                                                @if($filters['weight'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Weight
                                                </th>
                                                @endif
                                                @if($filters['blood_type'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Blood
                                                    Type</th>
                                                @endif
                                                @if($filters['gsis'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    GSIS
                                                    ID No.</th>
                                                @endif
                                                @if($filters['pagibig'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    PAGIBIG ID No.</th>
                                                @endif
                                                @if($filters['philhealth'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    PhilHealth ID No.</th>
                                                @endif
                                                @if($filters['sss'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    SSS
                                                    No.</th>
                                                @endif
                                                @if($filters['tin'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    TIN
                                                    No.</th>
                                                @endif
                                                @if($filters['agency_employee_no'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Agency
                                                    Employee No.</th>
                                                @endif
                                                @if($filters['permanent_selectedProvince'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Permanent Address (Province)</th>
                                                @endif
                                                @if($filters['permanent_selectedCity'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Permanent Address (City)</th>
                                                @endif
                                                @if($filters['permanent_selectedBarangay'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Permanent Address (Barangay)</th>
                                                @endif
                                                @if($filters['p_house_street'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Permanent Address (Street)</th>
                                                @endif
                                                @if($filters['permanent_selectedZipcode'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Permanent Address (Zip Code)</th>
                                                @endif
                                                @if($filters['residential_selectedProvince'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Residential Address (Province)</th>
                                                @endif
                                                @if($filters['residential_selectedCity'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Residential Address (City)</th>
                                                @endif
                                                @if($filters['residential_selectedBarangay'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Residential Address (Barangay)</th>
                                                @endif
                                                @if($filters['r_house_street'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Residential Address (Street)</th>
                                                @endif
                                                @if($filters['residential_selectedZipcode'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Residential Address (Zip Code)</th>
                                                @endif
                                                <th
                                                    class="px-5 py-3 text-gray-100 text-sm font-medium text-right sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                            @foreach($users as $user)
                                            <tr class="whitespace-nowrap">
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->name }}
                                                </td>
                                                @if($filters['date_of_birth'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->date_of_birth }}</td>
                                                @endif
                                                @if($filters['place_of_birth'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->place_of_birth }}</td>
                                                @endif
                                                @if($filters['sex'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->sex }}
                                                </td>
                                                @endif
                                                @if($filters['citizenship'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->citizenship }}</td>
                                                @endif
                                                @if($filters['civil_status'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->civil_status }}</td>
                                                @endif
                                                @if($filters['height'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->height }}
                                                </td>
                                                @endif
                                                @if($filters['weight'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->weight }}
                                                </td>
                                                @endif
                                                @if($filters['blood_type'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->blood_type
                                                    }}</td>
                                                @endif
                                                @if($filters['gsis'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->gsis
                                                    }}</td>
                                                @endif
                                                @if($filters['pagibig'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->pagibig
                                                    }}</td>
                                                @endif
                                                @if($filters['philhealth'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->philhealth
                                                    }}</td>
                                                @endif
                                                @if($filters['sss'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->sss
                                                    }}</td>
                                                @endif
                                                @if($filters['tin'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->tin
                                                    }}</td>
                                                @endif
                                                @if($filters['agency_employee_no'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->agency_employee_no
                                                    }}</td>
                                                @endif
                                                @if($filters['permanent_selectedProvince'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->permanent_selectedProvince
                                                    }}</td>
                                                @endif
                                                @if($filters['permanent_selectedCity'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->permanent_selectedCity
                                                    }}</td>
                                                @endif
                                                @if($filters['permanent_selectedBarangay'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->permanent_selectedBarangay
                                                    }}</td>
                                                @endif
                                                @if($filters['p_house_street'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->p_house_street
                                                    }}</td>
                                                @endif
                                                @if($filters['permanent_selectedZipcode'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->permanent_selectedZipcode
                                                    }}</td>
                                                @endif
                                                @if($filters['residential_selectedProvince'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->residential_selectedProvince
                                                    }}</td>
                                                @endif
                                                @if($filters['residential_selectedCity'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->residential_selectedCity
                                                    }}</td>
                                                @endif
                                                @if($filters['residential_selectedBarangay'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->residential_selectedBarangay
                                                    }}</td>
                                                @endif
                                                @if($filters['r_house_street'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->r_house_street
                                                    }}</td>
                                                @endif
                                                @if($filters['residential_selectedZipcode'])
                                                <td class="px-4 py-2 text-center">
                                                    {{
                                                    $user->residential_selectedZipcode
                                                    }}</td>
                                                @endif
                                                <td
                                                    class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <button wire:click="showUser({{ $user->id }})"
                                                        class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
