<div class="w-full flex justify-center">
    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p3 sm:p-0 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-6 pt-6 sm:mt-2">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Payroll Management</h1>
            </div>

            <div class="block sm:flex items-center justify-between pr-3 pl-3">

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

                <div class="block sm:flex items-center">
                    <button wire:click="toggleAddPayroll" 
                        class="mb-4 sm:mb-0 mr-0 sm:mr-4 px-4 py-2 bg-green-500 text-white rounded-md 
                        hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full sm:w-3/5
                        dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                        Add Payroll
                    </button>

                    <input type="search" id="search" wire:model.live="search" 
                    placeholder="Search..."
                    class="py-2 px-3 block w-full shadow-sm text-sm font-medium border-gray-400 
                    wire:text-neutral-800 dark:text-neutral-200
                    dark:hover:bg-slate-600 dark:border-slate-600 mb-4 sm:mb-0
                    rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
  
                </div>

                <div class="flex items-center">

                    <!-- Sort Dropdown -->
                    <div class="relative flex text-left mr-4">
                        <button wire:click="toggleDropdown"
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-2 mb-0 sm:mb-0 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Filter Column
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
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Export to Excel -->
                    <div class="relative inline-block text-left">
                        <button wire:click="exportExcel"
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
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
                <div class="inline-block w-full py-2 align-middle">
                    <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
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
                                                    <button wire:click="toggleEditPayroll({{ $payroll->user_id }})" class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                        <i class="fas fa-pencil-alt ml-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                        <div class="p-5 border-t border-gray-200 dark:border-slate-600 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            {{ $payrolls->links() }}
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>

   {{-- Add and Edit Payroll Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="editPayroll">
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
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
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
                        <input type="text" id="employee_number" wire:model='employee_number' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('employee_number') 
                            <span class="text-red-500 text-sm">The employee number is required!</span> 
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
                        <label for="sg_step" class="block text-sm font-medium text-gray-700 dark:text-slate-400">SG - Step</label>
                        <input type="text" id="sg_step" wire:model='sg_step' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('sg_step') 
                            <span class="text-red-500 text-sm">The SG Step is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="rate_per_month" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Rate per Month</label>
                        <input type="number" step="0.01" id="rate_per_month" wire:model='rate_per_month' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('rate_per_month') 
                            <span class="text-red-500 text-sm">The rate per month is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="personal_economic_relief_allowance" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Personal Economic Relief Allowance</label>
                        <input type="number" step="0.01" id="personal_economic_relief_allowance" wire:model='personal_economic_relief_allowance' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="gross_amount" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Gross Amount</label>
                        <input type="number" step="0.01" id="gross_amount" wire:model='gross_amount' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
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

                    {{-- <div class="col-span-1">
                        <label for="net_amount_received" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Net Amount Received</label>
                        <input type="number" step="0.01" id="net_amount_received" wire:model='net_amount_received' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('net_amount_received') 
                            <span class="text-red-500 text-sm">The net amount due is required!</span> 
                        @enderror
                    </div> --}}

                    {{-- <div class="col-span-1">
                        <label for="amount_due_first_half" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Amount Due First Half</label>
                        <input type="number" step="0.01" id="amount_due_first_half" wire:model='amount_due_first_half' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div>

                    <div class="col-span-1">
                        <label for="amount_due_second_half" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Amount Due Second Half</label>
                        <input type="number" step="0.01" id="amount_due_second_half" wire:model='amount_due_second_half' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    </div> --}}

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="savePayroll" class="spinner-border small text-primary" role="status">
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
