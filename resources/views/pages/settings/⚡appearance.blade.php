<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-heading level="2" class="sr-only">{{ __('Appearance settings') }}</x-heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <x-theme-switch block />
    </x-pages::settings.layout>
</section>
