@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'uk-border-rounded uk-input uk-width-1-1 uk-margin-bottom uk-margin-small-top light-fields dark-font ']) !!}>
