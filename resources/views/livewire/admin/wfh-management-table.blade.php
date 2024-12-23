<div class="w-full flex flex-col justify-center"
x-data="{ 
    selectedTab: 'cos',
    selectedSubTab: 'payroll',
}" 
x-cloak>

    <style>
        .scrollbar-thin1::-webkit-scrollbar {
                        width: 5px;
                    }

        .scrollbar-thin1::-webkit-scrollbar-thumb {
            background-color: #1a1a1a4b;
            /* cursor: grab; */
            border-radius: 0 50px 50px 0;
        }

        .scrollbar-thin1::-webkit-scrollbar-track {
            background-color: #ffffff23;
            border-radius: 0 50px 50px 0;
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

        .spinner-border-2 {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: rgb(255, 255, 255);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">

            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white" x-show="selectedTab === 'cos'">Work From Home Managememnt</h1>
            </div>


            <div class="flex justify-center w-full">
                <div wire:ignore>
                    <div id="map" style="height: 250px; width: 100%; border-radius: 8px; margin: 0;"></div>
                </div>
                <div class="text-sm flex mt-2">
                    <div class="w-1/2">
                        WFH Location: <br>
                        Lat: {{ $registeredLatitude ?? '...' }} <br>
                        Lng: {{ $registeredLongitude ?? '...' }} <br><br>
                    </div>
                </div>
            </div>

        </div>
    </div>



</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs"></script>
<script>
    let map;
    let marker;
    
    // Initialize map first
    function initMap() {
        // Default to a central location if no coordinates yet
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
    }
    
    // Function to update map with new coordinates
    function updateMap() {
        const lat = @this.latitude;
        const lng = @this.longitude;
        
        if (lat && lng) {
            if (!map) {
                initMap();
            }

            const newLocation = { lat: parseFloat(lat), lng: parseFloat(lng) };
            
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
    }
    
    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', initMap);
    
    // Check every 5 seconds
    setInterval(updateMap , 5000); 
</script>