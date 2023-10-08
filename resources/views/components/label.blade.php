@props(['value'])

<label {{ $attributes->merge(['class' => 'uk-text-small light-text']) }}>
    {{ $value ?? $slot }}
</label>
