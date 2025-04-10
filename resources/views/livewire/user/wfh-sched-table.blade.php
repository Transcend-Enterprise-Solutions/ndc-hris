<div x-data="{
    isModalOpen: false,
    selectedTab: @entangle('selectedTab').live
}">
    <div class="w-full flex justify-center">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Work From Home Requests</h1>

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

            <!-- Request Button -->
            <div class="flex left mb-6">
                <button @click="isModalOpen = true"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700">
                    WFH Request
                </button>
            </div>

            <!-- Tabs -->
            <div class="w-full">
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
                <div class="px-2 py-4 text-slate-700 dark:text-slate-300 text-sm">
                    @foreach (['pending', 'approved', 'rejected'] as $status)
                        <div x-show="selectedTab === '{{ $status }}'" id="tabpanel{{ ucfirst($status) }}" role="tabpanel" aria-labelledby="tab{{ ucfirst($status) }}">
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th class="px-4 py-2 text-center">Date Requested</th>
                                            <th class="px-4 py-2 text-center">WFH Date</th>
                                            <th class="px-4 py-2 text-center">Purpose</th>
                                            <th class="px-4 py-2 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests->where('status', $status) as $request)
                                            <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                                                <td class="px-4 py-2 text-center">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($request->wfhDay)->format('Y-m-d (D)') }}</td>
                                                <td class="px-4 py-2 text-center text-sm max-w-xs truncate" title="{{ $request->wfh_reason }}">
                                                    {{ $request->wfh_reason }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($request->status === 'pending')
                                                        <button wire:click="cancelRequest({{ $request->id }})" wire:confirm="Are you sure you want to cancel this request?" class="text-red-600 hover:text-red-900">
                                                            Cancel
                                                        </button>
                                                    @elseif($request->status === 'approved')
                                                        <span class="text-green-500">Approved on {{ $request->approved_at->format('Y-m-d H:i') }}</span>
                                                    @elseif($request->status === 'rejected')
                                                        <span class="text-red-500">Rejected on {{ $request->rejected_at->format('Y-m-d H:i') }}</span>
                                                        @if($request->rejection_reason)
                                                            <p class="text-sm italic mt-1">Reason: {{ $request->rejection_reason }}</p>
                                                        @endif

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

    <!-- WFH Request Modal -->
    <div x-show="isModalOpen"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div @click.away="isModalOpen = false"
            x-show="isModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="relative bg-white dark:bg-gray-800 p-6 mx-4 sm:mx-auto w-full max-w-md rounded-2xl shadow-lg">

            <!-- Modal header -->
            <div class="flex items-center justify-between pb-4 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    Request Work From Home
                </h3>
                <button @click="isModalOpen = false"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <form wire:submit.prevent="requestWfh" class="mt-4 space-y-4">
                <div>
                    <label for="wfhDay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                    <input type="date" id="wfhDay" wire:model="wfhDay" class="w-full p-2 mt-1 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                    @error('wfhDay')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="wfh_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purpose</label>
                    <textarea id="wfh_reason" wire:model="wfh_reason" rows="3" class="w-full p-2 mt-1 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700" placeholder="Please provide a reason for your WFH request"></textarea>
                    @error('wfh_reason')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


