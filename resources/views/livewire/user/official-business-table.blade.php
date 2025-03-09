<div class="w-full text-sm" x-data="{selectedTab: 'approved',}" x-cloak>

    <style>
        .scrollbar-thin1::-webkit-scrollbar {
                       width: 5px;
                   }

       .scrollbar-thin1::-webkit-scrollbar-thumb {
           background-color: #c5c5c54b;
       }

       .scrollbar-thin1::-webkit-scrollbar-track {
           background-color: #ffffff23;
       }

       @media (max-width: 1024px){
           .custom-d{
               display: block;
           }
       }

       @media (max-width: 768px){
           .m-scrollable{
               width: 100%;
               overflow-x: scroll;
           }
       }

       @media (min-width:1024px){
           .custom-p{
               padding-bottom: 14px !important;
           }
       }

       @-webkit-keyframes spinner-border {
           to {
               transform: rotate(360deg);
           }
       }

       @keyframes spinner-border {
           to {
               transform: rotate(360deg);
           }
       }

       .spinner-border {
           display: inline-block;
           width: 1rem;
           height: 1rem;
           vertical-align: text-bottom;
           border: 2px solid currentColor;
           border-right-color: transparent;
           border-radius: 50%;
           -webkit-animation: spinner-border .75s linear infinite;
           animation: spinner-border .75s linear infinite;
           color: rgb(0, 255, 42);
       }

       .dot-anim{
            background: rgb(168, 168, 255);
            width: 18px;
            height: 18px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: dot 2s ease-in-out infinite;
       }

       @keyframes dot{
            0%{
                transform: translate(-50%, -50%) scale(0.8);
            }50%{
                transform: translate(-50%, -50%) scale(1.2);
            }100%{
                transform: translate(-50%, -50%) scale(0.8);
            }
       }
   </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible p-3">
            <div class="pb-4 mb-3">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    My Official Business
                </h1>
            </div>

            {{-- @if($ongoingObs)
                <div class="w-full flex flex-col justify-center items-center mb-6 bg-gray-300 dark:bg-slate-900 border border-gray-300 dark:border-slate-900 shadow-xl" x-data="{ showDialog: false }">
                    <style>
                        .obs{
                            height: 200px;
                            width: 66%;
                        }

                        .obs2{
                            height: 200px;
                            width: 34%;
                        }

                        @media (max-width: 768px){
                            .obs,
                            .obs2{
                                width: 100%;
                            }
                        }
                    </style>

                    <div class="flex flex-col sm:flex-row justify-center items-center w-full overflow-hidden">
                        <div class="block shadow dark:bg-gray-900 relative obs">
                            <div class="w-full p-4">
                                <div class="flex w-full">
                                    <p class=""><span class="{{ $obStatus == 'ONGOING' ? 'text-green-500' : 'text-orange-500' }}">{{ $obStatus }}</span> Official Business: {{ $ongoingObs->company }}</p>
                                </div>
                                <div class="flex w-full">
                                    <div class="flex items-center">
                                        <p class="mr-2">Current Location: </p>
                                        <div class="relative flex items-center justify-center mr-3" style="height: 18px; width: 18px;">
                                            <div class="bg-blue-500 rounded-full border border-white z-10" style="height: 12px; width: 12px;"></div>
                                            <div class="bg-blue-500 rounded-full dot-anim opacity-40"></div>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <p class="">OB Location: </p><img src="{{ asset('/images/red-dot.png') }}" alt="map icon" style="width: 25px; height: 25px; margin-bottom:-3px;" />
                                    </div>
                                </div>
                                    <div>
                                        <p class="">Company: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->company }}</span></p>
                                        <p class="">Address: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->address }}</span></p>
                                        <p class="">Date: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->date }}</span></p>
                                        <p class="">Stary Time: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->time_start }}</span></p>
                                        <p class="">End Time: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->time_end }}</span></p>
                                        <p class="">Purpose: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->purpose }}</span></p>
                                    </div>
                            </div>
                            <div wire:ignore style="height: 240px; width: 100%;">
                                <div id="map2" style="height: 100%; width: 100%; margin: 0;"></div>
                            </div>
                        </div>

                        <div class="block p-6 shadow dark:bg-gray-900 relative obs2">
                            <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white text-center">OB ATTENDANCE</h5>
                            <div class="grid grid-cols-1 gap-2 p-4">
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch({{ $ongoingObs->id }}, 'timeIn', 'Time In')"
                                        {{ $hasObTimeIn ? 'disabled' : '' }}
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                            Time In{{ $hasObTimeIn ? (': ' . $hasObTimeIn) : '' }}
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch({{ $ongoingObs->id }}, 'timeOut', 'Time Out')"
                                        {{ $hasObTimeIn && !$hasObTimeOut ? '' : 'disabled' }}
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                            Time Out{{ $hasObTimeOut ? (': ' . $hasObTimeOut) : '' }}
                                        </span>
                                    </button>
                                </div>

                                @if($isWithinRadius)
                                    <div class="flex justify-center">
                                        <p class="text-blue-500 underline" @click="showDialog = true">OB Details</p>
                                    </div>
                                @endif
                            </div>

                            @if(!$isWithinRadius && $isTodayIsOb)
                                <div
                                    class="absolute inset-0 flex justify-center items-center bg-gray-200 dark:bg-slate-700 bg-opacity-90 dark:bg-opacity-90">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock" style="font-size: 3rem;"></i>
                                        <p class="font-bold mb-4">You have not arrived at <br>
                                            the OB location.</p>
                                        <p class="text-white bg-blue-500 p-2 rounded-md cursor-pointer hover:bg-blue-600" @click="showDialog = true">View OB Details</p>
                                    </div>
                                </div>
                            @elseif(!$isTodayIsOb)
                                <div
                                    class="absolute inset-0 flex justify-center items-center bg-gray-200 dark:bg-slate-700 bg-opacity-90 dark:bg-opacity-90">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock" style="font-size: 3rem;"></i>
                                        <p class="font-bold mb-4">Attendance will be available on <br>{{ \Carbon\Carbon::parse($ongoingObs->date)->format('F d, Y') }}</p>
                                        <p class="text-white bg-blue-500 p-2 rounded-md cursor-pointer hover:bg-blue-600" @click="showDialog = true">View OB Details</p>
                                    </div>
                                </div>
                            @endif

                            <div 
                                x-show="showDialog" 
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="translate-y-full opacity-0"
                                x-transition:enter-end="translate-y-0 opacity-100"
                                x-transition:leave="transition ease-in duration-200 transform"
                                x-transition:leave-start="translate-y-0 opacity-100"
                                x-transition:leave-end="translate-y-full opacity-0"
                                x-cloak 
                                class="absolute inset-0 bg-gray-200 dark:bg-slate-700 overflow-hidden">
                                <div class="p-6 scrollbar-thin1" style="height: 100%; overflow-y:scroll">
                                    <div>
                                        <button @click="showDialog = false" class="float-right focus:outline-none">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <h5 class="text-xl font-bold mb-4 text-center text-gray-700 dark:text-gray-50">OB Details</h5>
                                    <p class="">Company: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->company }}</span></p>
                                    <p class="">Address: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->address }}</span></p>
                                    <p class="">Date: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->date }}</span></p>
                                    <p class="">Stary Time: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->time_start }}</span></p>
                                    <p class="">End Time: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->time_end }}</span></p>
                                    <p class="">Purpose: <span class="text-gray-700 dark:text-gray-100">{{ $ongoingObs->purpose }}</span></p>
                                    <div class="w-full flex justify-center mt-6">
                                        <button 
                                            class="text-white bg-blue-500 p-2 rounded-md cursor-pointer hover:bg-blue-600"
                                            @click="showDialog = false">
                                            Attendance
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif --}}


            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'completed'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'unattended'">
                    <label for="search2" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search2" wire:model.live="search2"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'upcoming'">
                    <label for="search3" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search3" wire:model.live="search3"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'requests'">
                    <label for="search4" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search4" wire:model.live="search4"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'approved'">
                    <label for="search5" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search5" wire:model.live="search5"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'disapproved'">
                    <label for="search6" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search6" wire:model.live="search6"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">
                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddOB" 
                            class="text-sm mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Apply OB
                        </button>
                    </div>
                </div>

            </div>


            <div class="overflow-hidden text-sm pb-3">
                <div class="flex gap-2 overflow-x-auto -mb-2" class="relative">
                    <button @click="selectedTab = 'approved'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'approved', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'approved' }"
                        class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Approved OB
                    </button>
                    <button @click="selectedTab = 'requests'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'requests', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'requests' }"
                        class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        OB Requests
                    </button>
                    <button @click="selectedTab = 'disapproved'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'disapproved', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'disapproved' }"
                        class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Disapproved OB
                    </button>
                    <button @click="selectedTab = 'completed'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'completed', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'completed' }"
                        class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Attended
                    </button>
                    <button @click="selectedTab = 'upcoming'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'upcoming', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'upcoming' }"
                        class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Upcoming
                    </button>
                    <button @click="selectedTab = 'unattended'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'unattended', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'unattended' }"
                        class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Not Attended
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                        <div x-show="selectedTab === 'completed'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Reference No.
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Company
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Address
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Date
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time Schedule
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time In/Out
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Status
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($completedObs as $obs)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->reference_number }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->company }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->address }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->date }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Start: {{ $obs->time_start }} <br>
                                                    End: {{ $obs->time_end }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    In: {{ $obs->time_in }} <br>
                                                    Out: {{ $obs->time_out }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    <div 
                                                        class="truncate max-w-xs"
                                                        style="max-width: 20ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                    >
                                                        {{ $obs->purpose }}
                                                    </div>                                                
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    @if($obs->status == 1)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @elseif($obs->status == 2)
                                                        <span
                                                        class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="viewThisOB({{ $obs->id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($completedObs->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $completedObs->links() }}
                            </div>
                        </div>
                        <div x-show="selectedTab === 'upcoming'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Reference No.
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Company
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Address
                                            </th>
                                            {{-- <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Geolocation
                                            </th> --}}
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Date
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Status
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($upcomingObs as $obs)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->reference_number }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->company }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->address }}
                                                </td>
                                                {{-- <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td> --}}
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->date }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Start: {{ $obs->time_start }} <br>
                                                    End: {{ $obs->time_end }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    <div 
                                                        class="truncate max-w-xs"
                                                        style="max-width: 20ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                    >
                                                        {{ $obs->purpose }}
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    @if($obs->status == 1)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @elseif($obs->status == 2)
                                                        <span
                                                        class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">                                        
                                                        <button wire:click="viewThisOB({{ $obs->id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($upcomingObs->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $upcomingObs->links() }}
                            </div>
                        </div>
                        <div x-show="selectedTab === 'unattended'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Reference No.
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Company
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Address
                                            </th>
                                            {{-- <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Geolocation
                                            </th> --}}
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Date
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Status
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($unattendedObs as $obs)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->reference_number }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->company }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->address }}
                                                </td>
                                                {{-- <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td> --}}
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->date }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Start: {{ $obs->time_start }} <br>
                                                    End: {{ $obs->time_end }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    <div 
                                                        class="truncate max-w-xs"
                                                        style="max-width: 20ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                    >
                                                        {{ $obs->purpose }}
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    @if($obs->status == 1)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @elseif($obs->status == 2)
                                                        <span
                                                        class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="viewThisOB({{ $obs->id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($unattendedObs->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $unattendedObs->links() }}
                            </div>
                        </div>
                        <div x-show="selectedTab === 'requests'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Approval Status
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Reference No.
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Company
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Address
                                            </th>
                                            {{-- <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Geolocation
                                            </th> --}}
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Date
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Status
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($obRequests as $obs)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap relative" style="overflow-y: visible">
                                                    <div>
                                                        <span class="opacity-60">Supervisor: </span>
                                                            {{ $obs->supervisor }} <br/>
                                                        <span class="opacity-60">Status: </span>
                                                            <span class="{{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'text-green-500' :
                                                            (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'text-red-500' : 'text-orange-500') }}">
                                                        
                                                                {{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'Approved' :
                                                                (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'Disapproved' : 'Pending') }}
                                                            </span><br/>
                                                        @if($obs->date_sup_approved)
                                                            <span class="opacity-60">Date Approved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_approved)->format('F d, Y') }} <br/>
                                                        @endif
                                                        @if($obs->date_sup_disapproved)
                                                            <span class="opacity-60">Date Disapproved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_disapproved)->format('F d, Y') }} <br/>
                                                        @endif
                                                    </div>
                                                    <hr class="my-1 opacity-60">
                                                    <div>
                                                        <span class="opacity-60">HR: </span><span class="{{ $obs->hr ? '' : 'text-orange-500' }}">{{ $obs->hr ?: 'Pending' }}</span> <br/>
                                                        @if($obs->hr)
                                                            <span class="opacity-60">Status: </span>
                                                                <span class="{{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'text-green-500' :
                                                                (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'text-red-500' : 'text-orange-500') }}">
                                                            
                                                                    {{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'Approved' :
                                                                    (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'Disapproved' : 'Pending') }}
                                                                </span><br/>
                                                            @if($obs->date_sup_approved)
                                                                <span class="opacity-60">Date Approved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_approved)->format('F d, Y') }} <br/>
                                                            @endif
                                                            @if($obs->date_sup_disapproved)
                                                                <span class="opacity-60">Date Disapproved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_disapproved)->format('F d, Y') }} <br/>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->reference_number }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->company }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->address }}
                                                </td>
                                                {{-- <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td> --}}
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->date }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Start: {{ $obs->time_start }} <br>
                                                    End: {{ $obs->time_end }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    <div 
                                                        class="truncate max-w-xs"
                                                        style="max-width: 20ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                    >
                                                        {{ $obs->purpose }}
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    @if($obs->status == 1)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @elseif($obs->status == 2)
                                                        <span
                                                        class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">

                                                        @if(!$obs->date_sup_approved && !$obs->date_sup_disapproved)
                                                            <button wire:click="toggleEditOB({{ $obs->id }})" 
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                focus:outline-none" title="Edit">
                                                                <i class="fas fa-pencil-alt ml-3"></i>
                                                            </button>
                                                            <button wire:click="toggleDeleteOB({{ $obs->id }})" 
                                                                class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                                dark:hover:text-red-900" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                        
                                                        <button wire:click="viewThisOB({{ $obs->id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($obRequests->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $obRequests->links() }}
                            </div>
                        </div>
                        <div x-show="selectedTab === 'disapproved'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Approval Status
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Reference No.
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Company
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Address
                                            </th>
                                            {{-- <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Geolocation
                                            </th> --}}
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Date
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Status
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($disapprovedObs as $obs)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap relative" style="overflow-y: visible">
                                                    <div>
                                                        <span class="opacity-60">Supervisor: </span>
                                                            {{ $obs->supervisor }} <br/>
                                                        <span class="opacity-60">Status: </span>
                                                            <span class="{{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'text-green-500' :
                                                            (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'text-red-500' : 'text-orange-500') }}">
                                                        
                                                                {{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'Approved' :
                                                                (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'Disapproved' : 'Pending') }}
                                                            </span><br/>
                                                        @if($obs->date_sup_approved)
                                                            <span class="opacity-60">Date Approved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_approved)->format('F d, Y') }} <br/>
                                                        @endif
                                                        @if($obs->date_sup_disapproved)
                                                            <span class="opacity-60">Date Disapproved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_disapproved)->format('F d, Y') }} <br/>
                                                        @endif
                                                    </div>
                                                    <hr class="my-1 opacity-60">
                                                    <div>
                                                        <span class="opacity-60">HR: </span><span class="{{ $obs->hr ? '' : 'text-orange-500' }}">{{ $obs->hr ?: 'Pending' }}</span> <br/>
                                                        @if($obs->hr)
                                                            <span class="opacity-60">Status: </span>
                                                                <span class="{{ $obs->date_approved ? 'text-green-500' : ($obs->date_disapproved ? 'text-red-500' : 'text-orange-500') }}">
                                                                    {{ $obs->date_approved ? 'Approved' : ($obs->date_disapproved ? 'Disapproved' : 'Pending') }}
                                                                </span><br/>
                                                            @if($obs->date_approved)
                                                                <span class="opacity-60">Date Approved: </span>{{ \Carbon\Carbon::parse($obs->date_approved)->format('F d, Y') }} <br/>
                                                            @endif
                                                            @if($obs->date_disapproved)
                                                                <span class="opacity-60">Date Disapproved: </span>{{ \Carbon\Carbon::parse($obs->date_disapproved)->format('F d, Y') }} <br/>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->reference_number }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->company }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->address }}
                                                </td>
                                                {{-- <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td> --}}
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->date }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Start: {{ $obs->time_start }} <br>
                                                    End: {{ $obs->time_end }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    <div 
                                                        class="truncate max-w-xs"
                                                        style="max-width: 20ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                    >
                                                        {{ $obs->purpose }}
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    @if($obs->status == 1)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @elseif($obs->status == 2)
                                                        <span
                                                        class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="viewThisOB({{ $obs->id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($disapprovedObs->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $disapprovedObs->links() }}
                            </div>
                        </div>
                        <div x-show="selectedTab === 'approved'">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Approved By
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                Reference No.
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Company
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Address
                                            </th>
                                            {{-- <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Geolocation
                                            </th> --}}
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Date
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Time
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Status
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($approvedObs as $obs)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap relative" style="overflow-y: visible">
                                                    <div>
                                                        <span class="opacity-60">Supervisor: </span>
                                                            {{ $obs->supervisor }} <br/>
                                                        <span class="opacity-60">Status: </span>
                                                            <span class="{{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'text-green-500' :
                                                            (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'text-red-500' : 'text-orange-500') }}">
                                                        
                                                                {{ isset($obs->date_sup_approved) && $obs->date_sup_approved ? 'Approved' :
                                                                (isset($obs->date_sup_disapproved) && $obs->date_sup_disapproved ? 'Disapproved' : 'Pending') }}
                                                            </span><br/>
                                                        @if($obs->date_sup_approved)
                                                            <span class="opacity-60">Date Approved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_approved)->format('F d, Y') }} <br/>
                                                        @endif
                                                        @if($obs->date_sup_disapproved)
                                                            <span class="opacity-60">Date Disapproved: </span>{{ \Carbon\Carbon::parse($obs->date_sup_disapproved)->format('F d, Y') }} <br/>
                                                        @endif
                                                    </div>
                                                    <hr class="my-1 opacity-60">
                                                    <div>
                                                        <span class="opacity-60">HR: </span><span class="{{ $obs->hr ? '' : 'text-orange-500' }}">{{ $obs->hr ?: 'Pending' }}</span> <br/>
                                                        @if($obs->hr)
                                                            <span class="opacity-60">Status: </span>
                                                                <span class="{{ $obs->date_approved ? 'text-green-500' : ($obs->date_disapproved ? 'text-red-500' : 'text-orange-500') }}">
                                                                    {{ $obs->date_approved ? 'Approved' : ($obs->date_disapproved ? 'Disapproved' : 'Pending') }}
                                                                </span><br/>
                                                            @if($obs->date_approved)
                                                                <span class="opacity-60">Date Approved: </span>{{ \Carbon\Carbon::parse($obs->date_approved)->format('F d, Y') }} <br/>
                                                            @endif
                                                            @if($obs->date_disapproved)
                                                                <span class="opacity-60">Date Disapproved: </span>{{ \Carbon\Carbon::parse($obs->date_disapproved)->format('F d, Y') }} <br/>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->reference_number }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->company }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->address }}
                                                </td>
                                                {{-- <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td> --}}
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    {{ $obs->date }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Start: {{ $obs->time_start }} <br>
                                                    End: {{ $obs->time_end }}
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    <div 
                                                        class="truncate max-w-xs"
                                                        style="max-width: 20ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                    >
                                                        {{ $obs->purpose }}
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    @if($obs->status == 1)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @elseif($obs->status == 2)
                                                        <span
                                                        class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="viewThisOB({{ $obs->id }})" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="View">
                                                            <i class="fas fa-eye ml-3"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($approvedObs->isEmpty())
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                        No records!
                                    </div> 
                                @endif
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $approvedObs->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- Add and Edit OB Modal --}}
    <x-modal id="obModal" maxWidth="2xl" wire:model="editOB">
        <div class="p-4">
            <div class="rounded-lg mb-4 p-4 dark:text-gray-50 text-slate-900 font-bold text-lg">
                {{ $addOB ? 'Apply' : 'Edit' }} Official Business
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Company <span class="text-red-500">*</span></label>
                    <input type="text" id="company" wire:model.live='company' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('company') 
                        <span class="text-red-500 text-sm">The company is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address <span class="text-red-500">*</span></label>
                    <input type="text" id="address" wire:model.live='address' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('address') 
                        <span class="text-red-500 text-sm">The address is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2">
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date <span class="text-red-500">*</span></label>
                    <input type="date" id="date" wire:model.live='date' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('date') 
                        <span class="text-red-500 text-sm">The date is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="startTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" id="startTime" wire:model.live='startTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('startTime') 
                        <span class="text-red-500 text-sm">The start time is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="endTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">End Time <span class="text-red-500">*</span></label>
                    <input type="time" id="endTime" wire:model.live='endTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('endTime') 
                        <span class="text-red-500 text-sm">The end time is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Purpose <span class="text-red-500">*</span></label>
                    <textarea type="text" id="purpose" cols="30" rows="4" wire:model.live='purpose' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"></textarea>
                    @error('purpose') 
                        <span class="text-red-500 text-sm">The purpose is required!</span> 
                    @enderror
                </div>
            </div>

            
            {{-- <div class="mt-4  mb-1">
                <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Geolocation <span class="text-red-500">*</span></label>
            </div>
            <div class="flex-col justify-center w-full bg-gray-200 dark:bg-slate-700 border border-gray-300 mb-2" style="border-radius: 8px;">
                <div style="border-radius: 8px 8px 0 0;">
                    <input id="locationSearch" type="text" 
                           placeholder="Search location..." 
                           class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300
                                dark:hover:bg-slate-600 dark:border-slate-600
                                dark:text-gray-300 dark:bg-gray-800" style="border-radius: 8px 8px 0 0;"/>
                </div>
                <div wire:ignore class="w-full">
                    <div id="map" style="height: 250px; width: 100%; margin: 0;"></div>
                </div>

                <div class="text-sm flex mt-2 px-4">
                    <div class="w-1/2 mb-2">
                        Lat: <span class="text-gray-800 dark:text-gray-50">{{ $newLatitude ?? '...' }}</span> <br>
                        Lng: <span class="text-gray-800 dark:text-gray-50">{{ $newLongitude ?? '...' }}</span>
                    </div>
                </div>
                @error('newLatitude') 
                    <span class="text-red-500 text-sm">The geolocation is required!</span> 
                @enderror
            </div> --}}

             {{-- Save and Cancel buttons --}}
             <div class="mt-6 flex justify-end col-span-2">
                <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="saveOB">
                    <div wire:loading wire:target="saveOB" style="margin-right: 5px">
                        <div class="spinner-border small text-primary" role="status">
                        </div>
                    </div>
                    Save
                </button>
                <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                    Cancel
                </p>
            </div>
          
        </div>
    </x-modal>

     {{-- View OB Modal --}}
     <x-modal id="obModal" maxWidth="2xl" wire:model="viewOB">
        <div class="p-4">
            <div class="rounded-lg mb-4 p-4 dark:text-gray-50 text-slate-900 font-bold text-lg">
                Official Business: {{ $company}}
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Company</label>
                    <input type="text" id="company" wire:model='company' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                </div>
                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address</label>
                    <input type="text" id="address" wire:model='address' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                </div>
                <div class="col-span-2">
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date</label>
                    <input type="date" id="date" wire:model='date' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="startTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start Time</label>
                    <input type="time" id="startTime" wire:model='startTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="endTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">End Time</label>
                    <input type="time" id="endTime" wire:model='endTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                </div>
                <div class="col-span-2">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Purpose</label>
                    <textarea type="text" id="purpose" cols="30" rows="4" wire:model='purpose' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly></textarea>
                </div>

                @if($supDisapprovedDate == 'N/A')
                    <div class="col-span-2 sm:col-span-1">
                        <label for="approvedBySup" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Approved By (Supervisor)</label>
                        <input type="text" id="approvedBySup" wire:model='approvedBySup' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="supApprovedDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Approved Date (Supervisor)</label>
                        <input type="text" id="supApprovedDate" wire:model='supApprovedDate' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                @endif

                @if($supDisapprovedDate != 'N/A')
                    <div class="col-span-2 sm:col-span-1">
                        <label for="disapprovedBySup" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Dispproved By (Supervisor)</label>
                        <input type="text" id="disapprovedBySup" wire:model='disapprovedBySup' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="supDisapprovedDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Disapproved Date (Supervisor)</label>
                        <input type="text" id="supDisapprovedDate" wire:model='supDisapprovedDate' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                @endif

                @if($disapprovedDate == 'N/A')
                    <div class="col-span-2 sm:col-span-1">
                        <label for="approvedBy" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Approved By (HR)</label>
                        <input type="text" id="approvedBy" wire:model='approvedBy' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="approvedDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Approved Date (HR)</label>
                        <input type="text" id="approvedDate" wire:model='approvedDate' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                @endif

                @if($disapprovedDate != 'N/A')
                    <div class="col-span-2 sm:col-span-1">
                        <label for="disapprovedBy" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Disapproved By (HR)</label>
                        <input type="text" id="disapprovedBy" wire:model='disapprovedBy' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="disapprovedDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Disapproved Date (HR)</label>
                        <input type="text" id="disapprovedDate" wire:model='disapprovedDate' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
                    </div>
                @endif

            </div>
   
            {{-- <div class="mt-4  mb-1">
                <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Geolocation</label>
            </div>
            <div class="flex-col justify-center w-full bg-gray-200 dark:bg-slate-700 border border-gray-300 mb-2" style="border-radius: 8px;">
                <div wire:ignore class="w-full">
                    <div id="map3" style="height: 250px; width: 100%; margin: 0;"></div>
                </div>

                <div class="text-sm flex mt-2 px-4">
                    <div class="w-1/2 mb-2">
                        Lat: <span class="text-gray-800 dark:text-gray-50">{{ $registeredLatitude ?? '...' }}</span> <br>
                        Lng: <span class="text-gray-800 dark:text-gray-50">{{ $registeredLongitude ?? '...' }}</span>
                    </div>
                </div>
            </div> --}}

            <div class="mt-6 flex justify-end col-span-2">
                <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                    Close
                </p>
            </div>
          
        </div>
    </x-modal>

    {{-- Delete Modal --}}
    <x-modal id="deleteModal" maxWidth="md" wire:model="deleteId" centered>
        <div class="p-4">
            <div class="mb-4 text-slate-900 dark:text-gray-100 font-bold">
                Confirm Deletion
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">
                Are you sure you want to delete this?
            </label>
            <form wire:submit.prevent='deleteData'>
                <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                    <button class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <div wire:loading wire:target="deleteData" style="margin-bottom: 5px;">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                        Delete
                    </button>
                    <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Cancel
                    </p>
                </div>
            </form>

        </div>
    </x-modal>

    {{-- Confirmation Modal --}}
    <x-modal id="punchConfirmation" maxWidth="md" centered wire:model="showConfirmation">
        <div class="p-4">
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    Punch Confirmation
                </h3>
                <button @click="show = false"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="space-y-6">
                <p class="text-gray-700 dark:text-gray-300">
                    Are you sure you want to punch {{ $verifyType }}?
                </p>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button wire:click="recordObAttendance"
                        class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                        Yes
                    </button>
                    <button @click="show = false"
                        class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800">
                        No
                    </button>
                </div>
            </div>
        </div>
    </x-modal>

