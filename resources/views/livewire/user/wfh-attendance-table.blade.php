<div x-data="{ open: false }" class="w-full">

    <style>
        #map {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }
        
        #map:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
    </style>

    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                <div>
                    <div id="map" style="height: 400px; width: 100%; border-radius: 8px; margin: 20px 0;"></div>
                    
                    <div>
                        {{-- Location Debug information --}}
                        <div>
                            Location Info: <br>
                            Latitude value: {{ $latitude ?? 'null' }} <br>
                            Longitude value: {{ $longitude ?? 'null' }} <br><br>
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-col justify-center items-center">

                    <div id="clock" class="text-md font-semibold mb-2 text-gray-900 dark:text-white h-10 text-center">
                        <!-- Time will be displayed here -->
                    </div>
                    <div
                        class="flex flex-col sm:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-12">
                        <div
                            class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700 relative">
                            <h5
                                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
                                NYC WFH ATTENDANCE</h5>
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


            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs"></script>
<script>
    let map;
    let marker;

    function initMap() {
        // Default to a central location if no coordinates yet
        const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila coordinates
        
        // Parse the PHP variables safely
        const lat = {{ $latitude !== null ? $latitude : 'null' }};
        const lng = {{ $longitude !== null ? $longitude : 'null' }};
        
        const location = {
            lat: typeof lat === 'number' ? lat : defaultLocation.lat,
            lng: typeof lng === 'number' ? lng : defaultLocation.lng
        };

        // Initialize map
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
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

        // Add initial marker if we have valid coordinates
        if (typeof lat === 'number' && typeof lng === 'number') {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                title: 'Your Location',
                animation: google.maps.Animation.DROP
            });
        }
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);

    // Reinitialize map when Livewire updates the component
    document.addEventListener('livewire:navigated', initMap);

    // Listen for Livewire location updates
    Livewire.on('locationUpdated', (data) => {
        console.log('Location update received:', data); // Debug log
        
        if (data.locationData && data.locationData.latitude && data.locationData.longitude) {
            const newLocation = {
                lat: parseFloat(data.locationData.latitude),
                lng: parseFloat(data.locationData.longitude)
            };

            console.log('New location:', newLocation); // Debug log

            // Make sure map is initialized
            if (!map) {
                initMap();
            }

            // Update map center
            map.setCenter(newLocation);

            // Update or create marker
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

            // Add or update accuracy circle
            if (window.accuracyCircle) {
                window.accuracyCircle.setCenter(newLocation);
            } else {
                window.accuracyCircle = new google.maps.Circle({
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.2,
                    strokeWeight: 2,
                    fillColor: '#4285F4',
                    fillOpacity: 0.1,
                    map: map,
                    center: newLocation,
                    radius: 50
                });
            }
        }
    });

    // Handle Livewire updates
    document.addEventListener('livewire:update', () => {
        if (map) {
            google.maps.event.trigger(map, 'resize');
        }
    });
</script>

<script>
    function updateClock() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const timeString = now.toLocaleString('en-US', options);
        document.getElementById('clock').textContent = timeString;
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
