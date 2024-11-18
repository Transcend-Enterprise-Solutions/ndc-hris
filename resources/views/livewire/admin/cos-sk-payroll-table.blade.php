<div class="w-full flex flex-col justify-center"
x-data="{ 
    selectedTab: 'cos',
     selectedSubTab: 'payroll',
}" 
x-cloak>

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
            color: rgb(0, 255, 42);
        }

        .spinner-border-2 {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: rgb(255, 255, 255);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">

            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'cos'">COS SK Payroll</h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'signatories'">Manage Payroll & Payslip Signatories</h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'export'">
                    Payroll for the month of {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F') : '' }} {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('Y') : '' }}
                </h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'export'">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d') : '' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d') : '' }}
                </h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                {{-- Search COS Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'cos'">
                    <label for="search2" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search2" wire:model.live="search2"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                {{-- Search Input Export --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'export'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'export'">

                    <!-- Start Date -->
                    <div class="w-full sm:w-auto relative">
                        <label for="startDate" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Start Date</label>
                        <input type="date" id="startDate" wire:model.live='startDate' value="{{ $startDate }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                     <!-- End Date -->
                    <div class="w-full sm:w-auto relative">
                        <label for="endDate" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">End Date</label>
                        <input type="date" id="endDate" wire:model.live='endDate' value="{{ $endDate }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                    <!-- Save Payroll -->
                    @if($hasPayroll == false)
                        <div class="w-full sm:w-auto">
                            <button wire:click="recordPayroll"
                                class="mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                justify-center px-2 py-1.5 text-sm font-medium tracking-wide
                                text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                                rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                                type="button">
                                <div wire:loading wire:target="recordPayroll" style="margin-right: 5px">
                                    <div class="spinner-border small text-primary" role="status">
                                    </div>
                                </div>
                                Record Payroll
                            </button>
                        </div>
                    @endif

                    <!-- Export to Excel -->
                    <div class="w-full sm:w-auto relative">
                        <button wire:click="exportPayroll"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button" title="Export Payroll">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="" wire:loading.remove wire:target="exportPayroll">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="" wire:loading.remove wire:target="exportPayroll">
                            <div wire:loading wire:target="exportPayroll">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                        </button>
                    </div>

                </div>

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'cos'">

                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddCosPayroll" 
                            class="text-sm mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Add COS Payroll
                        </button>
                    </div>

                    <!-- Export to Excel -->
                    <div class="relative inline-block text-left">
                        <button wire:click="exportCosExcel"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button"  title="Export Payroll">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="" wire:loading.remove wire:target="exportCosExcel">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="" wire:loading.remove wire:target="exportCosExcel">
                            <div wire:loading wire:target="exportCosExcel">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Table -->
            <div class="flex flex-col">
                <div class="flex gap-2 overflow-x-auto -mb-2">
                    <button @click="selectedTab = 'cos'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'cos', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'cos' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                        Manage Payroll
                    </button>
                    <button @click="selectedTab = 'export'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'export', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'export' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Export Payroll
                    </button>
                    <button @click="selectedTab = 'signatories'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'signatories', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'signatories' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Signatories
                    </button>
                </div>
                <div class="-my-2 overflow-x-auto">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                            <div x-show="selectedTab === 'cos'">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                @foreach($cosColumns as $column => $visible)
                                                    @if($visible)
                                                        <th scope="col" class="px-5 py-3 {{ $column == 'name' ? 'text-left' : 'text-center' }} text-sm font-medium text-left uppercase">
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
                                                @foreach($cosPayrolls as $payroll)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        @foreach($cosColumns as $column => $visible)
                                                            @if($visible)
                                                                <td class="px-5 py-4 {{ $column == 'name' ? 'text-left' : 'text-center' }} text-sm font-medium whitespace-nowrap">
                                                                    @if(in_array($column, [
                                                                        'rate_per_month', 
                                                                        'additional_premiums',
                                                                        'adjustment',
                                                                        'withholding_tax',
                                                                        'nycempc',
                                                                        'other_deductions',
                                                                        'total_deduction',
                                                                    ]))
                                                                        {{ currency_format($payroll->$column) }}
                                                                    @elseif($column == 'employee_number')
                                                                        {{ $payroll->$column ? 'D-' . substr($payroll->$column, 1) : '' }}
                                                                    @elseif($column == 'name')
                                                                        {{ $payroll->surname .", "  . $payroll->first_name . " " . $payroll->middle_name ?: '' . " " . $payroll->name_extension ?: ''}}
                                                                    @else
                                                                        {{ $payroll->$column ?? '' }}
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <div class="relative">
                                                                <button wire:click="toggleEditCosPayroll({{ $payroll->user_id }})" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                                <button wire:click="toggleCosDelete({{ $payroll->user_id }})" 
                                                                    class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                                    dark:hover:text-red-900" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                    @if ($cosPayrolls->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                            No records!
                                        </div> 
                                    @endif
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $cosPayrolls->links() }}
                                </div>
                            </div>
                            <div x-show="selectedTab === 'export'">
                                <div class="overflow-x-auto">

                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Name</th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Employee Number
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Position
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Salary Grade
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Daily Salary Rate
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    No. of Days Covered
                                                </th>                            
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Gross Salary
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Absences (Days)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Absences (Amount)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Late/Undertime (Hours)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Late/Undertime (Hours -Amount)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Late/Undertime (Minutes)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Late/Undertime (Mins - Amount)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Gross Salary Less<br>(Absences/Lates/Undertime)
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Additional Premiums
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Adjustment
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Withholding Tax
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    NYCEMPC
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Other Deductions
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Total Deduction
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Net Amount Due
                                                </th>
                                                <th class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                            @foreach($payrolls as $payroll)
                                                <tr class="text-neutral-800 dark:text-neutral-200">
                                                    <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                        {{ $payroll['name'] ?? '' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ isset($payroll['employee_number']) ? 'D-' . substr($payroll['employee_number'], 1) : '' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ $payroll['position'] ?? '' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ $payroll['salary_grade'] ?? '' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['daily_salary_rate'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ $payroll['no_of_days_covered'] ?? '' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['gross_salary'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ zero_checker($payroll['absences_days'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['absences_amount'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ zero_checker($payroll['late_undertime_hours'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['late_undertime_hours_amount'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ zero_checker($payroll['late_undertime_mins'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['late_undertime_mins_amount'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['gross_salary_less'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['additional_premiums'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['adjustment'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['withholding_tax'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['nycempc'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['other_deductions'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['total_deductions'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['net_amount_due'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-gray-100 dark:bg-gray-800">
                                                        <div class="relative">
                                                            <button wire:click="viewPayroll({{ $payroll['user_id'] }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                                <i class="fas fa-eye ml-3"></i>
                                                            </button>
                                                        </div>
                                                        <button wire:click="exportPayslip({{ $payroll['user_id'] }})" class="relative z-10 peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 {{ $canExportPayslip ? '' : 'hidden' }}
                                                            text-sm font-medium tracking-wide text-gray-800 dark:text-white  hover:text-gray-300 focus:outline-nones" title="Export Payroll">
                                                            <div wire:loading wire:target="exportPayslip({{ $payroll['user_id'] }})">
                                                                <div class="ml-2 spinner-border small text-primary" role="status">
                                                                </div>
                                                            </div>
                                                            <i class="fas fa-file-export ml-3" wire:loading.remove wire:target="exportPayslip({{ $payroll['user_id'] }})"></i>
                                                        </button>
                                                        <div class="relative">
                                                            <button wire:click="exportIndivPayroll({{ $payroll['user_id'] }})" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 
                                                                text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                                title="Export Payroll">
                                                                <img class="flex dark:hidden ml-3 mt-4" src="/images/icons8-xls-export-dark.png" width="18" alt="" wire:target="exportIndivPayroll({{ $payroll['user_id'] }})"  wire:loading.remove>
                                                                <img class="hidden dark:block ml-3 mt-4" src="/images/icons8-xls-export-light.png" width="18" alt="" wire:target="exportIndivPayroll({{ $payroll['user_id'] }})" wire:loading.remove>
                                                                <div wire:loading wire:target="exportIndivPayroll({{ $payroll['user_id'] }})">
                                                                    <div class="mt-4 ml-3 spinner-border small text-primary" role="status">
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($payrolls->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                            Select start and end date!
                                        </div> 
                                    @endif
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                </div>
                            </div>
                            <div x-show="selectedTab === 'signatories'">
                                <div class="overflow-x-hidden">
                                    <div class="flex gap-2 overflow-x-auto dark:bg-gray-700 bg-gray-200 rounded-t-lg">
                                        <button @click="selectedSubTab = 'payroll'" 
                                                :class="{ 'font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400': selectedSubTab === 'payroll', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedSubTab !== 'payroll' }" 
                                                class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                                            Payroll Signatories
                                        </button>
                                        <button @click="selectedSubTab = 'payslip'" 
                                                :class="{ 'font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400': selectedSubTab === 'payslip', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedSubTab !== 'payslip' }" 
                                                class="h-min px-4 pt-2 pb-4 text-sm">
                                            Payslip Signatories
                                        </button>
                                    </div>
                                </div>
                                 {{-- COS Payroll View --}}
                                 <div x-show="selectedSubTab === 'payroll'">
                                    <div class="overflow-hidden mt-10">
                                        <div class="pb-4 mb-3 pt-4 sm:pt-0">
                                            <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">COS Payroll Footer View</h1>
                                        </div>
                                        <div class="overflow-x-auto bg-white text-xs text-black border border-black">
                                            <div class="block sm:flex">
                                                <div class="col-span-1 block border-r border-black">
                                                    <div class="h-32 w-full block border-b border-black relative">
                                                        <div class="flex">
                                                            <p class="px-2 border-r border-b border-black">A.</p>
                                                            <p class="pl-2">CERTIFIED: Services duly rendered as stated.</p>
                                                        </div>
                                                        <div class="absolute bottom-1 w-full flex">
                                                            <div class="flex flex flex-col items-center w-4/5">

                                                                {{-- E-Signature Here --}}

                                                                {{-- @if($cosPayroll['a'])
                                                                    <img src="{{ $cosPayroll['a']->signature ? route('signature.file', basename($cosPayroll['a']->signature)) : '/images/signature.png' }}"
                                                                        alt="signature" title="{{ $cosPayroll['a']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                        class="cursor-pointer {{ $cosPayroll['a']->signature ? '-mb-4' : '' }}" onclick="document.getElementById('cosPayroll_A').click()"
                                                                        style="height: {{ $cosPayroll['a']->signature ? '60px' : '30px' }}; width: auto;">
                                                                        <input type="file" id="cosPayroll_A" 
                                                                            wire:model="signatures.{{ $cosPayroll['a']->id }}" 
                                                                            style="display: none;" 
                                                                            accept="image/*">
                                                                    <div wire:loading wire:target="signatures.{{ $cosPayroll['a']->id }}" style="margin-left: 5px">
                                                                        <div class="spinner-border small text-primary" role="status">
                                                                        </div>
                                                                    </div>
                                                                    @error('signatures.' . $cosPayroll['a']->id) <span class="error">{{ $message }}</span> @enderror
                                                                @endif --}}
                                                                <p class="text-center font-bold text-sm">{{ $cosPayroll['a'] ? $cosPayroll['a']->name : 'XXXXXXXXXX' }}</p>
                                                                <p class="text-center">{{ $cosPayroll['a'] ? $cosPayroll['a']->position : 'Position' }}</p>
                                                            </div>
                                                            <div class="flex flex flex-col items-center justify-end w-1/5">
                                                                <p class="text-center underline">01/01/2024</p>
                                                                <p class="text-center">Date</p>
                                                            </div>
                                                        </div>
                                                        <div class="absolute right-0 top-0 p-2 bg-white">
                                                            @if($cosPayroll['a'])
                                                                <button wire:click="toggleEditSignatory({{ $cosPayroll['a']->user_id }}, 'cos_payroll')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                            @else
                                                                <button wire:click="toggleAddSignatory('cos_payroll', 'A')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="h-32 w-full relative">
                                                        <div class="flex">
                                                            <p class="px-2 border-r border-b border-black">B.</p>
                                                            <p class="pl-2">CERTIFIED: Supporting documents complete and proper; and cash available in the amount of</p>
                                                        </div>
                                                        <div class="flex">
                                                            <p class="px-2"></p>
                                                            <p class="pl-6 font-bold">₱ 4,884.16</p>
                                                        </div>
                                                        <div class="absolute bottom-1 w-full flex">
                                                            <div class="flex flex flex-col items-center w-4/5">

                                                                {{-- E-Signature Here --}}

                                                                {{-- @if($cosPayroll['b'])
                                                                    <img src="{{ $cosPayroll['b']->signature ? route('signature.file', basename($cosPayroll['b']->signature)) : '/images/signature.png' }}"
                                                                        alt="signature" title="{{ $cosPayroll['b']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                        class="cursor-pointer {{ $cosPayroll['b']->signature ? '-mb-4' : '' }}" 
                                                                        onclick="document.getElementById('cosPayroll_B').click()"
                                                                        style="height: {{ $cosPayroll['b']->signature ? '60px' : '30px' }}; width: auto;">
                                                                        <input type="file" id="cosPayroll_B" 
                                                                            wire:model="signatures.{{ $cosPayroll['b']->id }}" 
                                                                            style="display: none;" 
                                                                            accept="image/*">
                                                                    <div wire:loading wire:target="signatures.{{ $cosPayroll['b']->id }}" style="margin-left: 5px">
                                                                        <div class="spinner-border small text-primary" role="status">
                                                                        </div>
                                                                    </div>
                                                                    @error('signatures.' . $cosPayroll['b']->id) <span class="error">{{ $message }}</span> @enderror
                                                                @endif --}}
                                                                <p class="text-center font-bold text-sm">{{ $cosPayroll['b'] ? $cosPayroll['b']->name : 'XXXXXXXXXX' }}</p>
                                                                <p class="text-center">{{ $cosPayroll['b'] ? $cosPayroll['b']->position : 'Position' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="absolute right-0 top-0 p-2 bg-white">
                                                            @if($cosPayroll['b'])
                                                                <button wire:click="toggleEditSignatory({{ $cosPayroll['b']->user_id }}, 'cos_payroll')"  
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                            @else
                                                                <button wire:click="toggleAddSignatory('cos_payroll', 'B')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-1 block">
                                                    <div class="h-32 w-full border-b border-black relative">
                                                        <div class="flex">
                                                            <p class="px-2 border-r border-b border-black">C.</p>
                                                            <p class="pl-2 font-bold">APPROVED FOR PAYMENT: FOUR THOUSAND, EIGHT HUNDRED AND EIGHTY-FOUR AND 16/100 PESOS ONLY</p>
                                                        </div>
                                                        <div class="flex">
                                                            <p class="px-2"></p>
                                                            <p class="pl-6 font-bold">₱ 4,884.16</p>
                                                        </div>
                                                        <div class="absolute bottom-1 w-full flex">
                                                            <div class="flex flex flex-col items-center w-4/5">

                                                                {{-- E-Signature Here --}}

                                                                {{-- @if($cosPayroll['c'])
                                                                    <img src="{{ $cosPayroll['c']->signature ? route('signature.file', basename($cosPayroll['c']->signature)) : '/images/signature.png' }}"
                                                                        alt="signature" title="{{ $cosPayroll['c']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                        class="cursor-pointer {{ $cosPayroll['c']->signature ? '-mb-4' : '' }}" 
                                                                        onclick="document.getElementById('cosPayroll_C').click()"
                                                                        style="height: {{ $cosPayroll['c']->signature ? '60px' : '30px' }}; width: auto;">
                                                                        <input type="file" id="cosPayroll_C" 
                                                                            wire:model="signatures.{{ $cosPayroll['c']->id }}" 
                                                                            style="display: none;" 
                                                                            accept="image/*">
                                                                    <div wire:loading wire:target="signatures.{{ $cosPayroll['c']->id }}" style="margin-left: 5px">
                                                                        <div class="spinner-border small text-primary" role="status">
                                                                        </div>
                                                                    </div>
                                                                    @error('signatures.' . $cosPayroll['c']->id) <span class="error">{{ $message }}</span> @enderror
                                                                @endif --}}
                                                                <p class="text-center font-bold text-sm">{{ $cosPayroll['c'] ? $cosPayroll['c']->name : 'XXXXXXXXXX' }}</p>
                                                                <p class="text-center">{{ $cosPayroll['c'] ? $cosPayroll['c']->position : 'Position' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="absolute right-0 top-0 p-2 bg-white">
                                                            @if($cosPayroll['c'])
                                                                <button wire:click="toggleEditSignatory({{ $cosPayroll['c']->user_id }}, 'cos_payroll')"  
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                            @else
                                                                <button wire:click="toggleAddSignatory('cos_payroll', 'C')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="h-32 w-full relative">
                                                        <div class="flex">
                                                            <p class="px-2 border-r border-b border-black">D.</p>
                                                            <p class="pl-2">CERTIFIED: Each employee whose name <br>
                                                                appears above has been paid the amount indicated <br>
                                                                opposite on his/her name.
                                                            </p>
                                                        </div>
                                                        <div class="absolute bottom-1 w-full flex">
                                                            <div class="flex flex flex-col items-center w-4/5">

                                                                {{-- E-Signature Here --}}

                                                                {{-- @if($cosPayroll['d'])
                                                                    <img src="{{ $cosPayroll['d']->signature ? route('signature.file', basename($cosPayroll['d']->signature)) : '/images/signature.png' }}"
                                                                        alt="signature" title="{{ $cosPayroll['d']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                        class="cursor-pointer {{ $cosPayroll['d']->signature ? '-mb-4' : '' }}" 
                                                                        onclick="document.getElementById('cosPayroll_D').click()"
                                                                        style="height: {{ $cosPayroll['d']->signature ? '60px' : '30px' }}; width: auto;">
                                                                        <input type="file" id="cosPayroll_D" 
                                                                            wire:model="signatures.{{ $cosPayroll['d']->id }}" 
                                                                            style="display: none;" 
                                                                            accept="image/*">
                                                                    <div wire:loading wire:target="signatures.{{ $cosPayroll['d']->id }}" style="margin-left: 5px">
                                                                        <div class="spinner-border small text-primary" role="status">
                                                                        </div>
                                                                    </div>
                                                                    @error('signatures.' . $cosPayroll['d']->id) <span class="error">{{ $message }}</span> @enderror
                                                                @endif --}}
                                                                <p class="text-center font-bold text-sm">{{ $cosPayroll['d'] ? $cosPayroll['d']->name : 'XXXXXXXXXX' }}</p>
                                                                <p class="text-center">{{ $cosPayroll['d'] ? $cosPayroll['d']->position : 'Position' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="absolute right-0 top-0 p-2 bg-white">
                                                            @if($cosPayroll['d'])
                                                                <button wire:click="toggleEditSignatory({{ $cosPayroll['d']->user_id }}, 'cos_payroll')"  
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                            @else
                                                                <button wire:click="toggleAddSignatory('cos_payroll', 'D')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- COS Payslip View --}}
                                <div x-show="selectedSubTab === 'payslip'">
                                    <div class="overflow-hidden mt-10">
                                        <div class="pb-4 mb-3 pt-4 sm:pt-0">
                                            <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">COS Payslip Footer View</h1>
                                        </div>
                                        <div class="overflow-x-auto bg-white text-xs text-black border border-black px-2 py-6">
                                            <div class="block">
                                                <div class="flex border-b border-dashed border-black">
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 font-bold">NET PAY</div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 border-b font-bold border-solid border-black text-right">₱ 0,000.00</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4">Amount Due &nbsp&nbsp&nbsp&nbsp January 01-15 2024</div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 border-b border-solid border-black text-right">₱ 0,000.00</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4">Amount Due &nbsp&nbsp&nbsp&nbsp January 16-31 2024</div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 border-b border-solid border-black text-right">₱ 0,000.00</div>
                                                </div>
                                                <div class="flex mt-6">
                                                    <div class="w-1/4">Prepared By:</div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 relative">
                                                        Noted By:
                                                        <div class="absolute right-0 top-0 p-2 bg-white">
                                                            @if($cosPayslipSigns['notedBy'])
                                                                <button wire:click="toggleEditPayslipSignatory({{ $cosPayslipSigns['notedBy']->user_id }}, 'cos_payslip')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                            @else
                                                                <button wire:click="toggleAddPayslipSignatory('Noted By', 'cos_payslip')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="w-1/4"></div>
                                                </div>
                                                <div class="flex mt-6">
                                                    <div class="w-1/4 font-bold text-sm">

                                                        {{-- E-Signature Here --}}

                                                        {{-- @if($preparedBySignature)
                                                            <img src="{{ $preparedBySignature->signature ? route('signature.file', basename($preparedBySignature->signature)) : '/images/signature.png' }}"
                                                                alt="signature" title="Edit Signature"
                                                                class="cursor-pointer {{ $preparedBySignature->signature ? '-mb-4' : '' }}" 
                                                                onclick="document.getElementById('plantillaPayslip_PreparedBy').click()"
                                                                style="height: {{ $preparedBySignature->signature ? '60px' : '30px' }}; width: auto;">
                                                        @else
                                                            <img src="/images/signature.png"
                                                                alt="signature" title="Add Signature"
                                                                class="cursor-pointer" 
                                                                onclick="document.getElementById('plantillaPayslip_PreparedBy').click()"
                                                                style="height: 30px; width: auto;">
                                                        @endif --}}
                                                        <div wire:loading wire:target="preparedBySignature" style="margin-left: 5px">
                                                            <div class="spinner-border small text-primary" role="status">
                                                            </div>
                                                        </div>
                                                        <input type="file" id="plantillaPayslip_PreparedBy" 
                                                            wire:model.live="preparedBySign" 
                                                            style="display: none;" 
                                                            accept="image/*">
                                                        @error('preparedBySign') <span class="error">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 font-bold text-sm">

                                                        {{-- E-Signature Here --}}

                                                        {{-- @if($cosPayslipSigns['notedBy'])
                                                            <img src="{{ $cosPayslipSigns['notedBy']->signature ? route('signature.file', basename($cosPayslipSigns['notedBy']->signature)) : '/images/signature.png' }}"
                                                                alt="signature" title="{{ $cosPayslipSigns['notedBy']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                class="cursor-pointer {{ $cosPayslipSigns['notedBy']->signature ? '-mb-4' : '' }}" 
                                                                onclick="document.getElementById('plantillaPayslip_NotedBy').click()"
                                                                style="height: {{ $cosPayslipSigns['notedBy']->signature ? '60px' : '30px' }}; width: auto;">
                                                                <input type="file" id="plantillaPayslip_NotedBy" 
                                                                    wire:model="signatures.{{ $cosPayslipSigns['notedBy']->id }}" 
                                                                    style="display: none;" 
                                                                    accept="image/*">
                                                            <div wire:loading wire:target="signatures.{{ $cosPayslipSigns['notedBy']->id }}" style="margin-left: 5px">
                                                                <div class="spinner-border small text-primary" role="status">
                                                                </div>
                                                            </div>
                                                            @error('signatures.' . $cosPayslipSigns['notedBy']->id) <span class="error">{{ $message }}</span> @enderror
                                                        @endif --}}
                                                    </div>
                                                    <div class="w-1/4"></div>
                                                </div>
                                                <div class="flex mt-6">
                                                    <div class="w-1/4 font-bold text-sm">{{ $preparedBy->name }}</div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4 font-bold text-sm">{{ $cosPayslipSigns['notedBy'] ? $cosPayslipSigns['notedBy']->name : 'XXXXXXXXXX' }}</div>
                                                    <div class="w-1/4"></div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-1/4">{{ $preparedBy->position }}</div>
                                                    <div class="w-1/4"></div>
                                                    <div class="w-1/4">{{ $cosPayslipSigns['notedBy'] ? $cosPayslipSigns['notedBy']->position : 'Position' }}</div>
                                                    <div class="w-1/4"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Released Payrolls --}}
    <div x-show="selectedTab === 'export'" class="mt-4">
        @livewire('admin.payroll-component.cos-sk-recorded-payroll')
    </div>

    {{-- Add and Edit COS Payroll Modal --}}
    <x-modal id="cosPayroll" maxWidth="2xl" wire:model="editCosPayroll">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addCosPayroll ? 'Add' : 'Edit' }} COS Payroll
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='saveCosPayroll'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-full sm:col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model.live='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addCosPayroll ? '' : 'disabled' }}>
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($unpayrolledEmployees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="employee_number" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Number</label>
                        <input type="text" id="employee_number" wire:model='employee_number' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('employee_number') 
                            <span class="text-red-500 text-sm">The employee number is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="office_division" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Office/Division</label>
                        <input type="text" id="office_division" wire:model='office_division' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('office_division') 
                            <span class="text-red-500 text-sm">The office/division is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                        <input type="text" id="position" wire:model='position' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('position') 
                            <span class="text-red-500 text-sm">The position is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="sg" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Salary Grade</label>
                        <select id="sg" wire:model.live='sg' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="">Select Salary Grade</option>
                            @foreach ($salaryGrade as $sg)
                                <option value="{{ $sg->salary_grade }}">{{ $sg->salary_grade }}</option>
                            @endforeach
                        </select>                        
                        @error('sg') 
                            <span class="text-red-500 text-sm">The salary grade is required!</span> 
                        @enderror
                    </div>

                    {{-- <div class="col-span-full sm:col-span-1">
                        <label for="step" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Step</label>
                        <select id="step" wire:model.live='step' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="">Select Step</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>                        
                        @error('step') 
                            <span class="text-red-500 text-sm">The step is required!</span> 
                        @enderror
                    </div> --}}

                    <div class="col-span-full sm:col-span-1">
                        <label for="rate_per_month" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Rate per Month</label>
                        <input type="number" step="0.01" id="rate_per_month" wire:model.live='rate_per_month' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('rate_per_month') 
                            <span class="text-red-500 text-sm">The rate per month is required!</span> 
                        @enderror
                    </div>

                    
                    <div class="col-span-full sm:col-span-1">
                        <label for="additional_premiums" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Additional Premiums</label>
                        <input type="number" step="0.01" id="additional_premiums" wire:model.live='additional_premiums' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="adjustment" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Adjustment</label>
                        <input type="number" step="0.01" id="adjustment" wire:model.live='adjustment' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="withholding_tax" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Withholding Tax</label>
                        <input type="number" step="0.01" id="withholding_tax" wire:model.live='withholding_tax' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="nycempc" class="block text-sm font-medium text-gray-700 dark:text-slate-400">NYCEMPC</label>
                        <input type="number" step="0.01" id="nycempc" wire:model.live='nycempc' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="other_deductions" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Other Deductions</label>
                        <input type="number" step="0.01" id="other_deductions" wire:model.live='other_deductions' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="total_deduction" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Total Deduction</label>
                        <input type="number" step="0.01" id="total_deduction" wire:model.live='total_deduction' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 text-sm">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveCosPayroll" style="margin-right: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Delete Modal --}}
    <x-modal id="deleteModal" maxWidth="md" wire:model="deleteId" centered>
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
                    <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Cancel
                    </p>
                </div>
            </form>

        </div>
    </x-modal>
    
    {{-- Add and Edit Payroll Signatory Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="editSignatory" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addSignatory ? 'Add' : 'Edit' }} Payroll Signatory
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='saveSignatory'>
                <div class="grid grid-cols-1">
                    
                    <div class="col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>

                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveRole" class="spinner-border small text-primary" role="status">
                            </div>
                            Save
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Add and Edit Payslip Signatory Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="editPayslipSignatory" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addPayslipSignatory ? 'Add' : 'Edit' }} Payslip Signatory
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='savePayslipSignatory'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addPayslipSignatory ? '' : 'disabled' }}>
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="signatory" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Payslip Signatory</label>
                        <select id="userId" wire:model='signatory' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="">Select payslip signatory for</option>
                            <option value="Noted By">Noted By</option>
                            @if(!$addPayslipSignatory)
                                <option value="X">Remove Signatory</option>
                            @endif
                        </select>                        
                        @error('signatory') 
                            <span class="text-red-500 text-sm">The signatory is required!</span> 
                        @enderror
                    </div>


                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveRole" class="spinner-border small text-primary" role="status">
                            </div>
                            Save
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- View Modal --}}
    <x-modal id="viewPayroll" maxWidth="2xl" wire:model="view">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Payroll for {{ $name }}
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent=''>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-1">
                        <label for="name" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Name: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200 text-left">&nbsp{{ $name }}</p>
                    </div>
                    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="employee_number" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Employee Number: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200 text-left">&nbsp{{ $employee_number }}</p>
                    </div>

                    <div class="col-span-2 border-b border-slate-800">
                        <label for="position" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Position: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $position }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="sg_step" class="block text-xs font-medium text-gray-700 dark:text-slate-400"><SG>Step: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $sg_step }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="daily_salary_rate" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Daily salary rate: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $daily_salary_rate == 0 ? '-' : currency_format($daily_salary_rate) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="no_of_days_covered" class="block text-xs font-medium text-gray-700 dark:text-slate-400">No. of Days Covered: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $no_of_days_covered }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="gross_salary" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Gross Salary: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $gross_salary == 0 ? '-' : currency_format($gross_salary) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="absences_days" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Absences Days: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $absences_days }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="absences_amount" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Absences Amount: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $absences_amount == 0 ? '-' : currency_format($absences_amount) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="gross_amount" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Late/Undertime (hours): </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $late_undertime_hours }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="late_undertime_hours_amount" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Late/Undertime (hours) Amt.: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $late_undertime_hours_amount == 0 ? '-' : currency_format($late_undertime_hours_amount) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="late_undertime_mins" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Late/Undertime (minutes): </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $late_undertime_mins }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="late_undertime_mins_amount" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Late/Undertime (minutes) Amt.: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $late_undertime_mins_amount == 0 ? '-' : currency_format($late_undertime_mins_amount) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="gross_salary_less" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Gross Salary Less: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $gross_salary_less == 0 ? '-' : currency_format($gross_salary_less) }}</p>
                    </div>
                    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="w_holding_tax" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Withholding Tax: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $w_holding_tax == 0 ? '-' : currency_format($w_holding_tax) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="nycempc" class="block text-xs font-medium text-gray-700 dark:text-slate-400">NYCEMPC: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $nycempc == 0 ? '-' : currency_format($nycempc) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="total_deduction" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Total Deduction: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $total_deduction == 0 ? '-' : currency_format($total_deduction) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="net_amount_due" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Net Amount Received: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($net_amount_due) }}</p>
                    </div>

                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="exportIndivPayroll({{ $userId }})">
                            <div wire:loading wire:target="" class="spinner-border small text-primary" role="status">
                            </div>
                            Export
                            <div wire:loading wire:target="exportIndivPayroll({{ $userId }})" style="margin-left: 5px">
                                <div class="spinner-border-2 small text-primary" role="status">
                                </div>
                            </div>
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

</div>
