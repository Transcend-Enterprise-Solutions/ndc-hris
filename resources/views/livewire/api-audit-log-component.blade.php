<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">API Audit Logs</h2>

    <div class="mb-4 flex space-x-4">
        <input wire:model.live="search" type="text" placeholder="Search logs..." class="flex-grow px-4 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        
        <select wire:model.live="type" class="border p-2 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white border-gray-300 dark:border-gray-600">
            <option value="">All Types</option>
            <option value="auth_error">Authentication Error</option>
            <option value="fetch_error">Data Fetching Error</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border bg-white dark:bg-gray-800">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="border p-2 text-gray-900 dark:text-gray-200">Type</th>
                    <th class="border p-2 text-gray-900 dark:text-gray-200">Message</th>
                    <th class="border p-2 text-gray-900 dark:text-gray-200">Context</th>
                    <th class="border p-2 text-gray-900 dark:text-gray-200">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditLogs as $log)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="border p-2 text-gray-900 dark:text-gray-200">{{ $log->type }}</td>
                        <td class="border p-2 text-gray-900 dark:text-gray-200">{{ $log->message }}</td>
                        <td class="border p-2 text-gray-900 dark:text-gray-200 max-w-sm break-words">
                            <div class="max-h-20 overflow-hidden overflow-y-auto">
                                <pre class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md text-gray-800 dark:text-gray-200">{{ json_encode($log->context, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </td>

                        <td class="border p-2 text-gray-900 dark:text-gray-200">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="4" class="border p-2 text-center text-gray-900 dark:text-gray-200">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-gray-900 dark:text-gray-200">
        {{ $auditLogs->links() }}
    </div>
</div>
