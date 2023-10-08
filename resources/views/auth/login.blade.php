<x-guest-layout>

    <x-application-logo class="w-200"/>

    @section('body')

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="uk-margin-bottom" :errors="$errors" />

        <form method="POST" class="register-style-form" action="{{ route('login') }}">
            @csrf
            <h2 class="uk-text-center uk-margin-small-top">LOG IN</h2>
            <div class="uk-margin uk-text-center">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@s">
                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                    <input class="uk-input borderless uk-border-rounded dark-font" type="text" placeholder="ID or Email" name="login" :value="old('email')" required autofocus>
                </div>
            </div>

            <div class="uk-margin uk-text-center">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@">
                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                    <input class="uk-input borderless uk-border-rounded dark-font password" type="password" placeholder="Password"name="password" required autocomplete="current-password">
                    <a class="uk-form-icon uk-form-icon-flip togglePassword"  uk-icon="icon: eye">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" cx="10" cy="10" r="3.45"></circle><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path></svg>
                    </a>
                </div>
            </div>
            <div class="uk-margin uk-text-center justify-center uk-flex">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@uk-width-3-4@m uk-width-1-1@ uk-flex space-in-between">
                    <label class="light-link"><input name="remember" class="uk-checkbox" type="checkbox" checked> Remember Me</label>

                    @if (Route::has('password.request'))
                        <label class="light-link">
                            <a href="{{ route('password.request') }}" class="light-link"> Forget my password</a>
                        </label>
                    @endif
                </div>
            </div>
            <div class="recaptcha-wrapper">

                <!-- Google reCaptcha v2 -->
                {!! htmlFormSnippet() !!}
                @if($errors->has('g-recaptcha-response'))
                    <div class="error">
                        <span style="width: 200px; margin:auto;">{{ $errors->first('g-recaptcha-response') }}</span>
                    </div>
                @endif
            </div>

            <div class="uk-margin uk-text-center">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@">
                    <button class="uk-button uk-button-primary uk-width-1-1 border-radius" type="submit">SIGN IN</button>
                </div>
            </div>

            <div class="uk-margin uk-text-center">
                <div class="uk-inline">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="light-link">Sign Up</a>
                    @endif
                </div>
            </div>

        </form>
    @endsection
</x-guest-layout>
