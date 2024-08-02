<div class="w-full">
    <div class="flex flex-col sm:flex-row justify-between items-center w-full">
        <div class="overflow-x-auto w-full bg-white dark:bg-gray-800 rounded-2xl px-4 sm:px-6 shadow">
            <div class="pt-4 pb-4 flex flex-col sm:flex-row sm:items-center justify-between">
                <div class="flex flex-row justify-between items-center w-full">
                    <button wire:click="goToPreviousMonth" class="text-black dark:text-white px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-600 text-sm sm:text-base">Previous</button>
                    <h1 class="text-lg font-bold text-center text-black dark:text-white mt-2 sm:mt-0 flex-grow">
                        {{ Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                    </h1>
                    <button wire:click="goToNextMonth" class="text-black dark:text-white px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-600 text-sm sm:text-base">Next</button>
                </div>
            </div>

            <div class="pb-6">
                <div class="grid grid-cols-7 gap-1 text-center text-black dark:text-white">
                    <!-- Days of the week header -->
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="font-semibold text-xs sm:text-sm">{{ $day }}</div>
                    @endforeach

                    <!-- Calendar dates -->
                    @php
                        $firstDayOfMonth = Carbon\Carbon::create($currentYear, $currentMonth, 1);
                        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
                        $startOfCalendar = $firstDayOfMonth->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                        $endOfCalendar = $lastDayOfMonth->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
                    @endphp

                    @for ($day = $startOfCalendar; $day <= $endOfCalendar; $day->addDay())
                        <div class="border rounded-lg p-1 @if($day->month != $currentMonth) bg-gray-100 dark:bg-gray-700 @endif">
                            <div class="text-xs font-semibold mb-1">{{ $day->format('d') }}</div>

                            @if(!$day->isWeekend())
                                @php
                                    $scheduleFound = false;
                                @endphp

                                @foreach($schedules as $schedule)
                                    @if($day->between($schedule->start_date, $schedule->end_date))
                                        <div class="text-xs">
                                            @php
                                                $wfhDays = explode(',', $schedule->wfh_days);
                                            @endphp
                                            @if(in_array($day->format('l'), $wfhDays))
                                                <span class="block bg-blue-500 text-white rounded-lg p-1">WFH</span>
                                            @else
                                                <span class="block bg-green-500 text-white rounded-lg p-1">Office</span>
                                            @endif
                                        </div>
                                        @php
                                            $scheduleFound = true;
                                            break;
                                        @endphp
                                    @endif
                                @endforeach

                                @if(!$scheduleFound && $day->month == $currentMonth)
                                    <span class="block bg-yellow-500 text-white mt-1 rounded-lg p-1 text-xs">No Sched</span>
                                @endif
                            @endif

                            @foreach($holidays as $holiday)
                                @if($holiday->holiday_date->isSameDay($day))
                                    <span class="block bg-red-500 text-white mt-1 rounded-lg p-1 text-xs">{{ $holiday->description }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