</div>


{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs&libraries=places"></script>
<script>
    let map, map2, map3, marker, marker3, currentMarker, destinationMarker, searchBox, radiusCircle;

    function initMap() {
        const defaultLocation = { lat: 14.5995, lng: 120.9842 };
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: defaultLocation,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
            title: 'Your OB Location',
            animation: google.maps.Animation.DROP
        });

        google.maps.event.addListener(marker, 'dragend', function(event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();
            updateLivewireLocation(lat, lng);
        });

        const input = document.getElementById("locationSearch");
        searchBox = new google.maps.places.SearchBox(input);

        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length === 0) return;
            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            const location = place.geometry.location;
            const lat = location.lat();
            const lng = location.lng();
            map.setCenter(location);
            marker.setPosition(location);
            updateLivewireLocation(lat, lng);
        });
    }
    function updateLivewireLocation(lat, lng) {
        @this.set('newLatitude', lat);
        @this.set('newLongitude', lng);
    }


    const createAnimatedMarkerIcon = () => {
        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="-20 -20 40 40">
                <circle cx="0" cy="0" r="8" fill="#4286f4c7">
                    <animate
                        attributeName="r"
                        values="8;16;8"
                        dur="2s"
                        repeatCount="indefinite"
                        begin="0s"
                    />
                    <animate
                        attributeName="fill-opacity"
                        values="0.3;0.1;0.3"
                        dur="2s"
                        repeatCount="indefinite"
                        begin="0s"
                    />
                </circle>
                <!-- Main circle -->
                <circle cx="0" cy="0" r="8" fill="#4285F4" fill-opacity="0.7" stroke="white" stroke-width="1" />
            </svg>
        `;
    
        return {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20),
            origin: new google.maps.Point(0, 0)
        };
    };

    function initMap2() {
        const destination = { lat: {{ $ongoingObs ? $ongoingObs->lat : 0.00 }}, lng: {{ $ongoingObs ? $ongoingObs->lng : 0.00 }} };
        const lat = @this.latitude;
        const lng = @this.longitude;
        const currentLocation = { lat: parseFloat(lat), lng: parseFloat(lng) };

        if (!map2) {
            map2 = new google.maps.Map(document.getElementById("map2"), {
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }],
                    },
                ],
            });
        }

        if (currentMarker) {
            currentMarker.setPosition(currentLocation);
        } else {
            currentMarker = new google.maps.Marker({
                position: currentLocation,
                map: map2,
                title: 'Your Location',
                icon: createAnimatedMarkerIcon()
            });
        }

        destinationMarker = new google.maps.Marker({
            position: destination,
            map: map2,
            title: 'OB Location',
            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
        });

        if (radiusCircle) {
            radiusCircle.setMap(null);
        }

        radiusCircle = new google.maps.Circle({
            strokeColor: "#FF0000",
            strokeOpacity: 0.5,
            strokeWeight: 1,
            fillColor: "#FF0000",
            fillOpacity: 0.1,
            map: map2,
            center: destination,
            radius: 300
        });

        const bounds = new google.maps.LatLngBounds();
    
        bounds.extend(currentLocation);
        bounds.extend(destination);
        
        map2.fitBounds(bounds);
        bounds.union(radiusCircle.getBounds());
        
        const padding = {
            top: 50,
            right: 50,
            bottom: 50,
            left: 50
        };
        map2.fitBounds(bounds, padding);
    }

    function initMap3() {
        let lat, lng;
        if (@this.registeredLatitude && @this.registeredLongitude) {
            lat = parseFloat(@this.registeredLatitude);
            lng = parseFloat(@this.registeredLongitude);
        } else {
            lat = 14.5995;
            lng = 120.9842;
        }
        const obLocation = { lat, lng };

        if (!map3) {
            map3 = new google.maps.Map(document.getElementById("map3"), {
                zoom: 15,
                center: obLocation,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });
        }

        map3.setCenter(obLocation);
        if (!marker3) {
            marker3 = new google.maps.Marker({
                position: obLocation,
                map: map3,
                draggable: false,
                title: 'Your OB Location',
                animation: google.maps.Animation.DROP
            });
        } else {
            marker3.setPosition(obLocation);
        }
    }

    document.addEventListener('DOMContentLoaded', initMap);
    document.addEventListener('DOMContentLoaded', initMap2);
    document.addEventListener('DOMContentLoaded', initMap3);
    setInterval(initMap2 , 5000);
    setInterval(initMap3 , 5000);
</script> --}}