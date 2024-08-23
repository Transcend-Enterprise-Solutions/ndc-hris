<div x-data="{ selectedTab: 'vl' }" class="w-full">
    <!-- Table -->
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-auto">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Credits</h1>
                </div>

                <div class="flex flex-col mb-4">
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">Search</label>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Search input -->
                        <div>
                            <input type="search" id="search" wire:model.live="search"
                                placeholder="Enter employee name"
                                class="py-2 px-3 mt-1 block w-80 shadow-sm text-sm font-medium border-gray-400 dark:border-gray-600 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                        </div>
                        <!-- Button aligned with input field -->
                        <div>
                            <button wire:click="openInputCredits"
                                class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white">
                                <span
                                    class="relative px-4 py-2 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    Input Credits
                                </span>
                            </button>
                        </div>
                    </div>
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
                            @foreach ($leaveCredits as $leaveCredit)
                                <tr class="whitespace-nowrap">
                                    <td class="px-4 py-2 text-center">{{ $leaveCredit->user->name }}</td>

                                    <td class="px-4 py-2 text-center">
                                        <template x-if="selectedTab === 'vl'">
                                            <span>{{ number_format($leaveCredit->vl_total_credits ?? 0, 3) }}</span>
                                        </template>
                                        <template x-if="selectedTab === 'sl'">
                                            <span>{{ number_format($leaveCredit->sl_total_credits ?? 0, 3) }}</span>
                                        </template>
                                        <template x-if="selectedTab === 'spl'">
                                            <span>{{ number_format($leaveCredit->spl_total_credits ?? 0, 3) }}</span>
                                        </template>
                                    </td>

                                    <!-- Conditional Columns Based on Selected Tab -->
                                    <td class="px-4 py-2 text-center">
                                        <template x-if="selectedTab === 'vl'">
                                            <span>{{ $leaveCredit->vl_claimable_credits }}</span>
                                        </template>
                                        <template x-if="selectedTab === 'sl'">
                                            <span>{{ $leaveCredit->sl_claimable_credits }}</span>
                                        </template>
                                        <template x-if="selectedTab === 'spl'">
                                            <span>{{ $leaveCredit->spl_claimable_credits }}</span>
                                        </template>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <template x-if="selectedTab === 'vl'">
                                            <span>{{ $leaveCredit->vl_claimed_credits }}</span>
                                        </template>
                                        <template x-if="selectedTab === 'sl'">
                                            <span>{{ $leaveCredit->sl_claimed_credits }}</span>
                                        </template>
                                        <template x-if="selectedTab === 'spl'">
                                            <span>{{ $leaveCredit->spl_claimed_credits }}</span>
                                        </template>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                    {{-- {{ $leaveCredits->links() }} --}}
                </div>
            </div>
        </div>
    </div>

    <x-modal id="inputCredits" maxWidth="lg" wire:model="inputCredits" centered>
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    Input Credits
                </h3>
                <button wire:click="closeInputCredits"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form class="space-y-6" wire:submit.prevent="saveCredits">
                <!-- Employee Selection -->
                <div>
                    <label for="employee"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
                    <select id="employee" wire:model="employee"
                        class="w-full p-2 mt-1 border rounded-md text-gray-700 dark:text-gray-300 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select an employee</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                    @error('employee')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Leave Type Selection -->
                <div>
                    <label for="leaveType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leave
                        Type</label>
                    <select id="leaveType" wire:model="leaveType"
                        class="w-full p-2 mt-1 border rounded-md text-gray-700 dark:text-gray-300 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select</option>
                        <option value="vacation">Vacation Leave Credits</option>
                        <option value="sick">Sick Leave Credits</option>
                        <option value="spl">Special Privilege Leave Credits</option>
                    </select>
                    @error('leaveType')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Credits Inputs -->
                <div class="flex space-x-4">
                    <!-- Claimable Credits -->
                    <div class="w-1/2">
                        <label for="claimableCredits"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claimable Credits</label>
                        <input id="claimableCredits" type="number" wire:model="claimableCredits"
                            class="w-full p-2 mt-1 border rounded-md text-gray-700 dark:text-gray-300 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter claimable credits">
                        @error('claimableCredits')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Claimed Credits -->
                    <div class="w-1/2">
                        <label for="claimedCredits"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claimed Credits</label>
                        <input id="claimedCredits" type="number" wire:model="claimedCredits"
                            class="w-full p-2 mt-1 border rounded-md text-gray-700 dark:text-gray-300 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter claimed credits">
                        @error('claimedCredits')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <!-- Save Button -->
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                        Save
                    </button>

                    <!-- Close Button -->
                    <button type="button" wire:click="closeInputCredits"
                        class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800">
                        Close
                    </button>
                </div>
            </form>

        </div>
    </x-modal>

</div>
