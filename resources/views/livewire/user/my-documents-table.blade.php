<div class="w-full">
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-4/5 bg-white dark:bg-gray-800 rounded-lg p-3 shadow">
            <div class="pt-4 pb-4">
                <h1 class="text-lg font-bold text-center text-black dark:text-white">My Documents</h1>
            </div>

            <!-- Success and Error Messages -->
            @if ($message)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $message }}</span>
                </div>
            @endif

            @if ($error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            <!-- File Upload Area -->
            <div class="mt-4 p-6 border-2 border-dashed border-gray-300 rounded-lg text-center">
                <label for="file-upload" class="cursor-pointer">
                    <span class="text-blue-600 hover:underline">Choose files</span>
                </label>
                <input id="file-upload" type="file" multiple wire:model="files" class="hidden">

                @if ($files)
                    <ul class="mt-4 text-left">
                        @foreach ($files as $file)
                            <li>{{ $file->getClientOriginalName() }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Document Type Selection -->
            <select wire:model="documentType" class="mt-4 w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                <option value="" disabled selected>Select document type</option>
                @foreach ($availableDocumentTypes as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>

            <!-- Upload Button -->
            <button wire:click="uploadDocuments" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                Upload Documents
            </button>

            <!-- Existing Documents -->
            @if ($documents->count() > 0)
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Uploaded Documents</h2>
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Document Type</th>
                                <th class="py-2 px-4 border-b">File Name</th>
                                <th class="py-2 px-4 border-b">Uploaded At</th>
                                <th class="py-2 px-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td class="py-2 px-4 border-b text-center align-middle">{{ $document->document_type }}</td>
                                    <td class="py-2 px-4 border-b text-center align-middle">
                                        {{ strlen($document->file_name) > 10 ? substr($document->file_name, 0, 10) . '...' : $document->file_name }}
                                    </td>
                                    <td class="py-2 px-4 border-b text-center align-middle">{{ $document->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-2 px-4 border-b flex justify-center space-x-2">
                                        <button wire:click="updateDocument({{ $document->id }}, 'new_document_type')" class="text-blue-500 hover:text-blue-700" title="Update">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $document->id }})" class="text-red-500 hover:text-red-700" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Confirmation Modal -->
            <div x-data="{ open: @entangle('confirmDeleteModal') }" x-show="open" class="relative z-50 w-auto h-auto" @keydown.escape.window="open = false">
                <template x-teleport="body">
                    <div x-show="open" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                        <div x-show="open"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            @click="open = false" class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
                        <div x-show="open"
                            x-trap.inert.noscroll="open"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative w-full py-6 bg-white dark:bg-gray-800 px-7 sm:max-w-lg sm:rounded-lg">
                            <div class="flex items-center justify-between pb-2">
                                <h3 class="text-lg font-semibold">Confirm Delete</h3>
                                <button @click="open = false" class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="relative w-auto">
                                <p>Are you sure you want to delete this document?</p>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button @click="open = false" class="mr-2 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                <button wire:click="deleteDocument" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>
</div>
