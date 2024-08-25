<div x-data="{ selectedTab: 'vl' }" class="w-full">
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Leave Credits</h1>

        <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700">
            <button @click="selectedTab = 'vl'"
                :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': selectedTab === 'vl', 'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': selectedTab !== 'vl' }"
                class="h-min px-4 py-2 text-sm mb-4">
                Vacation Leave
            </button>
            <button @click="selectedTab = 'sl'"
                :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': selectedTab === 'sl', 'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': selectedTab !== 'sl' }"
                class="h-min px-4 py-2 text-sm mb-4">
                Sick Leave
            </button>
            <button @click="selectedTab = 'spl'"
                :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': selectedTab === 'spl', 'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': selectedTab !== 'spl' }"
                class="h-min px-4 py-2 text-sm mb-4">
                Special Privilege Leave
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th scope="col" class="px-4 py-2 text-center">Total Credits</th>
                        <th scope="col" class="px-4 py-2 text-center">Claimable Credits</th>
                        <th scope="col" class="px-4 py-2 text-center">Claimed Credits</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Vacation Leave Credits -->
                    <tr x-show="selectedTab === 'vl'" class="whitespace-nowrap">
                        <td class="px-4 py-2 text-center">{{ $vl_total_credits }}</td>
                        <td class="px-4 py-2 text-center">{{ $vl_claimable_credits }}</td>
                        <td class="px-4 py-2 text-center">{{ $vl_claimed_credits }}</td>
                    </tr>

                    <!-- Sick Leave Credits -->
                    <tr x-show="selectedTab === 'sl'" class="whitespace-nowrap">
                        <td class="px-4 py-2 text-center">{{ $sl_total_credits }}</td>
                        <td class="px-4 py-2 text-center">{{ $sl_claimable_credits }}</td>
                        <td class="px-4 py-2 text-center">{{ $sl_claimed_credits }}</td>
                    </tr>

                    <!-- Special Privilege Leave Credits -->
                    <tr x-show="selectedTab === 'spl'" class="whitespace-nowrap">
                        <td class="px-4 py-2 text-center">{{ $spl_total_credits }}</td>
                        <td class="px-4 py-2 text-center">{{ $spl_claimable_credits }}</td>
                        <td class="px-4 py-2 text-center">{{ $spl_claimed_credits }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
