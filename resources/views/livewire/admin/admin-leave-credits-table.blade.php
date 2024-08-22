<div x-data="{ selectedTab: 'vl' }" class="w-full">
    <!-- Table -->
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-auto">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Credits</h1>
                </div>

                <!-- Search input -->
                <div class="relative inline-block text-left mb-4">
                    <label for="search"
                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Search</label>
                    <input type="search" id="search" wire:model.live="search" placeholder="Enter employee name"
                        class="py-2 px-3 block w-80 shadow-sm text-sm font-medium border-gray-400 dark:border-gray-600 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                        <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700">
                            <button @click="selectedTab = 'vl'"
                                :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': selectedTab === 'vl', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': selectedTab !== 'vl' }"
                                class="h-min px-4 py-2 text-sm mb-4">
                                Vacation Leave
                            </button>
                            <button @click="selectedTab = 'sl'"
                                :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': selectedTab === 'sl', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': selectedTab !== 'sl' }"
                                class="h-min px-4 py-2 text-sm mb-4">
                                Sick Leave
                            </button>
                            <button @click="selectedTab = 'spl'"
                                :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': selectedTab === 'spl', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': selectedTab !== 'spl' }"
                                class="h-min px-4 py-2 text-sm mb-4">
                                Special Privilege Leave
                            </button>
                        </div>

                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                            <tr class="whitespace-nowrap">
                                <th scope="col" class="px-4 py-2 text-center">Name</th>
                                <th scope="col" class="px-4 py-2 text-center">Total Credits</th>
                                <th scope="col" class="px-4 py-2 text-center">Claimable Credits</th>
                                <th scope="col" class="px-4 py-2 text-center">Claimed Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Vacation Leave Credits -->
                            <template x-if="selectedTab === 'vl'">
                                @foreach ($leaveCredits as $leaveCredit)
                                    <tr class="whitespace-nowrap">
                                        <td class="px-4 py-2 text-center">{{ $leaveCredit->user->name }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->total_credits }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->vl_claimable_credits }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->vl_claimed_credits }}</td>
                                    </tr>
                                @endforeach
                            </template>

                            <!-- Sick Leave Credits -->
                            <template x-if="selectedTab === 'sl'">
                                @foreach ($leaveCredits as $leaveCredit)
                                    <tr class="whitespace-nowrap">
                                        <td class="px-4 py-2 text-center">{{ $leaveCredit->user->name }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->total_credits }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->sl_claimable_credits }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->sl_claimed_credits }}</td>
                                    </tr>
                                @endforeach
                            </template>

                            <!-- Special Privilege Leave Credits -->
                            <template x-if="selectedTab === 'spl'">
                                @foreach ($leaveCredits as $leaveCredit)
                                    <tr class="whitespace-nowrap">
                                        <td class="px-4 py-2 text-center">{{ $leaveCredit->user->name }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->total_credits }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->spl_claimable_credits }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leaveCredit->spl_claimed_credits }}</td>
                                    </tr>
                                @endforeach
                            </template>
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
