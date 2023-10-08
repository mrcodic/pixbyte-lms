<x-guest-layout>

    @section('title','Forgot Password')

    @section('body')

    <div class="uk-margin-medium-bottom uk-margin-large-top uk-text-default light-text uk-text-center forget-small-text"><h3>
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</h3>
    </div>
        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" class="register-style-form" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="uk-flex uk-flex-center uk-margin-top">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>

    @endsection
</x-guest-layout>
