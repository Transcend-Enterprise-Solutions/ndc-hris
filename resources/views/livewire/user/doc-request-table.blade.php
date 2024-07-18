<div class="w-full flex justify-center">
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Request a Document</h1>

        <!-- Success and Error Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Document Type Selection -->
        <select wire:model="documentType" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700 mb-4">
            <option value="" disabled selected>Select document type</option>
            @foreach ($availableDocumentTypes as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <!-- Submit Button -->
        <button wire:click="requestDocument" class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Submit Request
        </button>

        <!-- Document Requests List -->
        <h2 class="text-lg font-bold text-black dark:text-white mt-6 mb-4">My Document Requests</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800  overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th class="px-4 py-2 text-center">Document Type</th>
                        <th class="px-4 py-2 text-center">Date Requested</th>
                        <th class="px-4 py-2 text-center">Date Completed</th>
                        <th class="px-4 py-2 text-center">Status</th>
                        <th class="px-4 py-2 text-center">My Document</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $request->document_type }}</td>
                            <td class="px-4 py-2 text-center">{{ $request->date_requested->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">{{ $request->date_completed ? $request->date_completed->format('Y-m-d') : 'N/A' }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="px-3 py-1 rounded
                                    {{ $request->status == 'pending' ? 'bg-orange-500 text-white' : '' }}
                                    {{ $request->status == 'preparing' ? 'bg-blue-500 text-white' : '' }}
                                    {{ $request->status == 'completed' ? 'bg-green-500 text-white' : '' }}
                                    {{ $request->status == 'rejected' ? 'bg-red-500 text-white' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if ($request->file_path)
                                    <button wire:click="downloadDocument({{ $request->id }})" class="text-blue-500 hover:underline">
                                        {{ $request->filename }} (Download)
                                    </button>
                                @else
                                    No Document
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
