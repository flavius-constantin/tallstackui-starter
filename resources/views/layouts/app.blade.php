<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">

<head>
    @include('partials.head')
</head>

<body class="font-sans antialiased"
    x-cloak
    x-bind:class="{ 'dark bg-dark-800': darkTheme, 'bg-white': !darkTheme }">
    <x-layout>
        <x-slot:top>
            <x-dialog />
            <x-toast />
        </x-slot:top>
        <x-slot:header>
            <x-layout.header>
                <x-slot:right>
                    <x-user-menu initials/>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>
        <x-sidebar />
        {{ $slot }}
    </x-layout>
    @livewireScripts
</body>

</html>
