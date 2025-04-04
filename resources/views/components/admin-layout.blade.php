<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ env('APP_NAME')  }} - {{ $title ?? 'Page Title' }}</title>
    <link rel="icon" href="/sys.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <flux:brand href="#" logo="/sys.png" name="{{ env('APP_NAME') }}"
            class="px-2 dark:hidden" />
        <flux:brand href="#" logo="/sys.png" name="{{ env('APP_NAME') }}"
            class="px-2 hidden dark:flex" />


        <flux:navlist variant="outline">
            @if(auth()->user()->isAdmin())
            <flux:navlist.item icon="home" href="{{ route('dashboard') }}" wire:current="font-bold text-zinc-800">Dashboard</flux:navlist.item>
            <flux:navlist.group expandable heading="Game Center" class=" lg:grid">
                <flux:navlist.item href="{{ route('events.index') }}" wire:current="font-bold text-zinc-800">Events</flux:navlist.item>
                <flux:navlist.item href="{{ route('events.game-controller')}}" wire:current="font-bold text-zinc-800">Game Controller</flux:navlist.item>
            </flux:navlist.group>
            <flux:navlist.item icon="clipboard-document-list" href="{{ route('bet-history') }}" wire:current="font-bold text-zinc-800">Bet History</flux:navlist.item>
            <flux:navlist.item icon="trophy" href="{{ route('claim-history') }}" wire:current="font-bold text-zinc-800">Claim History</flux:navlist.item>
            <flux:navlist.item icon="users" href="{{ route('users.index') }}" wire:current="font-bold text-zinc-800">Users</flux:navlist.item>
            @endif

            @if(auth()->user()->isTeller())
            <flux:navlist.item icon="paper-airplane" href="{{ route('teller.console') }}" wire:current="font-bold text-zinc-800">Bet</flux:navlist.item>
            <flux:navlist.item icon="inbox-arrow-down" href="{{ route('teller.claim-ticket') }}" wire:current="font-bold text-zinc-800">Claim Ticket</flux:navlist.item>
            <flux:navlist.item icon="document-arrow-down" href="{{ route('teller.bet-history') }}" wire:current="font-bold text-zinc-800">Bet History</flux:navlist.item>
            <flux:navlist.item icon="document-arrow-up" href="{{ route('teller.claim-history') }}" wire:current="font-bold text-zinc-800">Claim History</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="{{ route('teller.summary') }}" wire:current="font-bold text-zinc-800">Summary</flux:navlist.item>
            @endif

            @if(auth()->user()->isController())
            <flux:navlist.item icon="puzzle-piece" href="{{ route('controller.events.index')}}" wire:current="font-bold text-zinc-800">Game Controller</flux:navlist.item>
            @endif
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="cog-6-tooth" href="{{ route('profile.settings') }}">Settings</flux:navlist.item>
            <!-- <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item> -->
        </flux:navlist>

        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:profile avatar="/blank.webp" name="{{ auth()->user()->username }}" />

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>{{ auth()->user()->username }}</flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <livewire:dashboard.logout-button />
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" alignt="start">
            <flux:profile avatar="/blank.webp" />

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>{{ auth()->user()->username }}</flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <livewire:dashboard.logout-button />
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>
    @persist('toast')
    <flux:toast position="top right" />
    @endpersist
    @fluxScripts
</body>

</html>
