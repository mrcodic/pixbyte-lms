@props(['errors'])

@if ($errors->any())
    <div uk-alert="" class="uk-alert uk-border-rounded uk-alert-danger" {{ $attributes }}>
        <div>
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
