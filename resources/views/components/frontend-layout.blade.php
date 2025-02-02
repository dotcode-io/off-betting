<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
};
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ env('APP_NAME')  }} - {{ $title ?? 'Page Title' }}</title>
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
            <flux:navlist.item icon="home" href="#" current>Dashboard</flux:navlist.item>
            <flux:navlist.item icon="inbox" href="#">Events</flux:navlist.item>
            <flux:navlist.item icon="puzzle-piece" href="#">Game Controller</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="#">Points History</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="#">Commission History</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="#">Commission Logs</flux:navlist.item>

            <flux:navlist.group expandable heading="Downline Menu" class="hidden lg:grid">
                <flux:navlist.item href="#" icon="users">Active Players</flux:navlist.item>
                <flux:navlist.item href="#" icon="user-group">Active Agents</flux:navlist.item>
                <flux:navlist.item href="" icon="user-circle">System Users</flux:navlist.item>
                <flux:navlist.item href="#" icon="user-plus">For Approval</flux:navlist.item>
                <flux:navlist.item href="" icon="user-minus">Deactivated</flux:navlist.item>
            </flux:navlist.group>

            <flux:navlist.group expandable heading="Admin Menu" class="hidden lg:grid">
                <flux:navlist.item href="#" icon="users">All Players</flux:navlist.item>
                <flux:navlist.item href="#" icon="user-group">All Agents</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
            <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
        </flux:navlist>

        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:profile avatar="https://fluxui.dev/img/demo/user.png" name="{{ Auth::user()->username }}" />

            @volt('layout.navigation.profile.dropdown')
            <flux:menu>

                <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click='logout'>Logout</flux:menu.item>
            </flux:menu>
            @endvolt
        </flux:dropdown>
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" alignt="start">
            <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />

            @volt('layout.navigation.profile.dropdown')
            <flux:menu>

                <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click='logout'>Logout</flux:menu.item>
            </flux:menu>
            @endvolt
        </flux:dropdown>
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>