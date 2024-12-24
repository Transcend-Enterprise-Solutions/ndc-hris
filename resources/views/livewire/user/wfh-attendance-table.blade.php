<div x-data="{ open: false }" class="w-full">

    <style>
        .map {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }
        
        .map:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .map2 {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }
        
        .map2:hover {
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
    </style>

    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                @if ($hasWFHLocation)
                    <div>
                        <div class="flex justify-between">
                            {{-- <x-date-clock-counter /> --}}
                            <div class="text-sm font-semibold mb-2 text-gray-900 dark:text-white h-10 text-left">
                                {{ $formattedTime ?? '...' }}
                            </div>
                            <div class="relative">
                                <i class="bi bi-three-dots-vertical cursor-pointer" @click="open = !open"></i>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute top-4 right-4 z-20 p-3 border border-gray-400 text-sm
                                    bg-white rounded-lg shadow-2xl dark:bg-gray-700" style="width: 250px">
                                    <span>{{ $locReqGranted && $hasRequested ? 'Your request to change WFH location has been approved' : 'Request to change WFH location' }}</span>
                                    <button wire:click="sendChangeLocRequest" {{ $hasRequested  ? 'disabled' : '' }}
                                        class="mt-4 px-4 py-2 text-white rounded w-full {{ $hasRequested  ? 'bg-gray-500' : 'bg-blue-500 hover:bg-blue-600' }}">
                                        {{ $hasRequested ? 'Request Sent' : 'Send Request' }}
                                        <div wire:loading wire:target="sendChangeLocRequest" style="margin-left: 5px">
                                            <div class="spinner-border small text-primary" role="status">
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div wire:ignore>
                            <div id="map" style="height: 250px; width: 100%; border-radius: 8px; margin: 0;"></div>
                        </div>

                        <div class="text-sm flex mt-2">
                            {{-- Location Debug information --}}
                            <div class="w-1/2">
                                WFH Location: <br>
                                Lat: {{ $registeredLatitude ?? '...' }} <br>
                                Lng: {{ $registeredLongitude ?? '...' }} <br><br>
                            </div>
                            <div class="w-1/2">
                                Currect Location: <br>
                                Lat: <span class="{{ $isWithinRadius ? 'text-green-500' : 'text-red-500' }}">{{ $latitude ?? '...' }}</span> <br>
                                Lng: <span class="{{ $isWithinRadius ? 'text-green-500' : 'text-red-500' }}">{{ $longitude ?? '...' }}</span> <br><br>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-center mb-4">
                        <button wire:click="toggleEditLocation" 
                            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                            Register Location
                        </button>
                    </div>
                @endif
                
                @if($locReqGranted && $hasRequested)
                    <div class="flex justify-center mb-4">
                        <button wire:click="toggleEditLocation" 
                            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                            Register Location
                        </button>
                    </div>
                @endif

                <div class="w-full flex flex-col justify-center items-center">
                    <div
                        class="flex flex-col sm:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-12">
                        <div
                            class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700 relative">
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
                                    class="absolute inset-0 flex justify-center items-center bg-gray-700 bg-opacity-75">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock text-white" style="font-size: 5rem;"></i>
                                        <p class="mt-2 text-white font-bold">WFH is not available today</p>
                                    </div>
                                </div>
                            @elseif($scheduleType === 'WFH' && !$isWithinRadius)
                                <div
                                    class="absolute inset-0 flex justify-center items-center bg-gray-700 bg-opacity-75">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock text-white" style="font-size: 5rem;"></i>
                                        <p class="mt-2 text-white font-bold">You are outside the allowed location for WFH attendance</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div
                            class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700 relative">
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

                {{-- Add WFH Location Modal --}}
                <x-modal id="registerLocation" maxWidth="md" centered wire:model="editLocation" x-data @open-modal.window="initMap2()">
                    <div class="p-4">
                        <div class="rounded-lg mb-4  dark:text-gray-50 text-slate-900 font-bold">
                            WFH Location
                            <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div wire:ignore class="w-full">
                            <div id="map2" style="height: 300px; width: 280px; border-radius: 8px; margin: 20px 0;"></div>
                        </div>

                        <div class="text-sm">
                            <div>
                                Location Info: <br>
                                Lat: {{ $latitude ?? '...' }} <br>
                                Lng: {{ $longitude ?? '...' }} <br><br>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end col-span-2">
                            <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click='saveLocation'>
                                Save
                            </button>
                            <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                                Cancel
                            </p>
                        </div>
                    </div>
                </x-modal>

            </div>
        </div>
    </div>

</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs"></script>
<script>
    let map, map2;
    let marker, marker2;
    const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila coordinates
    
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
                map2: map2,
                title: 'Your Location',
                animation: google.maps.Animation.DROP,
            });
        } else {
            marker2.setPosition(newLocation);
        }
    }
    
    // Function to update map with new coordinates
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

            initMap2();
        }
    }
    
    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);
    
    // Check every 5 seconds
    setInterval(updateMap , 5000); 

    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('modalOpened', () => {
            initMap2();
        });
    });
</script>

