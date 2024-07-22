<div class="w-full flex justify-center">
    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 pt-4 sm:pt-1">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Payroll Management</h1>
            </div>

            <div class="block sm:flex items-center justify-between">

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


                <div class="relative inline-block text-left">
                    <input type="search" id="search" wire:model="search" 
                    placeholder="Search..."
                    class="py-2 px-3 block w-full shadow-sm text-sm font-medium border-gray-400 
                    wire:text-neutral-800 dark:text-neutral-200 
                    dark:hover:bg-slate-600 dark:border-slate-600 mb-4
                    rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                </div>

                <div class="block sm:flex items-center">

                    <!-- Sort Dropdown -->
                    <div class="relative inline-block text-left mr-0 sm:mr-4">
                        <button wire:click="toggleDropdown"
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Sort Column
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>
                        @if($sortColumn)
                            <div
                                class="absolute z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg 
                                shadow-2xl dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Category</h6>
                                <ul class="space-y-2 text-sm">
                                    {{-- <li class="flex items-center">
                                        <input id="name" type="checkbox" wire:model="filters.name" class="h-4 w-4">
                                        <label for="name" class="ml-2 text-gray-900 dark:text-gray-300">Name</label>
                                    </li> --}}
                                    <li class="flex items-center">
                                        <input id="date_of_birth" type="checkbox" wire:model.live="filters.date_of_birth"
                                            class="h-4 w-4">
                                        <label for="date_of_birth" class="ml-2 text-gray-900 dark:text-gray-300">Birth
                                            Date</label>
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
                                        <input id="height" type="checkbox" wire:model.live="filters.height" class="h-4 w-4">
                                        <label for="height" class="ml-2 text-gray-900 dark:text-gray-300">Height</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="weight" type="checkbox" wire:model.live="filters.weight" class="h-4 w-4">
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
                                        <label for="gsis" class="ml-2 text-gray-900 dark:text-gray-300">GSIS ID No.</label>
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
                                        <input id="agency_employee_no" type="checkbox"
                                            wire:model.live="filters.agency_employee_no" class="h-4 w-4">
                                        <label for="agency_employee_no" class="ml-2 text-gray-900 dark:text-gray-300">Agency
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
                                        <input id="p_house_street" type="checkbox" wire:model.live="filters.p_house_street"
                                            class="h-4 w-4">
                                        <label for="p_house_street" class="ml-2 text-gray-900 dark:text-gray-300">Permanent
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
                                        <input id="p_house_street" type="checkbox" wire:model.live="filters.p_house_street"
                                            class="h-4 w-4">
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
                    <div class="relative inline-block text-left">
                        <button wire:click="exportUsers"
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 mb-4 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="25" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="25" alt="">
                        </button>
                    </div>

                </div>
            </div>

            <!-- Table -->

            <div class="flex flex-col p-3">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                            <div class="overflow-x-auto">

                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Name</th>
                                            @foreach($columns as $column => $visible)
                                                @if($visible)
                                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                        {{ ucwords(str_replace('_', ' ', $column)) }}
                                                    </th>
                                                @endif
                                            @endforeach
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach($users as $user)
                                            @foreach($payrolls as $payroll)
                                                @if($user->id == $payroll->user_id) <!-- Adjust this line according to your relationship -->
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">{{ $user->name ?? '' }}</td>
                                                        @foreach($columns as $column => $visible)
                                                            @if($visible)
                                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                    @if(in_array($column, [
                                                                        'rate_per_month', 
                                                                        'personal_economic_relief_allowance', 
                                                                        'gross_amount', 
                                                                        'additional_gsis_premium', 
                                                                        'lbp_salary_loan', 
                                                                        'nycea_deductions', 
                                                                        'sc_membership', 
                                                                        'total_loans', 
                                                                        'salary_loan', 
                                                                        'policy_loan', 
                                                                        'eal', 
                                                                        'emergency_loan', 
                                                                        'mpl', 
                                                                        'housing_loan', 
                                                                        'ouli_prem', 
                                                                        'gfal', 
                                                                        'cpl', 
                                                                        'pagibig_mpl', 
                                                                        'other_deduction_philheath_diff', 
                                                                        'life_retirement_insurance_premiums', 
                                                                        'pagibig_contribution', 
                                                                        'w_holding_tax', 
                                                                        'philhealth', 
                                                                        'total_deduction', 
                                                                        'net_amount_received', 
                                                                        'amount_due_first_half', 
                                                                        'amount_due_second_half'
                                                                    ]))
                                                                        {{ currency_format($payroll->$column) }}
                                                                    @else
                                                                        {{ $payroll->$column ?? '' }}
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <button wire:click="editPayroll({{ $user->id }})" class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                                <i class="fas fa-file-export ml-3"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                
                            </div>
                            <div class="p-5 border-t border-gray-200 dark:border-slate-600 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $users->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
