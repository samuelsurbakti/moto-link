@props([
    'target' => $attributes->get('target'),
    'text' => $attributes->get('text'),
])

<div wire:loading.flex wire:target="{{ $target }}" class="row h-px-100 justify-content-center align-items-center mb-4">
    <div class="sk-swing w-px-75 h-px-75">
        <div class="sk-swing-dot"></div>
        <div class="sk-swing-dot"></div>
    </div>
    <h5 class="text-center">{{ $text }}</h5>
</div>
