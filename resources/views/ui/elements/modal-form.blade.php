@props([
    'id',                  // ID modal
    'title',               // Judul modal
    'description' => null, // Deskripsi modal
    'loadingTargets' => [], // Array target loading
    'size' => 'lg',        // ukuran modal: sm, md, lg, xl
    'default_button' => true,
])

<div wire:ignore.self class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered">
        <div class="modal-content">
            {{-- Loading states --}}
            @foreach ($loadingTargets as $target)
                @if ($target == 'save')
                    <x-ui::elements.loading text="Menyimpan Data" target="{{ $target }}" />
                @else
                    <x-ui::elements.loading text="Mengambil Data" target="{{ $target }}" />
                @endif

            @endforeach

            {{-- Header --}}
            <div wire:loading.remove wire:target="{{ implode(', ', $loadingTargets) }}" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div wire:loading.remove wire:target="{{ implode(', ', $loadingTargets) }}" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">{{ $title }}</h3>
                    @if($description)
                        <p>{{ $description }}</p>
                    @endif
                </div>

                <form wire:submit="save" method="POST" novalidate>
                {{-- Konten/form disediakan oleh slot --}}
                    {{ $slot }}

                    @if ($default_button == true)
                        <div class="col-12 text-center mt-8">
                            <x-ui::elements.button type="submit" class="btn-primary me-sm-3 me-1">
                                Simpan
                            </x-ui::elements.button>
                            <x-ui::elements.button type="reset" class="btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Batalkan
                            </x-ui::elements.button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
