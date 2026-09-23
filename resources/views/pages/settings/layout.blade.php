<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-55">
        <nav class="space-y-1" aria-label="{{ __('Settings') }}">
            <x-navlist-item icon="user" :href="route('profile.edit')" :current="request()->routeIs('profile.*')" wire:navigate>{{ __('Profile') }}</x-navlist-item>
            <x-navlist-item icon="lock-closed" :href="route('security.edit')" :current="request()->routeIs('security.*')" wire:navigate>{{ __('Security') }}</x-navlist-item>
            <x-navlist-item icon="swatch" :href="route('appearance.edit')" :current="request()->routeIs('appearance.*')" wire:navigate>{{ __('Appearance') }}</x-navlist-item>
        </nav>
    </div>

    <x-separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <x-heading>{{ $heading ?? '' }}</x-heading>
        <x-subheading>{{ $subheading ?? '' }}</x-subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
