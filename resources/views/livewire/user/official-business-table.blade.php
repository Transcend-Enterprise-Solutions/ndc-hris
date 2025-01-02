<div class="w-full text-sm" x-data="{
    selectedTab: 'completed',
}" x-cloak>

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
   </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible p-3">
            <div class="pb-4 mb-3">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    My Official Business
                </h1>
            </div>

            @if($ongoingObs)
                @foreach ($ongoingObs as $obs)
                    <div class="w-full flex flex-col justify-center items-center mb-6 bg-gray-300 dark:bg-slate-900 border border-gray-300 dark:border-slate-900 shadow-xl" x-data="{ showDialog: false }">
                        <style>
                            .obs{
                                height: 240px;
                                width: 66%;
                            }

                            .obs2{
                                height: 240px;
                                width: 34%;
                            }

                            @media (max-width: 768px){
                                .obs,
                                .obs2{
                                    width: 100%;
                                }
                            }
                        </style>

                        <p class="py-2">Official Business: {{ $obs->company }}</p>
                        <div class="flex flex-col sm:flex-row justify-center items-center w-full overflow-hidden">
                            <div class="block shadow dark:bg-gray-900 relative obs">
                                <div wire:ignore style="height: 240px; width: 100%;">
                                    <div id="map" style="height: 100%; width: 100%; margin: 0;"></div>
                                </div>
                            </div>

                            <div class="block p-6 shadow dark:bg-gray-900 relative obs2">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white text-center">OB ATTENDANCE</h5>
                                <div class="grid grid-cols-1 gap-2 p-4">
                                    <div class="flex justify-center">
                                        <button wire:click="confirmPunch('morningIn', 'Morning In')"
                                            class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span
                                                class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                                Time In
                                            </span>
                                        </button>
                                    </div>
                                    <div class="flex justify-center">
                                        <button wire:click="confirmPunch('morningOut', 'Morning Out')"
                                            class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span
                                                class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                                Time Out
                                            </span>
                                        </button>
                                    </div>

                                    @if($isWithinRadius)
                                        <div class="flex justify-center">
                                            <p class="text-blue-500 underline" @click="showDialog = true">OB Details</p>
                                        </div>
                                    @endif
                                </div>

                                @if(!$isWithinRadius)
                                    <div
                                        class="absolute inset-0 flex justify-center items-center bg-gray-200 dark:bg-slate-700 bg-opacity-90 dark:bg-opacity-90">
                                        <div class="text-center">
                                            <i class="bi bi-person-lock" style="font-size: 3rem;"></i>
                                            <p class="font-bold mb-4">You have not arrived at <br>
                                                the OB location.</p>
                                            <p class="text-white bg-blue-500 p-2 rounded-md cursor-pointer hover:bg-blue-600" @click="showDialog = true">OB Details</p>
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
                                        <p class="">Company: <span class="text-gray-700 dark:text-gray-100">{{ $obs->company }}</span></p>
                                        <p class="">Address: <span class="text-gray-700 dark:text-gray-100">{{ $obs->address }}</span></p>
                                        <p class="">Date: <span class="text-gray-700 dark:text-gray-100">{{ $obs->date }}</span></p>
                                        <p class="">Stary Time: <span class="text-gray-700 dark:text-gray-100">{{ $obs->time_start }}</span></p>
                                        <p class="">End Time: <span class="text-gray-700 dark:text-gray-100">{{ $obs->time_end }}</span></p>
                                        <p class="">Purpose: <span class="text-gray-700 dark:text-gray-100">{{ $obs->purpose }}</span></p>
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
                @endforeach
            @endif


            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'completed'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter reference number or company">
                </div>
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'ongoing'">
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

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">
                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddOB" 
                            class="text-sm mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Add OB
                        </button>
                    </div>
                </div>

            </div>


            <div class="overflow-hidden text-sm pb-3">
                <div class="flex gap-2 overflow-x-auto -mb-2" class="relative">
                    <button @click="selectedTab = 'completed'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'completed', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'completed' }"
                        class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                        Completed
                    </button>
                    <button @click="selectedTab = 'upcoming'"
                        :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'upcoming', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'upcoming' }"
                        class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                        Upcoming
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
                                                Geolocation
                                            </th>
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
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td>
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
                                                    @if($obs->status)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="toggleEditOB" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="Edit">
                                                            <i class="fas fa-pencil-alt ml-3"></i>
                                                        </button>
                                                        <button wire:click="toggleDeleteOB" 
                                                            class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                            dark:hover:text-red-900" title="Delete">
                                                            <i class="fas fa-trash"></i>
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
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                Geolocation
                                            </th>
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
                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                    Lat: {{ $obs->lat }} <br>
                                                    Lng: {{ $obs->lng }}
                                                </td>
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
                                                    @if($obs->status)
                                                        <span
                                                            class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                    @else
                                                        <span
                                                            class="text-xs text-white bg-orange-500 rounded-lg py-1.5 px-4">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <div class="relative">
                                                        <button wire:click="toggleEditOB" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="Edit">
                                                            <i class="fas fa-pencil-alt ml-3"></i>
                                                        </button>
                                                        <button wire:click="toggleDeleteOB" 
                                                            class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                            dark:hover:text-red-900" title="Delete">
                                                            <i class="fas fa-trash"></i>
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
                {{ $addOB ? 'Add' : 'Edit' }} Official Business
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Company <span class="text-red-500">*</span></label>
                    <input type="text" id="company" wire:model='company' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('company') 
                        <span class="text-red-500 text-sm">The company is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address <span class="text-red-500">*</span></label>
                    <input type="text" id="address" wire:model='address' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('address') 
                        <span class="text-red-500 text-sm">The address is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2">
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date <span class="text-red-500">*</span></label>
                    <input type="date" id="date" wire:model='date' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('date') 
                        <span class="text-red-500 text-sm">The date is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="startTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" id="startTime" wire:model='startTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('startTime') 
                        <span class="text-red-500 text-sm">The start time is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="endTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">End Time <span class="text-red-500">*</span></label>
                    <input type="time" id="endTime" wire:model='endTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                    @error('endTime') 
                        <span class="text-red-500 text-sm">The end time is required!</span> 
                    @enderror
                </div>
                <div class="col-span-2">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Purpose <span class="text-red-500">*</span></label>
                    <textarea type="text" id="purpose" cols="30" rows="4" wire:model='purpose' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"></textarea>
                    @error('purpose') 
                        <span class="text-red-500 text-sm">The purpose is required!</span> 
                    @enderror
                </div>
            </div>

            
            <div class="mt-4  mb-1">
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
                        Lat: <span class="text-gray-800 dark:text-gray-50">{{ $registeredLatitude ?? '...' }}</span> <br>
                        Lng: <span class="text-gray-800 dark:text-gray-50">{{ $registeredLongitude ?? '...' }}</span>
                    </div>
                </div>
                @error('registeredLatitude') 
                    <span class="text-red-500 text-sm">The geolocation is required!</span> 
                @enderror
            </div>

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

