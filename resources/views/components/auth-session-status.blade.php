@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'uk-alert-success uk-border-rounded','uk-alert'=>'']) }}>
        {{ $status }}
    </div>
@endif
