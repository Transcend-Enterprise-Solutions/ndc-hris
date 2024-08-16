<div x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Audit Logs</h2>

        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mb-6">
            <input wire:model.live="search" type="text" placeholder="Search audits by ID or keyword..."
                   class="flex-grow px-4 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                   aria-label="Search audits">
            <input wire:model.live="dateFrom" type="date"
                   class="w-full sm:w-auto px-4 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                   placeholder="From Date" aria-label="From Date">
            <input wire:model.live="dateTo" type="date"
                   class="w-full sm:w-auto px-4 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                   placeholder="To Date" aria-label="To Date">
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('event')">
                            Event
                            @if ($sortField === 'event')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Details
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Date
                            @if ($sortField === 'created_at')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @foreach ($audits as $audit)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ ucfirst($audit->event) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                @if ($audit->auditable_type === App\Models\DocRequest::class)
                                    @if ($audit->event === 'created')
                                        <span>User {{ $audit->user->name ?? 'System' }} created a new document request (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Details:</strong><br>
                                            @foreach ($audit->new_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @elseif ($audit->event === 'updated')
                                        <span>User {{ $audit->user->name ?? 'System' }} updated document request (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Old values:</strong><br>
                                            @foreach ($audit->old_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                            <br>
                                            <strong>New values:</strong><br>
                                            @foreach ($audit->new_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @elseif ($audit->event === 'deleted')
                                        <span>User {{ $audit->user->name ?? 'System' }} deleted document request (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Old values:</strong><br>
                                            @foreach ($audit->old_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                @elseif ($audit->auditable_type === App\Models\DTRSchedule::class)
                                    @if ($audit->event === 'created')
                                        <span>User {{ $audit->user->name ?? 'System' }} created a new schedule (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Schedule Details:</strong><br>
                                            @foreach ($audit->new_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @elseif ($audit->event === 'updated')
                                        <span>User {{ $audit->user->name ?? 'System' }} updated schedule (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Old values:</strong><br>
                                            @foreach ($audit->old_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                            <br>
                                            <strong>New values:</strong><br>
                                            @foreach ($audit->new_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @elseif ($audit->event === 'deleted')
                                        <span>User {{ $audit->user->name ?? 'System' }} deleted schedule (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Old values:</strong><br>
                                            @foreach ($audit->old_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                @elseif ($audit->auditable_type === App\Models\Holiday::class)
                                    @if ($audit->event === 'created')
                                        <span>User {{ $audit->user->name ?? 'System' }} created a new holiday (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Holiday Details:</strong><br>
                                            @foreach ($audit->new_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @elseif ($audit->event === 'updated')
                                        <span>User {{ $audit->user->name ?? 'System' }} updated holiday (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Old values:</strong><br>
                                            @foreach ($audit->old_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                            <br>
                                            <strong>New values:</strong><br>
                                            @foreach ($audit->new_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @elseif ($audit->event === 'deleted')
                                        <span>User {{ $audit->user->name ?? 'System' }} deleted holiday (ID: {{ $audit->auditable_id }}).</span>
                                        <div class="mt-1 text-xs">
                                            <strong>Old values:</strong><br>
                                            @foreach ($audit->old_values as $key => $value)
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $audit->created_at->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $audits->links() }}
        </div>
    </div>
</div>
