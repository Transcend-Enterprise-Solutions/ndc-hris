<div class="w-full">

    <style>
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
        <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    Payroll Payment Slip
                </h1>
            </div>

            <div class="block sm:flex items-center mb-6 justify-between">

                <div class="block sm:flex items-center">

                    <!-- Select Date -->
                    <div class="mr-0 sm:mr-4 relative">
                        <label for="date" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Select Date</label>
                        <input type="month" id="date" wire:model.live='date' value="{{ $date }}"
                        class="mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800 mb-4 sm:mb-0">
                    </div>

                </div>

            </div>

            <!-- Table -->
            <div class="flex flex-col">
                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                    
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-full">
                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                <tr class="whitespace-nowrap">
                                    <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Date</th>
                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                        Amount Due (1 - 15)
                                    </th>
                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                        Amount Due (16 - end-of-month)
                                    </th>
                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                        Net Amount Received
                                    </th>
                                    <th style="width: 30px" class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                @if($type === "plantilla")
                                    @foreach($payslips as $payslip)
                                        <tr class="text-neutral-800 dark:text-neutral-200">
                                            <td scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                {{ \Carbon\Carbon::parse($payslip->start_date)->format('F') }},  {{ \Carbon\Carbon::parse($payslip->start_date)->format('Y') }}
                                            </td>
                                            <td scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                {{ $payslip->first_half_amount }}
                                            </td>
                                            <td scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                {{ $payslip->second_half_amount }}
                                            </td>
                                            <td scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                {{ $payslip->net_amount_received }}
                                            </td>
                                            <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-gray-100 dark:bg-gray-900">
                                                {{-- <button wire:click="viewPlantillaPayslip('{{ $payslip->start_date }}')" 
                                                    class="peer inline-flex items-center justify-center py-2 
                                                    text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                    focus:outline-none" title="View Payslip">
                                                    <i class="fas fa-eye"></i>
                                                </button> --}}
                                                <button wire:click="exportPlantillaPayslip('{{ $payslip->start_date }}')" 
                                                    class="inline-flex items-center justify-center py-2
                                                    text-sm font-medium tracking-wide hover:text-green-500 focus:outline-none" title="Export Payslip">
                                                    <i class="fas fa-file-export ml-2" wire:target="exportPlantillaPayslip('{{ $payslip->start_date }}')" wire:loading.remove></i>
                                                    <div wire:loading wire:target="exportPlantillaPayslip('{{ $payslip->start_date }}')" style="margin-top: -6px">
                                                        <div class="spinner-border small text-primary" role="status">
                                                        </div>
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($cosPayslips as $payslip)
                                        <tr class="text-neutral-800 dark:text-neutral-200">
                                            <td scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                {{ $payslip['month_year'] }}
                                            </td>
                                            <td scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                {{ $payslip['first_half_amount'] }}
                                            </td>
                                            <td scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                {{ $payslip['second_half_amount'] }}
                                            </td>
                                            <td scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                {{ $payslip['net_amount_received'] }}
                                            </td>
                                            <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-gray-100 dark:bg-gray-900">
                                                {{-- <button wire:click="viewPlantillaPayslip('{{ $payslip['month_year'] }}')" 
                                                    class="peer inline-flex items-center justify-center py-2 
                                                    text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                    focus:outline-none" title="View Payslip">
                                                    <i class="fas fa-eye"></i>
                                                </button> --}}
                                                <button wire:click="exportCosPayslip('{{ $payslip['month_year'] }}')" 
                                                    class="inline-flex items-center justify-center py-2
                                                    text-sm font-medium tracking-wide hover:text-green-500 focus:outline-none" title="Export Payslip">
                                                    <i class="fas fa-file-export ml-2" wire:target="exportCosPayslip('{{ $payslip['month_year'] }}')" wire:loading.remove></i>
                                                    <div wire:loading wire:target="exportCosPayslip('{{ $payslip['month_year'] }}')" style="margin-top: -6px">
                                                        <div class="spinner-border small text-primary" role="status">
                                                        </div>
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if ($type === "plantilla" && $payslips->isEmpty())
                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                No records!
                            </div> 
                        @elseif($cosPayslips->isEmpty())
                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                No records!
                            </div> 
                        @endif
                    </div>

                    @if($type === "plantilla")
                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            {{ $payslips->links() ?? '' }}
                        </div>
                    @else
                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            {{ $cosPayslips->links() ?? '' }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>


    {{-- View Plantilla Payslip Modal --}}
    <x-modal id="viewPayslip" maxWidth="2xl" wire:model="payslip">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Payroll for {{ $payslipDate }}
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent=''>
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="sg_step" class="block text-xs font-medium text-gray-700 dark:text-slate-400">SG - Step: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $sg_step }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="rate_per_month" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Rate per Month: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $rate_per_month == 0 ? '-' : currency_format($rate_per_month) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="personal_economic_relief_allowance" class="block text-xs font-medium text-gray-700 dark:text-slate-400">PERA: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $personal_economic_relief_allowance == 0 ? '-' : currency_format($personal_economic_relief_allowance) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="gross_amount" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Gross Amount: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $gross_amount == 0 ? '-' : currency_format($gross_amount) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="additional_gsis_premium" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Additional GSIS Premium: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $additional_gsis_premium == 0 ? '-' : currency_format($additional_gsis_premium) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="lbp_salary_loan" class="block text-xs font-medium text-gray-700 dark:text-slate-400">LBP Salary Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $lbp_salary_loan == 0 ? '-' : currency_format($lbp_salary_loan) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="nycea_deductions" class="block text-xs font-medium text-gray-700 dark:text-slate-400">NYCEA Deductions: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $nycea_deductions == 0 ? '-' : currency_format($nycea_deductions) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="sc_membership" class="block text-xs font-medium text-gray-700 dark:text-slate-400">SC Membership: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $sc_membership == 0 ? '-' : currency_format($sc_membership) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="total_loans" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Total Loans: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $total_loans == 0 ? '-' : currency_format($total_loans) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="salary_loan" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Salary Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $salary_loan == 0 ? '-' : currency_format($salary_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="policy_loan" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Policy Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $policy_loan == 0 ? '-' : currency_format($policy_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="eal" class="block text-xs font-medium text-gray-700 dark:text-slate-400">EAL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $eal == 0 ? '-' : currency_format($eal) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="emergency_loan" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Emergency Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $emergency_loan == 0 ? '-' : currency_format($emergency_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="mpl" class="block text-xs font-medium text-gray-700 dark:text-slate-400">MPL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $mpl == 0 ? '-' : currency_format($mpl) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="housing_loan" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Housing Loan: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $housing_loan == 0 ? '-' : currency_format($housing_loan) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="ouli_prem" class="block text-xs font-medium text-gray-700 dark:text-slate-400">OULI Premium: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $ouli_prem == 0 ? '-' : currency_format($ouli_prem) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="gfal" class="block text-xs font-medium text-gray-700 dark:text-slate-400">GFAL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $gfal == 0 ? '-' : currency_format($gfal) }}</p>
                    </div>
    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="cpl" class="block text-xs font-medium text-gray-700 dark:text-slate-400">CPL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $cpl == 0 ? '-' : currency_format($cpl) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="pagibig_mpl" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Pag-Ibig MPL: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $pagibig_mpl == 0 ? '-' : currency_format($pagibig_mpl) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="other_deduction_philheath_diff" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Other Deduction Philheath Differential: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $other_deduction_philheath_diff == 0 ? '-' : currency_format($other_deduction_philheath_diff) }}</p>
                    </div>

                    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="life_retirement_insurance_premiums" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Life Retirement Insurance Premiums: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $life_retirement_insurance_premiums == 0 ? '-' : currency_format($life_retirement_insurance_premiums) }}</p>
                    </div>
                    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="pagibig_contribution" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Pag-Ibig Contribution: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $pagibig_contribution == 0 ? '-' : currency_format($pagibig_contribution) }}</p>
                    </div>
                    
                    <div class="col-span-1 border-b border-slate-800">
                        <label for="w_holding_tax" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Withholding Tax: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $w_holding_tax == 0 ? '-' : currency_format($w_holding_tax) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="philhealth" class="block text-xs font-medium text-gray-700 dark:text-slate-400">PhilHealth: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $philhealth == 0 ? '-' : currency_format($philhealth) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="other_deductions" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Other Deductions: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $other_deductions == 0 ? '-' : currency_format($other_deductions) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="total_deduction" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Total Deduction: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ $total_deduction == 0 ? '-' : currency_format($total_deduction) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="amount_due_first_half" class="block text-xs font-medium text-gray-700 dark:text-slate-400">
                            Amount Due 
                            (1-15)
                        : </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($amount_due_first_half) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="amount_due_second_half" class="block text-xs font-medium text-gray-700 dark:text-slate-400">
                            Amount Due (16 - {{ $month ? \Carbon\Carbon::parse($month)->endOfMonth()->format('d') : '' }}) :
                        </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($amount_due_second_half) }}</p>
                    </div>

                    <div class="col-span-1 border-b border-slate-800">
                        <label for="net_amount_received" class="block text-xs font-medium text-gray-700 dark:text-slate-400">Net Amount Received: </label>
                        <p class="text-slate-800 text-sm dark:text-gray-200">&nbsp{{ currency_format($net_amount_received) }}</p>
                    </div>

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-2 text-sm">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="exportPlantillaPayslip('{{ $month }}')">
                            <div wire:loading wire:target="" class="spinner-border small text-primary" role="status">
                            </div>
                            Export
                            <div wire:loading wire:target="exportPlantillaPayslip('{{ $month }}')" style="margin-left: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Close
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

</div>
