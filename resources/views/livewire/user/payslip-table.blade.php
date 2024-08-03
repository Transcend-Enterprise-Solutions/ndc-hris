<div class="w-full flex justify-center">
    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    Payroll for the month of {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F') : '' }} {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('Y') : '' }}
                </h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d') : '' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d') : '' }}
                </h1>
            </div>

            <div class="block sm:flex items-center pb-2 justify-between">

                <div class="block sm:flex items-center">

                    <!-- Select Date -->
                    <div class="mr-0 sm:mr-4 relative">
                        <label for="date" class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Select Date</label>
                        <input type="month" id="date" wire:model.live='date' value="{{ $date }}"
                        class="mb-0 mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800 mb-4 sm:mb-0">
                    </div>

                    <div class="text-sm flex items-center">
                        <div class="flex items-center mr-3">
                            <input id="1" type="radio" wire:model.live="range" value="1"
                                class="h-4 w-4 text-blue-500 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="1"
                                class="ml-2 text-gray-900 dark:text-gray-300">
                                1-15
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="2" type="radio" wire:model.live="range" value="2"
                                class="h-4 w-4 text-blue-500 border-gray-300 dark:border-neutral-500 focus:ring-neutral-900 focus:ring-offset-2 focus:ring-2 focus:outline-none">
                            <label for="2"
                                class="ml-2 text-gray-900 dark:text-gray-300">
                                16-{{ $monthsEnd ? \Carbon\Carbon::parse($monthsEnd)->format('d') : '30' }}
                            </label>
                        </div>
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
                                                @foreach($columns as $column)
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        @if(in_array($column, [
                                                            'daily_salary_rate',
                                                            'gross_salary',
                                                            'absences_amount',
                                                            'late_undertime_hours_amount',
                                                            'late_undertime_mins_amount',
                                                            'gross_salary_less',
                                                            'withholding_tax',
                                                            'nycempc',
                                                            'total_deductions',
                                                            'net_amount_due',
                                                        ]))
                                                            {{ currency_format($payroll[$column]) }}
                                                        @else
                                                            {{ $payroll[$column] ?? '' }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-gray-100 dark:bg-gray-900">
                                                    <button wire:click="exportPayslip({{ $payroll->id }})" class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide hover:text-blue-600 focus:outline-none">
                                                        <i class="fas fa-file-export ml-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                
                            </div>
                            {{-- <div class="p-5 border-t border-gray-200 dark:border-slate-600 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                @if($payrolls instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $payrolls->links() }}
                                @else
                                    <!-- Display a message or alternative pagination for temporary payrolls -->
                                    <div class="flex justify-between items-center">
                                        <span>Showing {{ $payrolls->firstItem() }} to {{ $payrolls->lastItem() }} of {{ $payrolls->total() }} results</span>
                                        <div>
                                            @if($payrolls->previousPageUrl())
                                                <button wire:click="gotoPage({{ $payrolls->currentPage() - 1 }})" class="px-4 py-2 bg-blue-500 text-white rounded">Previous</button>
                                            @endif
                                            @if($payrolls->nextPageUrl())
                                                <button wire:click="gotoPage({{ $payrolls->currentPage() + 1 }})" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
