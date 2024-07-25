<div class="w-full">
    <div class="w-full flex justify-center mt-4 grid grid-cols-2 gap-2">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Details of Action on Application
                (Vacation Leave)
            </h1>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                        <tr class="whitespace-nowrap">
                            <th scope="col" class="px-4 py-2 text-center">Total Earned</th>
                            <th scope="col" class="px-4 py-2 text-center">Absence Undertime With Pay</th>
                            <th scope="col" class="px-4 py-2 text-center">Balance</th>
                            {{-- <th scope="col" class="px-4 py-2 text-center">Recommendation</th> --}}
                            <th scope="col" class="px-4 py-2 text-center">Absence Undertime Without Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($leaveApplications as $leaveApplication) --}}
                        {{-- @foreach($leaveApplication->vacationLeaveDetails as $details) --}}
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-2 text-center"></td>
                            <td class="px-4 py-2 text-center"></td>
                            <td class="px-4 py-2 text-center"></td>
                            {{-- <td class="px-4 py-2 text-center">{{ $details->recommendation }}</td> --}}
                            <td class="px-4 py-2 text-center"></td>
                        </tr>
                        {{-- @endforeach --}}
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Details of Action on Application
                (Sick Leave)
            </h1>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                        <tr class="whitespace-nowrap">
                            <th scope="col" class="px-4 py-2 text-center">Total Earned</th>
                            <th scope="col" class="px-4 py-2 text-center">Absence Undertime With Pay</th>
                            <th scope="col" class="px-4 py-2 text-center">Balance</th>
                            {{-- <th scope="col" class="px-4 py-2 text-center">Recommendation</th> --}}
                            <th scope="col" class="px-4 py-2 text-center">Absence Undertime Without Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($leaveApplications as $leaveApplication) --}}
                        {{-- @foreach($leaveApplication->sickLeaveDetails as $details) --}}
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-2 text-center"></td>
                            <td class="px-4 py-2 text-center"></td>
                            <td class="px-4 py-2 text-center"></td>
                            {{-- <td class="px-4 py-2 text-center">{{ $details->recommendation }}</td> --}}
                            <td class="px-4 py-2 text-center"></td>
                        </tr>
                        {{-- @endforeach --}}
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>