<div class="w-full">
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-full bg-white dark:bg-gray-800 rounded-2xl p-3 shadow">
            <div class="pt-4 pb-4">
                <h1 class="text-lg font-bold text-center text-black dark:text-white">My Documents</h1>
            </div>


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
                                        <a href="{{ Storage::url($document->file_path) }}" download class="text-blue-500 hover:underline">
                                            {{ strlen($document->file_name) > 10 ? substr($document->file_name, 0, 10) . '...' : $document->file_name }}
                                        </a>
                                    </td>
                                    <td class="py-2 px-4 border-b text-center align-middle">{{ $document->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-2 px-4 border-b flex justify-center space-x-2">
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
                    <div x-show="open" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-full h-full">
                        <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
                        <div class="bg-white rounded-lg shadow-lg w-1/3 mx-auto p-4 relative z-10">
                            <h2 class="text-lg font-semibold mb-4">Confirm Deletion</h2>
                            <p>Are you sure you want to delete this document?</p>
                            <div class="mt-4 flex justify-end">
                                <button @click="open = false" class="mr-2 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                <button wire:click="deleteDocument" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('refreshDocuments', event => {
        Livewire.start(); // This will refresh the Livewire component
    });
</script>


