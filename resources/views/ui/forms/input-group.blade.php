@props([
    'name' => $attributes->get('name') ?? $attributes->get('wire:model') ?? $attributes->get('wire:model.live'),
    'label' => null,
    'type' => 'text',
    'id' => $attributes->get('id') ?? $attributes->get('wire:model') ?? $attributes->get('name') ?? $attributes->get('wire:model.live'),
    'placeholder' => '',
    'container_class' => $attributes->get('container_class'),
    'front' => null,
    'behind' => null,
])

@php
    $hasError = $errors->has($name);
@endphp

<div class="{{ $container_class }}">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <div class="input-group input-group-merge">
        @if(!is_null($front))
            <span class="input-group-text">{{ $front }}</span>
        @endif

        <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'form-control' . ($hasError ? ' is-invalid' : '')
            ]) }}
        >

        @if(!is_null($behind))
            <span class="input-group-text">{{ $behind }}</span>
        @endif

    </div>

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @endif

    @if ($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
