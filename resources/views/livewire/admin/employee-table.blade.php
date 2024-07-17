<div class="p-10 flex justify-center w-full">
    <div class="w-full">
        <div class="flex items-center justify-end">
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

            <!-- Provinces Dropdown -->
            <div class="relative inline-block text-left">
                <button wire:click="toggleDropdownProvince"
                    class="mr-4 inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                    type="button">
                    Filter by Province
                    <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                </button>
                @if($dropdownForProvinceOpen)
                <div
                    class="absolute z-20 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Province</h6>
                    <ul class="space-y-2 text-sm">
                        @foreach($provinces as $province)
                        <li class="flex items-center">
                            <input id="province-{{ $province->province_description }}" type="radio"
                                wire:model.live="selectedProvince" value="{{ $province->province_description }}"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="province-{{ $province->province_description }}"
                                class="ml-2 text-gray-900 dark:text-gray-300">{{ $province->province_description
                                }}</label>
                        </li>
                        @endforeach
                        <li class="flex items-center">
                            <input id="any-province" type="radio" wire:model.live="selectedProvince" value=""
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="any-province" class="ml-2 text-gray-900 dark:text-gray-300">Any</label>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            <!-- Cities Dropdown -->
            <div class="relative inline-block text-left">
                <button wire:click="toggleDropdownCity"
                    class="mr-4 inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                    type="button">
                    Filter by City
                    <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                </button>
                @if($dropdownForCityOpen)
                <div
                    class="absolute z-20 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">City</h6>
                    <ul class="space-y-2 text-sm">
                        @if($cities)
                        @foreach($cities as $city)
                        <li class="flex items-center">
                            <input id="city-{{ $city->city_municipality_description }}" type="radio"
                                wire:model.live="selectedCity" value="{{ $city->city_municipality_description }}"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="city-{{ $city->city_municipality_description }}"
                                class="ml-2 text-gray-900 dark:text-gray-300">{{ $city->city_municipality_description
                                }}</label>
                        </li>
                        @endforeach
                        @endif
                        <li class="flex items-center">
                            <input id="any-city" type="radio" wire:model.live="selectedCity" value=""
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="any-city" class="ml-2 text-gray-900 dark:text-gray-300">Any</label>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            <!-- Barangay Dropdown -->
            <div class="relative inline-block text-left">
                <button wire:click="toggleDropdownBarangay"
                    class="mr-4 inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                    type="button">
                    Filter by Barangay
                    <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                </button>
                @if($dropdownForBarangayOpen)
                <div
                    class="absolute z-20 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Barangay</h6>
                    <ul class="space-y-2 text-sm">
                        @if($barangays)
                        @foreach($barangays as $barangay)
                        <li class="flex items-center">
                            <input id="barangay-{{ $barangay->barangay_description }}" type="radio"
                                wire:model.live="selectedBarangay" value="{{ $barangay->barangay_description }}"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="barangay-{{ $barangay->barangay_description }}"
                                class="ml-2 text-gray-900 dark:text-gray-300">{{ $barangay->barangay_description
                                }}</label>
                        </li>
                        @endforeach
                        @endif
                        <li class="flex items-center">
                            <input id="any-barangay" type="radio" wire:model.live="selectedBarangay" value=""
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="any-barangay" class="ml-2 text-gray-900 dark:text-gray-300">Any</label>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            <!-- Civil Status Dropdown -->
            <div class="relative inline-block text-left">
                <button wire:click="toggleDropdownCivilStatus"
                    class="mr-4 inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                    type="button">
                    Filter by Civil Status
                    <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                </button>
                @if($dropdownForCivilStatusOpen)
                <div class="absolute z-20 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Civil Status</h6>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center">
                            <input id="single" type="radio" wire:model.live="civil_status" value="Single"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="single" class="ml-2 text-gray-900 dark:text-gray-300">Single</label>
                        </li>
                        <li class="flex items-center">
                            <input id="married" type="radio" wire:model.live="civil_status" value="Married"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="married" class="ml-2 text-gray-900 dark:text-gray-300">Married</label>
                        </li>
                        <li class="flex items-center">
                            <input id="widowed" type="radio" wire:model.live="civil_status" value="Widowed"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="widowed" class="ml-2 text-gray-900 dark:text-gray-300">Widowed</label>
                        </li>
                        <li class="flex items-center">
                            <input id="separated" type="radio" wire:model.live="civil_status" value="Separated"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="separated" class="ml-2 text-gray-900 dark:text-gray-300">Separated</label>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            <!-- Sex Dropdown -->
            <div class="relative inline-block text-left">
                <button wire:click="toggleDropdownSex"
                    class="mr-4 inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                    type="button">
                    Filter by Sex
                    <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                </button>
                @if($dropdownForSexOpen)
                <div class="absolute z-20 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Sex</h6>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center">
                            <input id="default" type="radio" wire:model.live="sex" value=""
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="default" class="ml-2 text-gray-900 dark:text-gray-300">Default</label>
                        </li>
                        <li class="flex items-center">
                            <input id="male" type="radio" wire:model.live="sex" value="Male"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="male" class="ml-2 text-gray-900 dark:text-gray-300">Male</label>
                        </li>
                        <li class="flex items-center">
                            <input id="female" type="radio" wire:model.live="sex" value="Female"
                                class="h-4 w-4 text-neutral-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="female" class="ml-2 text-gray-900 dark:text-gray-300">Female</label>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            <!-- Filter Dropdown -->
            <div class="relative inline-block text-left">
                <button wire:click="toggleDropdown"
                    class="mr-4 inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                    type="button">
                    Filter by category
                    <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                </button>
                @if($dropdownForCategoryOpen)
                <div
                    class="absolute z-20 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Category</h6>
                    <ul class="space-y-2 text-sm">
                        {{-- <li class="flex items-center">
                            <input id="name" type="checkbox" wire:model="filters.name" class="h-4 w-4">
                            <label for="name" class="ml-2 text-gray-900 dark:text-gray-300">Name</label>
                        </li> --}}
                        <li class="flex items-center">
                            <input id="date_of_birth" type="checkbox" wire:model.live="filters.date_of_birth"
                                class="h-4 w-4">
                            <label for="date_of_birth" class="ml-2 text-gray-900 dark:text-gray-300">Birth Date</label>
                        </li>
                        <li class="flex items-center">
                            <input id="place_of_birth" type="checkbox" wire:model.live="filters.place_of_birth"
                                class="h-4 w-4">
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
                            <label for="citizenship" class="ml-2 text-gray-900 dark:text-gray-300">Citizenship</label>
                        </li>
                        <li class="flex items-center">
                            <input id="civil_status" type="checkbox" wire:model.live="filters.civil_status"
                                class="h-4 w-4">
                            <label for="civil_status" class="ml-2 text-gray-900 dark:text-gray-300">Civil Status</label>
                        </li>
                        <li class="flex items-center">
                            <input id="height" type="checkbox" wire:model.live="filters.height" class="h-4 w-4">
                            <label for="height" class="ml-2 text-gray-900 dark:text-gray-300">Height</label>
                        </li>
                        <li class="flex items-center">
                            <input id="weight" type="checkbox" wire:model.live="filters.weight" class="h-4 w-4">
                            <label for="weight" class="ml-2 text-gray-900 dark:text-gray-300">Weight</label>
                        </li>
                        <li class="flex items-center">
                            <input id="blood_type" type="checkbox" wire:model.live="filters.blood_type" class="h-4 w-4">
                            <label for="blood_type" class="ml-2 text-gray-900 dark:text-gray-300">Blood Type</label>
                        </li>
                        <li class="flex items-center">
                            <input id="gsis" type="checkbox" wire:model.live="filters.gsis" class="h-4 w-4">
                            <label for="gsis" class="ml-2 text-gray-900 dark:text-gray-300">GSIS ID No.</label>
                        </li>
                        <li class="flex items-center">
                            <input id="pagibig" type="checkbox" wire:model.live="filters.pagibig" class="h-4 w-4">
                            <label for="pagibig" class="ml-2 text-gray-900 dark:text-gray-300">PAGIBIG ID No.</label>
                        </li>
                        <li class="flex items-center">
                            <input id="philhealth" type="checkbox" wire:model.live="filters.philhealth" class="h-4 w-4">
                            <label for="philhealth" class="ml-2 text-gray-900 dark:text-gray-300">PhilHealth ID
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
                            <input id="agency_employee_no" type="checkbox" wire:model.live="filters.agency_employee_no"
                                class="h-4 w-4">
                            <label for="agency_employee_no" class="ml-2 text-gray-900 dark:text-gray-300">Agency
                                Employee No.</label>
                        </li>
                        <li class="flex items-center">
                            <input id="permanent_selectedProvince" type="checkbox"
                                wire:model.live="filters.permanent_selectedProvince" class="h-4 w-4">
                            <label for="permanent_selectedProvince"
                                class="ml-2 text-gray-900 dark:text-gray-300">Permanent Address (Province)</label>
                        </li>
                        <li class="flex items-center">
                            <input id="permanent_selectedCity" type="checkbox"
                                wire:model.live="filters.permanent_selectedCity" class="h-4 w-4">
                            <label for="permanent_selectedCity" class="ml-2 text-gray-900 dark:text-gray-300">Permanent
                                Address (City)</label>
                        </li>
                        <li class="flex items-center">
                            <input id="permanent_selectedBarangay" type="checkbox"
                                wire:model.live="filters.permanent_selectedBarangay" class="h-4 w-4">
                            <label for="permanent_selectedBarangay"
                                class="ml-2 text-gray-900 dark:text-gray-300">Permanent Address (Barangay)</label>
                        </li>
                        <li class="flex items-center">
                            <input id="p_house_street" type="checkbox" wire:model.live="filters.p_house_street"
                                class="h-4 w-4">
                            <label for="p_house_street" class="ml-2 text-gray-900 dark:text-gray-300">Permanent Address
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
                                class="ml-2 text-gray-900 dark:text-gray-300">Residential Address (Province)</label>
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
                                class="ml-2 text-gray-900 dark:text-gray-300">Residential Address (Barangay)</label>
                        </li>
                        <li class="flex items-center">
                            <input id="p_house_street" type="checkbox" wire:model.live="filters.p_house_street"
                                class="h-4 w-4">
                            <label for="p_house_street" class="ml-2 text-gray-900 dark:text-gray-300">Residential
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
            <button wire:click="exportUsers"
                class="inline-flex items-center justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none"
                type="button">
                Export to Excel
                <i class="bi bi-download w-5 h-5 ml-2"></i>
            </button>
        </div>

        <!-- Table -->
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block w-full py-2 align-middle">
                    <div class="overflow-hidden border rounded-lg border-neutral-500 dark:border-neutral-200">
                        <div class="overflow-x-auto">
                            <table class="divide-y divide-neutral-500 dark:divide-neutral-200 w-full min-w-full">
                                <thead class="text-neutral-500 dark:text-neutral-200 dark:bg-slate-100 bg-gray-900">
                                    <tr class="text-neutral-200 dark:text-neutral-800">
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Name
                                        </th>
                                        @if($filters['date_of_birth'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Birth
                                            Date</th>
                                        @endif
                                        @if($filters['place_of_birth'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Birth
                                            Place</th>
                                        @endif
                                        @if($filters['sex'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Sex
                                        </th>
                                        @endif
                                        @if($filters['citizenship'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Citizenship</th>
                                        @endif
                                        @if($filters['civil_status'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Civil
                                            Status</th>
                                        @endif
                                        @if($filters['height'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Height
                                        </th>
                                        @endif
                                        @if($filters['weight'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Weight
                                        </th>
                                        @endif
                                        @if($filters['blood_type'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Blood
                                            Type</th>
                                        @endif
                                        @if($filters['gsis'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">GSIS
                                            ID No.</th>
                                        @endif
                                        @if($filters['pagibig'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            PAGIBIG ID No.</th>
                                        @endif
                                        @if($filters['philhealth'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            PhilHealth ID No.</th>
                                        @endif
                                        @if($filters['sss'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">SSS
                                            No.</th>
                                        @endif
                                        @if($filters['tin'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">TIN
                                            No.</th>
                                        @endif
                                        @if($filters['agency_employee_no'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Agency
                                            Employee No.</th>
                                        @endif
                                        @if($filters['permanent_selectedProvince'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Permanent Address (Province)</th>
                                        @endif
                                        @if($filters['permanent_selectedCity'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Permanent Address (City)</th>
                                        @endif
                                        @if($filters['permanent_selectedBarangay'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Permanent Address (Barangay)</th>
                                        @endif
                                        @if($filters['p_house_street'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Permanent Address (Street)</th>
                                        @endif
                                        @if($filters['permanent_selectedZipcode'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Permanent Address (Zip Code)</th>
                                        @endif
                                        @if($filters['residential_selectedProvince'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Residential Address (Province)</th>
                                        @endif
                                        @if($filters['residential_selectedCity'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Residential Address (City)</th>
                                        @endif
                                        @if($filters['residential_selectedBarangay'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Residential Address (Barangay)</th>
                                        @endif
                                        @if($filters['r_house_street'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Residential Address (Street)</th>
                                        @endif
                                        @if($filters['residential_selectedZipcode'])
                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                            Residential Address (Zip Code)</th>
                                        @endif
                                        <th
                                            class="px-5 py-3 text-sm font-medium text-right uppercase sticky right-0 z-10 dark:bg-slate-100 bg-gray-900">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-500">
                                    @foreach($users as $user)
                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->name }}
                                        </td>
                                        @if($filters['date_of_birth'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->date_of_birth }}</td>
                                        @endif
                                        @if($filters['place_of_birth'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->place_of_birth }}</td>
                                        @endif
                                        @if($filters['sex'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->sex }}
                                        </td>
                                        @endif
                                        @if($filters['citizenship'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->citizenship }}</td>
                                        @endif
                                        @if($filters['civil_status'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->civil_status }}</td>
                                        @endif
                                        @if($filters['height'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->height }}
                                        </td>
                                        @endif
                                        @if($filters['weight'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->weight }}
                                        </td>
                                        @endif
                                        @if($filters['blood_type'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->blood_type
                                            }}</td>
                                        @endif
                                        @if($filters['gsis'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->gsis
                                            }}</td>
                                        @endif
                                        @if($filters['pagibig'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->pagibig
                                            }}</td>
                                        @endif
                                        @if($filters['philhealth'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->philhealth
                                            }}</td>
                                        @endif
                                        @if($filters['sss'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->sss
                                            }}</td>
                                        @endif
                                        @if($filters['tin'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->tin
                                            }}</td>
                                        @endif
                                        @if($filters['agency_employee_no'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->agency_employee_no
                                            }}</td>
                                        @endif
                                        @if($filters['permanent_selectedProvince'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->permanent_selectedProvince
                                            }}</td>
                                        @endif
                                        @if($filters['permanent_selectedCity'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->permanent_selectedCity
                                            }}</td>
                                        @endif
                                        @if($filters['permanent_selectedBarangay'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->permanent_selectedBarangay
                                            }}</td>
                                        @endif
                                        @if($filters['p_house_street'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->p_house_street
                                            }}</td>
                                        @endif
                                        @if($filters['permanent_selectedZipcode'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->permanent_selectedZipcode
                                            }}</td>
                                        @endif
                                        @if($filters['residential_selectedProvince'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->residential_selectedProvince
                                            }}</td>
                                        @endif
                                        @if($filters['residential_selectedCity'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->residential_selectedCity
                                            }}</td>
                                        @endif
                                        @if($filters['residential_selectedBarangay'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->residential_selectedBarangay
                                            }}</td>
                                        @endif
                                        @if($filters['r_house_street'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->r_house_street
                                            }}</td>
                                        @endif
                                        @if($filters['residential_selectedZipcode'])
                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{
                                            $user->residential_selectedZipcode
                                            }}</td>
                                        @endif
                                        <td
                                            class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 dark:bg-slate-900 bg-slate-100">
                                            <button wire:click="showUser({{ $user->id }})"
                                                class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Show</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-5 border-t border-neutral-500 dark:border-neutral-200">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if($selectedUser)
        <!-- Modal Popup -->
        <div class="fixed z-50 inset-0 overflow-y-auto" x-show="showModal" x-cloak>
            <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-4/5">
                    <!-- Modal content -->
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $selectedUserData->surname }}'s Profile
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Full name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUser->name }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Birth Date
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->date_of_birth }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Sex at Birth
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->sex }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Citizenship
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->citizenship }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Email address
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUser->email }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Phone number
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->mobile_number }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Civil Status
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->civil_status }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Blood Type
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->blood_type }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Height
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->height }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Weight
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->weight }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        GSIS ID No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->gsis }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        PAGIBIG ID No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->pagibig }}
                                    </dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        PhilHealth ID No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->philhealth }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        SSS No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->sss }}
                                    </dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        TIN No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->tin }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Agency Employee No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->agency_employee_no }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Spouse Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->spouse_name }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Spouse Birth Date
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->spouse_birth_date }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Children's Names
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $childrenNames ?? 'No children recorded' }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Children's Birth Dates
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $childrenBirthDates ?? 'No birth dates recorded' }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Fathers Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->fathers_name }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Mothers Maiden Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->mothers_maiden_name }}
                                    </dd>
                                </div>
                            </div>

                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Permanent Address
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $p_full_address }}
                                </dd>
                            </div>

                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Residential Address
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $r_full_address }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="w-full text-black text-center border-t border-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Educational Background
                            </h3>
                        </div>
                        <div class="border-t border-gray-200 px-4 sm:p-0">
                            <dl class="sm:divide-y sm:divide-gray-200">
                                <div class="grid grid-cols-2 divide-x">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Name of School
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->name_of_school }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-xs font-medium text-gray-500">
                                            Highest Educational Attainment
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->educ_background }}
                                        </dd>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 divide-x">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Degree
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->degree }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Year Graduated
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->year_graduated }}
                                        </dd>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 divide-x">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Period of Attendance (Start)
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->period_start_date }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Period of Attendance (End)
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->period_end_date }}
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6">
                        <button wire:click="closeUserProfile" class="text-blue-600 hover:text-blue-700">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>