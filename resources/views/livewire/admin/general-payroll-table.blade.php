<div class="w-full flex justify-center"
x-data="{ 
    selectedTab: 'plantilla',
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
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'plantilla'">Plantilla Payroll</h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'signatories'">Manage Payroll & Payslip Signatories</h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'export'">
                    General Payroll for the month of 
                    {{ $startMonth ? \Carbon\Carbon::parse($startMonth)->format('F') : '' }} 
                    @if(\Carbon\Carbon::parse($startMonth)->format('Y') != \Carbon\Carbon::parse($endMonth)->format('Y'))
                        {{ $startMonth ? \Carbon\Carbon::parse($startMonth)->format('Y') : '' }} - 
                        {{ $endMonth ? \Carbon\Carbon::parse($endMonth)->format('F') : '' }} 
                        {{ $endMonth ? \Carbon\Carbon::parse($endMonth)->format('Y') : '' }}
                    @elseif($endMonth)
                        - {{ $endMonth ? \Carbon\Carbon::parse($endMonth)->format('F') : '' }} 
                        {{ $startMonth ? \Carbon\Carbon::parse($startMonth)->format('Y') : '' }}
                    @else
                        {{ $startMonth ? \Carbon\Carbon::parse($startMonth)->format('Y') : '' }}
                    @endif
                </h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row md:flex-col lg:flex-row items-end justify-between md:items-start lg:items-end">

                {{-- Search Plantilla Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'plantilla'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                 {{-- Search General Input --}}
                <div class="w-full lg:w-1/4 sm:mr-4 md:w-full" x-show="selectedTab === 'export'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>
                
                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'plantilla'">

                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddPayroll" 
                            class="text-sm mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Add Plantilla Payroll
                        </button>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="w-full sm:w-auto relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-2 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Filter Column
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>

                        <div x-show="open"
                            class="absolute top-14 z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg 
                            shadow-2xl dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Columns</h6>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center" wire:click='toggleAllPayrollColumn'>
                                    <input id="allCol" type="checkbox" wire:model.live="allCol"
                                        class="h-4 w-4">
                                    <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Select All</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="employee_number" type="checkbox" wire:model.live="payrollColumns.employee_number"
                                        class="h-4 w-4">
                                    <label for="employee_number" class="ml-2 text-gray-900 dark:text-gray-300">Employee Number</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="office_division" type="checkbox" wire:model.live="payrollColumns.office_division"
                                        class="h-4 w-4">
                                    <label for="office_division" class="ml-2 text-gray-900 dark:text-gray-300">Office/Division</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="position" type="checkbox" wire:model.live="payrollColumns.position"
                                        class="h-4 w-4">
                                    <label for="position" class="ml-2 text-gray-900 dark:text-gray-300">Position</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="sg_step" type="checkbox" wire:model.live="payrollColumns.sg_step" class="h-4 w-4">
                                    <label for="sg_step" class="ml-2 text-gray-900 dark:text-gray-300">SG/STEP</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="citizenship" type="checkbox" wire:model.live="payrollColumns.rate_per_month"
                                        class="h-4 w-4">
                                    <label for="citizenship"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Rate per Month</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="personal_economic_relief_allowance" type="checkbox" wire:model.live="payrollColumns.personal_economic_relief_allowance"
                                    class="h-4 w-4">
                                    <label for="personal_economic_relief_allowance" class="ml-2 text-gray-900 dark:text-gray-300">Personal Economic Relief Allowance</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="gross_amount" type="checkbox" wire:model.live="payrollColumns.gross_amount"
                                        class="h-4 w-4">
                                    <label for="gross_amount"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Gross Amount</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="additional_gsis_premium" type="checkbox" wire:model.live="payrollColumns.additional_gsis_premium" class="h-4 w-4">
                                    <label for="additional_gsis_premium" class="ml-2 text-gray-900 dark:text-gray-300">Additional GSIS Premium</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="lbp_salary_loan" type="checkbox" wire:model.live="payrollColumns.lbp_salary_loan" class="h-4 w-4">
                                    <label for="lbp_salary_loan" class="ml-2 text-gray-900 dark:text-gray-300">LBP Salary Loan</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="nycea_deductions" type="checkbox" wire:model.live="payrollColumns.nycea_deductions"
                                        class="h-4 w-4">
                                    <label for="nycea_deductions" class="ml-2 text-gray-900 dark:text-gray-300">NYCEA Deductions</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="sc_membership" type="checkbox" wire:model.live="payrollColumns.sc_membership" class="h-4 w-4">
                                    <label for="sc_membership" class="ml-2 text-gray-900 dark:text-gray-300">SC Membership</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="total_loans" type="checkbox" wire:model.live="payrollColumns.total_loans"
                                        class="h-4 w-4">
                                    <label for="total_loans" class="ml-2 text-gray-900 dark:text-gray-300">Total Loans</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="salary_loan" type="checkbox" wire:model.live="payrollColumns.salary_loan"
                                        class="h-4 w-4">
                                    <label for="salary_loan" class="ml-2 text-gray-900 dark:text-gray-300">Salary Loan</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="policy_loan" type="checkbox" wire:model.live="payrollColumns.policy_loan" class="h-4 w-4">
                                    <label for="policy_loan" class="ml-2 text-gray-900 dark:text-gray-300">Policy Loan</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="eal" type="checkbox" wire:model.live="payrollColumns.eal" class="h-4 w-4">
                                    <label for="eal" class="ml-2 text-gray-900 dark:text-gray-300">EAL</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="emergency_loan" type="checkbox"
                                        wire:model.live="payrollColumns.emergency_loan" class="h-4 w-4">
                                    <label for="emergency_loan" class="ml-2 text-gray-900 dark:text-gray-300">Emergency Loan</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="mpl" type="checkbox"
                                        wire:model.live="payrollColumns.mpl" class="h-4 w-4">
                                    <label for="mpl"
                                        class="ml-2 text-gray-900 dark:text-gray-300">MPL</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="housing_loan" type="checkbox"
                                        wire:model.live="payrollColumns.housing_loan" class="h-4 w-4">
                                    <label for="housing_loan"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Housing Loan</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="ouli_prem" type="checkbox"
                                        wire:model.live="payrollColumns.ouli_prem" class="h-4 w-4">
                                    <label for="ouli_prem"
                                        class="ml-2 text-gray-900 dark:text-gray-300">OULI PREM</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="gfal" type="checkbox" wire:model.live="payrollColumns.gfal"
                                        class="h-4 w-4">
                                    <label for="gfal" class="ml-2 text-gray-900 dark:text-gray-300">GFAL</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="cpl" type="checkbox"
                                        wire:model.live="payrollColumns.cpl" class="h-4 w-4">
                                    <label for="cpl"
                                        class="ml-2 text-gray-900 dark:text-gray-300">CPL</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="pagibig_mpl" type="checkbox"
                                        wire:model.live="payrollColumns.pagibig_mpl" class="h-4 w-4">
                                    <label for="pagibig_mpl"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Pag-Ibig MPL</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="other_deduction_philheath_diff" type="checkbox"
                                        wire:model.live="payrollColumns.other_deduction_philheath_diff" class="h-4 w-4">
                                    <label for="other_deduction_philheath_diff"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Other Deduction Philhealth Differencial</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="life_retirement_insurance_premiums" type="checkbox"
                                        wire:model.live="payrollColumns.life_retirement_insurance_premiums" class="h-4 w-4">
                                    <label for="life_retirement_insurance_premiums"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Life Retirement Insurance Premiums</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="pagibig_contribution" type="checkbox" wire:model.live="payrollColumns.pagibig_contribution"
                                        class="h-4 w-4">
                                    <label for="pagibig_contribution"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Pag-Ibig Contribution</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="w_holding_tax" type="checkbox"
                                        wire:model.live="payrollColumns.w_holding_tax" class="h-4 w-4">
                                    <label for="w_holding_tax"
                                        class="ml-2 text-gray-900 dark:text-gray-300">W Holding Tax</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="philhealth" type="checkbox"
                                        wire:model.live="payrollColumns.philhealth" class="h-4 w-4">
                                    <label for="philhealth"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Philhealth</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="total_deduction" type="checkbox"
                                        wire:model.live="payrollColumns.total_deduction" class="h-4 w-4">
                                    <label for="total_deduction"
                                        class="ml-2 text-gray-900 dark:text-gray-300">Total Deduction</label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Export to Excel -->
                    <div class="relative inline-block text-left">
                        <button wire:click="exportPlantillaExcel"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button"  title="Export Payroll">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                            <div wire:loading wire:target="exportPlantillaExcel" style="margin-left: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="w-full sm:w-3/4 md:w-full flex flex-col sm:flex-row sm:justify-end sm:space-x-4 md:mt-8 md:items-end" x-show="selectedTab === 'export'">

                    <div class="w-full sm:w-auto relative mr-0 sm:mr-4 mt-4 sm:mt-0"  style="width: 50px;">
                        <div class="relative sm:absolute sm:bottom-0 sm:left-0 flex gap-2 items-center pb-4 sm:pb-0">
                            <input id="range" type="checkbox" wire:model.live='monthRange'>
                            <label for="range" class="text-sm">Range?</label>
                        </div>
                    </div>

                    <!-- Select Start Date -->
                    <div class="w-full sm:w-auto relative">
                        <label for="startMonth" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Select {{ $monthRange ? 'Start' : '' }} Month
                        </label>
                        <input type="month" id="startMonth" wire:model.live='startMonth' value="{{ $startMonth }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800" placeholder="Select month...">
                    </div>

                    <!-- Select End Date -->
                    <div class="w-full sm:w-auto relative {{ $monthRange ? '' : 'hidden' }} mt-4 sm:mt-0">
                        <label for="endMonth" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Select End Month</label>
                        <input type="month" id="endMonth" wire:model.live='endMonth' value="{{ $endMonth }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="mr-0 sm:mr-4 relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Filter Column
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>
         
                        <div x-show="open"
                            class="absolute top-12 z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg 
                            shadow-2xl dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Columns</h6>
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
                            type="button" title="Export Payroll">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                            <div wire:loading wire:target="exportExcel" style="margin-left: 5px">
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
                    <button @click="selectedTab = 'plantilla'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'plantilla', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'plantilla' }" 
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
                <div class="overflow-x-auto">
                    <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                        <div x-show="selectedTab === 'plantilla'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            @foreach($payrollColumns as $column => $visible)
                                                @if($visible)
                                                    <th scope="col" class="px-5 py-3 {{ $column == 'name' ? 'text-left' : 'text-center' }} text-sm font-medium text-left uppercase">
                                                        @if($column == 'emp_code')
                                                            EMPLOYEE NUMBER
                                                        @else
                                                            {{ ucwords(str_replace('_', ' ', $column)) }}
                                                        @endif
                                                    </th>
                                                @endif
                                            @endforeach
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                            @foreach($payrolls as $payroll)
                                                <tr class="text-neutral-800 dark:text-neutral-200">
                                                    @foreach($payrollColumns as $column => $visible)
                                                        @if($visible)
                                                            <td class="px-5 py-4 {{ $column == 'name' ? 'text-left' : 'text-center' }} text-sm font-medium whitespace-nowrap">
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
                                                        <div class="relative">
                                                            <button wire:click="toggleEditPayroll({{ $payroll->user_id }})" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                            <button wire:click="toggleDelete({{ $payroll->user_id }})" 
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
                                @if ($payrolls->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $payrolls->links() }}
                            </div>
                        </div>
                        <div x-show="selectedTab === 'export'">
                            <div class="overflow-x-auto">

                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium text-left uppercase {{ $columns['name'] ? '' : 'hidden' }}">Name</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['emp_code'] ? '' : 'hidden' }}">Employee Number</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['office_division'] ? '' : 'hidden' }}">Office/Division</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['position'] ? '' : 'hidden' }}">Position</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['sg_step'] ? '' : 'hidden' }}">SG Step</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['rate_per_month'] ? '' : 'hidden' }}">Rate Per Month</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['personal_economic_relief_allowance'] ? '' : 'hidden' }}">PERA</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['gross_amount'] ? '' : 'hidden' }}">Gross Amount</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['additional_gsis_premium'] ? '' : 'hidden' }}">Additional GSIS Premium</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['lbp_salary_loan'] ? '' : 'hidden' }}">LBP Salary Loan</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['nycea_deductions'] ? '' : 'hidden' }}">NYCEA Deductions</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['sc_membership'] ? '' : 'hidden' }}">SC Membership</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['total_loans'] ? '' : 'hidden' }}">Total Loans</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['salary_loan'] ? '' : 'hidden' }}">Salary Loan</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['policy_loan'] ? '' : 'hidden' }}">Policy Loan</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['eal'] ? '' : 'hidden' }}">EAL</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['emergency_loan'] ? '' : 'hidden' }}">Emergency Loan</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['mpl'] ? '' : 'hidden' }}">MPL</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['housing_loan'] ? '' : 'hidden' }}">Housing Loan</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['ouli_prem'] ? '' : 'hidden' }}">OULI Prem</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['gfal'] ? '' : 'hidden' }}">GFAL</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['cpl'] ? '' : 'hidden' }}">CPL</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['pagibig_mpl'] ? '' : 'hidden' }}">Pag-IBIG MPL</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['other_deduction_philheath_diff'] ? '' : 'hidden' }}">Other Deduction PhilHealth Diff</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['life_retirement_insurance_premiums'] ? '' : 'hidden' }}">Life Retirement Insurance Premiums</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['pagibig_contribution'] ? '' : 'hidden' }}">Pag-IBIG Contribution</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['w_holding_tax'] ? '' : 'hidden' }}">Withholding Tax</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['philhealth'] ? '' : 'hidden' }}">PhilHealth</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['total_deduction'] ? '' : 'hidden' }}">Total Deduction</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['net_amount_received'] ? '' : 'hidden' }}">Net Amount Received</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['amount_due_first_half'] ? '' : 'hidden' }}">
                                                Amount Due <br>
                                                ({{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('F') : '' }} 
                                                {{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('d') : '' }} - {{ $endDateFirstHalf ? \Carbon\Carbon::parse($endDateFirstHalf)->format('d') : '' }}
                                                {{ $startDateFirstHalf ? \Carbon\Carbon::parse($startDateFirstHalf)->format('Y') : '' }})
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase {{ $columns['amount_due_second_half'] ? '' : 'hidden' }}">
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
                                        @foreach($genPayrolls as $payroll)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap {{ $columns['name'] ? '' : 'hidden' }}">{{ $payroll->name }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['emp_code'] ? '' : 'hidden' }}">{{ $payroll->emp_code }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['office_division'] ? '' : 'hidden' }}">{{ $payroll->office_division }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['position'] ? '' : 'hidden' }}">{{ $payroll->position }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['sg_step'] ? '' : 'hidden' }}">{{ $payroll->sg_step }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['rate_per_month'] ? '' : 'hidden' }}">{{ currency_format($payroll->rate_per_month) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['personal_economic_relief_allowance'] ? '' : 'hidden' }}">{{ currency_format($payroll->personal_economic_relief_allowance) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['gross_amount'] ? '' : 'hidden' }}">{{ currency_format($payroll->gross_amount) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['additional_gsis_premium'] ? '' : 'hidden' }}">{{ currency_format($payroll->additional_gsis_premium) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['lbp_salary_loan'] ? '' : 'hidden' }}">{{ currency_format($payroll->lbp_salary_loan) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['nycea_deductions'] ? '' : 'hidden' }}">{{ currency_format($payroll->nycea_deductions) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['sc_membership'] ? '' : 'hidden' }}">{{ currency_format($payroll->sc_membership) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['total_loans'] ? '' : 'hidden' }}">{{ currency_format($payroll->total_loans) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['salary_loan'] ? '' : 'hidden' }}">{{ currency_format($payroll->salary_loan) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['policy_loan'] ? '' : 'hidden' }}">{{ currency_format($payroll->policy_loan) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['eal'] ? '' : 'hidden' }}">{{ currency_format($payroll->eal) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['emergency_loan'] ? '' : 'hidden' }}">{{ currency_format($payroll->emergency_loan) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['mpl'] ? '' : 'hidden' }}">{{ currency_format($payroll->mpl) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['housing_loan'] ? '' : 'hidden' }}">{{ currency_format($payroll->housing_loan) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['ouli_prem'] ? '' : 'hidden' }}">{{ currency_format($payroll->ouli_prem) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['gfal'] ? '' : 'hidden' }}">{{ currency_format($payroll->gfal) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['cpl'] ? '' : 'hidden' }}">{{ currency_format($payroll->cpl) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['pagibig_mpl'] ? '' : 'hidden' }}">{{ currency_format($payroll->pagibig_mpl) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['other_deduction_philheath_diff'] ? '' : 'hidden' }}">{{ currency_format($payroll->other_deduction_philheath_diff) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['life_retirement_insurance_premiums'] ? '' : 'hidden' }}">{{ currency_format($payroll->life_retirement_insurance_premiums) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['pagibig_contribution'] ? '' : 'hidden' }}">{{ currency_format($payroll->pagibig_contribution) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['w_holding_tax'] ? '' : 'hidden' }}">{{ currency_format($payroll->w_holding_tax) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['philhealth'] ? '' : 'hidden' }}">{{ currency_format($payroll->philhealth) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $columns['total_deduction'] ? '' : 'hidden' }}">{{ currency_format($payroll->total_deduction) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->total_amount_due) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->net_amount_due_first_half) }}</td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">{{ currency_format($payroll->net_amount_due_second_half) }}</td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="viewPayroll({{ $payroll->user_id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                    <div class="relative">
                                                        <button wire:click="exportPayslip({{ $payroll->user_id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 
                                                            text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                            title="Export Payslip">
                                                            <i class="fas fa-file-export ml-4"></i>
                                                            <div wire:loading wire:target="exportPayslip({{ $payroll->user_id }})" style="margin-left: 5px">
                                                                <div class="spinner-border small text-primary" role="status">
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($genPayrolls->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        Select a date!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{-- {{ $payrolls->links() ?? '' }} --}}
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
                            {{-- Plantilla Payroll View --}}
                            <div x-show="selectedSubTab === 'payroll'">
                                <div class="overflow-hidden mt-10">
                                    <div class="pb-4 mb-3 pt-4 sm:pt-0">
                                        <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Plantilla Payroll Footer View</h1>
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
                                                            @if($plantillaPayroll['a'])
                                                                <img src="{{ $plantillaPayroll['a']->signature ? route('signature.file', basename($plantillaPayroll['a']->signature)) : '/images/signature.png' }}"
                                                                    alt="signature" title="{{ $plantillaPayroll['a']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                    class="cursor-pointer {{ $plantillaPayroll['a']->signature ? '-mb-4' : '' }}" onclick="document.getElementById('plantillaPayroll_A').click()"
                                                                    style="height: {{ $plantillaPayroll['a']->signature ? '60px' : '30px' }}; width: auto;">
                                                                    <input type="file" id="plantillaPayroll_A" 
                                                                        wire:model="signatures.{{ $plantillaPayroll['a']->id }}" 
                                                                        style="display: none;" 
                                                                        accept="image/*">
                                                                <div wire:loading wire:target="signatures.{{ $plantillaPayroll['a']->id }}" style="margin-left: 5px">
                                                                    <div class="spinner-border small text-primary" role="status">
                                                                    </div>
                                                                </div>
                                                                @error('signatures.' . $plantillaPayroll['a']->id) <span class="error">{{ $message }}</span> @enderror
                                                            @endif
                                                            <p class="text-center font-bold text-sm">{{ $plantillaPayroll['a'] ? $plantillaPayroll['a']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $plantillaPayroll['a'] ? $plantillaPayroll['a']->position : 'Position' }}</p>
                                                        </div>
                                                        <div class="flex flex flex-col items-center justify-end w-1/5">
                                                            <p class="text-center underline">01/01/2024</p>
                                                            <p class="text-center">Date</p>
                                                        </div>
                                                    </div>
                                                    <div class="absolute right-0 top-0 p-2 bg-white">
                                                        @if($plantillaPayroll['a'])
                                                            <button wire:click="toggleEditSignatory({{ $plantillaPayroll['a']->user_id }}, 'plantilla_payroll')" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i title="Edit" class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                        @else
                                                            <button wire:click="toggleAddSignatory('plantilla_payroll', 'A')" 
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
                                                        <p class="pl-6 font-bold">PHP 4,884.16</p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            @if($plantillaPayroll['b'])
                                                                <img src="{{ $plantillaPayroll['b']->signature ? route('signature.file', basename($plantillaPayroll['b']->signature)) : '/images/signature.png' }}"
                                                                    alt="signature" title="{{ $plantillaPayroll['b']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                    class="cursor-pointer {{ $plantillaPayroll['b']->signature ? '-mb-4' : '' }}" onclick="document.getElementById('plantillaPayroll_B').click()"
                                                                    style="height: {{ $plantillaPayroll['b']->signature ? '60px' : '30px' }}; width: auto;">
                                                                <input type="file" id="plantillaPayroll_B" 
                                                                    wire:model="signatures.{{ $plantillaPayroll['b']->id }}" 
                                                                    style="display: none;" 
                                                                    accept="image/*">
                                                                <div wire:loading wire:target="signatures.{{ $plantillaPayroll['b']->id }}" style="margin-left: 5px">
                                                                    <div class="spinner-border small text-primary" role="status">
                                                                    </div>
                                                                </div>
                                                                @error('signatures.' . $plantillaPayroll['b']->id) <span class="error">{{ $message }}</span> @enderror
                                                            @endif
                                                            <p class="text-center font-bold text-sm">{{ $plantillaPayroll['b'] ? $plantillaPayroll['b']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $plantillaPayroll['b'] ? $plantillaPayroll['b']->position : 'Position' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="absolute right-0 top-0 p-2 bg-white">
                                                        @if($plantillaPayroll['b'])
                                                            <button wire:click="toggleEditSignatory({{ $plantillaPayroll['b']->user_id }}, 'plantilla_payroll')" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                        @else
                                                            <button wire:click="toggleAddSignatory('plantilla_payroll', 'B')" 
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
                                                        <p class="pl-6 font-bold">PHP 4,884.16</p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            @if($plantillaPayroll['c'])
                                                                <img src="{{ $plantillaPayroll['c']->signature ? route('signature.file', basename($plantillaPayroll['c']->signature)) : '/images/signature.png' }}"
                                                                    alt="signature" title="{{ $plantillaPayroll['c']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                    class="cursor-pointer {{ $plantillaPayroll['c']->signature ? '-mb-4' : '' }}" onclick="document.getElementById('plantillaPayroll_C').click()"
                                                                    style="height: {{ $plantillaPayroll['c']->signature ? '60px' : '30px' }}; width: auto;">
                                                                <input type="file" id="plantillaPayroll_C" 
                                                                    wire:model="signatures.{{ $plantillaPayroll['c']->id }}" 
                                                                    style="display: none;" 
                                                                    accept="image/*">
                                                                <div wire:loading wire:target="signatures.{{ $plantillaPayroll['c']->id }}" style="margin-left: 5px">
                                                                    <div class="spinner-border small text-primary" role="status">
                                                                    </div>
                                                                </div>
                                                                @error('signatures.' . $plantillaPayroll['c']->id) <span class="error">{{ $message }}</span> @enderror
                                                            @endif
                                                            <p class="text-center font-bold text-sm">{{ $plantillaPayroll['c'] ? $plantillaPayroll['c']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $plantillaPayroll['c'] ? $plantillaPayroll['c']->position : 'Position' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="absolute right-0 top-0 p-2 bg-white">
                                                        @if($plantillaPayroll['c'])
                                                            <button wire:click="toggleEditSignatory({{ $plantillaPayroll['c']->user_id }}, 'plantilla_payroll')" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                        @else
                                                            <button wire:click="toggleAddSignatory('plantilla_payroll', 'C')" 
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
                                                        <p class="pl-2"></p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            @if($plantillaPayroll['d'])
                                                                <img src="{{ $plantillaPayroll['d']->signature ? route('signature.file', basename($plantillaPayroll['d']->signature)) : '/images/signature.png' }}"
                                                                    alt="signature" title="{{ $plantillaPayroll['d']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                                    class="cursor-pointer {{ $plantillaPayroll['d']->signature ? '-mb-4' : '' }}" onclick="document.getElementById('plantillaPayroll_D').click()"
                                                                    style="height: {{ $plantillaPayroll['d']->signature ? '60px' : '30px' }}; width: auto;">
                                                                <input type="file" id="plantillaPayroll_D" 
                                                                    wire:model="signatures.{{ $plantillaPayroll['d']->id }}" 
                                                                    style="display: none;" 
                                                                    accept="image/*">
                                                                <div wire:loading wire:target="signatures.{{ $plantillaPayroll['d']->id }}" style="margin-left: 5px">
                                                                    <div class="spinner-border small text-primary" role="status">
                                                                    </div>
                                                                </div>
                                                                @error('signatures.' . $plantillaPayroll['d']->id) <span class="error">{{ $message }}</span> @enderror
                                                            @endif
                                                            <p class="text-center font-bold text-sm">{{ $plantillaPayroll['d'] ? $plantillaPayroll['d']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $plantillaPayroll['d'] ? $plantillaPayroll['d']->position : 'Position' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="absolute right-0 top-0 p-2 bg-white">
                                                        @if($plantillaPayroll['d'])
                                                            <button wire:click="toggleEditSignatory({{ $plantillaPayroll['d']->user_id }}, 'plantilla_payroll')" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                        @else
                                                            <button wire:click="toggleAddSignatory('plantilla_payroll', 'D')" 
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
                            {{-- Plantilla Payslip View --}}
                            <div x-show="selectedSubTab === 'payslip'">
                                <div class="overflow-hidden mt-10">
                                    <div class="pb-4 mb-3 pt-4 sm:pt-0">
                                        <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Platilla Payslip Footer View</h1>
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
                                                        @if($plantillaPayslipSigns['notedBy'])
                                                            <button wire:click="toggleEditPayslipSignatory({{ $plantillaPayslipSigns['notedBy']->user_id }}, 'plantilla_payslip')" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                        @else
                                                            <button wire:click="toggleAddPayslipSignatory('Noted By', 'plantilla_payslip')" 
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
                                                    @if($preparedBySignature)
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
                                                    @endif
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
                                                    @if($plantillaPayslipSigns['notedBy'])
                                                        <img src="{{ $plantillaPayslipSigns['notedBy']->signature ? route('signature.file', basename($plantillaPayslipSigns['notedBy']->signature)) : '/images/signature.png' }}"
                                                            alt="signature" title="{{ $plantillaPayslipSigns['notedBy']->signature ? 'Edit Signature' : 'Add Signature' }}"
                                                            class="cursor-pointer {{ $plantillaPayslipSigns['notedBy']->signature ? '-mb-4' : '' }}" 
                                                            onclick="document.getElementById('plantillaPayslip_NotedBy').click()"
                                                            style="height: {{ $plantillaPayslipSigns['notedBy']->signature ? '60px' : '30px' }}; width: auto;">
                                                            <input type="file" id="plantillaPayslip_NotedBy" 
                                                                wire:model="signatures.{{ $plantillaPayslipSigns['notedBy']->id }}" 
                                                                style="display: none;" 
                                                                accept="image/*">
                                                        <div wire:loading wire:target="signatures.{{ $plantillaPayslipSigns['notedBy']->id }}" style="margin-left: 5px">
                                                            <div class="spinner-border small text-primary" role="status">
                                                            </div>
                                                        </div>
                                                        @error('signatures.' . $plantillaPayslipSigns['notedBy']->id) <span class="error">{{ $message }}</span> @enderror
                                                    @endif
                                                </div>
                                                <div class="w-1/4"></div>
                                            </div>
                                            <div class="flex mt-6">
                                                <div class="w-1/4 font-bold text-sm">{{ $preparedBy->name }}</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4 font-bold text-sm">{{ $plantillaPayslipSigns['notedBy'] ? $plantillaPayslipSigns['notedBy']->name : 'XXXXXXXXXX' }}</div>
                                                <div class="w-1/4"></div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-1/4">{{ $preparedBy->position }}</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4">{{ $plantillaPayslipSigns['notedBy'] ? $plantillaPayslipSigns['notedBy']->position : 'Position' }}</div>
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

    {{-- Add and Edit Plantilla Payroll Modal --}}
    <x-modal id="payroll" maxWidth="2xl" wire:model="editPayroll">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addPayroll ? 'Add' : 'Edit' }} Payroll
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='savePayroll'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-2">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model.live='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addPayroll ? '' : 'disabled' }}>
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
                        <label for="employee_number" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Number</label>
                        <input type="text" id="employee_number" wire:model='employee_number' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('employee_number') 
                            <span class="text-red-500 text-sm">The employee number is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="office_division" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Office/Division</label>
                        <input type="text" id="office_division" wire:model='office_division' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('office_division') 
                            <span class="text-red-500 text-sm">The office/division is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                        <input type="text" id="position" wire:model='position' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('position') 
                            <span class="text-red-500 text-sm">The position is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
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

                    <div class="col-span-1">
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
                    </div>

                    <div class="col-span-1">
                        <label for="rate_per_month" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Rate per Month</label>
                        <input type="number" step="0.01" id="rate_per_month" wire:model='rate_per_month' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('rate_per_month') 
                            <span class="text-red-500 text-sm">The rate per month is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="personal_economic_relief_allowance" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Personal Economic Relief Allowance</label>
                        <input type="number" step="0.01" id="personal_economic_relief_allowance" wire:model.live='personal_economic_relief_allowance' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="gross_amount" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Gross Amount</label>
                        <input type="number" step="0.01" id="gross_amount" wire:model='gross_amount' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                        @error('gross_amount') 
                            <span class="text-red-500 text-sm">The gross amount is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="additional_gsis_premium" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Additional GSIS Premium</label>
                        <input type="number" step="0.01" id="additional_gsis_premium" wire:model='additional_gsis_premium' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="lbp_salary_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">LBP Salary Loan</label>
                        <input type="number" step="0.01" id="lbp_salary_loan" wire:model='lbp_salary_loan' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="nycea_deductions" class="block text-sm font-medium text-gray-700 dark:text-slate-400">NYCEA Deductions</label>
                        <input type="number" step="0.01" id="nycea_deductions" wire:model='nycea_deductions' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="sc_membership" class="block text-sm font-medium text-gray-700 dark:text-slate-400">SC Membership</label>
                        <input type="number" step="0.01" id="sc_membership" wire:model='sc_membership' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="total_loans" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Total Loans</label>
                        <input type="number" step="0.01" id="total_loans" wire:model='total_loans' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="salary_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Salary Loan</label>
                        <input type="number" step="0.01" id="salary_loan" wire:model='salary_loan' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="policy_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Policy Loan</label>
                        <input type="number" step="0.01" id="policy_loan" wire:model='policy_loan' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="eal" class="block text-sm font-medium text-gray-700 dark:text-slate-400">EAL</label>
                        <input type="number" step="0.01" id="eal" wire:model='eal' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="emergency_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Emergency Loan</label>
                        <input type="number" step="0.01" id="emergency_loan" wire:model='emergency_loan' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="mpl" class="block text-sm font-medium text-gray-700 dark:text-slate-400">MPL</label>
                        <input type="number" step="0.01" id="mpl" wire:model='mpl' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="housing_loan" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Housing Loan</label>
                        <input type="number" step="0.01" id="housing_loan" wire:model='housing_loan' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="ouli_prem" class="block text-sm font-medium text-gray-700 dark:text-slate-400">OULI Premium</label>
                        <input type="number" step="0.01" id="ouli_prem" wire:model='ouli_prem' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="gfal" class="block text-sm font-medium text-gray-700 dark:text-slate-400">GFAL</label>
                        <input type="number" step="0.01" id="gfal" wire:model='gfal' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
    
                    <div class="col-span-1">
                        <label for="cpl" class="block text-sm font-medium text-gray-700 dark:text-slate-400">CPL</label>
                        <input type="number" step="0.01" id="cpl" wire:model='cpl' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="pagibig_mpl" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Pag-Ibig MPL</label>
                        <input type="number" step="0.01" id="pagibig_mpl" wire:model='pagibig_mpl' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="other_deduction_philheath_diff" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Other Deduction Philheath Differential</label>
                        <input type="number" step="0.01" id="other_deduction_philheath_diff" wire:model='other_deduction_philheath_diff' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    
                    <div class="col-span-1">
                        <label for="life_retirement_insurance_premiums" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Life Retirement Insurance Premiums</label>
                        <input type="number" step="0.01" id="life_retirement_insurance_premiums" wire:model='life_retirement_insurance_premiums' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>
                    
                    <div class="col-span-1">
                        <label for="pagibig_contribution" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Pag-Ibig Contribution</label>
                        <input type="number" step="0.01" id="pagibig_contribution" wire:model='pagibig_contribution' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('pagibig_contribution') 
                            <span class="text-red-500 text-sm">The Pag-Ibig contribution is required!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label for="w_holding_tax" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Withholding Tax</label>
                        <input type="number" step="0.01" id="w_holding_tax" wire:model='w_holding_tax' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('w_holding_tax') 
                            <span class="text-red-500 text-sm">The withholding tax is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="philhealth" class="block text-sm font-medium text-gray-700 dark:text-slate-400">PhilHealth</label>
                        <input type="number" step="0.01" id="philhealth" wire:model='philhealth' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('philhealth') 
                            <span class="text-red-500 text-sm">The philhealth is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="total_deduction" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Total Deduction</label>
                        <input type="number" step="0.01" id="total_deduction" wire:model='total_deduction' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('total_deduction') 
                            <span class="text-red-500 text-sm">The total deduction is required!</span> 
                        @enderror
                    </div>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="savePayroll" style="margin-right: 5px">
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
</div>