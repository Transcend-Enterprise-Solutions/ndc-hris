<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="/build/assets/app-B9GXRaBV.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="/build/assets/app-6skvEm6U.css">
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.5.0/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <style>
        .py-3\.4 {
            padding-top: 0.85rem;
            /* Equivalent to 3.4 * 0.25rem */
            padding-bottom: 0.85rem;
            /* Equivalent to 3.4 * 0.25rem */
        }

        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .right-side-login {
            height: 100%;
            width: 100%;
            overflow: visible;
            position: fixed;
            top: 0;
            right: 0;
            z-index: 0;
        }

        .right-side-login img {
            position: absolute;
            bottom: 0;
            right: 0;
        }
    </style>

</head>

<body class="font-inter antialiased bg-slate-100 bg-white text-slate-600 dark:text-slate-400">

    @livewire('registration')

    
    <div class="right-side-login animate-slide-in-right">
        <img src="/images/Vector.png" alt="login bg" >
    </div>

    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.5.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</body>

</html>
