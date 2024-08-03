<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/images/hris-logo.png" type="image/x-icon">

    <title>NYC - HRIS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script defer src="build/assets/app-B9GXRaBV.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="build/assets/app-BJKUccW0.css">

    @livewireStyles
    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevents horizontal scroll */
        }

        .right-side-login {
            height: 100%;
            width: 100%;
            overflow-x: hidden; /* Ensure no horizontal scroll on the right side */
            overflow-y: hidden;
            position: absolute;
            top: 0;
            right: 0;
        }

        .right-side-login img {
            position: absolute;
            height: 100%;
            right: 0;
            z-index: 1;
        }

        .right-side-login div {
            height: 100%;
            width: 50%;
            right: 0;
            top: 0;
            position: absolute;
            background: #004AAD;
            z-index: 0;
        }

        .login-logo {
            position: relative;
            z-index: 1;
        }

        .main-container {
            overflow-x: hidden; /* Prevents any horizontal scroll within the main content */
        }
    </style>
</head>

<body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400">

    <main class="bg-white main-container">

        <div class="relative flex overflow-hidden">

            <!-- Content -->
            <div class="w-full md:w-1/2">

                <div class="min-h-[100dvh] h-full flex flex-col after:flex-1">

                    <!-- Header -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                            <!-- Logo -->
                            <a class="block" href="{{ route('dashboard') }}">
                                <img src="images/nyc-logo.png" alt="logo" class="h-12">
                            </a>
                        </div>
                    </div>

                    <div class="max-w-sm mx-auto w-full px-4 py-8">
                        {{ $slot }}
                    </div>

                </div>

            </div>

            <!-- Image -->
            <div
                x-data
                x-init="$el.style.transform = 'translateX(100%)'; $el.style.opacity = 0;
                        setTimeout(() => {
                            $el.style.transition = 'transform 0.7s ease, opacity 0.7s ease';
                            $el.style.transform = 'translateX(0)';
                            $el.style.opacity = 1;
                        }, 100);"
                class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2 overflow-hidden" aria-hidden="true">

                <div class="right-side-login">
                    <div></div>
                    <img src="/images/rimg.png" alt="login bg">
                </div>

                <div
                    x-data
                    x-init="$el.style.transform = 'translateX(100%)'; $el.style.opacity = 0;
                            setTimeout(() => {
                                $el.style.transition = 'transform 0.7s ease, opacity 0.7s ease';
                                $el.style.transform = 'translateX(0)';
                                $el.style.opacity = 1;
                            }, 300);"
                    class="flex items-center justify-center w-full h-full login-logo">

                    <img class="w-1/2 h-1/2 object-contain" src="{{ asset('images/hris-logo.png') }}" width="760"
                        height="1024" alt="Authentication image" />
                </div>
            </div>

        </div>

    </main>

    @livewireScripts
</body>

</html>
