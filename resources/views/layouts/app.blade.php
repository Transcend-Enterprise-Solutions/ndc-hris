<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="{ 'dark': $store.darkMode }"
    x-init="$store.darkMode = localStorage.getItem('dark-mode') === 'true'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/images/ndc_logo.png" type="image/x-icon">

    <title>NDC - HRIS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@marcreichel/alpine-auto-animate@latest/dist/alpine-auto-animate.min.js"
        defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>

    <!-- Scripts -->
    <script defer src="build/assets/app-DEoBNXZR.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="build/assets/app-CqmQecxL.css">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewireStyles
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', localStorage.getItem('dark-mode') === 'true');

            Alpine.effect(() => {
                document.documentElement.classList.toggle('dark', Alpine.store('darkMode'));
                localStorage.setItem('dark-mode', Alpine.store('darkMode'));
            })
        });
    </script>
</head>

<body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400"
    :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') === 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <!-- Page wrapper -->
    <div class="flex h-[100dvh] overflow-hidden">

        <x-app.sidebar />

        <x-toast />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if ($attributes['background']) {{ $attributes['background'] }} @endif"
            x-ref="contentarea">

            <x-app.header />

            <main class="grow">
                {{ $slot }}
            </main>

        </div>

    </div>

    <script>
        document.addEventListener('livewire:navigating', () => {
            Alpine.store('darkMode', localStorage.getItem('dark-mode') === 'true');
        });
    </script>

    <script>
        function initLocationHandling() {
            const currentPath = window.location.pathname;
            if (window.ReactNativeWebView) {
                // Running in React Native web view
                window.ReactNativeWebView.postMessage(JSON.stringify({
                    type: 'routeInfo',
                    route: currentPath
                }));
            } else {
                if(currentPath == '/home' || currentPath == '/daily-time-record/official-business'){
                    getLocationForBrowser();
                }
            }
        }

        function getLocationForBrowser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        sendLocationToApp(position.coords.latitude, position.coords.longitude);
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        alert('Unable to retrieve location. Please enable location services in your browser settings and try again.');
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                console.error('Geolocation is not supported by this browser.');
                alert('Geolocation is not supported by this browser.');
            }
        }

        function sendLocationToApp(latitude, longitude) {
            const locationData = {
                latitude: latitude,
                longitude: longitude,
                // formattedTime: new Date().toLocaleTimeString(),
            };

            Livewire.dispatch('locationUpdated', { locationData });
        }

        document.addEventListener('DOMContentLoaded', initLocationHandling);
        document.addEventListener('livewire:navigated', initLocationHandling);
        window.addEventListener('popstate', initLocationHandling);
    </script>

@livewireScripts
</body>
</html>
