<div class="w-full" 
    x-data="{ 
        selectedTab: 'none',
        selectedSubTab: 'plantilla_payroll',
        selectedSubTab2: 'plantilla_payslip'
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
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Payroll Management</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                {{-- Search Plantilla Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'plantilla'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                {{-- Search COS Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'cos'">
                    <label for="search2" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search2" wire:model.live="search2"
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
                    <div class="w-full sm:w-auto relative">
                        <button wire:click="toggleDropdown"
                            class="mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-2 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Filter Column
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>
                        @if($sortColumn)
                            <div
                                class="absolute top-14 z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg 
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
                                        <input id="office_division" type="checkbox" wire:model.live="columns.office_division"
                                            class="h-4 w-4">
                                        <label for="office_division" class="ml-2 text-gray-900 dark:text-gray-300">Office/Division</label>
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
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Export to Excel -->
                    <div class="relative inline-block text-left">
                        <button wire:click="exportExcel"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button"  title="Export Payroll">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
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
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                        </button>
                    </div>
                </div>
                
            </div>

            <!-- Table -->
            <div class="flex flex-col">
                <div class="flex gap-2 overflow-x-auto -mb-2 hidden">
                    <button @click="selectedTab = 'plantilla'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'plantilla', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'plantilla' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                        Plantilla Payroll
                    </button>
                    <button @click="selectedTab = 'cos'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'cos', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'cos' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        COS Regular Payroll
                    </button>
                    <button @click="selectedTab = 'payroll_signatories'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'payroll_signatories', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'payroll_signatories' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Payroll Signatories
                    </button>
                    <button @click="selectedTab = 'payslip_signatories'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'payslip_signatories', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'payslip_signatories' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Payslip Signatories
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                        <div x-show="selectedTab === 'plantilla'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            @foreach($columns as $column => $visible)
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
                                            @foreach($payrolls as $payroll)
                                                <tr class="text-neutral-800 dark:text-neutral-200">
                                                    @foreach($columns as $column => $visible)
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
                        <div x-show="selectedTab === 'payroll_signatories'">
                            <div class="overflow-x-hidden">
                                <div class="flex gap-2 overflow-x-auto dark:bg-gray-700 bg-gray-200 rounded-t-lg">
                                    <button @click="selectedSubTab = 'plantilla_payroll'" 
                                            :class="{ 'font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400': selectedSubTab === 'plantilla_payroll', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedSubTab !== 'plantilla_payroll' }" 
                                            class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                                        Plantilla Payroll Signatories
                                    </button>
                                    <button @click="selectedSubTab = 'cos_payroll'" 
                                            :class="{ 'font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400': selectedSubTab === 'cos_payroll', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedSubTab !== 'cos_payroll' }" 
                                            class="h-min px-4 pt-2 pb-4 text-sm">
                                        COS Payroll Signatories
                                    </button>
                                </div>
                            </div>
                            {{-- Plantilla Payroll View --}}
                            <div x-show="selectedSubTab === 'plantilla_payroll'">
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
                                                        <p class="pl-6 font-bold">₱ 4,884.16</p>
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
                                                        <p class="pl-6 font-bold">₱ 4,884.16</p>
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
                                                        <p class="pl-2">CERTIFIED: Each employee whose name <br>
                                                            appears above has been paid the amount indicated <br>
                                                            opposite on his/her name.
                                                        </p>
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
                            {{-- COS Payroll View --}}
                            <div x-show="selectedSubTab === 'cos_payroll'">
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
                                                            @if($cosPayroll['a'])
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
                                                            @endif
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
                                                            @if($cosPayroll['b'])
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
                                                            @endif
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
                                                            @if($cosPayroll['c'])
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
                                                            @endif
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
                                                            @if($cosPayroll['d'])
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
                                                            @endif
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
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            </div>
                        </div>
                        <div x-show="selectedTab === 'payslip_signatories'">
                            <div class="overflow-x-hidden">
                                <div class="flex gap-2 overflow-x-auto dark:bg-gray-700 bg-gray-200 rounded-t-lg">
                                    <button @click="selectedSubTab2 = 'plantilla_payslip'" 
                                            :class="{ 'font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400': selectedSubTab2 === 'plantilla_payslip', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedSubTab2 !== 'plantilla_payslip' }" 
                                            class="h-min px-4 pt-2 pb-4 text-sm">
                                        Plantilla Payslip Signatories
                                    </button>
                                    <button @click="selectedSubTab2 = 'cos_payslip'" 
                                            :class="{ 'font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400': selectedSubTab2 === 'cos_payslip', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedSubTab2 !== 'cos_payslip' }" 
                                            class="h-min px-4 pt-2 pb-4 text-sm">
                                        COS Payslip Signatories
                                    </button>
                                </div>
                            </div>
                            {{-- Plantilla Payslip View --}}
                            <div x-show="selectedSubTab2 === 'plantilla_payslip'">
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
                            {{-- COS Payslip View --}}
                            <div x-show="selectedSubTab2 === 'cos_payslip'">
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
                                                    @if($cosPayslipSigns['notedBy'])
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
                                                    @endif
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
                        <input type="text" id="office_division" wire:model='office_division' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('office_division') 
                            <span class="text-red-500 text-sm">The office/division is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                        <input type="text" id="position" wire:model='position' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
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
                    
                    <div class="col-span-2">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model.live='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addCosPayroll ? '' : 'disabled' }}>
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
                        <input type="text" id="office_division" wire:model='office_division' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('office_division') 
                            <span class="text-red-500 text-sm">The office/division is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                        <input type="text" id="position" wire:model='position' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
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

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2">
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
                            @foreach ($empPayrolled as $employee)
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
                            @foreach ($empPayrolled as $employee)
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
