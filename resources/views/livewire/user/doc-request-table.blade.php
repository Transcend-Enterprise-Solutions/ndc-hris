<div x-data="{
    selectedTab: 'pending', // Default tab
}">
    <div class="w-full flex justify-center">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Request a Document</h1>

            <!-- Document Type Selection -->
            <select wire:model="documentType" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700 mb-4">
                <option value="" selected>Select document type</option>
                @foreach ($availableDocumentTypes as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>

            <!-- Submit Button -->
            <button wire:click="requestDocument" class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Submit Request
            </button>

            <!-- Tabs -->
            <div class="w-full mt-6">
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
                            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                    <tr class="whitespace-nowrap">
                                        <th class="px-4 py-2 text-center">Document Type</th>
                                        <th class="px-4 py-2 text-center">Date Requested</th>
                                        <th class="px-4 py-2 text-center">Date Completed</th>
                                        <th class="px-4 py-2 text-center">My Document</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests->where('status', $status) as $request)
                                        <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                                            <td class="px-4 py-2 text-center">{{ $request->document_type }}</td>
                                            <td class="px-4 py-2 text-center">{{ $request->date_requested->format('Y-m-d') }}</td>
                                            <td class="px-4 py-2 text-center">{{ $request->date_completed ? $request->date_completed->format('Y-m-d') : 'N/A' }}</td>
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
