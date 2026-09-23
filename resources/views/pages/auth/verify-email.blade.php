<x-layouts::auth :title="__('Email verification')">
    <div class="mt-4 flex flex-col gap-6">
        <p class="text-center">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-button :text="__('Resend verification email')" submit block data-test="resend-verification-email-button" />
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button :text="__('Log out')" variant="ghost" submit class="text-sm cursor-pointer" data-test="logout-button" />
            </form>
        </div>
    </div>
</x-layouts::auth>
