<div class="w-full flex justify-center">
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Admin Daily Time Record</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th class="px-4 py-2 text-center">Employee ID</th>
                        <th class="px-4 py-2 text-center">Employee Name</th>
                        <th class="px-4 py-2 text-center">Date</th>
                        <th class="px-4 py-2 text-center">Morning In</th>
                        <th class="px-4 py-2 text-center">Noon Out</th>
                        <th class="px-4 py-2 text-center">Noon In</th>
                        <th class="px-4 py-2 text-center">Afternoon Out</th>
                        <th class="px-4 py-2 text-center">Late (minutes)</th>
                        <th class="px-4 py-2 text-center">Overtime (minutes)</th>
                        <th class="px-4 py-2 text-center">Hours Rendered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $empCode => $empTransactions)
                        @foreach ($empTransactions as $date => $dateTransactions)
                            @php
                                $timeRecords = $this->calculateTimeRecords($dateTransactions);
                                $employee = \App\Models\User::where('emp_code', $empCode)->first();
                            @endphp
                            <tr class="whitespace-nowrap">
                                <td class="px-4 py-2 text-center">{{ $empCode }}</td>
                                <td class="px-4 py-2 text-center">{{ $employee->name }}</td>
                                <td class="px-4 py-2 text-center">{{ $date }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['morningIn'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['morningOut'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['afternoonIn'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['afternoonOut'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['late'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['overtime'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['totalHoursRendered'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
