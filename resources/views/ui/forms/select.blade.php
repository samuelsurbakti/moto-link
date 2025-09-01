@props([
    'wireModel',
    'label',
    'placeholder' => '',
    'containerClass' => 'col-12 mb-4',
    'initSelect2Class' => 'select2',
    'options' => [], // Properti untuk data opsi sederhana (misal: array key-value)
    'valueField' => 'id', // Nama field untuk nilai option
    'textField' => 'title', // Nama field untuk teks option
    'selected' => null, // Untuk pre-select nilai
])

<div class="{{ $containerClass }}">
    <label class="form-label" for="{{ $wireModel }}">{{ $label }}</label>
    <select
        wire:model.live="{{ $wireModel }}"
        id="{{ $wireModel }}"
        {{ $attributes->merge(['class' => 'form-select '.$initSelect2Class.' ' . ($errors->has(str_replace('wire:model.', '', $wireModel)) ? 'is-invalid' : '')]) }}
        style="width: 100%;"
        data-placeholder="{{ $placeholder }}"
    >
        <option></option> {{-- Opsi kosong untuk placeholder --}}

        {{-- Slot untuk opsi kustom yang lebih kompleks --}}
        {{ $slot }}

        {{-- Looping untuk opsi sederhana jika slot tidak digunakan --}}
        @if ($slot->isEmpty() && !empty($options))
            @foreach($options as $option)
                <option value="{{ $option[$valueField] ?? $option }}" @if($selected == ($option[$valueField] ?? $option)) selected @endif>
                    {{ $option[$textField] ?? $option }}
                </option>
            @endforeach
        @endif
    </select>
    @error(str_replace('wire:model.', '', $wireModel))
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
