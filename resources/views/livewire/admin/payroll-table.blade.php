<div class="w-full flex justify-center">

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
            color: rgb(0, 255, 98);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">

            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    Payroll for the month of {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F') : '' }} {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('Y') : '' }}
                </h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d') : '' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d') : '' }}
                </h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                {{-- Search Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                {{-- Filters --}}
                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">

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

                    <!-- Sort Dropdown -->
                    {{-- <div class="relative inline-block text-left mr-0 sm:mr-4">
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
                                    <li class="flex items-center" wire:click='toggleAllColumn'>
                                        <input id="allCol" type="checkbox" wire:model.live="allCol"
                                            class="h-4 w-4">
                                        <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Select All</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="employee_number" type="checkbox" wire:model.live="columns.employee_number"
                                            class="h-4 w-4">
                                        <label for="employee_number" class="ml-2 text-gray-900 dark:text-gray-300">Employee Number</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="position" type="checkbox" wire:model.live="columns.position"
                                            class="h-4 w-4">
                                        <label for="position" class="ml-2 text-gray-900 dark:text-gray-300">Position</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="salary_grade" type="checkbox" wire:model.live="columns.salary_grade" class="h-4 w-4">
                                        <label for="salary_grade" class="ml-2 text-gray-900 dark:text-gray-300">Salary Grade</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="daily_salary_rate" type="checkbox" wire:model.live="columns.daily_salary_rate"
                                            class="h-4 w-4">
                                        <label for="daily_salary_rate"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Daily Salary Rate</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="no_of_days_covered" type="checkbox" wire:model.live="columns.no_of_days_covered"
                                        class="h-4 w-4">
                                        <label for="no_of_days_covered" class="ml-2 text-gray-900 dark:text-gray-300">No. of Days Covered</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gross_salary" type="checkbox" wire:model.live="columns.gross_salary"
                                            class="h-4 w-4">
                                        <label for="gross_salary"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Gross Salary</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="absences_days" type="checkbox" wire:model.live="columns.absences_days" class="h-4 w-4">
                                        <label for="absences_days" class="ml-2 text-gray-900 dark:text-gray-300">Absences (Days)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="absences_amount" type="checkbox" wire:model.live="columns.absences_amount" class="h-4 w-4">
                                        <label for="absences_amount" class="ml-2 text-gray-900 dark:text-gray-300">Absences (Amount)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_hours" type="checkbox" wire:model.live="columns.late_undertime_hours"
                                            class="h-4 w-4">
                                        <label for="late_undertime_hours" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Hours)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_hours_amount" type="checkbox" wire:model.live="columns.late_undertime_hours_amount" class="h-4 w-4">
                                        <label for="late_undertime_hours_amount" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Hours -Amount)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_mins" type="checkbox" wire:model.live="columns.late_undertime_mins"
                                            class="h-4 w-4">
                                        <label for="late_undertime_mins" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Minutes)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_mins_amount" type="checkbox" wire:model.live="columns.late_undertime_mins_amount"
                                            class="h-4 w-4">
                                        <label for="late_undertime_mins_amount" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Mins - Amount)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gross_salary_less" type="checkbox" wire:model.live="columns.gross_salary_less" class="h-4 w-4">
                                        <label for="gross_salary_less" class="ml-2 text-gray-900 dark:text-gray-300">Gross Salary (Less)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="withholding_tax" type="checkbox" wire:model.live="columns.withholding_tax" class="h-4 w-4">
                                        <label for="withholding_tax" class="ml-2 text-gray-900 dark:text-gray-300">Withholding Tax</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="nycempc" type="checkbox"
                                            wire:model.live="columns.nycempc" class="h-4 w-4">
                                        <label for="nycempc" class="ml-2 text-gray-900 dark:text-gray-300">NYCEMPC</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="total_deductions" type="checkbox"
                                            wire:model.live="columns.total_deductions" class="h-4 w-4">
                                        <label for="total_deductions"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Total Deduction</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="net_amount_due" type="checkbox"
                                            wire:model.live="columns.net_amount_due" class="h-4 w-4">
                                        <label for="net_amount_due"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Net Amount Due</label>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div> --}}

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
                                Save Payroll
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
                            <div wire:loading wire:target="exportPayroll" style="margin-right: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
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
                                            @if($weekdayRegularHolidays)
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Regular Holiday/s
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Regular Holiday/s (Amount)
                                                </th>
                                            @endif
                                            @if($weekdaySpecialHolidays)
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Special Holiday/s
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                    Special Holiday/s (Amount)
                                                </th>
                                            @endif

                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Leave With Pay
                                            </th>

                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Leave With Pay (Amount)
                                            </th>

                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Gross Salary
                                            </th>

                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Leave Without Pay
                                            </th>

                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Leave Without Pay (Amount)
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
                                                Withholding Tax
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                NYCEMPC
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
                                                    {{ $payroll['employee_number'] ?? '' }}
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
                                                @if($weekdayRegularHolidays)
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ zero_checker($payroll['regular_holidays'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['regular_holidays_amount'] ?? 0) }}
                                                    </td>
                                                @endif
                                                @if($weekdaySpecialHolidays)
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ zero_checker($payroll['special_holidays'] ?? 0) }}
                                                    </td>
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        {{ currency_format($payroll['special_holidays_amount'] ?? 0) }}
                                                    </td>
                                                @endif
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ zero_checker($payroll['leave_days_withpay'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ currency_format($payroll['leave_payment'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ currency_format($payroll['gross_salary'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ zero_checker($payroll['leave_days_withoutpay'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ currency_format($payroll['leave_days_withoutpay_amount'] ?? 0) }}
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
                                                    {{ currency_format($payroll['withholding_tax'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ currency_format($payroll['nycempc'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ currency_format($payroll['total_deductions'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ currency_format($payroll['net_amount_due'] ?? 0) }}
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-gray-100 dark:bg-gray-800">
                                                    {{-- <button wire:click="exportPayslip({{ $payroll['user_id'] }})" class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide hover:text-blue-600 focus:outline-none">
                                                        <div wire:loading wire:target="exportPayslip" style="margin-right: 5px">
                                                            <div class="spinner-border small text-primary" role="status">
                                                            </div>
                                                        </div>
                                                        <i class="fas fa-file-export ml-3"></i>
                                                    </button> --}}
                                                    <div class="relative">
                                                        <button wire:click="viewPayroll({{ $payroll['user_id'] }})"
                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5
                                                        -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600
                                                        focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
