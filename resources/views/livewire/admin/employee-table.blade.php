<div class="w-full" x-data="{
    selectedTab: 'C1',
}" x-cloak>

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

    {{-- Personal Data Sheet --}}
    @if ($personalDataSheetOpen && $selectedUser)
        <div class="flex justify-center w-full">
            <div class="overflow-x-auto w-full bg-white rounded-2xl p-3 shadow dark:bg-gray-800 relative">
                <button wire:click="closePersonalDataSheet"
                    class="absolute top-2 right-2 text-black dark:text-white whitespace-nowrap mx-2">
                    <i class="bi bi-x-circle" title="Close"></i>
                </button>

                <div class="pt-4 pb-4">
                    <h1 class="text-3xl font-bold text-center text-slate-800 dark:text-white">PERSONAL DATA SHEET</h1>
                </div>

                <!-- Export to Excel -->
                <div
                    class="w-full flex flex-col sm:flex-row sm:justify-end sm:space-x-4 relative inline-block text-left mb-6 sm:mb-0">
                    <button wire:click="exportPDS"
                        class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                        justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                        text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                        rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                        type="button" title="Export PDS">
                        <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                        <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22"
                            alt="">
                        <div wire:loading wire:target="exportPDS" style="margin-left: 5px">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                    </button>
                </div>

                <div class="overflow-hidden text-sm pb-3">
                    <div class="flex gap-2 overflow-x-auto -mb-2" class="relative">
                        <button @click="selectedTab = 'C1'"
                            :class="{ 'font-bold text-gray-100 dark:text-gray-700 bg-gray-400 dark:bg-slate-300 rounded-t-lg': selectedTab === 'C1', 'text-slate-500 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'C1' }"
                            class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                            C1
                        </button>
                        <button @click="selectedTab = 'C2'"
                            :class="{ 'font-bold text-gray-100 dark:text-gray-700 bg-gray-400 dark:bg-slate-300 rounded-t-lg': selectedTab === 'C2', 'text-slate-500 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'C2' }"
                            class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                            C2
                        </button>
                        <button @click="selectedTab = 'C3'"
                            :class="{ 'font-bold text-gray-100 dark:text-gray-700 bg-gray-400 dark:bg-slate-300 rounded-t-lg': selectedTab === 'C3', 'text-slate-500 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'C3' }"
                            class="h-min px-4 pt-2 pb-4 text-sm">
                            C3
                        </button>
                        <button @click="selectedTab = 'C4'"
                            :class="{ 'font-bold text-gray-100 dark:text-gray-700 bg-gray-400 dark:bg-slate-300 rounded-t-lg': selectedTab === 'C4', 'text-slate-500 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'C4' }"
                            class="h-min px-4 pt-2 pb-4 text-sm">
                            C4
                        </button>
                    </div>

                    <div x-show="selectedTab === 'C1'" class="relative z-10">
                        {{-- Employee's Data --}}
                        <div
                            class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">
                            I. PERSONAL INFORMATION
                        </div>
                        <div>

                            <div class="custom-d flex w-full">

                                <div class="w-full sm:w-2/4 block">
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Surname</p>
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                            {{ $userData->surname ?: 'N/A' }}</p>
                                    </div>

                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Firstname</p>
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                            {{ $userData->first_name ?: 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="w-full sm:w-2/4 block">
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 dark:bg-slate-700 bg-gray-50">
                                            Middlename</p>
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                            {{ $userData->middle_name ?: 'N/A' }}</p>
                                    </div>

                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Name Extension</p>
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 w-full p-1 dark:text-gray-200">
                                            {{ $userData->name_extension ?: 'N/A' }}</p>
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
                                            {{ $userData->date_of_birth ? \Carbon\Carbon::parse($userData->date_of_birth)->format('F d, Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Place of Birth</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->place_of_birth ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Sex</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->sex ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Civil Status</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->civil_status ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Citizenship</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->citizenship ?: 'N/A' }}  <span class="text-xs opacity-80">{{ $userData->dual_citizenship_type ? '| ' . $userData->dual_citizenship_type : '' }} {{ $userData->dual_citizenship_country ? '| ' . $userData->dual_citizenship_country : '' }}</span></p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Height</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->height ?: 'N/A' }}m</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Weight</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->weight ?: 'N/A' }}kg</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Bloodtype</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->blood_type ?: 'N/A' }}</p>
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
                                            {{ $userData->permanent_selectedBarangay }}
                                            {{ $userData->permanent_selectedCity }} <br>
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
                                            {{ $userData->residential_selectedBarangay }}
                                            {{ $userData->residential_selectedCity }} <br>
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
                                            {{ $userData->tel_number ?: 'N/A' }}</p>
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
                                            {{ $userData->mobile_number ?: 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="w-full sm:w-2/4 block">
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Email</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->email ?: 'N/A' }}</p>
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
                                            {{ $userData->gsis ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Pag-Ibig ID No.</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->pagibig ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            PhilHealth ID No.</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->philhealth ?: 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="w-full sm:w-2/4 block">
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            SSS No.</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->sss ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            TIN No.</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->tin ?: 'N/A' }}</p>
                                    </div>
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Agency Employee No.</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userData->agency_employee_no ?: 'N/A' }}</p>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Family Background --}}
                        <div class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold">II.
                            FAMILY BACKGROUND
                        </div>
                        <div>
                            {{-- Spouse --}}
                            <div
                                class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                                <p class="p-1 w-full font-bold dark:text-gray-200">Spouse</p>
                            </div>

                            @if ($userSpouse)
                                <div class="custom-d flex w-full">
                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Surname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->surname ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Firstname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->first_name ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Middlename</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->middle_name ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Name Extension</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->name_extension ?: 'N/A' }}</p>
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
                                                {{ $userSpouse->birth_date ? \Carbon\Carbon::parse($userSpouse->birth_date)->format('m/d/Y') : 'N/A' }}
                                            </p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Occupation</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->occupation ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Employer</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->employer ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Tel. No.</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userSpouse->tel_number ?: 'N/A' }}</p>
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
                                                {{ $userSpouse->business_address ?: 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Father --}}
                            <div
                                class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                                <p class="p-1 w-full font-bold dark:text-gray-200">Father</p>
                            </div>

                            @if ($userFather)
                                <div class="custom-d flex w-full">

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Surname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userFather->surname ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Firstname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userFather->first_name ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Middlename</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userFather->middle_name ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Name Extension</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userFather->name_extension ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                </div>
                            @endif

                            {{-- Mother's Maiden Name --}}
                            <div
                                class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                                <p class="p-1 w-full font-bold dark:text-gray-200">Mother's Maiden Name</p>
                            </div>

                            @if ($userMother)
                                <div class="custom-d flex w-full">

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Surname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userMother->surname ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Firstname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userMother->first_name ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Middlename</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userMother->middle_name ?: 'N/A' }}</p>
                                        </div>

                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Name Extension</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $userMother->name_extension ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                </div>
                            @endif

                            {{-- Children --}}
                            <div
                                class="flex w-full sm:w-auto bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600">
                                <p class="p-1 w-full font-bold dark:text-gray-200">Children</p>
                            </div>

                            @if ($userChildren)
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
                                                    {{ $child->childs_birth_date ? \Carbon\Carbon::parse($child->childs_birth_date)->format('m/d/Y') : 'N/A' }}
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
                                        {{ $educ->level ?: 'N/A' }}
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
                                                {{ $educ->name_of_school ?: 'N/A' }}</p>
                                        </div>
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Period of Attendance</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                From: {{ $educ->from ? \Carbon\Carbon::parse($educ->from)->format('m/d/Y') : 'N/A' }} <br>
                                                To: {{ $educ->to ? \Carbon\Carbon::parse($educ->to)->format('m/d/Y') : 'Present' }}
                                            </p>
                                        </div>
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Scholarship/Academic Honors Received</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $educ->award ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-2/4 block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Basic Education/<br>Degree/Course</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $educ->basic_educ_degree_course ?: 'N/A' }}</p>
                                        </div>
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Highest Level/<br>Units Earned</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $educ->highest_level_unit_earned ?: 'N/A' }}</p>
                                        </div>
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Year Graduated</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $educ->year_graduated ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="selectedTab === 'C2'" class="relative z-10">
                        {{-- Civil Service Eligibility --}}
                        <div
                            class="rounded-t-lg bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $eligibility && $eligibility->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
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
                                        @foreach ($eligibility as $elig)
                                            <tr class="dark:text-gray-200">
                                                <td
                                                    class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $elig->eligibility ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $elig->rating ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $elig->date ? \Carbon\Carbon::parse($elig->date)->format('m/d/Y') : 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $elig->place_of_exam ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $elig->license ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $elig->date_of_validity ? \Carbon\Carbon::parse($elig->date_of_validity)->format('m/d/Y') : 'N/A' }}
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
                                                SALARY/JOB/PAY GRADE & STEP</th>
                                            <th
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                                Status of Appointment</th>
                                            <th
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 font-medium text-left uppercase">
                                                GOV'T SERVICE</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach ($workExperience as $exp)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                                    <div class="flex w-full">
                                                        <div
                                                            class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                            {{ $exp->start_date ? \Carbon\Carbon::parse($exp->start_date)->format('m/d/Y') : 'N/A' }}
                                                        </div>
                                                        <div
                                                            class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                            {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('m/d/Y') : 'Present' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $exp->position ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $exp->department ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ '₱ ' . number_format($exp->monthly_salary, 2) }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $exp->sg_step ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $exp->status_of_appointment ?: 'N/A' }}</td>
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
                    </div>

                    <div x-show="selectedTab === 'C3'" class="relative z-10">
                        {{-- Voluntary Work --}}
                        <div
                            class="rounded-t-lg bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold {{ $voluntaryWorks && $voluntaryWorks->isNotEmpty() ? '' : 'border-b-2 border-gray-200 dark:border-slate-600' }}">
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
                                        @foreach ($voluntaryWorks as $voluntary)
                                            <tr>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $voluntary->org_name ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $voluntary->org_address ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                                    <div class="flex w-full">
                                                        <div
                                                            class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                            {{ $voluntary->start_date ? \Carbon\Carbon::parse($voluntary->start_date)->format('m/d/Y') : 'N/A' }}
                                                        </div>
                                                        <div
                                                            class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                            {{ $voluntary->end_date ? \Carbon\Carbon::parse($voluntary->end_datee)->format('m/d/Y') : 'Present' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-sm text-left">
                                                    {{ $voluntary->no_of_hours ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-sm text-left">
                                                    {{ $voluntary->position_nature ?: 'N/A' }}
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
                                                width="20%">
                                                Conducted/Sponsored By
                                            </th>
                                            <th class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-700 font-medium text-left uppercase"
                                                width="20%">
                                                Certificate
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach ($lds as $ld)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $ld->title ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                                    <div class="flex w-full">
                                                        <div
                                                            class="flex justify-center border-r border-r-gray-300 p-1 w-2/4">
                                                            {{ $ld->start_date ? \Carbon\Carbon::parse($ld->start_date)->format('m/d/Y') : 'N/A' }}
                                                        </div>
                                                        <div
                                                            class="flex justify-center border-l border-l-gray-300 p-1 w-2/4">
                                                            {{ $ld->end_date ? \Carbon\Carbon::parse($ld->end_date)->format('m/d/Y') : 'Present' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $ld->no_of_hours ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $ld->type_of_ld ?: 'N/A' }}
                                                </td>
                                                <td
                                                    class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    {{ $ld->conducted_by ?: 'N/A' }}
                                                </td>
                                                <td class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                    @php
                                                        $fileName = $ld->certificate ? basename($ld->certificate) : 'N/A';
                                                        $truncatedFileName = strlen($fileName) > 15 ? substr($fileName, 0, 15) . '...' : $fileName;
                                                    @endphp
                                                    <span class="{{ $ld->certificate ? 'text-blue-500 cursor-pointer' : '' }}" @if($ld->certificate)wire:click='downloadCertificate({{ $ld->id }})'@endif>{{ $truncatedFileName }}</span>
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

                            <div
                                class="custom-d flex w-full border-r-2 border-l-2 border-gray-200 dark:border-slate-600">
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

                            <div
                                class="custom-d flex w-full border-r-2 border-l-2 border-gray-200 dark:border-slate-600">
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
                                        @foreach ($non_acads_distinctions as $non_acads_distinction)
                                            <tr class="dark:text-gray-200">
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $non_acads_distinction->award ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $non_acads_distinction->ass_org_name ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $non_acads_distinction->date_received ? \Carbon\Carbon::parse($non_acads_distinction->date_received)->format('m/d/Y') : 'N/A' }}
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
                                        @foreach ($assOrgMemberships as $assOrgMembership)
                                            <tr class="dark:text-gray-200">
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $assOrgMembership->ass_org_name ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $assOrgMembership->position ?: 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>

                    <div x-show="selectedTab === 'C4'" class="relative z-10">
                        <div class="m-scrollable">
                            <div
                                class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">
                            </div>

                            {{-- 34 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>34.</p>
                                        <p class="ml-2 mb-4">
                                            Are you related by consanguinity or affinity to the appointing or
                                            recommending authority, or to the <br>
                                            chief of bureau or office or to the person who has immediate supervision
                                            over you in the Office, <br>
                                            Bureau or Department where you will be apppointed,
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end items-start px-4 bg-white dark:bg-slate-700 relative">
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            a. within the third degree?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-center p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model='q34aAnswer' name="answer34a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model='q34aAnswer' name="answer34a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            b. within the fourth degree (for Local Government Unit - Career Employees)?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q34bAnswer' name="answer34b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q34bAnswer' name="answer34b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q34bDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 35 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>35.</p>
                                        <p class="ml-2">
                                            a. Have you ever been found guilty of any administrative offense?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q35aAnswer' name="answer35a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q35aAnswer' name="answer35a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q35aDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            b. Have you been criminally charged before any court?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q35bAnswer' name="answer35b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q35bAnswer' name="answer35b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="flex w-full  mt-2">
                                                <p class="w-2/5 text-right text-gray-400">Date Filed:</p>
                                                <div class="w-3/5 border-b border-black dark:border-white ml-2 mb-2">
                                                    {{ $q35bDate_filed }}
                                                </div>
                                            </div>
                                            <div class="flex w-full">
                                                <p class="w-2/5 text-right text-gray-400">Status of Case/s:</p>
                                                <div class="w-3/5 border-b border-black dark:border-white ml-2 mb-2">
                                                    {{ $q35bStatus }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 36 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>36.</p>
                                        <p class="ml-2">
                                            Have you ever been convicted of any crime or violation of any law, decree,
                                            ordinance or regulation by any court or tribunal?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q36aAnswer' name="answer36a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q36aAnswer' name="answer36a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q36aDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 37 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>37.</p>
                                        <p class="ml-2">
                                            Have you ever been separated from the service in any of the following modes:
                                            resignation, retirement, dropped from the rolls, dismissal,
                                            termination, end of term, finished contract or phased out (abolition) in the
                                            public or private sector?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q37aAnswer' name="answer37a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q37aAnswer' name="answer37a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q37aDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 38 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>38.</p>
                                        <p class="ml-2">
                                            a. Have you ever been a candidate in a national or local election held
                                            within the last year (except Barangay election)?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q38aAnswer' name="answer38a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q38aAnswer' name="answer38a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q38aDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            b. Have you resigned from the government service during the three (3)-month
                                            period before
                                            the last election to promote/actively campaign for a national or local
                                            candidate?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q38bAnswer' name="answer38b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q38bAnswer' name="answer38b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q38bDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 39 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>39.</p>
                                        <p class="ml-2">
                                            Have you acquired the status of an immigrant or permanent resident of
                                            another country?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q39aAnswer' name="answer39a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q39aAnswer' name="answer39a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, give details (country):</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q39aDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 40 --}}
                            <div
                                class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p>40.</p>
                                        <p class="ml-2 mb-4">
                                            Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for
                                            Disabled Persons (RA 7277); and (c)
                                            Solo Parents Welfare Act of 2000 (RA 8972), please answer the following
                                            items:
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end items-start px-4 bg-white dark:bg-slate-700">
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            a. Are you a member of any indigenous group?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model='q40aAnswer' name="answer40a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model='q40aAnswer' name="answer40a"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, please specify:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q40aDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            b. Are you a person with disability?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q40bAnswer' name="answer40b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q40bAnswer' name="answer40b"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, please specify ID No:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q40bDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
                                        <p class="ml-6">
                                            c. Are you a solo parent?
                                        </p>
                                    </div>
                                    <div
                                        class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                        <div class="flex items-center">
                                            <input id="yes" type="radio" value="1"
                                                wire:model.live='q40cAnswer' name="answer40c"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">Yes</label>
                                            <input id="yes" class="ml-10" value="0" type="radio"
                                                wire:model.live='q40cAnswer' name="answer40c"
                                                style="pointer-events: none">
                                            <label for="yes" class="ml-2">No</label>
                                        </div>
                                        <div
                                            class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                            <p class="text-gray-400">If YES, please specify ID No:</p>
                                            <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                                {{ $q40cDetails }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                        @foreach ($references as $reference)
                                            <tr class="dark:text-gray-200">
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $reference->firstname }}
                                                    {{ $reference->middle_initial ? $reference->middle_initial . '.' : '' }}
                                                    {{ $reference->surname }}</td>
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $reference->address ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $reference->tel_number ?: 'N/A' }}</td>
                                                <td
                                                    class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                    {{ $reference->mobile_number ?: 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            {{-- Gov ID --}}
                            <div class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600">
                                <div class="w-full block sm:flex">
                                    <div
                                        class="w-full bg-gray-100 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 flex flex-col justify-end relative">
                                        <div
                                            class="w-full border-b-2 border-gray-200 dark:border-slate-600 p-2 bg-gray-100 dark:bg-slate-700">
                                            <p class="w-full">Government Issued ID (i.e.Passport, GSIS, SSS, PRC,
                                                Driver's License, etc.) </p>
                                            <p class="w-full text-right">PLEASE INDICATE ID Number</p>
                                        </div>
                                        <div class="flex w-full border-b-2 border-gray-200 dark:border-slate-600 px-2 bg-gray-50 dark:bg-gray-800 items-center"
                                            style="height: 50px">
                                            <p class="w-2/3">Government Issued ID:</p>
                                            @if ($editGovId)
                                                <input type="text" value="{{ $govId }}"
                                                    wire:model='govId'
                                                    class="w-1/3 text-sm bg-gray-100 text-gray-800 w-full" autofocus
                                                    style="height: 35px">
                                            @elseif($govId)
                                                <p class="w-2/3 text-gray-800 dark:text-gray-100 text-right">
                                                    {{ $govId ?: 'N/A' }}</p>
                                            @endif
                                        </div>
                                        <div class="flex w-full border-b-2 border-gray-200 dark:border-slate-600 px-2 bg-gray-50 dark:bg-gray-800 items-center"
                                            style="height: 50px">
                                            <p class="w-2/3">ID/License/Passport No.:</p>
                                            @if ($editGovId)
                                                <input type="text" value="{{ $idNumber }}"
                                                    wire:model='idNumber'
                                                    class="w-1/3 text-sm bg-gray-100 text-gray-800 w-full" autofocus
                                                    style="height: 35px">
                                            @elseif($idNumber)
                                                <p class="w-2/3 text-gray-800 dark:text-gray-100 text-right">
                                                    {{ $idNumber ?: 'N/A' }}</p>
                                            @endif
                                        </div>
                                        <div class="flex w-full px-2 bg-gray-50 dark:bg-gray-800 items-center"
                                            style="height: 50px">
                                            <p class="w-2/3">Date/Place of Issuance:</p>
                                            @if ($editGovId)
                                                <input type="text" wire:model='dateIssued'
                                                    class="w-1/3 text-sm bg-gray-100 text-gray-800 w-full" autofocus
                                                    style="height: 35px">
                                            @elseif($dateIssued)
                                                <p class="w-2/3 text-gray-800 dark:text-gray-100 text-right">
                                                    {{ $dateIssued ?: 'N/A' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-400 dark:bg-slate-700 p-2 text-white flex justify-center rounded-b-lg">
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Employees List
                    </h1>
                </div>

                <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">


                    {{-- Search Input --}}
                    <div class="w-full sm:w-1/3 sm:mr-4">
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                        <input type="text" id="search" wire:model.live="search"
                            class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                                dark:hover:bg-slate-600 dark:border-slate-600
                                dark:text-gray-300 dark:bg-gray-800"
                            placeholder="Enter employee name or ID">
                    </div>


                    <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">

                        <!-- Filter Dropdown -->
                        <div x-data="{ open: @entangle('toggleDropdownFilter') }" class="w-full sm:w-auto">
                            <button @click="open = !open"
                                class="mt-4 sm:mt-0 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                    justify-center px-2 py-1.5 text-sm font-medium tracking-wide
                                    text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                    rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none w-full sm:w-fit"
                                type="button">
                                Group by
                                <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                            </button>

                            {{-- @if ($dropdownForFilter) --}}
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-20 w-64 p-3 border border-gray-400
                                        bg-white rounded-lg shadow-2xl dark:bg-gray-700
                                        overflow-x-hidden scrollbar-thin1"
                                style="height: fit-content">

                                <!-- Provinces Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownProvince"
                                        class="w-full mr-4 p-2 mb-4 text-left text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 
                                        transition-colors duration-200 rounded-lg border border-gray-400 hover:bg-gray-200 
                                        dark:hover:bg-slate-600 focus:outline-none
                                        {{ $dropdownForProvinceOpen ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                                        type="button">
                                        Group by Province
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if ($dropdownForProvinceOpen)
                                    <div class="w-full absolute z-20">
                                        <div
                                            class="w-full p-3 rounded-lg border border-gray-400 shadow-md bg-gray-100 dark:bg-gray-800
                                             max-h-60 overflow-y-auto scrollbar-thin1">
                                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Province
                                            </h6>
                                            <ul class="space-y-2 text-sm">
                                                <li class="flex items-center">
                                                    <input id="select-all-provinces" type="checkbox"
                                                        wire:model.live="selectAllProvinces"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="select-all-provinces"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Select
                                                        All</label>
                                                </li>
                                                @foreach ($provinces as $province)
                                                    <li class="flex items-center">
                                                        <input id="province-{{ $province->province_description }}"
                                                            type="checkbox" wire:model.live="selectedProvinces"
                                                            value="{{ $province->province_description }}"
                                                            class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                        <label for="province-{{ $province->province_description }}"
                                                            class="ml-2 text-gray-900 dark:text-gray-300">{{ $province->province_description }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="h-3 w-full"></div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Cities Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownCity"
                                        class="w-full mr-4 p-2 mb-4 text-left text-sm font-medium tracking-wide text-neutral-800 
                                        dark:text-neutral-200 transition-colors duration-200 rounded-lg border border-gray-400 
                                        hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600
                                        {{ $dropdownForCityOpen ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                                        type="button">
                                        Group by City
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if ($dropdownForCityOpen)
                                    <div class="w-full absolute z-20">
                                        <div
                                            class="w-full p-3 rounded-lg border border-gray-400 shadow-md bg-gray-100 dark:bg-gray-800
                                             max-h-60 overflow-y-auto scrollbar-thin1">
                                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">City
                                            </h6>
                                            <ul class="space-y-2 text-sm">
                                                <li class="flex items-center">
                                                    <input id="select-all-cities" type="checkbox"
                                                        wire:model.live="selectAllCities"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="select-all-cities"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Select
                                                        All</label>
                                                </li>
                                                @foreach ($cities as $city)
                                                    <li class="flex items-center">
                                                        <input id="city-{{ $city->city_municipality_description }}"
                                                            type="checkbox" wire:model.live="selectedCities"
                                                            value="{{ $city->city_municipality_description }}"
                                                            class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                        <label for="city-{{ $city->city_municipality_description }}"
                                                            class="ml-2 text-gray-900 dark:text-gray-300">{{ $city->city_municipality_description }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="h-3 w-full"></div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Barangay Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownBarangay"
                                        class="w-full mr-4 p-2 mb-4 text-left text-sm font-medium tracking-wide text-neutral-800 
                                        dark:text-neutral-200 transition-colors duration-200 rounded-lg border 
                                        border-gray-400 hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600
                                        {{ $dropdownForBarangayOpen ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                                        type="button">
                                        Group by Barangay
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if ($dropdownForBarangayOpen)
                                    <div class="w-full absolute z-20">
                                        <div
                                            class="w-full p-3 rounded-lg border border-gray-400 shadow-md bg-gray-100 dark:bg-gray-800
                                             max-h-60 overflow-y-auto scrollbar-thin1">
                                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Barangay
                                            </h6>
                                            <ul class="space-y-2 text-sm">
                                                <li class="flex items-center">
                                                    <input id="select-all-barangays" type="checkbox"
                                                        wire:model.live="selectAllBarangays"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="select-all-barangays"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Select
                                                        All</label>
                                                </li>
                                                @foreach ($barangays as $barangay)
                                                    <li class="flex items-center">
                                                        <input id="barangay-{{ $barangay->barangay_description }}"
                                                            type="checkbox" wire:model.live="selectedBarangays"
                                                            value="{{ $barangay->barangay_description }}"
                                                            class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                        <label for="barangay-{{ $barangay->barangay_description }}"
                                                            class="ml-2 text-gray-900 dark:text-gray-300">{{ $barangay->barangay_description }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="h-3 w-full"></div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Civil Status Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownCivilStatus"
                                        class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600
                                                {{ $dropdownForCivilStatusOpen ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                                        type="button">
                                        Group by Civil Status
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if ($dropdownForCivilStatusOpen)
                                    <div class="w-full absolute z-20">
                                        <div
                                            class="w-full p-3 rounded-lg border border-gray-400
                                                shadow-md bg-gray-100 dark:bg-gray-800 max-h-60 overflow-y-auto scrollbar-thin1">
                                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                                                Civil Status
                                            </h6>
                                            <ul class="space-y-2 text-sm">
                                                <li class="flex items-center">
                                                    <input id="single" type="checkbox"
                                                        wire:model.live="selectedCivilStatuses" value="Single"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="single"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Single</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="married" type="checkbox"
                                                        wire:model.live="selectedCivilStatuses" value="Married"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="married"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Married</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="widowed" type="checkbox"
                                                        wire:model.live="selectedCivilStatuses" value="Widowed"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="widowed"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Widowed</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="separated" type="checkbox"
                                                        wire:model.live="selectedCivilStatuses" value="Separated"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="separated"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Separated</label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="h-3 w-full"></div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Sex Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownSex"
                                        class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600
                                                {{ $dropdownForSexOpen ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                                        type="button">
                                        Group by Sex
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if ($dropdownForSexOpen)
                                    <div class="w-full absolute z-20">
                                        <div
                                            class="w-full p-3 rounded-lg border border-gray-400
                                                    shadow-md bg-gray-100 dark:bg-gray-800 max-h-60 overflow-y-auto scrollbar-thin1">
                                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Sex
                                            </h6>
                                            <ul class="space-y-2 text-sm">
                                                <li class="flex items-center">
                                                    <input id="default" type="radio" wire:model.live="sex"
                                                        value=""
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-blue-500 focus:text-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="default"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">All</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="male" type="radio" wire:model.live="sex"
                                                        value="Male"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-blue-500 focus:text-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="male"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Male</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="female" type="radio" wire:model.live="sex"
                                                        value="Female"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-blue-500 focus:text-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="female"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Female</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="others" type="radio" wire:model.live="sex"
                                                        value="others"
                                                        class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-blue-500 focus:text-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                    <label for="others"
                                                        class="ml-2 text-gray-900 dark:text-gray-300">Others</label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="h-3 w-full"></div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Learning and Development Dropdown -->
                                <div class="relative inline-block text-left w-full">
                                    <button wire:click="toggleDropdownLD"
                                        class="w-full mr-4 p-2 mb-4 text-left
                                                text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200
                                                transition-colors duration-200 rounded-lg border border-gray-400
                                                hover:bg-gray-200 focus:outline-none dark:hover:bg-slate-600
                                                {{ $dropdownForLDOpen ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                                        type="button">
                                        Group by L&D
                                        <i class="bi bi-chevron-down w-5 h-5 ml-2 float-right"></i>
                                    </button>
                                    @if ($dropdownForLDOpen)
                                        <div class="w-full absolute z-20">
                                            <div
                                                class="w-full p-3 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-400
                                                    shadow-md max-h-60 overflow-y-auto scrollbar-thin1">
                                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                                                    Learning and Development
                                                </h6>
                                                <ul class="space-y-2 text-sm">
                                                    <li class="flex items-center">
                                                        <input id="married" type="checkbox"
                                                            wire:model.live="selectedLD" value="Technical"
                                                            class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                        <label for="married"
                                                            class="ml-2 text-gray-900 dark:text-gray-300">Technical</label>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <input id="widowed" type="checkbox"
                                                            wire:model.live="selectedLD" value="Supervisory"
                                                            class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                        <label for="widowed"
                                                            class="ml-2 text-gray-900 dark:text-gray-300">Supervisory</label>
                                                    </li>
                                                    <li class="flex items-center">
                                                        <input id="separated" type="checkbox"
                                                            wire:model.live="selectedLD" value="Leadership"
                                                            class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 checked:bg-blue-500 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                                                        <label for="separated"
                                                            class="ml-2 text-gray-900 dark:text-gray-300">Leadership</label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="h-3 w-full"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- @endif --}}
                        </div>

                        <!-- Sort Dropdown -->
                        <div x-data="{ open: @entangle('dropdownForCategoryOpen') }" class="w-full sm:w-auto">
                            <button @click="open = !open"
                                class="mt-4 sm:mt-0 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                justify-center px-2 py-1.5 text-sm font-medium tracking-wide
                                text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none w-full sm:w-fit"
                                type="button">
                                Filter Column
                                <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                            </button>

                            {{-- @if ($dropdownForCategoryOpen) --}}
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg
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
                                        <label for="place_of_birth"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Birth
                                            Place</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="sex" type="checkbox" wire:model.live="filters.sex"
                                            class="h-4 w-4">
                                        <label for="sex"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Sex</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="citizenship" type="checkbox" wire:model.live="filters.citizenship"
                                            class="h-4 w-4">
                                        <label for="citizenship"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Citizenship</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="civil_status" type="checkbox"
                                            wire:model.live="filters.civil_status" class="h-4 w-4">
                                        <label for="civil_status" class="ml-2 text-gray-900 dark:text-gray-300">Civil
                                            Status</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="height" type="checkbox" wire:model.live="filters.height"
                                            class="h-4 w-4">
                                        <label for="height"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Height</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="weight" type="checkbox" wire:model.live="filters.weight"
                                            class="h-4 w-4">
                                        <label for="weight"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Weight</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="blood_type" type="checkbox" wire:model.live="filters.blood_type"
                                            class="h-4 w-4">
                                        <label for="blood_type" class="ml-2 text-gray-900 dark:text-gray-300">Blood
                                            Type</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gsis" type="checkbox" wire:model.live="filters.gsis"
                                            class="h-4 w-4">
                                        <label for="gsis" class="ml-2 text-gray-900 dark:text-gray-300">GSIS
                                            ID
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
                                        <label for="philhealth"
                                            class="ml-2 text-gray-900 dark:text-gray-300">PhilHealth
                                            ID
                                            No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="sss" type="checkbox" wire:model.live="filters.sss"
                                            class="h-4 w-4">
                                        <label for="sss" class="ml-2 text-gray-900 dark:text-gray-300">SSS
                                            No.</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="tin" type="checkbox" wire:model.live="filters.tin"
                                            class="h-4 w-4">
                                        <label for="tin" class="ml-2 text-gray-900 dark:text-gray-300">TIN
                                            No.</label>
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
                                        <input id="r_house_street" type="checkbox"
                                            wire:model.live="filters.r_house_street" class="h-4 w-4">
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
                                    <!-- Add new filter for active_status -->
                                    <li class="flex items-center">
                                        <input id="active_status" type="checkbox"
                                            wire:model.live="filters.active_status" class="h-4 w-4">
                                        <label for="active_status"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Active Status</label>
                                    </li>
                                    <!-- Add new filter for appointment -->
                                    <li class="flex items-center">
                                        <input id="appointment" type="checkbox"
                                            wire:model.live="filters.appointment" class="h-4 w-4">
                                        <label for="appointment"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Appointment</label>
                                    </li>
                                    <!-- Add new filter for date_hired -->
                                    <li class="flex items-center">
                                        <input id="date_hired" type="checkbox"
                                            wire:model.live="filters.date_hired" class="h-4 w-4">
                                        <label for="date_hired" class="ml-2 text-gray-900 dark:text-gray-300">Date
                                            Hired</label>
                                    </li>
                                    <!-- Add new filter for years_in_gov_service -->
                                    <li class="flex items-center">
                                        <input id="years_in_gov_service" type="checkbox"
                                            wire:model.live="filters.years_in_gov_service" class="h-4 w-4">
                                        <label for="years_in_gov_service"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Years in Gov Service</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="learning_and_development" type="checkbox"
                                            wire:model.live="filters.learning_and_development" class="h-4 w-4">
                                        <label for="learning_and_development"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Learning and Development</label>
                                    </li>
                                </ul>
                            </div>
                            {{-- @endif --}}
                        </div>

                        <!-- Export to Excel -->
                        <div class="w-full sm:w-auto">
                            <button wire:click="exportUsers"
                                class="mt-4 sm:mt-0 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                    justify-center px-4 py-1.5 text-sm font-medium tracking-wide
                                    text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                    rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                                type="button" title="Export to Excel">
                                <img class="flex dark:hidden" src="/images/export-excel.png" width="22"
                                    alt="" wire:target="exportUsers" wire:loading.remove>
                                <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22"
                                    alt="" wire:target="exportUsers" wire:loading.remove>
                                <div wire:loading wire:target="exportUsers">
                                    <div class="spinner-border small text-primary" role="status"></div>
                                </div>
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
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                    Name
                                                </th>
                                                @if ($filters['date_of_birth'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Birth
                                                        Date</th>
                                                @endif
                                                @if ($filters['place_of_birth'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Birth
                                                        Place</th>
                                                @endif
                                                @if ($filters['sex'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Sex
                                                    </th>
                                                @endif
                                                @if ($filters['citizenship'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Citizenship</th>
                                                @endif
                                                @if ($filters['civil_status'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Civil
                                                        Status</th>
                                                @endif
                                                @if ($filters['height'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Height
                                                    </th>
                                                @endif
                                                @if ($filters['weight'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Weight
                                                    </th>
                                                @endif
                                                @if ($filters['blood_type'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Blood
                                                        Type</th>
                                                @endif
                                                @if ($filters['gsis'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        GSIS
                                                        ID No.</th>
                                                @endif
                                                @if ($filters['pagibig'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        PAGIBIG ID No.</th>
                                                @endif
                                                @if ($filters['philhealth'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        PhilHealth ID No.</th>
                                                @endif
                                                @if ($filters['sss'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        SSS
                                                        No.</th>
                                                @endif
                                                @if ($filters['tin'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        TIN
                                                        No.</th>
                                                @endif
                                                @if ($filters['agency_employee_no'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Agency
                                                        Employee No.</th>
                                                @endif
                                                @if ($filters['permanent_selectedProvince'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Permanent Address (Province)</th>
                                                @endif
                                                @if ($filters['permanent_selectedCity'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Permanent Address (City)</th>
                                                @endif
                                                @if ($filters['permanent_selectedBarangay'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Permanent Address (Barangay)</th>
                                                @endif
                                                @if ($filters['p_house_street'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Permanent Address (Street)</th>
                                                @endif
                                                @if ($filters['permanent_selectedZipcode'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Permanent Address (Zip Code)</th>
                                                @endif
                                                @if ($filters['residential_selectedProvince'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Residential Address (Province)</th>
                                                @endif
                                                @if ($filters['residential_selectedCity'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Residential Address (City)</th>
                                                @endif
                                                @if ($filters['residential_selectedBarangay'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Residential Address (Barangay)</th>
                                                @endif
                                                @if ($filters['r_house_street'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Residential Address (Street)</th>
                                                @endif
                                                @if ($filters['residential_selectedZipcode'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Residential Address (Zip Code)</th>
                                                @endif
                                                @if ($filters['active_status'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Active Status</th>
                                                @endif
                                                @if ($filters['appointment'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Nature of Appointment</th>
                                                @endif
                                                @if ($filters['date_hired'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Date Hired</th>
                                                @endif
                                                @if ($filters['years_in_gov_service'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Years in Gov Service</th>
                                                @endif
                                                @if ($filters['learning_and_development'])
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                        Learning and Development</th>
                                                @endif
                                                <th
                                                    class="px-5 py-3 text-gray-100 text-sm font-medium text-right sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                            @foreach ($users as $user)
                                                <tr class="text-sm whitespace-nowrap">
                                                    <td class="px-4 py-2 text-left">
                                                        {{ $user->name }}
                                                    </td>
                                                    @if ($filters['date_of_birth'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->date_of_birth }}</td>
                                                    @endif
                                                    @if ($filters['place_of_birth'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->place_of_birth }}</td>
                                                    @endif
                                                    @if ($filters['sex'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->sex == 'No' ? 'Prefer Not To Say' : $user->sex }}
                                                        </td>
                                                    @endif
                                                    @if ($filters['citizenship'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->citizenship }}</td>
                                                    @endif
                                                    @if ($filters['civil_status'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->civil_status }}</td>
                                                    @endif
                                                    @if ($filters['height'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->height }}
                                                        </td>
                                                    @endif
                                                    @if ($filters['weight'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->weight }}
                                                        </td>
                                                    @endif
                                                    @if ($filters['blood_type'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->blood_type }}</td>
                                                    @endif
                                                    @if ($filters['gsis'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->gsis }}</td>
                                                    @endif
                                                    @if ($filters['pagibig'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->pagibig }}</td>
                                                    @endif
                                                    @if ($filters['philhealth'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->philhealth }}</td>
                                                    @endif
                                                    @if ($filters['sss'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->sss }}</td>
                                                    @endif
                                                    @if ($filters['tin'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->tin }}</td>
                                                    @endif
                                                    @if ($filters['agency_employee_no'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->agency_employee_no }}</td>
                                                    @endif
                                                    @if ($filters['permanent_selectedProvince'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->permanent_selectedProvince }}</td>
                                                    @endif
                                                    @if ($filters['permanent_selectedCity'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->permanent_selectedCity }}</td>
                                                    @endif
                                                    @if ($filters['permanent_selectedBarangay'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->permanent_selectedBarangay }}</td>
                                                    @endif
                                                    @if ($filters['p_house_street'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->p_house_street }}</td>
                                                    @endif
                                                    @if ($filters['permanent_selectedZipcode'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->permanent_selectedZipcode }}</td>
                                                    @endif
                                                    @if ($filters['residential_selectedProvince'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->residential_selectedProvince }}</td>
                                                    @endif
                                                    @if ($filters['residential_selectedCity'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->residential_selectedCity }}</td>
                                                    @endif
                                                    @if ($filters['residential_selectedBarangay'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->residential_selectedBarangay }}</td>
                                                    @endif
                                                    @if ($filters['r_house_street'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->r_house_street }}</td>
                                                    @endif
                                                    @if ($filters['residential_selectedZipcode'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->residential_selectedZipcode }}</td>
                                                    @endif
                                                    @if ($filters['active_status'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->active_status_label }}</td>
                                                    @endif
                                                    @if ($filters['appointment'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->appointment }}</td>
                                                    @endif
                                                    @if ($filters['date_hired'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->date_hired }}</td>
                                                    @endif
                                                    @if ($filters['years_in_gov_service'])
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->years_in_gov_service ?? 'N/A' }}</td>
                                                    @endif
                                                    @php
                                                        $ld = $learnDev->where('user_id', $user->id)->get();
                                                    @endphp
                                                    @if ($filters['learning_and_development'])
                                                        <td class="px-4 py-2 text-center">
                                                            @if($ld)
                                                                @foreach ($ld as $item)                                                              
                                                                    {{ $item ? ('• ' . $item->type_of_ld) : '' }}
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td
                                                        class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                        <button wire:click="showUser({{ $user->id }})"
                                                            class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                            <i class="fas fa-eye" title="Show Details"></i>
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
