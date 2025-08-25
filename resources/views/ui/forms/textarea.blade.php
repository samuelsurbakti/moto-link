@props([
    'name' => $attributes->get('name') ?? $attributes->get('wire:model') ?? $attributes->get('wire:model.live'),
    'label' => null,
    'id' => $attributes->get('id') ?? $attributes->get('wire:model') ?? $attributes->get('name') ?? $attributes->get('wire:model.live'),
    'placeholder' => '',
    'container_class' => $attributes->get('container_class'),
])

@php
    $hasError = $errors->has($name);
@endphp

<div class="{{ $container_class }}">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'form-control' . ($hasError ? ' is-invalid' : '')
        ]) }}
    ></textarea>

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @endif

    @if ($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
