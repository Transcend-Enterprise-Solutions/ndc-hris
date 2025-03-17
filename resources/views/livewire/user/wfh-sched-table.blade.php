    <div x-data="{ selectedTab: @entangle('selectedTab').live }">
        <div class="w-full flex justify-center">
            <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
                <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Request Work From Home</h1>

                @if (session()->has('message'))
                    <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-4 px-4 py-2 bg-red-100 text-red-800 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Date Selection -->
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <input type="date" id="wfhDay" wire:model="wfhDay" class="w-full sm:w-auto p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                    @error('wfhDay')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Submit Button -->
                    <button wire:click="requestWfh" class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Submit Request
                    </button>
                </div>

                <!-- Tabs -->
                <div class="w-full mt-6">
                    <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700" role="tablist">
                        <button @click="selectedTab = 'pending'; $wire.setSelectedTab('pending')"
                            :class="selectedTab === 'pending' ? 'font-bold text-violet-700 mt-2 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600' : 'text-slate-700 font-medium mt-2 dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black'"
                            class="h-min px-4 py-2 text-sm" role="tab">
                            Pending
                        </button>

                        <button @click="selectedTab = 'approved'; $wire.setSelectedTab('approved')"
                            class="group px-4 py-2 text-sm font-medium mt-2 transition duration-150 ease-in-out"
                            :class="selectedTab === 'approved' ? 'text-violet-700 border-b-2 border-violet-700 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            role="tab">
                            Approved
                        </button>

                        <button @click="selectedTab = 'rejected'; $wire.setSelectedTab('rejected')"
                            class="group px-4 py-2 mt-2 text-sm font-medium transition duration-150 ease-in-out"
                            :class="selectedTab === 'rejected' ? 'text-violet-700 border-b-2 border-violet-700 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            role="tab">
                            Rejected
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="px-2 py-4 text-slate-700 dark:text-slate-300">
                        @foreach (['pending', 'approved', 'rejected'] as $status)
                            <div x-show="selectedTab === '{{ $status }}'" id="tabpanel{{ ucfirst($status) }}" role="tabpanel" aria-labelledby="tab{{ ucfirst($status) }}">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th class="px-4 py-2 text-center">Date Requested</th>
                                                <th class="px-4 py-2 text-center">WFH Date</th>
                                                <th class="px-4 py-2 text-center">Status</th>
                                                <th class="px-4 py-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($requests->where('status', $status) as $request)
                                                <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                                                    <td class="px-4 py-2 text-center">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                                    <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($request->wfhDay)->format('Y-m-d (D)') }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        @if($request->status == 'pending')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                Pending
                                                            </span>
                                                        @elseif($request->status == 'approved')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                Approved
                                                            </span>
                                                        @elseif($request->status == 'rejected')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                Rejected
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        @if($request->status === 'pending')
                                                            <button wire:click="cancelRequest({{ $request->id }})" wire:confirm="Are you sure you want to cancel this request?" class="text-red-600 hover:text-red-900">
                                                                Cancel
                                                            </button>
                                                        @else
                                                            <span class="text-gray-400">No actions available</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($requests->where('status', $status)->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No {{ ucfirst($status) }} requests available.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
