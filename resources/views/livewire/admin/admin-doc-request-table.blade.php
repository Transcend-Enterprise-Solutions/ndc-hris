<div x-data="{
    showDeleteModal: false,
    deleteRequestId: null,
    selectedTab: 'pending', // Default tab
}">
    <div class="w-full">
        <div class="flex justify-center w-full">
            <div class="overflow-x-auto w-full bg-white dark:bg-gray-800 rounded-2xl px-6 shadow">
                <div class="pt-4 pb-4">
                    <h1 class="text-lg font-bold text-center text-black dark:text-white">Document Requests</h1>
                </div>
                <!-- Tabs -->
                <div class="w-full">
                    <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700" role="tablist">
                        <button @click="selectedTab = 'pending'" :class="selectedTab === 'pending' ? 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600' : 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black'" class="h-min px-4 py-2 text-sm" role="tab">Pending</button>
                        <button @click="selectedTab = 'preparing'" :class="selectedTab === 'preparing' ? 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600' : 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black'" class="h-min px-4 py-2 text-sm" role="tab">Preparing</button>
                        <button @click="selectedTab = 'completed'" :class="selectedTab === 'completed' ? 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600' : 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black'" class="h-min px-4 py-2 text-sm" role="tab">Completed</button>
                        <button @click="selectedTab = 'rejected'" :class="selectedTab === 'rejected' ? 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600' : 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black'" class="h-min px-4 py-2 text-sm" role="tab">Rejected</button>
                    </div>
                    <!-- Tab Content -->
                    <div class="px-2 py-4 text-slate-700 dark:text-slate-300">
                        @foreach (['pending', 'preparing', 'completed', 'rejected'] as $status)
                            <div x-show="selectedTab === '{{ $status }}'" id="tabpanel{{ ucfirst($status) }}" role="tabpanel" aria-labelledby="tab{{ ucfirst($status) }}">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Document Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Date Requested</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Date Completed</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Your Document</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                        @foreach ($requests->where('status', $status) as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">{{ $request->user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $request->document_type }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $request->date_requested->format('Y-m-d') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $request->date_completed ? $request->date_completed->format('Y-m-d') : 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">
                                                    @if ($status === 'preparing')
                                                        <input type="file" wire:model="uploadedFile.{{ $request->id }}" class="mt-2 mb-2">
                                                        <button wire:click="uploadDocument({{ $request->id }})" class="text-green-500 hover:text-green-700">
                                                            <i class="fas fa-upload"></i> Upload
                                                        </button>
                                                    @elseif ($request->file_path)
                                                        <button wire:click="downloadDocument({{ $request->id }})" class="text-blue-500 hover:underline">
                                                            {{ $request->filename }} (Download)
                                                        </button>
                                                    @else
                                                        No Document
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">
                                                    <div class="flex justify-center space-x-2">
                                                        @if ($status === 'pending')
                                                            <button wire:click="approveRequest({{ $request->id }})" class="text-blue-500 hover:text-blue-700 " title="Approve Request">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button wire:click="rejectRequest({{ $request->id }})" class="text-red-500 hover:text-red-700" title="Reject Request">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                        <button @click="showDeleteModal = true; deleteRequestId = {{ $request->id }}" class="text-gray-500 hover:text-gray-700">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

    <!-- Delete Confirmation Modal -->
    <template x-if="showDeleteModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                <h2 class="text-lg font-bold mb-4">Confirm Deletion</h2>
                <p class="mb-4">Are you sure you want to delete this document request?</p>
                <div class="flex justify-end space-x-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded">Cancel</button>
                    <button @click="$wire.deleteRequest(deleteRequestId); showDeleteModal = false" class="px-4 py-2 bg-red-500 text-white rounded">Delete</button>
                </div>
            </div>
        </div>
    </template>
</div>
