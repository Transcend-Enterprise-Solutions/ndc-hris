<div x-data="{
    showDeleteModal: false,
    documentToDeleteId: null,
    notificationMessage: '',
    notificationType: '',
    showNotification: false
}" x-init="
    $wire.on('show-delete-modal', () => {
        showDeleteModal = true;
    });
    $wire.on('notify', event => {
        notificationMessage = event.message;
        notificationType = event.type;
        showNotification = true;
        setTimeout(() => showNotification = false, 3000);
    });
">
    <div class="w-full">
        <div class="flex justify-center w-full">
            <div class="overflow-x-auto w-full bg-white dark:bg-gray-800 rounded-2xl px-6 shadow">
                <div class="pt-4 pb-4">
                    <h1 class="text-lg font-bold text-center text-black dark:text-white">Employee Documents</h1>
                </div>
                <div x-data="{ selectedTab: 'saln' }" class="w-full">
                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700" role="tablist" aria-label="tab options">
                        @foreach ($tabs as $key => $label)
                            <button @click="selectedTab = '{{ $key }}'" :aria-selected="selectedTab === '{{ $key }}'" :tabindex="selectedTab === '{{ $key }}' ? '0' : '-1'" :class="selectedTab === '{{ $key }}' ? 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600' : 'text-slate-700 font-medium dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanel{{ ucfirst($key) }}">{{ $label }}</button>
                        @endforeach
                    </div>
                    <div class="px-2 py-4 text-slate-700 dark:text-slate-300">
                        @foreach ($tabs as $key => $label)
                            <div x-show="selectedTab === '{{ $key }}'" id="tabpanel{{ ucfirst($key) }}" role="tabpanel" aria-labelledby="tab{{ ucfirst($key) }}">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Employee Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Upload Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                        @foreach ($documentsByType[$key] ?? [] as $document)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">{{ $document->user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">{{ $document->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200">
                                                    <a href="{{ Storage::url($document->file_path) }}" download class="text-blue-500 hover:text-blue-700" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button @click="$wire.confirmDelete({{ $document->id }})" class="text-red-500 hover:text-red-700" title="Delete" :disabled="$wire.isDeleting">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (empty($documentsByType[$key]))
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No documents available.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div
        x-show="showDeleteModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg max-w-sm w-full"
            @click.away="showDeleteModal = false"
            @keydown.escape.window="showDeleteModal = false"
        >
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Are you sure you want to delete this document? This action cannot be undone.</p>
            <div class="mt-4 flex justify-end gap-4">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-700">Cancel</button>
                <button wire:click="deleteDocument" @click="showDeleteModal = false" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" :disabled="$wire.isDeleting">
                    <span x-show="!$wire.isDeleting">Delete</span>
                    <span x-show="$wire.isDeleting">Deleting...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-show="showNotification" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" class="fixed bottom-5 right-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4" :class="{ 'bg-green-100 border-green-400': notificationType === 'success', 'bg-red-100 border-red-400': notificationType === 'error' }">
        <p x-text="notificationMessage" :class="{ 'text-green-700': notificationType === 'success', 'text-red-700': notificationType === 'error' }"></p>
    </div>
</div>
