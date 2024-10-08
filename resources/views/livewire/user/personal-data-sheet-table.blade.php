<div class="w-full" x-data="{
    selectedTab: 'C1',
}" x-cloak>

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

    {{-- Main Display --}}
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-full bg-white rounded-2xl p-3 shadow dark:bg-gray-800">

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
                    <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
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
                    <button @click="selectedTab = 'E-Signature'"
                        :class="{ 'font-bold text-gray-100 dark:text-gray-700 bg-gray-400 dark:bg-slate-300 rounded-t-lg': selectedTab === 'E-Signature', 'text-slate-500 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'E-Signature' }"
                        class="h-min px-4 pt-2 pb-4 text-sm whitespace-nowrap">
                        E-Signature
                    </button>
                </div>

                <div x-show="selectedTab === 'C1'" class="relative z-10">
                    {{-- Employee's Data --}}
                    <div
                        class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">
                        I. PERSONAL INFORMATION
                        <i title="Edit"
                            class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-1 cursor-pointer"
                            wire:click="toggleEditPersonalInfo"></i>
                    </div>
                    <div>

                        <div class="custom-d flex w-full">

                            <div class="w-full lg:w-2/4 md:w-full block">
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

                            <div class="w-full lg:w-2/4 md:w-full block">
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

                            <div class="w-full lg:w-2/4 md:w-full block">
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
                                        {{ $userData->citizenship ?: 'N/A' }} <span
                                            class="text-xs opacity-80">{{ $userData->dual_citizenship_type ? '| ' . $userData->dual_citizenship_type : '' }}
                                            {{ $userData->dual_citizenship_country ? '| ' . $userData->dual_citizenship_country : '' }}</span>
                                    </p>
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

                            <div class="w-full lg:w-2/4 md:w-full block">
                                <div class="flex w-full sm:w-auto" style="height: 112px">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 px-1 w-3/6 bg-gray-50 dark:bg-slate-700  py-2.5">
                                        Permanent Address</p>
                                    <p class="custom-p w-full border border-gray-200 dark:border-slate-600 px-1 py-2.5 dark:text-gray-200 text-xs"
                                        style="overflow: hidden;">
                                        @php
                                            $address1 = explode(',', $userData->p_house_street ?: '');
                                        @endphp
                                        @foreach ($address1 as $add)
                                            {{ $add != 'N/A' ? $add . ' ' : ' ' }}
                                        @endforeach
                                        <br>
                                        {{ $userData->permanent_selectedBarangay != 'N/A' ? $userData->permanent_selectedBarangay : '' }}
                                        {{ $userData->permanent_selectedCity != 'N/A' ? $userData->permanent_selectedCity : '' }}
                                        <br>
                                        {{ $userData->permanent_selectedProvince != 'N/A' ? $userData->permanent_selectedProvince : '' }},
                                        Philippines <br>
                                        {{ $userData->permanent_selectedZipcode != 'N/A' ? $userData->permanent_selectedZipcode : '' }}
                                    </p>
                                </div>
                                <div class="flex w-full sm:w-auto" style="height: 112px">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 px-1 w-3/6 bg-gray-50 dark:bg-slate-700  py-2.5">
                                        Residential Address</p>
                                    <p class="w-full border border-gray-200 dark:border-slate-600 px-1 py-2.5 dark:text-gray-200 text-xs"
                                        style="overflow: hidden;">
                                        @php
                                            $address2 = explode(',', $userData->r_house_street ?: '');
                                        @endphp
                                        @foreach ($address2 as $add)
                                            {{ $add != 'N/A' ? $add . ' ' : ' ' }}
                                        @endforeach
                                        <br>
                                        {{ $userData->residential_selectedBarangay != 'N/A' ? $userData->residential_selectedBarangay : '' }}
                                        {{ $userData->residential_selectedCity != 'N/A' ? $userData->residential_selectedCity : '' }}
                                        <br>
                                        {{ $userData->residential_selectedProvince != 'N/A' ? $userData->residential_selectedProvince : '' }},
                                        Philippines <br>
                                        {{ $userData->residential_selectedZipcode != 'N/A' ? $userData->residential_selectedZipcode : '' }}
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

                            <div class="w-full lg:w-2/4 md:w-full block">
                                <div class="flex w-full sm:w-auto">
                                    <p
                                        class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                        Mobile No.</p>
                                    <p
                                        class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                        {{ $userData->mobile_number ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="w-full lg:w-2/4 md:w-full block">
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

                            <div class="w-full lg:w-2/4 md:w-full block">
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

                            <div class="w-full lg:w-2/4 md:w-full block">
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
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right mt-2  mr-2 cursor-pointer {{ $userSpouse ? '' : 'hidden' }}"
                                wire:click="toggleEditSpouse"></i>
                            <i title="Delete"
                                class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-2  mr-2 cursor-pointer {{ $userSpouse ? '' : 'hidden' }}"
                                wire:click="toggleDelete('spouse', '')"></i>
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer {{ $userSpouse ? 'hidden' : '' }}"
                                wire:click="toggleAddSpouse"></i>
                        </div>

                        @if ($userSpouse)
                            <div class="custom-d flex w-full">
                                <div class="w-full lg:w-2/4 md:w-full block">
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

                                <div class="w-full lg:w-2/4 md:w-full block">
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
                                <div class="w-full lg:w-2/4 md:w-full block">
                                    <div class="flex w-full sm:w-auto">
                                        <p
                                            class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                            Date of Birth</p>
                                        <p
                                            class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                            {{ $userSpouse->birth_date ? \Carbon\Carbon::parse($userSpouse->birth_date)->format('F d, Y') : 'N/A' }}
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

                                <div class="w-full lg:w-2/4 md:w-full block">
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
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right mt-2  mr-2 cursor-pointer {{ $userFather ? '' : 'hidden' }}"
                                wire:click="toggleEditFather"></i>
                            <i title="Delete"
                                class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-2  mr-2 cursor-pointer {{ $userFather ? '' : 'hidden' }}"
                                wire:click="toggleDelete('father', '')"></i>
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer {{ $userFather ? 'hidden' : '' }}"
                                wire:click="toggleAddFather"></i>
                        </div>

                        @if ($userFather)
                            <div class="custom-d flex w-full">

                                <div class="w-full lg:w-2/4 md:w-full block">
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

                                <div class="w-full lg:w-2/4 md:w-full block">
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
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right mt-2  mr-2 cursor-pointer {{ $userMother ? '' : 'hidden' }}"
                                wire:click="toggleEditMother"></i>
                            <i title="Delete"
                                class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-2  mr-2 cursor-pointer {{ $userMother ? '' : 'hidden' }}"
                                wire:click="toggleDelete('mother', '')"></i>
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer {{ $userMother ? 'hidden' : '' }}"
                                wire:click="toggleAddMother"></i>
                        </div>

                        @if ($userMother)
                            <div class="custom-d flex w-full">

                                <div class="w-full lg:w-2/4 md:w-full block">
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

                                <div class="w-full lg:w-2/4 md:w-full block">
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
                            @if ($userChildren && $userChildren->isNotEmpty())
                                <i title="Edit"
                                    class="fas fa-edit text-blue-500 hover:text-blue-700 float-right mt-2 mr-2 cursor-pointer"
                                    wire:click="toggleEditChildren"></i>
                            @endif
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer"
                                wire:click="toggleAddChildren"></i>
                        </div>

                        @if ($userChildren)
                            @foreach ($userChildren as $child)
                                <div class="custom-d flex w-full">

                                    <div class="w-full lg:w-2/4 md:w-full block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Fullname</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $child->childs_name ?: 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full lg:w-2/4 md:w-full block">
                                        <div class="flex w-full sm:w-auto">
                                            <p
                                                class="border border-gray-200 dark:border-slate-600 p-1 w-3/6 bg-gray-50 dark:bg-slate-700">
                                                Date of Birth</p>
                                            <p
                                                class="w-full border border-gray-200 dark:border-slate-600 p-1 dark:text-gray-200">
                                                {{ $child->childs_birth_date ? \Carbon\Carbon::parse($child->childs_birth_date)->format('F d, Y') : 'N/A' }}
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('child', {{ $child->id }})"></i>
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
                        <i title="Add"
                            class="fas fa-plus text-green-300 dark:text-green-600 hover:text-green-700 float-right pt-1 cursor-pointer"
                            wire:click="toggleAddEducBackground"></i>
                        @if ($educBackground && $educBackground->isNotEmpty())
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-1 mr-2 cursor-pointer"
                                wire:click="toggleEditEducBackground"></i>
                        @endif
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
                                    <i title="Delete"
                                        class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                        wire:click="toggleDelete('educ', {{ $educ->id }})"></i>
                                </p>
                            </div>
                            <div class="custom-d flex w-full">

                                <div class="w-full lg:w-2/4 md:w-full block">
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
                                            From:
                                            {{ $educ->from ? \Carbon\Carbon::parse($educ->from)->format('m/d/Y') : 'N/A' }}
                                            <br>
                                            To:
                                            {{ $educ->to ? \Carbon\Carbon::parse($educ->to)->format('m/d/Y') : '' }}{{ $educ->toPresent ?: '' }}
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

                                <div class="w-full lg:w-2/4 md:w-full block">
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
                        <i title="Add"
                            class="fas fa-plus text-green-300 dark:text-green-600 hover:text-green-700 float-right pt-1 cursor-pointer"
                            wire:click="toggleAddEligibility"></i>
                        @if ($eligibility && $eligibility->isNotEmpty())
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-1 mr-2 cursor-pointer"
                                wire:click="toggleEditEligibility"></i>
                        @endif
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
                                                {{ $elig->date ? \Carbon\Carbon::parse($elig->date)->format('m/d/Y') : 'N/A' }}
                                            </td>
                                            <td
                                                class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                {{ $elig->place_of_exam ?: 'N/A' }}</td>
                                            <td
                                                class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                {{ $elig->license ?: 'N/A' }}</td>
                                            <td
                                                class="p-1 border-2 border border-gray-200 dark:border-slate-600 text-left">
                                                {{ $elig->date_of_validity ? \Carbon\Carbon::parse($elig->date_of_validity)->format('m/d/Y') : 'N/A' }}
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('elig', {{ $elig->id }})"></i>
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
                        <i title="Add"
                            class="fas fa-plus text-green-300 dark:text-green-600 hover:text-green-700 float-right pt-1 cursor-pointer"
                            wire:click="toggleAddWorkExp"></i>
                        @if ($workExperience && $workExperience->isNotEmpty())
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-1 mr-2 cursor-pointer"
                                wire:click="toggleEditWorkExp"></i>
                        @endif
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
                                                <div class="flex w-full items-center">
                                                    <div class="flex justify-center p-1 w-2/4">
                                                        {{ $exp->start_date ? \Carbon\Carbon::parse($exp->start_date)->format('m/d/Y') : 'N/A' }}
                                                    </div>
                                                    <strong>-</strong>
                                                    <div class="flex justify-center p-1 w-2/4">
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
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('exp', {{ $exp->id }})"></i>
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
                        <i title="Add"
                            class="fas fa-plus text-green-300 dark:text-green-600 hover:text-green-700 float-right pt-1 cursor-pointer"
                            wire:click="toggleAddVoluntaryWorks"></i>
                        @if ($voluntaryWorks && $voluntaryWorks->isNotEmpty())
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-1 mr-2 cursor-pointer"
                                wire:click="toggleEditVoluntaryWorks"></i>
                        @endif
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
                                                <div class="flex w-full items-center">
                                                    <div class="flex justify-center p-1 w-2/4">
                                                        {{ $voluntary->start_date ? \Carbon\Carbon::parse($voluntary->start_date)->format('m/d/Y') : 'N/A' }}
                                                    </div>
                                                    <strong>-</strong>
                                                    <div class="flex justify-center p-1 w-2/4">
                                                        {{ $voluntary->end_date ? \Carbon\Carbon::parse($voluntary->end_date)->format('m/d/Y') : 'Present' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-sm text-left">
                                                {{ $voluntary->no_of_hours ?: 'N/A' }}</td>
                                            <td
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-sm text-left">
                                                {{ $voluntary->position_nature ?: 'N/A' }}
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('voluntary', {{ $voluntary->id }})"></i>
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
                        <i title="Add"
                            class="fas fa-plus text-green-300 dark:text-green-600 hover:text-green-700 float-right pt-1 cursor-pointer"
                            wire:click="toggleAddLearnAndDev"></i>
                        @if ($lds && $lds->isNotEmpty())
                            <i title="Edit"
                                class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-1 mr-2 cursor-pointer"
                                wire:click="toggleEditLearnAndDev"></i>
                        @endif
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
                                                {{ $ld->title }}</td>
                                            <td
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left w-1/5">
                                                <div class="flex w-full items-center">
                                                    <div class="flex justify-center p-1 w-2/4">
                                                        {{ $ld->start_date ? \Carbon\Carbon::parse($ld->start_date)->format('m/d/Y') : 'N/A' }}
                                                    </div>
                                                    <strong>-</strong>
                                                    <div class="flex justify-center p-1 w-2/4">
                                                        {{ $ld->end_date ? \Carbon\Carbon::parse($ld->end_date)->format('m/d/Y') : 'Present' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                {{ $ld->no_of_hours ?: 'N/A' }}</td>
                                            <td
                                                class="p-1 border-2 border-gray-200 dark:border-slate-600 dark:text-gray-200 text-left">
                                                {{ $ld->type_of_ld ?: 'N/A' }}</td>
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
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1 mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('ld', {{ $ld->id }})"></i>
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
                            @if ($skills && $skills->isNotEmpty())
                                <i title="Edit"
                                    class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-2 pr-1.5 cursor-pointer"
                                    wire:click="toggleEditSkills"></i>
                            @endif
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer"
                                wire:click="toggleAddSkills"></i>
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
                            @if ($hobbies && $hobbies->isNotEmpty())
                                <i title="Edit"
                                    class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-2 pr-1.5 cursor-pointer"
                                    wire:click="toggleEditHobbies"></i>
                            @endif
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer"
                                wire:click="toggleAddHobbies"></i>
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
                            @if ($non_acads_distinctions && $non_acads_distinctions->isNotEmpty())
                                <i title="Edit"
                                    class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-2 pr-1.5 cursor-pointer"
                                    wire:click="toggleEditNonAcads"></i>
                            @endif
                            <i title="Add"
                                class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer"
                                wire:click="toggleAddNonAcads"></i>
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
                                                {{ $non_acads_distinction->award }}</td>
                                            <td
                                                class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                {{ $non_acads_distinction->ass_org_name }}</td>
                                            <td
                                                class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                {{ $non_acads_distinction->date_received ? \Carbon\Carbon::parse($non_acads_distinction->date_received)->format('m/d/Y') : '' }}
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('nonacad', {{ $non_acads_distinction->id }})"></i>
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
                            @if ($assOrgMemberships && $assOrgMemberships->isNotEmpty())
                                <i title="Edit"
                                    class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-2 pr-1.5 cursor-pointer"
                                    wire:click="toggleEditMemberships"></i>
                            @endif
                            <i class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer"
                                wire:click="toggleAddMemberships" title="Add"></i>
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
                                                {{ $assOrgMembership->ass_org_name }}</td>
                                            <td
                                                class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                {{ $assOrgMembership->position }}
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('membership', {{ $assOrgMembership->id }})"></i>
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
                                        Are you related by consanguinity or affinity to the appointing or recommending
                                        authority, or to the <br>
                                        chief of bureau or office or to the person who has immediate supervision over
                                        you in the Office, <br>
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
                                        <input id="yes" type="radio" value="1" wire:model='q34aAnswer'
                                            name="answer34a"
                                            style="pointer-events: {{ $editAnswer['q34a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model='q34aAnswer' name="answer34a"
                                            style="pointer-events: {{ $editAnswer['q34a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <i title="{{ $editAnswer['q34a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q34a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q34a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q34a']) wire:click="saveC4Question('34', 'a', 'q34aAnswer')"
                                        @else
                                            wire:click="editC4Question('q34a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q34a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q34a')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q34b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q34bAnswer' name="answer34b"
                                            style="pointer-events: {{ $editAnswer['q34b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q34b'] && $q34bAnswer)
                                                <input type="text" value="{{ $q34bDetails }}"
                                                    wire:model='q34bDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q34bDetails)
                                                {{ $q34bDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q34b'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q34b'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q34b'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q34b']) wire:click="saveC4Question('34', 'b', 'q34bAnswer', 'q34bDetails')"
                                        @else
                                            wire:click="editC4Question('q34b')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q34b'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q34b')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q35a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q35aAnswer' name="answer35a"
                                            style="pointer-events: {{ $editAnswer['q35a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q35a'] && $q35aAnswer)
                                                <input type="text" value="{{ $q35aDetails }}"
                                                    wire:model='q35aDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q35aDetails)
                                                {{ $q35aDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q35a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q35a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q35a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q35a']) wire:click="saveC4Question('35', 'a', 'q35aAnswer', 'q35aDetails')"
                                        @else
                                            wire:click="editC4Question('q35a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q35a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q35a')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q35b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q35bAnswer' name="answer35b"
                                            style="pointer-events: {{ $editAnswer['q35b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="flex w-full  mt-2">
                                            <p class="w-2/5 text-right text-gray-400">Date Filed:</p>
                                            <div class="w-3/5 border-b border-black dark:border-white ml-2 mb-2">
                                                @if ($editAnswer['q35b'] && $q35bAnswer)
                                                    <input type="date" value="{{ $q35bDate_filed }}"
                                                        wire:model='q35bDate_filed'
                                                        class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                                @elseif($q35bDate_filed)
                                                    {{ $q35bDate_filed ?: '' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex w-full">
                                            <p class="w-2/5 text-right text-gray-400">Status of Case/s:</p>
                                            <div class="w-3/5 border-b border-black dark:border-white ml-2 mb-2">
                                                @if ($editAnswer['q35b'] && $q35bAnswer)
                                                    <input type="text" value="{{ $q35bStatus }}"
                                                        wire:model='q35bStatus'
                                                        class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                                @elseif($q35bStatus)
                                                    {{ $q35bStatus }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q35b'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q35b'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q35b'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q35b']) wire:click="saveC4Question('35', 'b', 'q35bAnswer')"
                                        @else
                                            wire:click="editC4Question('q35b')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q35b'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q35b')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q36a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q36aAnswer' name="answer36a"
                                            style="pointer-events: {{ $editAnswer['q36a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q36a'] && $q36aAnswer)
                                                <input type="text" value="{{ $q36aDetails }}"
                                                    wire:model='q36aDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q36aDetails)
                                                {{ $q36aDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q36a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q36a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q36a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q36a']) wire:click="saveC4Question('36', 'a', 'q36aAnswer', 'q36aDetails')"
                                        @else
                                            wire:click="editC4Question('q36a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q36a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q36a')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q37a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q37aAnswer' name="answer37a"
                                            style="pointer-events: {{ $editAnswer['q37a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q37a'] && $q37aAnswer)
                                                <input type="text" value="{{ $q37aDetails }}"
                                                    wire:model='q37aDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q37aDetails)
                                                {{ $q37aDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q37a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q37a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q37a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q37a']) wire:click="saveC4Question('37', 'a', 'q37aAnswer', 'q37aDetails')"
                                        @else
                                            wire:click="editC4Question('q37a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q37a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q37a')" style="top: 10px;"></i>
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
                                        a. Have you ever been a candidate in a national or local election held within
                                        the last year (except Barangay election)?
                                    </p>
                                </div>
                                <div
                                    class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                    <div class="flex items-center">
                                        <input id="yes" type="radio" value="1"
                                            wire:model.live='q38aAnswer' name="answer38a"
                                            style="pointer-events: {{ $editAnswer['q38a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q38aAnswer' name="answer38a"
                                            style="pointer-events: {{ $editAnswer['q38a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q38a'] && $q38aAnswer)
                                                <input type="text" value="{{ $q38aDetails }}"
                                                    wire:model='q38aDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q38aDetails)
                                                {{ $q38aDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q38a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q38a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q38a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q38a']) wire:click="saveC4Question('38', 'a', 'q38aAnswer', 'q38aDetails')"
                                        @else
                                            wire:click="editC4Question('q38a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q38a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q38a')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q38b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q38bAnswer' name="answer38b"
                                            style="pointer-events: {{ $editAnswer['q38b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q38b'] && $q38bAnswer)
                                                <input type="text" value="{{ $q38bDetails }}"
                                                    wire:model='q38bDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q38bDetails)
                                                {{ $q38bDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q38b'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q38b'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q38b'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q38b']) wire:click="saveC4Question('38', 'b', 'q38bAnswer', 'q38bDetails')"
                                        @else
                                            wire:click="editC4Question('q38b')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q38b'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q38b')" style="top: 10px;"></i>
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
                                        Have you acquired the status of an immigrant or permanent resident of another
                                        country?
                                    </p>
                                </div>
                                <div
                                    class="w-full sm:w-2/6 flex flex-col justify-end p-2 items-start px-4 bg-white dark:bg-slate-700 relative">
                                    <div class="flex items-center">
                                        <input id="yes" type="radio" value="1"
                                            wire:model.live='q39aAnswer' name="answer39a"
                                            style="pointer-events: {{ $editAnswer['q39a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q39aAnswer' name="answer39a"
                                            style="pointer-events: {{ $editAnswer['q39a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, give details (country):</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q39a'] && $q39aAnswer)
                                                <input type="text" value="{{ $q39aDetails }}"
                                                    wire:model='q39aDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q39aDetails)
                                                {{ $q39aDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q39a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q39a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q39a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q39a']) wire:click="saveC4Question('39', 'a', 'q39aAnswer', 'q39aDetails')"
                                        @else
                                            wire:click="editC4Question('q39a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q39a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q39a')" style="top: 10px;"></i>
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
                                        Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled
                                        Persons (RA 7277); and (c)
                                        Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:
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
                                            wire:model.live='q40aAnswer' name="answer40a"
                                            style="pointer-events: {{ $editAnswer['q40a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q40aAnswer' name="answer40a"
                                            style="pointer-events: {{ $editAnswer['q40a'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, please specify:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q40a'] && $q40aAnswer)
                                                <input type="text" value="{{ $q40aDetails }}"
                                                    wire:model='q40aDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q40aDetails)
                                                {{ $q40aDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q40a'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q40a'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q40a'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q40a']) wire:click="saveC4Question('40', 'a', 'q40aAnswer', 'q40aDetails')"
                                        @else
                                            wire:click="editC4Question('q40a')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q40a'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q40a')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q40b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q40bAnswer' name="answer40b"
                                            style="pointer-events: {{ $editAnswer['q40b'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, please specify ID No:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q40b'] && $q40bAnswer)
                                                <input type="text" value="{{ $q40bDetails }}"
                                                    wire:model='q40bDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q40bDetails)
                                                {{ $q40bDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q40b'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q40b'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q40b'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q40b']) wire:click="saveC4Question('40', 'b', 'q40bAnswer', 'q40bDetails')"
                                        @else
                                            wire:click="editC4Question('q40b')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q40b'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q40b')" style="top: 10px;"></i>
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
                                            style="pointer-events: {{ $editAnswer['q40c'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">Yes</label>
                                        <input id="yes" class="ml-10" value="0" type="radio"
                                            wire:model.live='q40cAnswer' name="answer40c"
                                            style="pointer-events: {{ $editAnswer['q40c'] ? 'all' : 'none' }}">
                                        <label for="yes" class="ml-2">No</label>
                                    </div>
                                    <div
                                        class="w-full block items-center text-gray-800 dark:text-gray-100 text-xs mt-2">
                                        <p class="text-gray-400">If YES, please specify ID No:</p>
                                        <div class="w-full border-b border-black dark:border-white mt-2 mb-2">
                                            @if ($editAnswer['q40c'] && $q40cAnswer)
                                                <input type="text" value="{{ $q40cDetails }}"
                                                    wire:model='q40cDetails'
                                                    class="text-sm bg-gray-100 text-gray-800 w-full" autofocus>
                                            @elseif($q40cDetails)
                                                {{ $q40cDetails }}
                                            @endif
                                        </div>
                                    </div>
                                    <i title="{{ $editAnswer['q40c'] ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editAnswer['q40c'] ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editAnswer['q40c'] ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editAnswer['q40c']) wire:click="saveC4Question('40', 'c', 'q40cAnswer', 'q40cDetails')"
                                        @else
                                            wire:click="editC4Question('q40c')" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editAnswer['q40c'] ? '' : 'hidden' }}"
                                        wire:click="cancelEditC4Question('q40c')" style="top: 10px;"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Character References --}}
                        <div
                            class="flex w-full sm:w-auto border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <p class="p-1 w-full font-bold">CHARACTER REFERENCES</p>
                            @if ($references && $references->isNotEmpty())
                                <i title="Edit"
                                    class="fas fa-edit text-blue-500 hover:text-blue-700 float-right pt-2 pr-1.5 cursor-pointer"
                                    wire:click="toggleEditReferences"></i>
                            @endif
                            <i class="fas fa-plus text-green-500 hover:text-green-700 float-right mt-2  mr-2 cursor-pointer"
                                wire:click="toggleAddReferences" title="Add"></i>
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
                                                {{ $reference->address }}</td>
                                            <td
                                                class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                {{ $reference->tel_number }}</td>
                                            <td
                                                class="p-1 border-r-2 border-l-2 border-t-2 border-gray-200 dark:border-slate-600 text-left">
                                                {{ $reference->mobile_number }}
                                                <i title="Delete"
                                                    class="fas fa-trash text-red-500 hover:text-red-700 float-right mt-1  mr-1 cursor-pointer"
                                                    wire:click="toggleDelete('refs', {{ $reference->id }})"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        {{-- ID's and Photo --}}
                        <div class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600">
                            <div class="w-full block sm:flex">
                                <div
                                    class="w-full bg-gray-100 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 flex flex-col justify-end relative">
                                    <div
                                        class="w-full border-b-2 border-gray-200 dark:border-slate-600 p-2 bg-gray-100 dark:bg-slate-700">
                                        <p class="w-full">Government Issued ID (i.e.Passport, GSIS, SSS, PRC, Driver's
                                            License, etc.) </p>
                                        <p class="w-full text-right">PLEASE INDICATE ID Number</p>
                                    </div>
                                    <div class="flex w-full border-b-2 border-gray-200 dark:border-slate-600 px-2 bg-gray-50 dark:bg-gray-800 items-center"
                                        style="height: 50px">
                                        <p class="w-2/3">Government Issued ID:</p>
                                        @if ($editGovId)
                                            <input type="text" value="{{ $govId }}" wire:model='govId'
                                                class="w-1/3 text-sm bg-gray-100 text-gray-800 w-full" autofocus
                                                style="height: 35px">
                                        @elseif($govId)
                                            <p class="w-2/3 text-gray-800 dark:text-gray-100 text-right">
                                                {{ $govId }}</p>
                                        @endif
                                    </div>
                                    <div class="flex w-full border-b-2 border-gray-200 dark:border-slate-600 px-2 bg-gray-50 dark:bg-gray-800 items-center"
                                        style="height: 50px">
                                        <p class="w-2/3">ID/License/Passport No.:</p>
                                        @if ($editGovId)
                                            <input type="text" value="{{ $idNumber }}" wire:model='idNumber'
                                                class="w-1/3 text-sm bg-gray-100 text-gray-800 w-full" autofocus
                                                style="height: 35px">
                                        @elseif($idNumber)
                                            <p class="w-2/3 text-gray-800 dark:text-gray-100 text-right">
                                                {{ $idNumber }}</p>
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
                                                {{ $dateIssued }}</p>
                                        @endif
                                    </div>
                                    <i title="{{ $editGovId ? 'Save' : 'Edit' }}"
                                        class="absolute top-2 mr-2 float-right {{ $editGovId ? 'right-8' : 'right-2' }} cursor-pointer text-sm
                                        {{ $editGovId ? 'bi-check2-square text-green-500 hover:text-green-700' : 'fas fa-edit text-blue-500 hover:text-blue-700' }}"
                                        @if ($editGovId) wire:click="saveGovId"
                                        @else
                                            wire:click="toggleEditGovId" @endif>
                                    </i>
                                    <i title="Cancel"
                                        class="absolute mr-2 right-2 float-right cursor-pointer bi-x-square text-red-500 hover:text-red-700 text-xs
                                        {{ $editGovId ? '' : 'hidden' }}"
                                        wire:click="toggleEditGovId" style="top: 10px;"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div x-show="selectedTab === 'E-Signature'" class="relative z-10">
                    <div class="m-scrollable">
                        <div
                            class="bg-gray-400 dark:bg-slate-300 p-2 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">
                        </div>

                        <div
                            class="flex flex-col w-full border-2 border-gray-200 dark:border-slate-600 bg-gray-100 dark:bg-slate-700">
                            <div class="w-full block sm:flex">
                                <div
                                    class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-lg">
                                    <h3>Upload your E-Signature</h3>
                                </div>
                                <div
                                    class="w-full sm:w-2/6 flex flex-col justify-end items-start px-4 bg-white dark:bg-slate-700 relative">

                                    <div class="mt-4 flex items-center">
                                        <p class="text-md">E-Signature</p>
                                    </div>

                                    @if ($temporaryUrl)
                                        <!-- Show the text prompt when a file is selected -->
                                        <div class="mt-2 mb-2 border rounded-md w-40 h-40">
                                            <span class="text-blue-600 font-semibold">Click "Upload" to save the
                                                signature</span>
                                        </div>
                                    @elseif ($eSignature && $eSignature->file_path)
                                        <!-- Show the stored signature if it's already uploaded -->
                                        <div class="mt-2 mb-2 border rounded-md">
                                            <img src="{{ route('signature.file', basename($eSignature->file_path)) }}"
                                                alt="E-Signature" class="rounded-md w-40 h-40" />
                                        </div>
                                    @else
                                        <!-- Show a placeholder if no signature is uploaded yet -->
                                        <div class="mt-2 mb-2 border rounded-md w-40 h-40">
                                            <span class="text-gray-500">No signature uploaded</span>
                                        </div>
                                    @endif

                                    <div class="mt-2">
                                        <form wire:submit.prevent="uploadSignature">
                                            <input type="file" id="e_signature" wire:model="e_signature"
                                                accept="image/*" class="hidden" required>

                                            <label for="e_signature"
                                                class="cursor-pointer px-2 py-3 rounded-md bg-slate-200 dark:bg-slate-800 border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 text-slate-600 dark:text-slate-300 mt-2 me-1">
                                                <span wire:loading wire:target="e_signature">Loading...</span>
                                                <span wire:loading.remove wire:target="e_signature">Select an
                                                    Image</span>
                                            </label>

                                            @error('e_signature')
                                                <span class="error">{{ $message }}</span>
                                            @enderror

                                            <button type="submit"
                                                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">Upload
                                                Photo</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full block sm:flex">
                                <div
                                    class="w-full sm:w-4/6 flex items-start p-2 text-gray-800 dark:text-gray-100 bg-slate-100 dark:bg-slate-900 text-xs">
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

    {{-- Personal Info Edit Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="personalInfo">
        <div class="p-4">
            <div
                class="bg-slate-800 rounded-t-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Edit Personal Information
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='savePersonalInfo'>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="surname"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Surname</label>
                        <input type="text" id="surname" wire:model='surname'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('surname')
                            <span class="text-red-500 text-sm">The surname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Firstname</label>
                        <input type="text" id="first_name" wire:model='first_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('first_name')
                            <span class="text-red-500 text-sm">The firstname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="middle_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Middlename</label>
                        <input type="text" id="middle_name" wire:model='middle_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('middle_name')
                            <span class="text-red-500 text-sm">The middlename is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="name_extension"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name Extension</label>
                        <input type="text" id="name_extension" wire:model='name_extension'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('name_extension')
                            <span class="text-red-500 text-sm">The name extension is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="date_of_birth"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of Birth</label>
                        <input type="date" id="date_of_birth" wire:model='date_of_birth'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('date_of_birth')
                            <span class="text-red-500 text-sm">The date of birth is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="place_of_birth"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Place of Birth</label>
                        <input type="text" id="place_of_birth" wire:model='place_of_birth'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('place_of_birth')
                            <span class="text-red-500 text-sm">The place of birth is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="sex"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Sex at Birth</label>
                        <input type="text" id="sex" wire:model='sex'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('sex')
                            <span class="text-red-500 text-sm">The sex is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="civil_status"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Civil Status</label>
                        <select wire:model='civil_status'
                            class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
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
                        <!-- Citizenship Radio Buttons -->
                        <div class="w-full">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Citizenship</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="citizenship" value="Filipino"
                                        wire:model.live="citizenship"
                                        class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-2">Filipino</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" name="citizenship" value="Dual Citizenship"
                                        wire:model.live="citizenship"
                                        class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-2">Dual Citizenship</span>
                                </label>
                            </div>
                            @error('citizenship')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dual Citizenship Additional Fields -->
                        @if ($citizenship === 'Dual Citizenship')
                            <!-- Dual Citizenship Type Radio Buttons -->
                            <div class="w-full mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-400">Dual
                                    Citizenship Type</label>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="dual_citizenship_type" value="By Birth"
                                            wire:model="dual_citizenship_type"
                                            class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <span class="ml-2">By Birth</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" name="dual_citizenship_type"
                                            value="By Naturalization" wire:model="dual_citizenship_type"
                                            class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <span class="ml-2">By Naturalization</span>
                                    </label>
                                </div>
                                @error('dual_citizenship_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Country Select Field -->
                            <div class="w-full mt-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Country</label>
                                <select wire:model="dual_citizenship_country"
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->name }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('dual_citizenship_country')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="height"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Height</label>
                        <input type="number" step="0.01" id="height" wire:model='height'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('height')
                            <span class="text-red-500 text-sm">The height is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="weight"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Weight</label>
                        <input type="number" step="0.01" id="weight" wire:model='weight'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('weight')
                            <span class="text-red-500 text-sm">The weight is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="blood_type"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Bloodtype</label>
                        <input type="text" id="blood_type" wire:model='blood_type'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('blood_type')
                            <span class="text-red-500 text-sm">The bloodtype is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="tel_number"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Tel Number</label>
                        <input type="text" id="tel_number" wire:model='tel_number'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('tel_number')
                            <span class="text-red-500 text-sm">The telephone number is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="mobile_number"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Mobile Number</label>
                        <input type="text" id="mobile_number" wire:model='mobile_number'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('mobile_number')
                            <span class="text-red-500 text-sm">The mobile number is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Email</label>
                        <input type="email" id="email" wire:model='email'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('email')
                            <span class="text-red-500 text-sm">The email is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="gsis"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">GSIS ID No.</label>
                        <input type="text" id="gsis" wire:model='gsis'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('gsis')
                            <span class="text-red-500 text-sm">The GSIS ID No. is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">SSS ID No.</label>
                        <input type="text" id="first_name" wire:model='sss'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('sss')
                            <span class="text-red-500 text-sm">The SSS ID No. is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Pag-Ibig ID
                            No.</label>
                        <input type="text" id="first_name" wire:model='pagibig'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('pagibig')
                            <span class="text-red-500 text-sm">The Pag-IBIG ID No. is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">PhilHealth ID
                            No.</label>
                        <input type="text" id="first_name" wire:model='philhealth'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('philhealth')
                            <span class="text-red-500 text-sm">The Philhealth No. is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">TIN ID No.</label>
                        <input type="text" id="tin" wire:model='tin'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('tin')
                            <span class="text-red-500 text-sm">The TIN ID No. is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-2">
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Agency Employee
                            No.</label>
                        <input type="text" id="agency_employee_no" wire:model='agency_employee_no'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('agency_employee_no')
                            <span class="text-red-500 text-sm">The Agency Employee No. is required!</span>
                        @enderror
                    </div>

                    <fieldset
                        class="col-span-2 sm:col-span-2 border border-gray-300 p-4 rounded-lg overflow-hidden w-full">
                        <legend class="px-2"> Permanent Address </legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_province"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Province</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                    wire:model='p_province' id="p_province" name="p_province" required>
                                    @if ($pprovinces)
                                        <option value="{{ $p_province }}" style="opacity: .6;">
                                            {{ $p_province }}</option>
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
                                <label for="p_city"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">City</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                    wire:model='p_city' id="p_city" name="p_city" required>
                                    @if ($pcities)
                                        <option value="{{ $p_city }}" style="opacity: .6;">
                                            {{ $p_city }}</option>
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
                                <label for="p_barangay"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Barangay</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                    wire:model='p_barangay' id="p_barangay" name="p_barangay" required>
                                    @if ($pbarangays)
                                        <option value="{{ $p_barangay }}" style="opacity: .6;">
                                            {{ $p_barangay }}</option>
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
                                <label for="p_zipcode"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Zip
                                    Code</label>
                                <input type="number" id="p_zipcode" wire:model='p_zipcode'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('p_zipcode')
                                    <span class="text-red-500 text-sm">The Zip Code is required!</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_house_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">House/Block/Lot
                                    No.</label>
                                <input type="text" id="p_house_number" wire:model='p_house_number'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('p_house_number')
                                    <span class="text-red-500 text-sm">The House number is required!</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="p_street"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Street</label>
                                <input type="text" id="p_street" wire:model='p_street'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('p_street')
                                    <span class="text-red-500 text-sm">The Street is required!</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-2">
                                <label for="p_subdivision"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Subdivision/Village</label>
                                <input type="text" id="p_subdivision" wire:model='p_subdivision'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('p_subdivision')
                                    <span class="text-red-500 text-sm">The Subdivision/Village is required!</span>
                                @enderror
                            </div>

                        </div>
                    </fieldset>

                    <fieldset
                        class="col-span-2 sm:col-span-2 border border-slate-400 p-4 rounded-lg overflow-hidden w-full">
                        <legend class="px-2"> Residential Address </legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="surname"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Province</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                    wire:model.live="r_province" id="r_province" name="r_province" required>
                                    @if ($pprovinces)
                                        <option value="{{ $r_province }}" style="opacity: .6;">
                                            {{ $r_province }}</option>
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
                                <label for="surname"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">City</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                    wire:model.live="r_city" id="r_city" name="r_city" required>
                                    @if ($rcities)
                                        <option value="{{ $r_city }}" style="opacity: .6;">
                                            {{ $r_city }}</option>
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
                                <label for="surname"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Barangay</label>
                                <select
                                    class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                    wire:model.live="r_barangay" id="r_barangay" name="r_barangay" required>
                                    @if ($rbarangays)
                                        <option value="{{ $r_barangay }}" style="opacity: .6;">
                                            {{ $r_barangay }}</option>
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
                                <label for="first_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Zip
                                    Code</label>
                                <input type="number" id="first_name" wire:model='r_zipcode'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('r_zipcode')
                                    <span class="text-red-500 text-sm">The Zip Code is required!</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="r_house_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">House/Block/Lot
                                    No.</label>
                                <input type="text" id="r_house_number" wire:model='r_house_number'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('r_house_number')
                                    <span class="text-red-500 text-sm">The House number is required!</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="r_street"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Street</label>
                                <input type="text" id="r_street" wire:model='r_street'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('r_street')
                                    <span class="text-red-500 text-sm">The Street is required!</span>
                                @enderror
                            </div>

                            <div class="col-span-2 sm:col-span-2">
                                <label for="r_subdivision"
                                    class="block text-sm font-medium text-gray-700 dark:text-slate-400">Subdivision/Village</label>
                                <input type="text" id="r_subdivision" wire:model='r_subdivision'
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                @error('r_subdivision')
                                    <span class="text-red-500 text-sm">The Subdivision/Village is required!</span>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 sm:col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="savePersonalInfo" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Spouse's Info Add and Edit Modal --}}
    <x-modal id="spouseModal" maxWidth="2xl" wire:model="editSpouse">
        <div class="p-4">
            <div
                class="bg-slate-800 rounded-t-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addSpouse ? 'Add' : 'Edit' }} Spouse's Information
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveSpouse'>
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_surname"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Surname</label>
                        <input type="text" id="spouse_surname" wire:model='spouse_surname'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_surname')
                            <span class="text-red-500 text-sm">The surname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Firstname</label>
                        <input type="text" id="spouse_first_name" wire:model='spouse_first_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_first_name')
                            <span class="text-red-500 text-sm">The firstname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_middle_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Middlename</label>
                        <input type="text" id="spouse_middle_name" wire:model='spouse_middle_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_middle_name')
                            <span class="text-red-500 text-sm">The middlename is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_name_extension"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name Extension</label>
                        <input type="text" id="spouse_name_extension" wire:model='spouse_name_extension'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_name_extension')
                            <span class="text-red-500 text-sm">The name extension is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_date_of_birth"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of Birth</label>
                        <input type="date" id="spouse_date_of_birth" wire:model='spouse_date_of_birth'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_date_of_birth')
                            <span class="text-red-500 text-sm">The date is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_occupation"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Occupation</label>
                        <input type="text" id="spouse_occupation" wire:model='spouse_occupation'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_occupation')
                            <span class="text-red-500 text-sm">The occupation is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_employer"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employer</label>
                        <input type="text" id="spouse_employer" wire:model='spouse_employer'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_employer')
                            <span class="text-red-500 text-sm">The employer is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="spouse_emp_tel_num"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Tel Number</label>
                        <input type="text" id="spouse_emp_tel_num" wire:model='spouse_emp_tel_num'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_emp_tel_num')
                            <span class="text-red-500 text-sm">The telephone number is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-2">
                        <label for="spouse_emp_business_address"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Business
                            Address</label>
                        <input type="text" id="spouse_emp_business_address"
                            wire:model='spouse_emp_business_address'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('spouse_emp_business_address')
                            <span class="text-red-500 text-sm">The business address is required!</span>
                        @enderror
                    </div>



                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 sm:col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveSpouse" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
                            wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Father's Name Add and Edit Modal --}}
    <x-modal id="fatherModal" maxWidth="2xl" wire:model="editFather">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addFather ? 'Add' : 'Edit' }} Father's Name
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveFather'>
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2 sm:col-span-1">
                        <label for="father_surname"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Surname</label>
                        <input type="text" id="father_surname" wire:model='father_surname'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('father_surname')
                            <span class="text-red-500 text-sm">The surname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="father_first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Firstname</label>
                        <input type="text" id="father_first_name" wire:model='father_first_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('father_first_name')
                            <span class="text-red-500 text-sm">The firstname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="father_middle_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Middlename</label>
                        <input type="text" id="father_middle_name" wire:model='father_middle_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('father_middle_name')
                            <span class="text-red-500 text-sm">The middlename is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="father_name_extension"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name Extension</label>
                        <input type="text" id="father_name_extension" wire:model='father_name_extension'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('father_name_extension')
                            <span class="text-red-500 text-sm">The name extension is required!</span>
                        @enderror
                    </div>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 sm:col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveFather" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
                            wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Mother's Maiden Name Add and Edit Modal --}}
    <x-modal id="motherModal" maxWidth="2xl" wire:model="editMother">
        <div class="p-4">
            <div
                class="bg-slate-800 rounded-t-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addMother ? 'Add' : 'Edit' }} Mother's Mainden Name
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveMother'>
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2 sm:col-span-1">
                        <label for="mother_surname"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Surname</label>
                        <input type="text" id="mother_surname" wire:model='mother_surname'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('mother_surname')
                            <span class="text-red-500 text-sm">The surname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="mother_first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Firstname</label>
                        <input type="text" id="mother_first_name" wire:model='mother_first_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('mother_first_name')
                            <span class="text-red-500 text-sm">The firstname is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="mother_middle_name"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Middlename</label>
                        <input type="text" id="mother_middle_name" wire:model='mother_middle_name'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                        @error('mother_middle_name')
                            <span class="text-red-500 text-sm">The middlename is required!</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="mother_name_extension"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name Extension</label>
                        <input type="text" id="mother_name_extension" wire:model='mother_name_extension'
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 sm:col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveMother" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
                            wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Children's Info Add and Edit Modal --}}
    <x-modal id="childrenModal" maxWidth="2xl" wire:model="editChildren">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addChildren ? 'Add' : 'Edit' }} Children's Information
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveChildren'>
                <div class="grid grid-cols-1">

                    @if ($addChildren != true)
                        @foreach ($children as $index => $child)
                            <div
                                class="grid grid-cols-2 gap-4 mb-4  mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="childs_name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Fullname</label>
                                    <input type="text" id="childs_name_{{ $index }}"
                                        wire:model="children.{{ $index }}.childs_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('children.' . $index . '.childs_name')
                                        <span class="text-red-500 text-sm">The fullname is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="childs_birth_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of
                                        Birth</label>
                                    <input type="date" id="childs_birth_date_{{ $index }}"
                                        wire:model="children.{{ $index }}.childs_birth_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('children.' . $index . '.childs_birth_date')
                                        <span class="text-red-500 text-sm">The date of birth is required!</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach ($newChildren as $index => $child)
                            <div class="grid grid-cols-2 gap-4 mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="new_childs_name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Fullname
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewChild({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="new_childs_name_{{ $index }}"
                                        wire:model="newChildren.{{ $index }}.childs_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newChildren.' . $index . '.childs_name')
                                        <span class="text-red-500 text-sm">The fullname is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="new_childs_birth_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of
                                        Birth
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewChild({{ $index }})"></i>
                                    </label>
                                    <input type="date" id="new_childs_birth_date_{{ $index }}"
                                        wire:model="newChildren.{{ $index }}.childs_birth_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newChildren.' . $index . '.childs_birth_date')
                                        <span class="text-red-500 text-sm">The date of birth is required!</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewChild"
                            class="bg-blue-500 hover:bg-blue-700 hover:text-white text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Child
                        </button>
                    @endif

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveChildren" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
                            wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Educational Background Add and Edit Modal --}}
    <x-modal id="educBackgroundModal" maxWidth="2xl" wire:model="editEducBackground">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addEducBackground ? 'Add' : 'Edit' }} Educational Background
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveEducationBackground'>
                <div class="grid grid-cols-1">

                    @if ($addEducBackground != true)
                        @foreach ($education as $index => $educ)
                            <div class="grid grid-cols-2 gap-4 mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">

                                <div class="col-span-2 sm:col-span-2 mt-3 pt-2 border-b">
                                    <label for="education_level_{{ $index }}"
                                        class="block font-bold text-sm text-gray-700 dark:text-slate-300 uppercase">{{ $educ['level'] }}</label>
                                </div>

                                <div class="col-span-2 sm:col-span-2">
                                    <label for="name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name of
                                        School</label>
                                    <input type="text" id="name_{{ $index }}"
                                        wire:model="education.{{ $index }}.name_of_school"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('education.' . $index . '.name_of_school')
                                        <span class="text-red-500 text-sm">The name of school is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="from_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">From</label>
                                    <input type="date" id="from_{{ $index }}"
                                        wire:model="education.{{ $index }}.from"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('education.' . $index . '.from')
                                        <span class="text-red-500 text-sm">The start period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">To</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="education.{{ $index }}.to"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $education[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $education[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="education.{{ $index }}.toPresent"
                                                value="Present" @if ($education[$index]['toPresent']) checked @endif>
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('education.' . $index . '.to' || 'education.' . $index . '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="educ_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Basic
                                        Education/Degree/Course</label>
                                    <input type="text" id="educ_{{ $index }}"
                                        wire:model="education.{{ $index }}.basic_educ_degree_course"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('education.' . $index . '.basic_educ_degree_course')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="award_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Scholarship/Academic
                                        Honors Received</label>
                                    <input type="text" id="award_{{ $index }}"
                                        wire:model="education.{{ $index }}.award"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="earned_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Highest
                                        Level/Units Earned</label>
                                    <input type="text" id="earned_{{ $index }}"
                                        wire:model="education.{{ $index }}.highest_level_unit_earned"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="yearGrad_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Year
                                        Graduated</label>
                                    <input type="number" id="yearGrad_{{ $index }}"
                                        wire:model="education.{{ $index }}.year_graduated"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('education.' . $index . '.year_graduated')
                                        <span class="text-red-500 text-sm">The year graduated is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($newEducation as $index => $educ)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="level_code_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Level
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewEducation({{ $index }})"></i>
                                    </label>
                                    <select id="level_code_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.level_code"
                                        class="mt-1 px-2 pt-2 pb-2.5 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        <option value=""></option>
                                        <option value="1">Elementary</option>
                                        <option value="2">Secondary</option>
                                        <option value="3">Vocational/Trade Course</option>
                                        <option value="4">College</option>
                                        <option value="5">Graduate Studies</option>
                                    </select>
                                    @error('newEducation.' . $index . '.level_code')
                                        <span class="text-red-500 text-sm">The level is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name of
                                        School
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewEducation({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="name_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.name_of_school"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEducation.' . $index . '.name_of_school')
                                        <span class="text-red-500 text-sm">The name of school is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="from_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">From</label>
                                    <input type="date" id="from_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.from"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEducation.' . $index . '.from')
                                        <span class="text-red-500 text-sm">The start period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">To</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="newEducation.{{ $index }}.to"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $newEducation[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $newEducation[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="newEducation.{{ $index }}.toPresent"
                                                value="Present">
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('newEducation.' . $index . '.to' || 'newEducation.' . $index . '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="educ_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Basic
                                        Education/Degree/Course</label>
                                    <input type="text" id="educ_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.basic_educ_degree_course"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEducation.' . $index . '.basic_educ_degree_course')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="award_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Scholarship/Academic
                                        Honors Received</label>
                                    <input type="text" id="award_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.award"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="earned_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Highest
                                        Level/Units Earned</label>
                                    <input type="text" id="earned_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.highest_level_unit_earned"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="yearGrad_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Year
                                        Graduated</label>
                                    <input type="number" id="yearGrad_{{ $index }}"
                                        wire:model="newEducation.{{ $index }}.year_graduated"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEducation.' . $index . '.year_graduated')
                                        <span class="text-red-500 text-sm">The year graduated is required!</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewEducation"
                            class="bg-blue-500 hover:bg-blue-700 text-slate-700 hover:text-white dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Education
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveEducationBackground" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Eligibility Add and Edit Modal --}}
    <x-modal id="eligibilityModal" maxWidth="2xl" wire:model="editEligibility">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addEligibility ? 'Add' : 'Edit' }} Civil Service Eligibility
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveEligibility'>
                <div class="grid grid-cols-1">

                    @if ($addEligibility != true)
                        @foreach ($eligibilities as $index => $elig)
                            <div class="grid grid-cols-2 gap-4 mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="eligibility_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Eligibility</label>
                                    <input type="text" id="eligibility_{{ $index }}"
                                        wire:model="eligibilities.{{ $index }}.eligibility"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('eligibilities.' . $index . '.eligibility')
                                        <span class="text-red-500 text-sm">The eligibility is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="rating_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Rating</label>
                                    <input type="number" step="0.01" id="rating_{{ $index }}"
                                        wire:model="eligibilities.{{ $index }}.rating"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('eligibilities.' . $index . '.rating')
                                        <span class="text-red-500 text-sm">The rating is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of
                                        Examination/Confernment</label>
                                    <input type="date" id="date_{{ $index }}"
                                        wire:model="eligibilities.{{ $index }}.date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('eligibilities.' . $index . '.date')
                                        <span class="text-red-500 text-sm">The date of exam/confernment is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="place_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Place of
                                        Examination/Confernment</label>
                                    <input type="text" id="place_{{ $index }}"
                                        wire:model="eligibilities.{{ $index }}.place_of_exam"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('eligibilities.' . $index . '.place_of_exam')
                                        <span class="text-red-500 text-sm">The place of exam/confernment is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="license_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">License
                                        Number</label>
                                    <input type="text" id="license_{{ $index }}"
                                        wire:model="eligibilities.{{ $index }}.license"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="date_of_validity_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of
                                        Validity</label>
                                    <input type="date" id="date_of_validity_{{ $index }}"
                                        wire:model="eligibilities.{{ $index }}.date_of_validity"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($newEligibilities as $index => $elig)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="eligibility_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Eligibility
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewEligibility({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="eligibility_{{ $index }}"
                                        wire:model="newEligibilities.{{ $index }}.eligibility"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEligibilities.' . $index . '.eligibility')
                                        <span class="text-red-500 text-sm">The eligibility is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="rating_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Rating
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewEligibility({{ $index }})"></i>
                                    </label>
                                    <input type="number" id="rating_{{ $index }}"
                                        wire:model="newEligibilities.{{ $index }}.rating"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEligibilities.' . $index . '.rating')
                                        <span class="text-red-500 text-sm">The rating is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of
                                        Examination/Confernment</label>
                                    <input type="date" id="date_{{ $index }}"
                                        wire:model="newEligibilities.{{ $index }}.date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEligibilities.' . $index . '.date')
                                        <span class="text-red-500 text-sm">The date of exam/confernment is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="place_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Place of
                                        Examination/Confernment</label>
                                    <input type="text" id="place_{{ $index }}"
                                        wire:model="newEligibilities.{{ $index }}.place_of_exam"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newEligibilities.' . $index . '.place_of_exam')
                                        <span class="text-red-500 text-sm">The place of exam/confernment is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="license_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">License
                                        Number</label>
                                    <input type="text" id="license_{{ $index }}"
                                        wire:model="newEligibilities.{{ $index }}.license"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="date_of_validity_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date of
                                        Validity</label>
                                    <input type="date" id="date_of_validity_{{ $index }}"
                                        wire:model="newEligibilities.{{ $index }}.date_of_validity"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>
                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewEligibility"
                            class="bg-blue-500 hover:bg-blue-700 hover:text-white text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Eligibility
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveEligibility" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Work Experience Add and Edit Modal --}}
    <x-modal id="workExpModal" maxWidth="2xl" wire:model="editWorkExp">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addWorkExp ? 'Add' : 'Edit' }} Work Experience
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveWorkExp'>
                <div class="grid grid-cols-1">

                    @if ($addWorkExp != true)
                        @foreach ($workExperiences as $index => $exp)
                            <div class="grid grid-cols-2 gap-4 mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="comp_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Department/Agency/Office/Company</label>
                                    <input type="text" id="comp_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.department"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('workExperiences.' . $index . '.department')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="gov_service_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Gov't
                                        Service</label>
                                    <div
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                        <label class="inline-flex items-center">
                                            <input type="radio" id="gov_service_yes_{{ $index }}"
                                                wire:model.live="workExperiences.{{ $index }}.gov_service"
                                                value="1" class="form-radio text-green-600">
                                            <span class="ml-2">Yes</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" id="gov_service_no_{{ $index }}"
                                                wire:model.live="workExperiences.{{ $index }}.gov_service"
                                                value="0" class="form-radio text-green-600">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                    @error('workExperiences.' . $index . '.gov_service')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('workExperiences.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="workExperiences.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $workExperiences[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $workExperiences[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="workExperiences.{{ $index }}.toPresent"
                                                value="Present" @if ($workExperiences[$index]['toPresent']) checked @endif>
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('workExperiences.' . $index . '.end_date' || 'workExperiences.' . $index .
                                        '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.position"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('workExperiences.' . $index . '.position')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="status_of_appointment_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Status of
                                        Appointment</label>
                                    <input type="text" id="status_of_appointment_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.status_of_appointment"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="monthly_salary_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Monthly
                                        Salary</label>
                                    <input type="number" step="0.01" id="monthly_salary_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.monthly_salary"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="sg_step_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Salary/Job/Pay
                                        Grade & Step (if applicable)</label>
                                    <input type="text" id="sg_step_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.sg_step"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                        placeholder="Format (00-0) / Increment">
                                </div>

                                @if($workExperiences[$index]['gov_service'])
                                    <fieldset class="border border-gray-300 rounded-md pt-2 pl-2 pr-2 pb-4 col-span-2 grid grid-cols-2 gap-4">
                                        <legend class="px-2">For Service Record</legend>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="pera_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Longevity Pay/Allowance</label>
                                            <input type="number" step="0.01" id="pera_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.pera"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="branch_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Branch of Service</label>
                                            <select name="branch" id="branch_{{ $index }}" class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                                wire:model="workExperiences.{{ $index }}.branch">
                                                <option value="">Select Branch of Service</option>
                                                <option value="NGA">NGA</option>
                                                <option value="LGU">LGU</option>
                                                <option value="SUCcs">SUCcs</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="leave_absence_wo_pay_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Leave/Absences W/O Pay</label>
                                            <input type="number" id="leave_absence_wo_pay_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.leave_absence_wo_pay"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="remarks_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Remarks</label>
                                            <input type="text" id="remarks_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.remarks"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        {{-- <div class="col-span-2 sm:col-span-1">
                                            <label for="separation_date_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Separation Date</label>
                                            <input type="date" id="separation_date_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.separation_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="separation_cause_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Separation Cause</label>
                                            <input type="text" id="separation_cause_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.separation_cause"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div> --}}
                                    </fieldset>
                                @endif

                            </div>
                        @endforeach
                    @else
                        @foreach ($newWorkExperiences as $index => $exp)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="comp_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Department/Agency/Office/Company
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewWorkExp({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="comp_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.department"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.department')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="gov_service_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Gov't
                                        Service
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewWorkExp({{ $index }})"></i>
                                    </label>
                                    <div
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                        <label class="inline-flex items-center">
                                            <input type="radio" id="gov_service_yes_{{ $index }}"
                                                wire:model.live="newWorkExperiences.{{ $index }}.gov_service"
                                                value="1" class="form-radio text-green-600">
                                            <span class="ml-2">Yes</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" id="gov_service_no_{{ $index }}"
                                                wire:model.live="newWorkExperiences.{{ $index }}.gov_service"
                                                value="0" class="form-radio text-green-600">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                    @error('newWorkExperiences.' . $index . '.gov_service')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="newWorkExperiences.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $newWorkExperiences[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $newWorkExperiences[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="newWorkExperiences.{{ $index }}.toPresent"
                                                value="Present">
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('newWorkExperiences.' . $index . '.end_date' || 'newWorkExperiences.' .
                                        $index . '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.position"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.position')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="status_of_appointment_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Status of
                                        Appointment</label>
                                    <input type="text" id="status_of_appointment_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.status_of_appointment"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="monthly_salary_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Monthly
                                        Salary</label>
                                    <input type="number" id="monthly_salary_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.monthly_salary"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="sg_step_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Salary/Job/Pay
                                        Grade & Step (if applicable)</label>
                                    <input type="text" id="sg_step_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.sg_step"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                        placeholder="Format (00-0) / Increment">
                                </div>

                                @if($newWorkExperiences[$index]['gov_service'])
                                    <fieldset class="border border-gray-300 rounded-md pt-2 pl-2 pr-2 pb-4 col-span-2 grid grid-cols-2 gap-4">
                                        <legend class="px-2">For Service Record</legend>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="pera_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Longevity Pay/Allowance</label>
                                            <input type="number" step="0.01" id="pera_{{ $index }}"
                                                wire:model="newWorkExperiences.{{ $index }}.pera"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="branch_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Branch of Service</label>
                                            <input type="text" id="branch_{{ $index }}"
                                                wire:model="newWorkExperiences.{{ $index }}.branch"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="leave_absence_wo_pay_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Leave/Absences W/O Pay</label>
                                            <input type="number" id="leave_absence_wo_pay_{{ $index }}"
                                                wire:model="newWorkExperiences.{{ $index }}.leave_absence_wo_pay"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="remarks_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Remarks</label>
                                            <input type="text" id="remarks_{{ $index }}"
                                                wire:model="newWorkExperiences.{{ $index }}.remarks"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        {{-- <div class="col-span-2 sm:col-span-1">
                                            <label for="separation_date_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Separation Date</label>
                                            <input type="date" id="separation_date_{{ $index }}"
                                                wire:model="newWorkExperiences.{{ $index }}.separation_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="separation_cause_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Separation Cause</label>
                                            <input type="text" id="separation_cause_{{ $index }}"
                                                wire:model="newWorkExperiences.{{ $index }}.separation_cause"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div> --}}
                                    </fieldset>
                                @endif

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewWorkExp"
                            class="bg-blue-500 hover:bg-blue-700 hover:text-white text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Work Experience
                        </button>
                    @endif



                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveWorkExp" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Voluntary Work Add and Edit Modal --}}
    <x-modal id="voluntaryWorkModal" maxWidth="2xl" wire:model="editVoluntaryWorks">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addVoluntaryWorks ? 'Add' : 'Edit' }} Voluntary Work
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveVoluntaryWork'>
                <div class="grid grid-cols-1">

                    @if ($addVoluntaryWorks != true)
                        @foreach ($voluntaryWork as $index => $work)
                            <div class="grid grid-cols-2 gap-4 mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name of
                                        Organization</label>
                                    <input type="text" id="name_{{ $index }}"
                                        wire:model="voluntaryWork.{{ $index }}.org_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('voluntaryWork.' . $index . '.org_name')
                                        <span class="text-red-500 text-sm">The name of organization is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="comp_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Place of
                                        Organization</label>
                                    <input type="text" id="comp_{{ $index }}"
                                        wire:model="voluntaryWork.{{ $index }}.org_address"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('voluntaryWork.' . $index . '.org_address')
                                        <span class="text-red-500 text-sm">The place of organization is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="voluntaryWork.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('voluntaryWork.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="voluntaryWork.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $voluntaryWork[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $voluntaryWork[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="voluntaryWork.{{ $index }}.toPresent"
                                                value="Present" @if ($voluntaryWork[$index]['toPresent']) checked @endif>
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('voluntaryWork.' . $index . '.end_date' || 'voluntaryWork.' . $index .
                                        '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="hours_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Number of
                                        Hours</label>
                                    <input type="number" id="hours_{{ $index }}"
                                        wire:model="voluntaryWork.{{ $index }}.no_of_hours"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('voluntaryWork.' . $index . '.no_of_hours')
                                        <span class="text-red-500 text-sm">The number of hours is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position/Nature
                                        of Work</label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="voluntaryWork.{{ $index }}.position_nature"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('voluntaryWork.' . $index . '.position_nature')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($newVoluntaryWorks as $index => $work)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name of
                                        Organization
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewVoluntaryWork({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="name_{{ $index }}"
                                        wire:model="newVoluntaryWorks.{{ $index }}.org_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newVoluntaryWorks.' . $index . '.org_name')
                                        <span class="text-red-500 text-sm">The name of organization is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Place of
                                        Organization
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewVoluntaryWork({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="comp_{{ $index }}"
                                        wire:model="newVoluntaryWorks.{{ $index }}.org_address"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newVoluntaryWorks.' . $index . '.org_address')
                                        <span class="text-red-500 text-sm">The place of organization is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="newVoluntaryWorks.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newVoluntaryWorks.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="newVoluntaryWorks.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $newVoluntaryWorks[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $newVoluntaryWorks[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="newVoluntaryWorks.{{ $index }}.toPresent"
                                                value="Present">
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('newVoluntaryWorks.' . $index . '.end_date' || 'newVoluntaryWorks.' . $index
                                        . '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="hours_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Number of
                                        Hours</label>
                                    <input type="number" id="hours_{{ $index }}"
                                        wire:model="newVoluntaryWorks.{{ $index }}.no_of_hours"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newVoluntaryWorks.' . $index . '.no_of_hours')
                                        <span class="text-red-500 text-sm">The number of hours is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position/Nature
                                        of Work</label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="newVoluntaryWorks.{{ $index }}.position_nature"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newVoluntaryWorks.' . $index . '.position_nature')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewVoluntaryWork"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Voluntary Work
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveVoluntaryWork" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Learning and Development Add and Edit Modal --}}
    <x-modal id="learnDevModal" maxWidth="2xl" wire:model="editLearnDev">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addLearnDev ? 'Add' : 'Edit' }} Learning and Development
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveLearnAndDev'>
                <div class="grid grid-cols-1">

                    @if ($addLearnDev != true)
                        @foreach ($learnAndDevs as $index => $learnAndDev)
                            <div class="grid grid-cols-2 gap-4  mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="title_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Title of
                                        Training</label>
                                    <input type="text" id="title_{{ $index }}"
                                        wire:model="learnAndDevs.{{ $index }}.title"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('learnAndDevs.' . $index . '.title')
                                        <span class="text-red-500 text-sm">The title of training is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="type_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Type of
                                        LD</label>
                                    <select name="ld_type" id="" wire:model="learnAndDevs.{{ $index }}.type_of_ld" 
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        <option value="">Select type of LD</option>
                                        <option value="Supervisory">Supervisory</option>
                                        <option value="Technical">Technical</option>
                                        <option value="Leadership">Leadership</option>
                                    </select>
                                    @error('learnAndDevs.' . $index . '.type_of_ld')
                                        <span class="text-red-500 text-sm">The type of ld is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="learnAndDevs.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('learnAndDevs.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="learnAndDevs.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $learnAndDevs[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $learnAndDevs[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <label for="to_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="learnAndDevs.{{ $index }}.toPresent"
                                                value="Present" @if ($learnAndDevs[$index]['toPresent']) checked @endif>
                                        </div>
                                    </div>
                                    @error('learnAndDevs.' . $index . '.end_date' || 'learnAndDevs.' . $index .
                                        '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="hours_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Number of
                                        Hours</label>
                                    <input type="number" id="hours_{{ $index }}"
                                        wire:model="learnAndDevs.{{ $index }}.no_of_hours"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('learnAndDevs.' . $index . '.no_of_hours')
                                        <span class="text-red-500 text-sm">The number of hours is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="conducted_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Conducted/Sponsored
                                        By</label>
                                    <input type="text" id="conducted_{{ $index }}"
                                        wire:model="learnAndDevs.{{ $index }}.conducted_by"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('learnAndDevs.' . $index . '.conducted_by')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                  <!-- File upload section -->
                                  <div class="flex flex-col items-center justify-center w-full col-span-2 mt-4 md:mt-0">
                                    <label for="dropzone-file{{ $index }}"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Click
                                                    to upload</span></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF</p>
                                        </div>
                                        <input id="dropzone-file{{ $index }}" type="file" wire:model="learnAndDevs.{{ $index }}.certificate"
                                            class="hidden" accept="application/pdf" />
                                    </label>

                                    <!-- Display selected files -->
                                    @if ($learnAndDevs[$index]['certificate'])
                                        <div class="mt-4">
                                            <ul class="list-disc list-inside">
                                                <li class="flex items-center text-md text-gray-700 dark:text-gray-300">
                                                    @if(is_string($learnAndDevs[$index]['certificate']))
                                                        {{ $learnAndDevs[$index]['certificate'] ? basename($learnAndDevs[$index]['certificate']) : '' }} 
                                                    @else
                                                        {{ $learnAndDevs[$index]['certificate']->getClientOriginalName() }}
                                                    @endif
                                                    <button type="button" wire:click="removeFile({{ $index }})"
                                                        class="ml-2 text-red-500">
                                                        &times;
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($newLearnAndDevs as $index => $learnAndDev)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="title_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Title of
                                        Training
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewLearnAndDev({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="title_{{ $index }}"
                                        wire:model="newLearnAndDevs.{{ $index }}.title"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newLearnAndDevs.' . $index . '.title')
                                        <span class="text-red-500 text-sm">The title of training is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="type_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Type of LD
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewLearnAndDev({{ $index }})"></i>
                                    </label>
                                    <select name="ld_type" id="" wire:model="newLearnAndDevs.{{ $index }}.type_of_ld" 
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        <option value="">Select type of LD</option>
                                        <option value="Supervisory">Supervisory</option>
                                        <option value="Technical">Technical</option>
                                        <option value="Leadership">Leadership</option>
                                    </select>
                                    @error('newLearnAndDevs.' . $index . '.type_of_ld')
                                        <span class="text-red-500 text-sm">The type of ld is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="newLearnAndDevs.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newLearnAndDevs.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="newLearnAndDevs.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $newLearnAndDevs[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $newLearnAndDevs[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <label for="to_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="newLearnAndDevs.{{ $index }}.toPresent"
                                                value="Present">
                                        </div>
                                    </div>
                                    @error('newLearnAndDevs.' . $index . '.end_date' || 'newLearnAndDevs.' . $index .
                                        '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="hours_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Number of
                                        Hours</label>
                                    <input type="number" id="hours_{{ $index }}"
                                        wire:model="newLearnAndDevs.{{ $index }}.no_of_hours"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newLearnAndDevs.' . $index . '.no_of_hours')
                                        <span class="text-red-500 text-sm">The number of hours is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="conducted_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Conducted/Sponsored
                                        By</label>
                                    <input type="text" id="conducted_{{ $index }}"
                                        wire:model="newLearnAndDevs.{{ $index }}.conducted_by"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newLearnAndDevs.' . $index . '.conducted_by')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <!-- File upload section -->
                                <div class="flex flex-col items-center justify-center w-full col-span-2 mt-4 md:mt-0">
                                    <label for="dropzone-file{{ $index }}"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Click
                                                    to upload</span></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF</p>
                                        </div>
                                        <input id="dropzone-file{{ $index }}" type="file" wire:model="newLearnAndDevs.{{ $index }}.certificate"
                                            class="hidden" accept="application/pdf" />
                                    </label>

                                    <!-- Display selected files -->
                                    @if ($newLearnAndDevs[$index]['certificate'])
                                        <div class="mt-4">
                                            <ul class="list-disc list-inside">
                                                <li class="flex items-center text-md text-gray-700 dark:text-gray-300">
                                                    {{ $newLearnAndDevs[$index]['certificate']->getClientOriginalName() }}
                                                    <button type="button" wire:click="removeFile({{ $index }})"
                                                        class="ml-2 text-red-500">
                                                        &times;
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewLearnAndDev"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Training
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveLearnAndDev" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Skills Add and Edit Modal --}}
    <x-modal id="skillsModal" maxWidth="md" wire:model="editSkills">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addSkills ? 'Add' : 'Edit' }} Skills
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveSkills'>
                <div class="grid grid-cols-1">

                    @if ($addSkills != true)
                        @foreach ($mySkills as $index => $skill)
                            <div class="grid grid-cols-1 gap-4">
                                <div class="col-span-1 sm:col-span-1 mb-3">
                                    <label for="skill_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Skill</label>
                                    <input type="text" id="skill_{{ $index }}"
                                        wire:model="mySkills.{{ $index }}.skill"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                    <i class="fas fa-trash cursor-pointer text-red-500 hover:text-red-700 float-right mr-3"
                                        wire:click="deleteSkill({{ $skill['id'] }}, {{ $index }})"
                                        style="margin-top: -30px"></i>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach ($myNewSkills as $index => $skll)
                            <div class="grid grid-cols-1 gap-4">

                                <div class="col-span-1 sm:col-span-1 mb-3">
                                    <label for="skill_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400"></label>
                                    <input type="text" id="skill_{{ $index }}"
                                        wire:model="myNewSkills.{{ $index }}.skill"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    <i class="fas fa-times cursor-pointer text-red-500 hover:text-red-700 float-right mr-3"
                                        wire:click="removeNewSkill({{ $index }})"
                                        style="margin-top: -30px"></i>
                                </div>

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewSkill"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Skill
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveSkills" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Hobbies Add and Edit Modal --}}
    <x-modal id="hobbiesModal" maxWidth="md" wire:model="editHobbies">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addHobbies ? 'Add' : 'Edit' }} Hobbies
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveHobbies'>
                <div class="grid grid-cols-1">

                    @if ($addHobbies != true)
                        @foreach ($myHobbies as $index => $hbby)
                            <div class="grid grid-cols-1 gap-4">

                                <div class="col-span-1 sm:col-span-1 mb-3">
                                    <label for="hobby_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400"></label>
                                    <input type="text" id="hobby_{{ $index }}"
                                        wire:model="myHobbies.{{ $index }}.hobby"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    <i class="fas fa-trash cursor-pointer text-red-500 hover:text-red-700 float-right mr-3"
                                        wire:click="deleteHobby({{ $hbby['id'] }}, {{ $index }})"
                                        style="margin-top: -30px"></i>
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($myNewHobbies as $index => $hbby)
                            <div class="grid grid-cols-1 gap-4">

                                <div class="col-span-1 sm:col-span-1 mb-3">
                                    <label for="hobby_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400"></label>
                                    <input type="text" id="hobby_{{ $index }}"
                                        wire:model="myNewHobbies.{{ $index }}.hobby"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    <i class="fas fa-times cursor-pointer text-red-500 hover:text-red-700 float-right mr-3"
                                        wire:click="removeNewHobby({{ $index }})"
                                        style="margin-top: -30px"></i>
                                </div>

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewHobby"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Hobby
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveHobbies" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Non-Academic Distinction/Recognition Add and Edit Modal --}}
    <x-modal id="nonAcadModal" maxWidth="2xl" wire:model="editNonAcad">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addNonAcad ? 'Add' : 'Edit' }} Non-Academic Distinction/Recognition
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveNonAcad'>
                <div class="grid grid-cols-1">

                    @if ($addNonAcad != true)
                        @foreach ($nonAcads as $index => $nonAcad)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-2">
                                    <label for="award_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Award</label>
                                    <input type="text" id="award_{{ $index }}"
                                        wire:model="nonAcads.{{ $index }}.award"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('nonAcads.' . $index . '.award')
                                        <span class="text-red-500 text-sm">The award is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="org_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Association/Organization
                                        Name</label>
                                    <input type="text" id="org_{{ $index }}"
                                        wire:model="nonAcads.{{ $index }}.ass_org_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('nonAcads.' . $index . '.ass_org_name')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date
                                        Received</label>
                                    <input type="date" id="date_{{ $index }}"
                                        wire:model="nonAcads.{{ $index }}.date_received"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('nonAcads.' . $index . '.date_received')
                                        <span class="text-red-500 text-sm">The date received is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($newNonAcads as $index => $nonAcad)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-2">
                                    <label for="award_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Award
                                        <i class="fas fa-times cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewNonAcad({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="award_{{ $index }}"
                                        wire:model="newNonAcads.{{ $index }}.award"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newNonAcads.' . $index . '.award')
                                        <span class="text-red-500 text-sm">The award is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="org_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Association/Organization
                                        Name</label>
                                    <input type="text" id="org_{{ $index }}"
                                        wire:model="newNonAcads.{{ $index }}.ass_org_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newNonAcads.' . $index . '.ass_org_name')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date
                                        Received</label>
                                    <input type="date" id="date_{{ $index }}"
                                        wire:model="newNonAcads.{{ $index }}.date_received"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newNonAcads.' . $index . '.date_received')
                                        <span class="text-red-500 text-sm">The date received is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewNonAcad"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Non-Academic Distinction/Recognition
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveNonAcad" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Membership in Association/Organization Add and Edit Modal --}}
    <x-modal id="membershipsModal" maxWidth="2xl" wire:model="editMemberships">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addMemberships ? 'Add' : 'Edit' }} Membership in Association/Organization
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveMemberships'>
                <div class="grid grid-cols-1">

                    @if ($addMemberships != true)
                        @foreach ($memberships as $index => $member)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="ass_org_name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Association/Organization
                                        Name</label>
                                    <input type="text" id="ass_org_name_{{ $index }}"
                                        wire:model="memberships.{{ $index }}.ass_org_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('memberships.' . $index . '.ass_org_name')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="memberships.{{ $index }}.position"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('memberships.' . $index . '.position')
                                        <span class="text-red-500 text-sm">This position is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($newMemberships as $index => $member)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="ass_org_name_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Association/Organization
                                        Name
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewMembership({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="ass_org_name_{{ $index }}"
                                        wire:model="newMemberships.{{ $index }}.ass_org_name"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newMemberships.' . $index . '.ass_org_name')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewMembership({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="newMemberships.{{ $index }}.position"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newMemberships.' . $index . '.position')
                                        <span class="text-red-500 text-sm">This position is required!</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewMembership"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Membership
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveMemberships" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Character Reference Add and Edit Modal --}}
    <x-modal id="referenceModal" maxWidth="2xl" wire:model="editReferences">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addReferences ? 'Add' : 'Edit' }} Character Reference
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveReferences'>
                <div class="grid grid-cols-1">

                    @if ($addReferences != true)
                        @foreach ($myReferences as $index => $refs)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="firstname_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Firstname</label>
                                    <input type="text" id="firstname_{{ $index }}"
                                        wire:model="myReferences.{{ $index }}.firstname"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myReferences.' . $index . '.firstname')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="middle_initial_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Middle
                                        Initial</label>
                                    <input type="text" id="middle_initial_{{ $index }}"
                                        wire:model="myReferences.{{ $index }}.middle_initial"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myReferences.' . $index . '.middle_initial')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="surname_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Surname</label>
                                    <input type="text" id="surname_{{ $index }}"
                                        wire:model="myReferences.{{ $index }}.surname"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myReferences.' . $index . '.surname')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="address_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address</label>
                                    <input type="text" id="address_{{ $index }}"
                                        wire:model="myReferences.{{ $index }}.address"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myReferences.' . $index . '.address')
                                        <span class="text-red-500 text-sm">This position is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="tel_number_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Tel
                                        Number</label>
                                    <input type="text" id="tel_number_{{ $index }}"
                                        wire:model="myReferences.{{ $index }}.tel_number"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myReferences.' . $index . '.tel_number')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="mobile_number_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Mobile
                                        Number</label>
                                    <input type="number" id="mobile_number_{{ $index }}"
                                        wire:model="myReferences.{{ $index }}.mobile_number"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myReferences.' . $index . '.mobile_number')
                                        <span class="text-red-500 text-sm">This position is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($myNewReferences as $index => $refs)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="firstname_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Firstname
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewReference({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="firstname_{{ $index }}"
                                        wire:model="myNewReferences.{{ $index }}.firstname"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myNewReferences.' . $index . '.firstname')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="middle_initial_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Middle
                                        Initial
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewReference({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="middle_initial_{{ $index }}"
                                        wire:model="myNewReferences.{{ $index }}.middle_initial"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myNewReferences.' . $index . '.middle_initial')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="surname_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Surname</label>
                                    <input type="text" id="surname_{{ $index }}"
                                        wire:model="myNewReferences.{{ $index }}.surname"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myNewReferences.' . $index . '.surname')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="address_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address</label>
                                    <input type="text" id="address_{{ $index }}"
                                        wire:model="myNewReferences.{{ $index }}.address"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myNewReferences.' . $index . '.address')
                                        <span class="text-red-500 text-sm">This position is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="tel_number_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Tel
                                        Number</label>
                                    <input type="text" id="tel_number_{{ $index }}"
                                        wire:model="myNewReferences.{{ $index }}.tel_number"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myNewReferences.' . $index . '.tel_number')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="mobile_number_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Mobile
                                        Number</label>
                                    <input type="number" id="mobile_number_{{ $index }}"
                                        wire:model="myNewReferences.{{ $index }}.mobile_number"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('myNewReferences.' . $index . '.mobile_number')
                                        <span class="text-red-500 text-sm">This position is required!</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewReference"
                            class="bg-blue-500 hover:text-white hover:bg-blue-700 text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Reference
                        </button>
                    @endif


                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveReferences" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

        </div>
    </x-modal>

    {{-- Delete Modal --}}
    <x-modal id="deleteModal" maxWidth="md" wire:model="delete" centered>
        <div class="p-4">
            <div class="mb-4 text-slate-900 dark:text-gray-100 font-bold">
                Confirm Deletion
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">
                Are you sure you want to delete this {{ $deleteMessage }}?
            </label>
            <form wire:submit.prevent='deleteData'>
                <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                    <button class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <div wire:loading wire:target="deleteData" style="margin-bottom: 5px;">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                        Delete
                    </button>
                    <p @click="show = false"
                        class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Cancel
                    </p>
                </div>
            </form>

        </div>
    </x-modal>
</div>
