<x-guest-layout>

    <x-application-logo class="w-200"/>

    @section('body')
    <div class="uk-text-center uk-margin-medium-bottom top-bar-mobile-app login">
        <h3>For notifications and reports download the mobile app from playstore 🤗👇</h3>
        <a href="https://play.google.com/store/apps/details?id=com.pixbyte.mives"><img src="{{asset('img/google-play.webp')}}" alt="playstore"></a>
    </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="uk-margin-bottom" :errors="$errors" />

        <form method="POST" class="register-style-form" action="{{ route('parentLogin') }}">
            @csrf
            <h2 class="uk-text-center uk-margin-small-top">LOG IN</h2>
            <div class="uk-margin uk-text-center">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@s">
                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                    <input class="uk-input borderless uk-border-rounded dark-font" type="text" placeholder="ID" name="login" :value="old('login')" required autofocus>
                </div>
            </div>

            <div class="uk-margin uk-text-center">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@">
                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                    <input class="uk-input borderless uk-border-rounded dark-font" type="password" placeholder="Password"name="password" required autocomplete="current-password">
                </div>
            </div>
            <div class="uk-margin uk-text-center justify-center uk-flex">
                <div class="uk-inline uk-width-3-4@m uk-width-1-1@uk-width-3-4@m uk-width-1-1@ uk-flex space-in-between">
                    <label class="light-link"><input name="remember" class="uk-checkbox" type="checkbox" checked> Remember Me</label>

                    <label class="light-link">
                        <a class="light-link" id="parent-forgetpass"> Forget my password</a>
                    </label>
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


        </form>

    @endsection

    @section('script')
        <script>
            $('#parent-forgetpass').click(function(){
                Swal.fire({
                    title: "Forget your password !",
                    // text: "please contact us on:",
                    icon: 'question',
                    html:
                    `<h4 class="uk-text-muted uk-margin-small-top uk-margin-small-bottom">please contact us on:</h4>
                    <h4 class="uk-text-muted uk-margin-small-top uk-margin-small-bottom">phone : +2923810293</h4>
                    <h4 class="uk-text-muted uk-margin-small-top uk-margin-small-bottom">phone : +2923810293</h4>`,
                    focusConfirm: false,
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    cancelButtonText: "Ok",
                })
            })
        </script>
    @endsection
</x-guest-layout>
