<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxStyles
    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc."
                class="px-2 dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc."
                class="px-2 hidden dark:flex" />

            <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass" />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="home" href="{{ route('dashboard') }}" wire:current="font-bold text-zinc-800">Dashboard</flux:navlist.item>
                <flux:navlist.group expandable heading="Game Center" class="hidden lg:grid">
                    <flux:navlist.item href="{{ route('events.game-controller')}}" wire:current="font-bold text-zinc-800">Game Controller</flux:navlist.item>
                    <flux:navlist.item href="{{ route('events.index') }}" wire:current="font-bold text-zinc-800">Events</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
                <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
            </flux:navlist>

            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:profile avatar="https://fluxui.dev/img/demo/user.png" name="Olivia Martin" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                        <flux:menu.radio>Truly Delta</flux:menu.radio>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" alignt="start">
                <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                        <flux:menu.radio>Truly Delta</flux:menu.radio>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>
            @persist('toast')
                <flux:toast position="top right"/>
            @endpersist
        @fluxScripts
    </body>

</html>
