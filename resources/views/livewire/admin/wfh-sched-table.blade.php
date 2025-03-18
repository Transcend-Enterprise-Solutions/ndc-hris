<div x-data="{ selectedTab: @entangle('selectedTab').live }">
    <div class="w-full flex justify-center">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">WFH Request Approval</h1>

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
                                            <th class="px-4 py-2 text-center">Employee</th>
                                            <th class="px-4 py-2 text-center">Date Requested</th>
                                            <th class="px-4 py-2 text-center">WFH Date</th>
                                            <th class="px-4 py-2 text-center">Purpose</th>
                                            <th class="px-4 py-2 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests->where('status', $status) as $request)
                                            <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                                                <td class="px-4 py-2 text-center">{{ $request->user->name }}</td>
                                                <td class="px-4 py-2 text-center">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($request->wfhDay)->format('Y-m-d (D)') }}</td>
                                                <td class="px-4 py-2 text-center">{{ $request->wfh_reason}}</td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($request->status === 'pending')
                                                        <div class="flex justify-center space-x-2">
                                                            <button wire:click="approveRequest({{ $request->id }})" wire:confirm="Are you sure you want to approve this request?" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                                                Approve
                                                            </button>
                                                            <button @click="$refs.rejectModal{{ $request->id }}.showModal()" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                                Reject
                                                            </button>
                                                        </div>

                                                        <dialog id="rejectModal{{ $request->id }}" x-ref="rejectModal{{ $request->id }}" class="p-4 rounded-lg shadow-xl bg-white dark:bg-gray-800 w-full max-w-md">
                                                            <div class="flex flex-col">
                                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject WFH Request</h3>
                                                                <label class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Reason for rejection (optional)</label>
                                                                <textarea wire:model="reason" class="p-2 border rounded mb-4 text-gray-700 dark:text-gray-300 dark:bg-gray-700" rows="3"></textarea>
                                                                <div class="flex justify-end space-x-2">
                                                                    <button @click="$refs.rejectModal{{ $request->id }}.close()" class="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                                                        Cancel
                                                                    </button>
                                                                    <button wire:click="rejectRequest({{ $request->id }})" @click="$refs.rejectModal{{ $request->id }}.close()" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                                        Confirm Rejection
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </dialog>
                                                    @elseif($request->status === 'approved')
                                                        <span class="text-green-500">Approved on {{ $request->approved_at->format('Y-m-d H:i') }}</span>
                                                    @elseif($request->status === 'rejected')
                                                        <span class="text-red-500">Rejected on {{ $request->rejected_at->format('Y-m-d H:i') }}</span>
                                                        @if($request->rejection_reason)
                                                            <p class="text-sm italic mt-1">Reason: {{ $request->rejection_reason }}</p>
                                                        @endif
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
