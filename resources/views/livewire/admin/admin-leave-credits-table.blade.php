<div class="w-full">
    {{-- Table --}}
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Request</h1>
                </div>

                <!-- Search input -->
                <div class="relative inline-block text-left">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Search</label>
                    <input type="search" id="search" wire:model.live="search" placeholder="Enter employee name"
                        class="py-2 px-3 block w-80 shadow-sm text-sm font-medium border-gray-400
                        wire:text-neutral-800 dark:text-neutral-200 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                </div>

                <div class="flex flex-col p-3">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block w-full py-2 align-middle">
                            <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase text-center">Name</th>
                                                <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase text-center">Total Credits</th>
                                                <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase text-center">Claimable Credits</th>
                                                <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase text-center">Claimed Credits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leaveCredits as $leaveCredit)
                                            <tr class="whitespace-nowrap">
                                                <td class="px-4 py-2 text-center">{{ $leaveCredit->user->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-center">{{ $leaveCredit->total_credits }}</td>
                                                <td class="px-4 py-2 text-center">{{ $leaveCredit->claimable_credits }}</td>
                                                <td class="px-4 py-2 text-center">{{ $leaveCredit->total_claimed_credits ?? 'N/A' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $leaveCredits->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