</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs&libraries=places"></script>
<script>
    let map, marker, searchBox;

    function initMap() {
        const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila coordinates

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

        // Marker initialization
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
            title: 'Your OB Location',
            animation: google.maps.Animation.DROP
        });

        // Listen for marker drag events
        google.maps.event.addListener(marker, 'dragend', function(event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();
            updateLivewireLocation(lat, lng);
        });

        // Initialize the search box
        const input = document.getElementById("locationSearch");
        searchBox = new google.maps.places.SearchBox(input);

        // Bias the search results to the current map bounds
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        // Listen for the search box selection
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length === 0) return;

            // Get the location of the selected place
            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            const location = place.geometry.location;
            const lat = location.lat();
            const lng = location.lng();

            // Update map and marker
            map.setCenter(location);
            marker.setPosition(location);

            // Update Livewire variables
            updateLivewireLocation(lat, lng);
        });
    }

    // Function to update Livewire variables
    function updateLivewireLocation(lat, lng) {
        @this.set('registeredLatitude', lat);
        @this.set('registeredLongitude', lng);
    }

    // Initialize map on page load
    document.addEventListener('DOMContentLoaded', initMap);


    function initMap2() {
        if(@this.ongoingObs){
            const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila coordinates
            if (!map2) {
                map2 = new google.maps.Map(document.getElementById("map2"), {
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
                            stylers: [{ visibility: "off" }],
                        },
                    ],
                });
            }
    
            // Add marker
            const lat = @this.latitude;
            const lng = @this.longitude;
            const newLocation = { lat: parseFloat(lat), lng: parseFloat(lng) };
            map2.setCenter(newLocation);
            if (!marker2) {
                marker2 = new google.maps.Marker({
                    position: newLocation,
                    map: map2,
                    title: 'Your Location',
                    animation: google.maps.Animation.DROP,
                });
            } else {
                marker2.setPosition(newLocation);
            }
        }
    }
</script>