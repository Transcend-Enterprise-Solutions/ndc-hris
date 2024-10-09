<div class="flex justify-center w-full">
    <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
        <div class="pb-4 mb-3 pt-4 sm:pt-0">
            <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white mb-4">Released Payrolls</h1>

            <div class="flex flex-col">
                <!-- Select Date -->
                <div class="w-full sm:w-60 relative mb-4">
                    <label for="recordMonth"
                        class="absolute bottom-10 block text-sm font-medium text-gray-700 dark:text-slate-400">Select Month
                    </label>
                    <input type="month" id="recordMonth" wire:model.live='recordMonth'
                        value="{{ $recordMonth }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                        dark:hover:bg-slate-600 dark:border-slate-600
                        dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Select month...">
                </div>

                <div class="overflow-x-auto">
                    <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-full">
                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                    <tr class="whitespace-nowrap">
                                        <th scope="col"
                                            class="px-5 py-3 text-left text-sm font-medium text-left uppercase">
                                            Inclusive Date</th>
                                        <th width="10%"
                                            class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                    @foreach ($releasedPayrolls as $payroll)
                                        <tr class="text-neutral-800 dark:text-neutral-200">
                                            <td
                                                class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($payroll->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($payroll->end_date)->format('F d, Y') }}
                                            </td>
                                            <td width="10%"
                                                class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                <div class="relative">
                                                    <button
                                                        wire:click="exportExcel('{{ $payroll->start_date }}', '{{ $payroll->end_date }}')"
                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 
                                                        text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                        title="Export Payroll">
                                                        <img class="flex dark:hidden ml-3 mt-4"
                                                            src="/images/icons8-xls-export-dark.png"
                                                            width="22" alt=""
                                                            wire:target="exportExcel('{{ $payroll->start_date }}', '{{ $payroll->end_date }}')"
                                                            wire:loading.remove>
                                                        <img class="hidden dark:block ml-3 mt-4"
                                                            src="/images/icons8-xls-export-light.png"
                                                            width="22" alt=""
                                                            wire:target="exportExcel('{{ $payroll->start_date }}', '{{ $payroll->end_date }}')"
                                                            wire:loading.remove>
                                                        <div wire:loading
                                                            wire:target="exportExcel('{{ $payroll->start_date }}', '{{ $payroll->end_date }}')">
                                                            <div class="mt-4 ml-3 spinner-border small text-primary"
                                                                role="status">
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($releasedPayrolls->isEmpty())
                                <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                    No Record
                                </div>
                            @endif
                        </div>
                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            {{ $releasedPayrolls->links() ?? '' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
