<div x-data="{ 
    open: false, 
    showWFHLocHistory: '{{ request()->query('showWFHLocHistory', false) }}',
    viewWFHLocHistory: false,
}" class="w-full">

    <style>
        #map {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }
        
        #map:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        #map2 {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }
        
        #map2:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
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
            color: white;
        }

        .scrollbar-thin1::-webkit-scrollbar {
            width: 5px;
        }

        .scrollbar-thin1::-webkit-scrollbar-thumb {
            background-color: #1a1a1a4b;
        }

        .scrollbar-thin1::-webkit-scrollbar-track {
            background-color: #b6b6b6;
        }
    </style>

    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                @if ($hasWFHLocation)
                    <div class="flex-col mb-4 justify-center w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-gray-800 overflow-hidden relative" style="border-radius: 8px;">
                        
                        {{-- WFH Location Modal ---------------------------------------------------------------------------------}}
                        <div>
                            <div class="flex justify-between px-4 pt-4">
                                {{-- <x-date-clock-counter /> --}}
                                <div class="text-sm font-semibold text-gray-900 dark:text-white h-10 text-left">
                                    <i class="bi bi-clock"></i> {{ $formattedTime2 ?: $formattedTime }}
                                </div>
                                <div class="relative">
                                    <i class="bi bi-three-dots-vertical cursor-pointer" @click="open = !open"></i>
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute top-4 right-4 z-20 p-3 border border-gray-400 text-sm
                                        rounded-lg shadow-2xl bg-white dark:bg-slate-800" style="width: 250px">
                                        <span>{{ $locReqGranted ? 'Request to change WFH location' : 'Change WFH location is pending for approval' }}</span>
                                        @if ($locReqGranted)
                                            <p wire:click="toggleEditLocation('request')"
                                                class="mt-1 cursor-pointer px-2 py-2 text-gray-800 dark:text-white hover:text-blue-500 rounded w-full hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                                <i class="bi bi-geo-alt"></i> Change WFH Location
                                            </p>
                                        @else
                                            <p class="mt-1 px-2 py-2 text-gray-800 dark:text-white text-left rounded w-full opacity-50">
                                                <i class="bi bi-check2-circle"></i> Request Sent
                                            </p>
                                        @endif

                                        <p wire:click="showLocReqHistory" @click="showWFHLocHistory = true" class="mt-1 px-2 cursor-pointer py-2 text-gray-800 dark:text-white hover:text-blue-500 text-left rounded w-full hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                            <i class="bi bi-clock-history"></i> History
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div wire:ignore>
                                <div id="map" style="height: 250px; width: 100%; margin: 0;"></div>
                            </div>

                            <div class="text-sm flex p-4">
                                {{-- Location Debug information --}}
                                <div class="w-1/2">
                                   <span class="font-bold">WFH Location</span> <br>
                                    Lat: <span class="text-gray-800 dark:text-white">{{ $registeredLatitude ?? '...' }}</span> <br>
                                    Lng: <span class="text-gray-800 dark:text-white">{{ $registeredLongitude ?? '...' }}</span> <br>
                                </div>
                                <div class="w-1/2">
                                    <span class="font-bold">Currect Location</span> <br>
                                    Lat: <span class="{{ $isWithinRadius ? 'text-green-500' : 'text-red-500' }}">{{ $latitude ?? '...' }}</span> <br>
                                    Lng: <span class="{{ $isWithinRadius ? 'text-green-500' : 'text-red-500' }}">{{ $longitude ?? '...' }}</span> <br>
                                </div>
                            </div>
                        </div>

                        {{-- WFH Location History Modal ---------------------------------------------------------------------------------}}
                        <div 
                            x-show="showWFHLocHistory" 
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="translate-y-full opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="translate-y-0 opacity-100"
                            x-transition:leave-end="translate-y-full opacity-0"
                            x-cloak 
                            class="absolute inset-0 bg-gray-50 dark:bg-slate-700 overflow-hidden w-full h-full z-50 flex">
                            <div class="p-6 overflow-hidden relative">
                                <button @click="showWFHLocHistory = false" 
                                        class="px-3 text-white rounded-md absolute
                                        text-sm bg-gray-500 hover:bg-gray-600  
                                        focus:outline-none" title="Close" style="top: 15px; right: 15px; z-index: 11;">
                                        Close
                                </button>

                                <div class="w-full flex justify-between bg-gray-50 dark:bg-slate-700 z-10" style="position: sticky; top: 0;">
                                    <div class="w-full sm:w-1/3 sm:mr-4 mb-4">
                                        <label for="search" class="block text-md font-medium text-gray-800 dark:text-white mb-1"><i class="bi bi-clock-history"></i> WFH Location History</label>
                                        <input type="text" id="search" wire:model.live="search"
                                            class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                                                dark:hover:bg-slate-600 dark:border-slate-600
                                                dark:text-gray-300 dark:bg-gray-800"
                                            placeholder="Search address">
                                    </div>
                                </div>

                                <div class="border dark:border-gray-600 scrollbar-thin1" style="height: 280px; overflow-y:scroll;">
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full">
                                            <thead class="bg-gray-100 dark:bg-gray-600">
                                                <tr class="whitespace-nowrap">
                                                    <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                        Date
                                                    </th>
                                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                        Status
                                                    </th>
                                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                        Address
                                                    </th>
                                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                        Geolocation
                                                    </th>
                                                    <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                        Request Attachment
                                                    </th>
                                                    <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 bg-gray-600 dark:bg-gray-600">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-300 dark:divide-gray-600">
                                                @foreach($history as $employee)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-left text-sm font-medium text-nowrap">
                                                            {{ \Carbon\Carbon::parse($employee->date_approved)->format('F d, Y') }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium text-nowrap">
                                                            @if($employee->status == 1)
                                                                <span
                                                                    class="text-xs text-white bg-green-500 rounded-lg py-1.5 px-4">Approved</span>
                                                            @elseif($employee->status == 2)
                                                                <span
                                                                class="text-xs text-white bg-red-500 rounded-lg py-1.5 px-4">Disapproved</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium text-nowrap">
                                                            {{ $employee->address ?? 'None' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            Lat: {{ $employee->curr_lat ?? 'None' }} <br>
                                                            Lng: {{ $employee->curr_lng ?? 'None' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap {{ $employee->attachment ? '' : 'opacity-30' }}">
                                                            {{ $employee->attachment ?? 'None' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 bg-white dark:bg-gray-800">
                                                            <div class="relative">
                                                                @php
                                                                    $thisName = trim($employee->surname . ', ' . $employee->first_name . ' ' . 
                                                                        ($employee->middle_name ? $employee->middle_name . ' ' : '') . 
                                                                        ($employee->name_extension ?? ''));
                                                                @endphp
                                                                <button wire:click="viewWFHLocHistory({{ $employee->id }})" @click="viewWFHLocHistory = true"
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600
                                                                    focus:outline-none" title="View">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($history->isEmpty())
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                No records!
                                            </div> 
                                        @endif
                                    </div>
                                    <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-100 dark:bg-gray-600">
                                        {{ $history->links() }}
                                    </div>

                                </div> 
                            </div>
                            <div class="w-full bg-gray-50 dark:bg-slate-700 z-10 absolute bottom-0" style="height: 15px;">
                            </div>
                        </div>

                        {{-- View WFH Location History Modal ---------------------------------------------------------------------------------}}
                        <div 
                            x-show="viewWFHLocHistory" 
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="translate-y-full opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="translate-y-0 opacity-100"
                            x-transition:leave-end="translate-y-full opacity-0"
                            x-cloak 
                            class="absolute inset-0 bg-gray-50 dark:bg-slate-700 overflow-hidden w-full h-full z-50 flex">
                            <div class="p-6 overflow-hidden relative w-full h-full">
                                <button @click="viewWFHLocHistory = false" 
                                        class="px-3 text-white rounded-md absolute
                                        text-sm bg-gray-500 hover:bg-gray-600  
                                        focus:outline-none" title="Close" style="top: 15px; right: 15px; z-index: 11;">
                                        Close
                                </button>

                                <div class="flex-col justify-center w-full bg-gray-200 dark:bg-slate-700 border border-gray-300 dark:border-gray-800 mb-2 mt-6 scrollbar-thin1" style="height: 330px; overflow-y:scroll;">
                                    <div wire:ignore class="w-full">
                                        <div id="map3" style="height: 250px; width: 100%; margin: 0;"></div>
                                    </div>
                    
                                    <div class="text-sm grid grid-cols-2 mt-2 px-4 mb-2">
                                        <div class="col-span-2 sm:col-span-1">
                                            Address: <span class="text-gray-800 dark:text-gray-50">{{ $address ?? '...' }}</span>
                                            Geolocation: <span class="text-gray-800 dark:text-gray-50">{{ 'Lat: ' . $registeredLatitude . ' | Lng: ' . $registeredLongitude }}</span><br>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <span class="{{ $approveOnly ? 'hidden' : '' }}">
                                                Approved By: <span class="text-gray-800 dark:text-gray-50">{{ $approvedBy ?? '...' }}</span><br>
                                                Date Approved: <span class="text-gray-800 dark:text-gray-50">{{ $approvedDate ?? '...' }}</span>
                                            </span>
                                            <span class="{{ $approveOnly ? '' : 'hidden' }}">
                                                Disapproved By: <span class="text-gray-800 dark:text-gray-50">{{ $disapprovedBy ?? '...' }}</span><br>
                                                Date Disapproved: <span class="text-gray-800 dark:text-gray-50">{{ $disapprovedDate ?? '...' }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="flex justify-center mb-4">
                        <button wire:click="toggleEditLocation('register')" 
                            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                            Register WFH Location
                        </button>
                    </div>
                @endif

                <div class="w-full flex flex-col justify-center items-center">
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-12">

                        <div class="block max-w-sm p-6 border border-gray-200 rounded-lg shadow bg-gray-50 dark:bg-slate-700 dark:border-gray-700 relative">
                            <h5
                                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
                                NDC WFH ATTENDANCE</h5>
                            <div class="grid grid-cols-1 gap-4 p-4">

                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('morningIn', 'Morning In')"
                                        @if ($morningInDisabled) disabled @endif
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                            Morning In
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('morningOut', 'Morning Out')"
                                        @if ($morningOutDisabled) disabled @endif
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                            Morning Out
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('afternoonIn', 'Afternoon In')"
                                        @if ($afternoonInDisabled) disabled @endif
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                            Afternoon In
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('afternoonOut', 'Afternoon Out')"
                                        @if ($afternoonOutDisabled) disabled @endif
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64 transition-all duration-75 ease-in group-disabled:bg-opacity-0 group-disabled:text-white">
                                            Afternoon Out
                                        </span>
                                    </button>
                                </div>

                            </div>

                            @if ($scheduleType !== 'WFH')
                                <div
                                    class="absolute inset-0 flex justify-center items-center bg-gray-700 bg-opacity-75 rounded-lg">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock text-white" style="font-size: 5rem;"></i>
                                        <p class="mt-2 text-white font-bold">WFH is not available today</p>
                                    </div>
                                </div>
                            @elseif($scheduleType === 'WFH' && !$isWithinRadius)
                                <div
                                    class="absolute inset-0 flex justify-center items-center bg-gray-700 bg-opacity-75 rounded-lg">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock text-white" style="font-size: 5rem;"></i>
                                        <p class="mt-2 text-white font-bold">You are outside the allowed location for WFH attendance</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="block max-w-sm p-6 border border-gray-200 rounded-lg shadow bg-gray-50 dark:bg-slate-700 dark:border-gray-700 relative">
                            <h3
                                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
                                {{ $scheduleType === 'WFH' ? 'WFH Punch Time' : 'Onsite Punch Time' }}
                            </h3>

                            @php
                                $today = \Carbon\Carbon::now()->format('l'); // Get the current day name
                            @endphp

                            <div class="mb-4">
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white text-center border-b">
                                    {{ $today }}
                                </h4>

                                <div class="mt-2 text-center">
                                    @if ($scheduleType === 'WFH')
                                        @foreach (['Morning In', 'Morning Out', 'Afternoon In', 'Afternoon Out'] as $type)
                                            <div class="mb-2 text-center">
                                                <strong>{{ $type }}</strong>
                                                <div>
                                                    @forelse ($groupedTransactions[$type] ?? [] as $transaction)
                                                        <div class="text-gray-700 dark:text-gray-300">
                                                            {{ \Carbon\Carbon::parse($transaction->punch_time)->format('H:i:s') }}
                                                        </div>
                                                    @empty
                                                        <div class="text-gray-400">No punch time recorded</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- Onsite punch times from EmployeesDTR --}}
                                        <div class="mb-2 text-center">
                                            <strong>Morning In</strong>
                                            <div>{{ $groupedTransactions->morning_in ?? 'No punch time recorded' }}
                                            </div>
                                        </div>
                                        <div class="mb-2 text-center">
                                            <strong>Morning Out</strong>
                                            <div>{{ $groupedTransactions->morning_out ?? 'No punch time recorded' }}
                                            </div>
                                        </div>
                                        <div class="mb-2 text-center">
                                            <strong>Afternoon In</strong>
                                            <div>{{ $groupedTransactions->afternoon_in ?? 'No punch time recorded' }}
                                            </div>
                                        </div>
                                        <div class="mb-2 text-center">
                                            <strong>Afternoon Out</strong>
                                            <div>{{ $groupedTransactions->afternoon_out ?? 'No punch time recorded' }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <x-modal id="punchConfirmation" maxWidth="md" centered wire:model="showConfirmation">
                    <div class="p-4">
                        <div class="flex items-center justify-between pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                                Punch Confirmation
                            </h3>
                            <button wire:click="closeConfirmation"
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
                                <button wire:click="confirmYes"
                                    class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    Yes
                                </button>
                                <button wire:click="closeConfirmation"
                                    class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>
                </x-modal>
  
            </div>
        </div>
    </div>
    
    {{-- Add WFH Location Modal --}}
    <x-modal id="registerLocation" maxWidth="md" wire:model="editLocation" x-data @open-modal.window="initMap2()">
        <div class="p-4">
            <div class="rounded-lg mb-4  dark:text-gray-50 text-slate-900 font-bold">
                {{ $editLocMessage }} WFH Location
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address <span class="text-red-500">*</span></label>
                <input type="text" id="address" wire:model.live='address' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                @error('address') 
                    <span class="text-red-500 text-sm">The address is required!</span> 
                @enderror
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
                    <div id="map2" style="height: 250px; width: 100%; margin: 0;"></div>
                </div>

                <div class="text-sm flex mt-2 px-4">
                    <div class="w-1/2 mb-2">
                        Location Info: <br>
                        Lat: <span class="text-gray-800 dark:text-white">{{ $newLat ?? '...' }}</span> <br>
                        Lng: <span class="text-gray-800 dark:text-white">{{ $newLng ?? '...' }}</span> <br>
                    </div>
                </div>
                @error('newLat') 
                    <span class="text-red-500 text-sm">The geolocation is required!</span> 
                @enderror
                <div class="w-full" style="border-radius:  0 0 8px 8px;">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 w-full text-sm" 
                            @click="getCurrentLocation" style="border-radius:  0 0 8px 8px;">
                        Use My Current Location
                    </button>
                </div>
            </div>

            <div class="mt-4 flex justify-end col-span-2">
                <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click='saveLocation'>
                    {{ $editLocMessage == 'Change' ? 'Send Request' : 'Save' }}
                </button>
                <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                    Cancel
                </p>
            </div>
        </div>
    </x-modal>


</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs&libraries=places"></script>
<script>
    let map, map2, map3, searchBox;
    let marker, marker2, marker3;
    const defaultLocation = { lat: 14.5995, lng: 120.9842 };
    
    function initMap() {
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
    }

    function initMap2() {
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
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
        marker2 = new google.maps.Marker({
            position: defaultLocation,
            map: map2,
            draggable: true,
            title: 'Your WFH Location',
            animation: google.maps.Animation.DROP
        });

        // Listen for marker drag events
        google.maps.event.addListener(marker2, 'dragend', function(event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();
            updateLivewireLocation(lat, lng);
        });

        const input = document.getElementById("locationSearch");
        searchBox = new google.maps.places.SearchBox(input);

        map2.addListener("bounds_changed", () => {
            searchBox.setBounds(map2.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length === 0) return;
            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            const location = place.geometry.location;
            const lat = location.lat();
            const lng = location.lng();
            map2.setCenter(location);
            marker2.setPosition(location);
            updateLivewireLocation(lat, lng);
        });
    }
    function updateLivewireLocation(lat, lng) {
        @this.set('newLat', lat);
        @this.set('newLng', lng);
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map2.setCenter({ lat, lng });
                    marker2.setPosition({ lat, lng });
                    updateLivewireLocation(lat, lng);
                },
                (error) => {
                    alert("Unable to retrieve your location. Please allow location access or try again.");
                    console.error(error);
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }


    function updateMap() {
        const lat = @this.latitude;
        const lng = @this.longitude;

        if (lat && lng) {
            const newLocation = { lat: parseFloat(lat), lng: parseFloat(lng) };

            if (!map) {initMap();}
            map.setCenter(newLocation);
            if (marker) {
                marker.setPosition(newLocation);
            } else {
                marker = new google.maps.Marker({
                    position: newLocation,
                    map: map,
                    title: 'Your Location',
                    animation: google.maps.Animation.DROP
                });
            }
        }
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('init-map2', () => {
            initMap2();
        });
    });
    document.addEventListener('DOMContentLoaded', initMap);
    setInterval(updateMap , 5000);
    
    function sendTimeToApp(latitude, longitude) {
        if (!window.ReactNativeWebView) {
            const date = new Date();
            const time = date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                hour12: true
            });
            Livewire.dispatch('timeUpdate', { time });
        }
    }

    setInterval(sendTimeToApp , 1000); 

    function viewWFHLocHistoryMap() {
        const lat = @this.registeredLatitude;
        const lng = @this.registeredLongitude;
        
        if (lat && lng) {
            if (!map3) {
                const defaultLocation = { lat: 14.5995, lng: 120.9842 };
                map3 = new google.maps.Map(document.getElementById("map3"), {
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
            }

            const newLocation = { lat: parseFloat(lat), lng: parseFloat(lng) };
            map3.setCenter(newLocation);
            if (marker3) {
                marker3.setPosition(newLocation);
            } else {
                marker3 = new google.maps.Marker({
                    position: newLocation,
                    map: map3,
                    title: 'Your WFH Location',
                    animation: google.maps.Animation.DROP
                });
            }
        }
    }
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showWFHLocHistory', () => {
            viewWFHLocHistoryMap();
        });
    });
</script>

