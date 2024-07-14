<div class="w-full flex justify-center">
    <div class="w-3/4 bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md overflow-x-auto">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Document Requests</h1>

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

        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="px-4 py-2 text-center">Name</th>
                    <th class="px-4 py-2 text-center">Document Type</th>
                    <th class="px-4 py-2 text-center">Date Requested</th>
                    <th class="px-4 py-2 text-center">Date Completed</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Your Document</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr class="border-b dark:border-gray-600">
                        <td class="px-2 py-2 text-center text-sm">{{ $request->user->name }}</td>
                        <td class="px-2 py-2 text-center text-sm">{{ $request->document_type }}</td>
                        <td class="px-2 py-2 text-center text-sm">{{ $request->date_requested->format('Y-m-d') }}</td>
                        <td class="px-2 py-2 text-center text-sm">{{ $request->date_completed ? $request->date_completed->format('Y-m-d') : 'N/A' }}</td>
                        <td class="px-2 py-2 text-center text-sm">
                            <button wire:click="openStatusOptions({{ $request->id }})"
                                class="px-3 py-1 rounded
                                {{ $request->status == 'pending' ? 'bg-orange-500 text-white' : '' }}
                                {{ $request->status == 'preparing' ? 'bg-blue-500 text-white' : '' }}
                                {{ $request->status == 'completed' ? 'bg-green-500 text-white' : '' }}
                                {{ $request->status == 'rejected' ? 'bg-red-500 text-white' : '' }}">
                                {{ ucfirst($request->status) }}
                            </button>
                        </td>
                        <td class="px-2 py-2 text-center text-sm">
                            @if ($request->status == 'preparing')
                                <input type="file" wire:model="uploadedFile" class="mt-2 mb-2">
                                <button wire:click="uploadDocument" class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-upload"></i>
                                </button>
                            @else
                                @if ($request->file_path)
                                    <a href="{{ asset($request->file_path) }}" class="text-blue-500 hover:underline" download>
                                        Download
                                    </a>
                                @else
                                    No Document
                                @endif
                            @endif
                        </td>
                        <td class="px-2 py-2 text-center text-sm flex justify-center space-x-2">
                            @if ($request->status == 'pending')
                                <button wire:click="approveRequest({{ $request->id }})" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button wire:click="rejectRequest({{ $request->id }})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            <button wire:click="deleteRequest({{ $request->id }})" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
