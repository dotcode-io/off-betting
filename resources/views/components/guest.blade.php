<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME')  }} - {{ $title ?? 'Page Title' }}</title>
    <link rel="icon" href="/sys.png" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex justify-center items-center m-auto max-w-md min-h-screen bg-white dark:bg-zinc-800">
    <div class="w-11/12 md:w-full">
        {{ $slot }}
    </div>
    @persist('toast')
    <flux:toast />
    @endpersist
    @fluxScripts
</body>

</html>
