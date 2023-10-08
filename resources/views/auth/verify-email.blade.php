<x-guest-layout>
    @section('title','Email Verification')

    @section('body')
        <div class="email-verify uk-margin-top">

            <div class="uk-margin-medium-bottom uk-text-default light-text"><h3>
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</h3>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="uk-margin-medium-bottom uk-text-default success-text">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="uk-flex uk-flex-center uk-flex-between uk-width-1-1">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-button class="w-100">
                            {{ __('Resend Verification Email') }}
                        </x-button>
                    </div>
                </form>

                <a class="light-link" href="{{ route('logout')}}">
                    {{ __('Log Out') }}
                </a>
            </div>

        </div>
    @endsection
</x-guest-layout>
