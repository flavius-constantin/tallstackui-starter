<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;
    use Interactions;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->toast()->success(__('Profile updated.'))->send();
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-heading level="2" class="sr-only">{{ __('Profile settings') }}</x-heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <x-input
                wire:model="name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                autocomplete="name" />

            <div>
                <x-input
                    wire:model="email"
                    :label="__('Email')"
                    type="email"
                    required
                    autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                <div>
                    <x-text class="mt-4">
                        {{ __('Your email address is unverified.') }}

                        <a class="text-primary-500 underline text-sm cursor-pointer hover:no-underline" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </a>
                    </x-text>

                    @if (session('status') === 'verification-link-sent')
                    <x-text class="mt-2 font-medium !dark:text-green-400 text-green-600!">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </x-text>
                    @endif
                </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button loading="updateProfileInformation" submit block :text="__('Save')" data-test="update-profile-button" />
                </div>
            </div>
        </form>

        @if ($this->showDeleteUser)
        <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
