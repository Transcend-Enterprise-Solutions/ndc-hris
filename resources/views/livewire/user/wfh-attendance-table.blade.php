<div x-data="{ open: false }" class="w-full">
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="w-full flex flex-col justify-center items-center">
                    <div>
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px; margin: 20px 0;"></div>
                        
                        {{-- Keep debug info but hide it by default --}}
                        <details class="mt-4">
                            <summary class="cursor-pointer text-sm text-gray-600">Show Debug Info</summary>
                            <div class="p-4 bg-gray-50 rounded-lg mt-2">
                                <p>Latitude exists: {{ isset($latitude) ? 'yes' : 'no' }}</p>
                                <p>Longitude exists: {{ isset($longitude) ? 'yes' : 'no' }}</p>
                                <p>Latitude value: {{ $latitude ?? 'null' }}</p>
                                <p>Longitude value: {{ $longitude ?? 'null' }}</p>
                            </div>
                        </details>
                    </div>

                    <div id="clock" class="text-lg font-semibold mb-4 text-gray-900 dark:text-white h-10 text-center">
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

                {{-- <x-modal id="passwordConfirmation" maxWidth="md" centered wire:model="inputPassword">
                    <div class="p-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-100">Verify
                            Password</label>
                        <input type="password" wire:model="password"
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            placeholder="Enter your password" wire:keydown.enter="verifyPassword">

                        @if ($errorMessage)
                            <div class="mt-2 text-red-600 text-sm font-medium">{{ $errorMessage }}</div>
                        @endif

                        <button wire:click="verifyPassword"
                            class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap mt-4">Submit</button>
                        <button wire:click="closeVerification"
                            class="btn bg-gray-200 dark:bg-gray-600 hover:bg-gray-700 text-gray-800 dark:text-white whitespace-nowrap mt-4">Cancel</button>
                    </div>
                </x-modal> --}}
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

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
<script>
    let map;
    let marker;
    
    function initMap() {
        // Default to a central location if no coordinates yet
        const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila coordinates
        const location = {
            lat: {{ $latitude ?? 'null' }},
            lng: {{ $longitude ?? 'null' }}
        };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location.lat && location.lng ? location : defaultLocation,
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

        if (location.lat && location.lng) {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                title: 'Your Location',
                animation: google.maps.Animation.DROP
            });

            // Add accuracy circle
            new google.maps.Circle({
                strokeColor: '#4285F4',
                strokeOpacity: 0.2,
                strokeWeight: 2,
                fillColor: '#4285F4',
                fillOpacity: 0.1,
                map: map,
                center: location,
                radius: 50 // You can adjust this or use actual accuracy from location data
            });
        }
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);

    // Listen for Livewire location updates
    Livewire.on('locationUpdated', (data) => {
        if (data.locationData) {
            const newLocation = {
                lat: parseFloat(data.locationData.latitude),
                lng: parseFloat(data.locationData.longitude)
            };

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
        }
    });
</script>
@endpush

@push('styles')
<style>
    #map {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        transition: all 0.3s ease;
    }
    
    #map:hover {
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
</style>
@endpush

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
