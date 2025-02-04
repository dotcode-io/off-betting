<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ env('APP_NAME')  }} - {{ $title ?? 'Page Title' }}</title>
    <link rel="icon" href="/sys.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 !bg-[#111111]">
    <flux:main>
        {{ $slot }}
    </flux:main>
    @persist('toast')
    <flux:toast position="top right" />
    @endpersist
    @fluxScripts
</body>

</html>