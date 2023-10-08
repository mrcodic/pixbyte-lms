<button {{ $attributes->merge(['type' => 'submit', 'class' => 'uk-button uk-button-primary uk-width-1-2@l uk-width-1-1@m  border-radius']) }}>
    {{ $slot }}
</button>
