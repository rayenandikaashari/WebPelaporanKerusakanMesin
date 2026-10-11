@props(['status'])

<span {{ $attributes->merge(['class' => 'status-badge']) }}>
    {{ $status }}
</span>