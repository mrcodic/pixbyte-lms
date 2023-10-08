<x-guest-layout>
    @section('title','Password Confirmation')

        <x-application-logo class="w-200"></x-application-logo>
    @section('body')
        <div class="uk-margin-medium-bottom uk-margin-large-top uk-text-default light-text uk-text-center"><h3>
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</h3>
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="uk-margin-bottom" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block uk-width-1-1"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <div class="uk-flex uk-flex-end uk-width-1-1">
                <x-button>
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    @endsection
</x-guest-layout>
