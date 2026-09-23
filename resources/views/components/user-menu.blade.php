@props([
    'initials' => false,
])

@if($initials)
   <div class="flex items-center space-x-2">
    <x-avatar :text="auth()->user()->initials()" xs />
@endif

    <x-dropdown text="{{ auth()->user()->name }}">
        <x-slot:header>
            <x-theme-switch block />
        </x-slot:header>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown.items :text="__('Settings')" :href="route('profile.edit')" />
            <x-dropdown.items :text="__('Logout')" data-test="logout-button" onclick="event.preventDefault(); this.closest('form').submit();" separator />
        </form>
    </x-dropdown>

@if($initials)
    </div>
@endif
