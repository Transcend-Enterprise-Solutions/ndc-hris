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
            color: white;
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    General Payroll for the month of {{ $date ? \Carbon\Carbon::parse($date)->format('F') : '' }} {{ $date ? \Carbon\Carbon::parse($date)->format('Y') : '' }}
                </h1>
                {{-- <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d') : '' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d') : '' }}
                </h1> --}}
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

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">

                    <!-- Select Date -->
                    <div class="w-full sm:w-auto relative">
                        <label for="date" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Select Date</label>
                        <input type="month" id="date" wire:model.live='date' value="{{ $date }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                    {{-- <!-- Sort Dropdown -->
                    <div class="mr-0 sm:mr-4">
                        <button wire:click="toggleDropdown"
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-2 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Sort Column
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>
                        @if($sortColumn)
                            <div
                                class="absolute top-12 z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg 
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
                                        <input id="sg_step" type="checkbox" wire:model.live="columns.sg_step" class="h-4 w-4">
                                        <label for="sg_step" class="ml-2 text-gray-900 dark:text-gray-300">SG/STEP</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="citizenship" type="checkbox" wire:model.live="columns.rate_per_month"
                                            class="h-4 w-4">
                                        <label for="citizenship"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Rate per Month</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="personal_economic_relief_allowance" type="checkbox" wire:model.live="columns.personal_economic_relief_allowance"
                                        class="h-4 w-4">
                                        <label for="personal_economic_relief_allowance" class="ml-2 text-gray-900 dark:text-gray-300">Personal Economic Relief Allowance</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gross_amount" type="checkbox" wire:model.live="columns.gross_amount"
                                            class="h-4 w-4">
                                        <label for="gross_amount"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Gross Amount</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="additional_gsis_premium" type="checkbox" wire:model.live="columns.additional_gsis_premium" class="h-4 w-4">
                                        <label for="additional_gsis_premium" class="ml-2 text-gray-900 dark:text-gray-300">Additional GSIS Premium</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="lbp_salary_loan" type="checkbox" wire:model.live="columns.lbp_salary_loan" class="h-4 w-4">
                                        <label for="lbp_salary_loan" class="ml-2 text-gray-900 dark:text-gray-300">LBP Salary Loan</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="nycea_deductions" type="checkbox" wire:model.live="columns.nycea_deductions"
                                            class="h-4 w-4">
                                        <label for="nycea_deductions" class="ml-2 text-gray-900 dark:text-gray-300">NYCEA Deductions</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="sc_membership" type="checkbox" wire:model.live="columns.sc_membership" class="h-4 w-4">
                                        <label for="sc_membership" class="ml-2 text-gray-900 dark:text-gray-300">SC Membership</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="total_loans" type="checkbox" wire:model.live="columns.total_loans"
                                            class="h-4 w-4">
                                        <label for="total_loans" class="ml-2 text-gray-900 dark:text-gray-300">Total Loans</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="salary_loan" type="checkbox" wire:model.live="columns.salary_loan"
                                            class="h-4 w-4">
                                        <label for="salary_loan" class="ml-2 text-gray-900 dark:text-gray-300">Salary Loan</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="policy_loan" type="checkbox" wire:model.live="columns.policy_loan" class="h-4 w-4">
                                        <label for="policy_loan" class="ml-2 text-gray-900 dark:text-gray-300">Policy Loan</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="eal" type="checkbox" wire:model.live="columns.eal" class="h-4 w-4">
                                        <label for="eal" class="ml-2 text-gray-900 dark:text-gray-300">EAL</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="emergency_loan" type="checkbox"
                                            wire:model.live="columns.emergency_loan" class="h-4 w-4">
                                        <label for="emergency_loan" class="ml-2 text-gray-900 dark:text-gray-300">Emergency Loan</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="mpl" type="checkbox"
                                            wire:model.live="columns.mpl" class="h-4 w-4">
                                        <label for="mpl"
                                            class="ml-2 text-gray-900 dark:text-gray-300">MPL</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="housing_loan" type="checkbox"
                                            wire:model.live="columns.housing_loan" class="h-4 w-4">
                                        <label for="housing_loan"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Housing Loan</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="ouli_prem" type="checkbox"
                                            wire:model.live="columns.ouli_prem" class="h-4 w-4">
                                        <label for="ouli_prem"
                                            class="ml-2 text-gray-900 dark:text-gray-300">OULI PREM</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gfal" type="checkbox" wire:model.live="columns.gfal"
                                            class="h-4 w-4">
                                        <label for="gfal" class="ml-2 text-gray-900 dark:text-gray-300">GFAL</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="cpl" type="checkbox"
                                            wire:model.live="columns.cpl" class="h-4 w-4">
                                        <label for="cpl"
                                            class="ml-2 text-gray-900 dark:text-gray-300">CPL</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="pagibig_mpl" type="checkbox"
                                            wire:model.live="columns.pagibig_mpl" class="h-4 w-4">
                                        <label for="pagibig_mpl"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Pag-Ibig MPL</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="other_deduction_philheath_diff" type="checkbox"
                                            wire:model.live="columns.other_deduction_philheath_diff" class="h-4 w-4">
                                        <label for="other_deduction_philheath_diff"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Other Deduction Philhealth Differencial</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="life_retirement_insurance_premiums" type="checkbox"
                                            wire:model.live="columns.life_retirement_insurance_premiums" class="h-4 w-4">
                                        <label for="life_retirement_insurance_premiums"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Life Retirement Insurance Premiums</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="pagibig_contribution" type="checkbox" wire:model.live="columns.pagibig_contribution"
                                            class="h-4 w-4">
                                        <label for="pagibig_contribution"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Pag-Ibig Contribution</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="w_holding_tax" type="checkbox"
                                            wire:model.live="columns.w_holding_tax" class="h-4 w-4">
                                        <label for="w_holding_tax"
                                            class="ml-2 text-gray-900 dark:text-gray-300">W Holding Tax</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="philhealth" type="checkbox"
                                            wire:model.live="columns.philhealth" class="h-4 w-4">
                                        <label for="philhealth"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Philhealth</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="total_deduction" type="checkbox"
                                            wire:model.live="columns.total_deduction" class="h-4 w-4">
                                        <label for="total_deduction"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Total Deduction</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="net_amount_received" type="checkbox"
                                            wire:model.live="columns.net_amount_received" class="h-4 w-4">
                                        <label for="net_amount_received"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Net Amount Received</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="amount_due_first_half" type="checkbox"
                                            wire:model.live="columns.amount_due_first_half" class="h-4 w-4">
                                        <label for="amount_due_first_half"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Amount Due (First Half)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="amount_due_second_half" type="checkbox"
                                            wire:model.live="columns.amount_due_second_half" class="h-4 w-4">
                                        <label for="amount_due_second_half"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Amount Due (Second Half)</label>
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
                        <button wire:click="exportExcel"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button" aria-describedby="excelExport">
                            <div wire:loading wire:target="exportExcel" style="margin-right: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                        </button>
                        <div id="excelExport" class="absolute -top-5 left-1/2 -translate-x-1/2 z-10 whitespace-nowrap rounded bg-gray-600 px-2 py-1 text-center text-sm text-white opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 dark:text-black" role="tooltip">Export Payroll</div>
                    </div>

                </div>
                
            </div>

            <!-- Table -->

            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                        <div class="overflow-x-auto">

                            <table class="w-full min-w-full">
                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                    <tr class="whitespace-nowrap">
                                        <th scope="col" class="px-5 py-3 text-left text-sm font-medium text-left uppercase">Name</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Employee Number</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Office/Division</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Position</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">SG Step</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Rate Per Month</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">PERA</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Gross Amount</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Additional GSIS Premium</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">LBP Salary Loan</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">NYCEA Deductions</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">SC Membership</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Total Loans</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Salary Loan</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Policy Loan</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">EAL</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Emergency Loan</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">MPL</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Housing Loan</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">OULI Prem</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">GFAL</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">CPL</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Pag-IBIG MPL</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Other Deduction PhilHealth Diff</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Life Retirement Insurance Premiums</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Pag-IBIG Contribution</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Withholding Tax</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">PhilHealth</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Total Deduction</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">Net Amount Received</th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                            Amount Due <br>
                                            ({{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('F') : '' }} 
                                            {{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('d') : '' }} - {{ $endDateFirstHalf ? \Carbon\Carbon::parse($endDateFirstHalf)->format('d') : '' }}
                                            {{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('Y') : '' }})
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                            Amount Due <br>
                                            ({{ $startDateSecondHalf ? \Carbon\Carbon::parse($startDateSecondHalf)->format('F') : '' }} 
                                            {{ $startDateSecondHalf ? \Carbon\Carbon::parse($startDateSecondHalf)->format('d') : '' }} - {{ $endDateSecondHalf ? \Carbon\Carbon::parse($endDateSecondHalf)->format('d') : '' }}
                                            {{ $startDateSecondHalf ? \Carbon\Carbon::parse($startDateSecondHalf)->format('Y') : '' }})
                                        </th>
                                        <th class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                    @foreach($payrolls as $payroll)
                                        <tr class="text-neutral-800 dark:text-neutral-200">
                                            <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">{{ $payroll->name }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ $payroll->employee_number }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ $payroll->office_division }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ $payroll->position }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ $payroll->sg_step }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->rate_per_month) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->personal_economic_relief_allowance) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->gross_amount) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->additional_gsis_premium) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->lbp_salary_loan) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->nycea_deductions) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->sc_membership) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->total_loans) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->salary_loan) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->policy_loan) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->eal) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->emergency_loan) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->mpl) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->housing_loan) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->ouli_prem) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->gfal) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->cpl) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->pagibig_mpl) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->other_deduction_philheath_diff) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->life_retirement_insurance_premiums) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->pagibig_contribution) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->w_holding_tax) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->philhealth) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->total_deduction) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->total_amount_due) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->net_amount_due_first_half) }}</td>
                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->net_amount_due_second_half) }}</td>
                                            <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                <div class="relative">
                                                    <button wire:click="viewPayroll({{ $payroll->user_id }})" 
                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                        -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                        focus:outline-none" aria-describedby="tooltip1">
                                                        <i class="fas fa-eye ml-3"></i>
                                                    </button>
                                                    <!-- Tooltip Text -->
                                                    <div id="tooltip1" class="absolute top-1/2 right-10 transform -translate-y-1/2 z-10 whitespace-nowrap rounded px-2 py-1 text-center text-sm text-white opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 bg-gray-600 dark:text-black" role="tooltip">View</div>
                                                </div>
                                                <div class="relative">
                                                    <button wire:click="exportPayslip({{ $payroll->user_id }})" 
                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 
                                                        text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                        aria-describedby="tooltip2">
                                                        <i class="fas fa-file-export ml-4"></i>
                                                        <div wire:loading wire:target="exportPayslip({{ $payroll->user_id }})" style="margin-left: 5px">
                                                            <div class="spinner-border small text-primary" role="status">
                                                            </div>
                                                        </div>
                                                    </button>
                                                    <!-- Tooltip Text -->
                                                    <div id="tooltip2" class="absolute top-1/2 right-10 transform -translate-y-1/2 z-10 whitespace-nowrap rounded px-2 py-1 text-center text-sm text-white opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 bg-gray-600 dark:text-black" role="tooltip">Export Payslip</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                        {{-- <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            {{ $payrolls->links() ?? '' }}
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

   {{-- View Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="payroll">
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
                    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="employee_number" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Number: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $employee_number }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Office/Division: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $office_division }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $position }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="sg_step" class="block text-sm font-medium text-gray-700 dark:text-slate-400">SG - Step: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $sg_step }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="rate_per_month" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Rate per Month: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $rate_per_month == 0 ? '-' : currency_format($rate_per_month) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="personal_economic_relief_allowance" class="block text-sm font-medium text-gray-700 dark:text-slate-400">PERA: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $personal_economic_relief_allowance == 0 ? '-' : currency_format($personal_economic_relief_allowance) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="gross_amount" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Gross Amount: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $gross_amount == 0 ? '-' : currency_format($gross_amount) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="additional_gsis_premium" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Additional GSIS Premium: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $additional_gsis_premium == 0 ? '-' : currency_format($additional_gsis_premium) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="lbp_salary_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">LBP Salary Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $lbp_salary_loan == 0 ? '-' : currency_format($lbp_salary_loan) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="nycea_deductions" class="block text-sm font-medium text-gray-700 dark:text-slate-400">NYCEA Deductions: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $nycea_deductions == 0 ? '-' : currency_format($nycea_deductions) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="sc_membership" class="block text-sm font-medium text-gray-700 dark:text-slate-400">SC Membership: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $sc_membership == 0 ? '-' : currency_format($sc_membership) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="total_loans" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Total Loans: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $total_loans == 0 ? '-' : currency_format($total_loans) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="salary_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Salary Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $salary_loan == 0 ? '-' : currency_format($salary_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="policy_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Policy Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $policy_loan == 0 ? '-' : currency_format($policy_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="eal" class="block text-sm font-medium text-gray-700 dark:text-slate-400">EAL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $eal == 0 ? '-' : currency_format($eal) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="emergency_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Emergency Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $emergency_loan == 0 ? '-' : currency_format($emergency_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="mpl" class="block text-sm font-medium text-gray-700 dark:text-slate-400">MPL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $mpl == 0 ? '-' : currency_format($mpl) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="housing_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Housing Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $housing_loan == 0 ? '-' : currency_format($housing_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="ouli_prem" class="block text-sm font-medium text-gray-700 dark:text-slate-400">OULI Premium: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $ouli_prem == 0 ? '-' : currency_format($ouli_prem) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="gfal" class="block text-sm font-medium text-gray-700 dark:text-slate-400">GFAL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $gfal == 0 ? '-' : currency_format($gfal) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="cpl" class="block text-sm font-medium text-gray-700 dark:text-slate-400">CPL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $cpl == 0 ? '-' : currency_format($cpl) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="pagibig_mpl" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Pag-Ibig MPL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $pagibig_mpl == 0 ? '-' : currency_format($pagibig_mpl) }}</p>
                    </div>

                    <div class="col-span-2 border-b border-slate-800 flex">
                        <label for="other_deduction_philheath_diff" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Other Deduction Philheath Differential: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $other_deduction_philheath_diff == 0 ? '-' : currency_format($other_deduction_philheath_diff) }}</p>
                    </div>

                    
                    <div class="col-span-2 border-b border-slate-800 flex">
                        <label for="life_retirement_insurance_premiums" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Life Retirement Insurance Premiums: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $life_retirement_insurance_premiums == 0 ? '-' : currency_format($life_retirement_insurance_premiums) }}</p>
                    </div>
                    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="pagibig_contribution" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Pag-Ibig Contribution: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $pagibig_contribution == 0 ? '-' : currency_format($pagibig_contribution) }}</p>
                    </div>
                    
                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="w_holding_tax" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Withholding Tax: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $w_holding_tax == 0 ? '-' : currency_format($w_holding_tax) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="philhealth" class="block text-sm font-medium text-gray-700 dark:text-slate-400">PhilHealth: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $philhealth == 0 ? '-' : currency_format($philhealth) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="total_deduction" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Total Deduction: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $total_deduction == 0 ? '-' : currency_format($total_deduction) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="amount_due_first_half" class="block text-sm font-medium text-gray-700 dark:text-slate-400">
                            Amount Due 
                            ({{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('F') : '' }} 
                            {{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('d') : '' }} - {{ $endDateFirstHalf ? \Carbon\Carbon::parse($endDateFirstHalf)->format('d') : '' }}
                            {{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('Y') : '' }})
                        : </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($amount_due_first_half) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="amount_due_second_half" class="block text-sm font-medium text-gray-700 dark:text-slate-400">
                            Amount Due 
                            ({{ $startDateSecondHalf ? \Carbon\Carbon::parse($startDateSecondHalf)->format('F') : '' }} 
                            {{ $startDateSecondHalf ? \Carbon\Carbon::parse($startDateSecondHalf)->format('d') : '' }} - {{ $endDateSecondHalf ? \Carbon\Carbon::parse($endDateSecondHalf)->format('d') : '' }}
                            {{ $startDateSecondHalf ? \Carbon\Carbon::parse($startDateSecondHalf)->format('Y') : '' }})
                        : </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($amount_due_second_half) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800 flex">
                        <label for="net_amount_received" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Net Amount Received: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($net_amount_received) }}</p>
                    </div>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="exportPayslip({{ $userId }})">
                            <div wire:loading wire:target="" class="spinner-border small text-primary" role="status">
                            </div>
                            Export
                            <div wire:loading wire:target="exportPayslip({{ $userId }})" style="margin-left: 5px">
                                <div class="spinner-border small text-primary" role="status">
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