<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <x-modal
        id="confirm-user-deletion"
        x-on:open="$tsui.focus('password')"
        size="lg">
        <form
            id="confirm-user-deletion-form"
            wire:submit="deleteUser"
            class="space-y-6">
            <div>
                <x-heading size="lg">
                    {{ __('Are you sure you want to delete your account?') }}
                </x-heading>

                <x-subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </x-subheading>
            </div>

            <x-password
                wire:model="password"
                :label="__('Password')" />
        </form>

        <x-slot:footer between>
            <x-button
                type="button"
                outline
                :text="__('Cancel')"
                x-on:click="$tsui.close.modal('confirm-user-deletion')"
                data-test="cancel-delete-user-button" />

            <x-button
                submit
                form="confirm-user-deletion-form"
                loading="deleteUser"
                color="red"
                :text="__('Delete account')"
                data-test="confirm-delete-user-button" />
        </x-slot:footer>
    </x-modal>
</div>
