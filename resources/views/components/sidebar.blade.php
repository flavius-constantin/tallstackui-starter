<x-slot:menu>
    <x-side-bar smart collapsible navigate>
        <x-slot:brand>
            <div class="my-4 flex items-center justify-center">
                <x-app-logo />
            </div>
        </x-slot:brand>
        <x-slot:brand-collapsed>
            <div class="my-4 flex items-center justify-center">
                <x-app-logo-icon class="size-8!" />
            </div>
        </x-slot:brand-collapsed>
        <x-side-bar.item text="Dashboard" icon="home" :route="route('dashboard')" />
        <x-side-bar.item text="Welcome Page" icon="arrow-uturn-left" :route="route('home')" />

        <x-slot:footer>
            <p class="text-sm text-gray-500">v0.0.1</p>
        </x-slot:footer>
    </x-side-bar>
</x-slot:menu>
