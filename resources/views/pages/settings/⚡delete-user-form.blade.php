<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <x-heading>{{ __('Delete account') }}</x-heading>
        <x-subheading>{{ __('Delete your account and all of its resources') }}</x-subheading>
    </div>

    <x-button
        x-on:click="$tsui.open.modal('confirm-user-deletion')"
        :text="__('Delete account')"
        outline
        color="red"
    />

    <livewire:pages::settings.delete-user-modal />
</section>
