<x-guest-layout>
    @section('title','Registration')

    @section('body')
        <!-- Validation Errors -->
        <x-auth-validation-errors class="uk-margin-bottom" :errors="$errors" />

        <form method="POST" class="register-style-form" action="{{ route('register') }}">
                @csrf
                <!-- Name -->
            <div>
                <x-label for="first_name" :value="__('First Name')" />

                <x-input id="first_name" class="block uk-width-1-1" type="text" name="first_name" :value="old('first_name')" required autofocus />
            </div>

            <div>
                <x-label for="last_name" :value="__('Last Name')" />

                <x-input id="last_name" class="block uk-width-1-1" type="text" name="last_name" :value="old('last_name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block uk-width-1-1" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Password')" />

                <x-input id="passwords" class="block uk-width-1-1"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block uk-width-1-1"
                                type="password"
                                name="password_confirmation" required />
            </div>
            <div style="display: flex;justify-content: center; margin-bottom: 8px">
            <!-- Google reCaptcha v2 -->
            {!! htmlFormSnippet() !!}
            @if($errors->has('g-recaptcha-response'))
                <div>
                    <small class="text-danger">{{ $errors->first('g-recaptcha-response') }}</small>
                </div>
            @endif
            </div>
            <div class="uk-flex uk-flex-center uk-flex-around uk-width-1-1">
                <a class="light-link pt-10" href="#login-model" uk-toggle>
                    {{ __('Already registered?') }}
                </a>

                <x-button>
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>

        <x-login></x-login>
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {

            })
        </script>
    @endsection
</x-guest-layout>

