<x-guest-layout>
    @section('title','Reset Password')

    <x-application-logo class="w-200"></x-application-logo>

    @section('body')
        <!-- Validation Errors -->
        <x-auth-validation-errors class="uk-margin-bottom" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block uk-width-1-1" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block uk-width-1-1" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block uk-width-1-1"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <div class="uk-flex uk-flex-end uk-width-1-1">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>

        @endsection

</x-guest-layout>
