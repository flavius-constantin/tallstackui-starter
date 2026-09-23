<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showVerificationStep = false;

    public bool $setupComplete = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(bool $requiresConfirmation): void
    {
        $this->requiresConfirmation = $requiresConfirmation;
    }

    #[On('start-two-factor-setup')]
    public function startTwoFactorSetup(): void
    {
        $enableTwoFactorAuthentication = app(EnableTwoFactorAuthentication::class);
        $enableTwoFactorAuthentication(auth()->user());

        $this->loadSetupData();
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        $user = auth()->user()?->fresh();

        try {
            if (! $user || ! $user->two_factor_secret) {
                throw new Exception('Two-factor setup secret is not available.');
            }

            $this->qrCodeSvg = $user->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
        $this->dispatch('two-factor-enabled');
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        $confirmTwoFactorAuthentication(auth()->user(), $this->code);

        $this->setupComplete = true;

        $this->closeModal();

        $this->dispatch('two-factor-enabled');
    }

    /**
     * Reset two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showVerificationStep',
            'setupComplete',
        );

        $this->resetErrorBag();
    }

    /**
     * Get the current modal configuration state.
     */
    #[Computed]
    public function modalConfig(): array
    {
        if ($this->setupComplete) {
            return [
                'title' => __('Two-factor authentication enabled'),
                'description' => __('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText' => __('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verify authentication code'),
                'description' => __('Enter the 6-digit code from your authenticator app.'),
                'buttonText' => __('Continue'),
            ];
        }

        return [
            'title' => __('Enable two-factor authentication'),
            'description' => __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText' => __('Continue'),
        ];
    }
}; ?>

<div>
    <x-modal
        id="two-factor-setup-modal"
        size="md"
        x-on:close="$wire.closeModal()">
        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                    <div class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                        <div class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div>
                        </div>
                        @endfor
                    </div>

                    <div class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                        @for ($i = 1; $i <= 5; $i++)
                            <div>
                    </div>
                    @endfor
                </div>

                <x-icon name="qr-code" class="relative z-20 size-5!" />
            </div>
        </div>

        <div class="space-y-2 text-center">
            <x-heading size="lg">{{ $this->modalConfig['title'] }}</x-heading>
            <x-text>{{ $this->modalConfig['description'] }}</x-text>
        </div>
</div>

@if ($showVerificationStep)
<div class="space-y-6">
    <div
        x-data
        class="flex flex-col items-center space-y-3 justify-center"
        x-init="$nextTick(() => $el.querySelector('input')?.focus())">
        <x-pin
            name="code"
            wire:model="code"
            length="6" />
    </div>

    <div class="flex items-center space-x-3">
        <x-button
            outline
            class="flex-1"
            loading="resetVerification"
            wire:click=" resetVerification"
            :text="__('Back')" />

        <x-button
            class="flex-1"
            loading="confirmTwoFactor"
            wire:click=" confirmTwoFactor"
            x-bind:disabled="$wire.code.length < 6"
            :text="__('Confirm')" />
    </div>
</div>
@else
@error('setupData')
<x-alert icon="x-circle" :text="$message" color="red" :dismiss="6" />
@enderror

<div class="flex justify-center">
    <div class="relative w-64 overflow-hidden border rounded-lg border-stone-200 dark:border-stone-700 aspect-square">
        @empty($qrCodeSvg)
        <div class="absolute inset-0 flex items-center justify-center bg-white dark:bg-stone-700 animate-pulse">
            <x-icon name="arrow-path" class="animate-spin size-5!" />
        </div>
        @else
        <div x-data class="flex items-center justify-center h-full p-4">
            <div
                class="bg-white p-3 rounded"
                :style="($flux.appearance === 'dark' || ($flux.appearance === 'system' && $flux.dark)) ? 'filter: invert(1) brightness(1.5)' : ''">
                {!! $qrCodeSvg !!}
            </div>
        </div>
        @endempty
    </div>
</div>

<x-button
    :disabled="$errors->has('setupData')"
    block
    loading="showVerificationIfNecessary"
    wire:click="showVerificationIfNecessary"
    :text="$this->modalConfig['buttonText']" />

<div class=" space-y-4">
    <div class="relative flex items-center justify-center w-full">
        <div class="absolute inset-0 w-full h-px top-1/2 bg-stone-200 dark:bg-stone-600"></div>
        <span class="relative px-2 text-sm bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-400">
            {{ __('or, enter the code manually') }}
        </span>
    </div>

    @empty($manualSetupKey)
    <div class="flex items-center justify-center w-full p-2 bg-stone-100 dark:bg-stone-700">
        <x-icon name="arrow-path" class="animate-spin size-5!" />
    </div>
    @else
    <x-clipboard :text="$manualSetupKey" />
    @endempty
</div>
@endif
</div>
</x-modal>
</div>
