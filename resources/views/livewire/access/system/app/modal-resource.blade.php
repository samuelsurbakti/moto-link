<?php

use App\Models\Sys\App;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public ?string $app_id = null;

    #[Validate('required|string', as: 'Nama')]
    public ?string $app_name = null;

    #[Validate('required|string', as: 'Subdomain')]
    public ?string $app_subdomain = null;

    #[Validate('required|integer', as: 'Urutan')]
    public $app_order_number;

    #[On('reset_app')]
    public function reset_app()
    {
        $this->reset(['app_id', 'app_name', 'app_subdomain', 'app_order_number']);
        $this->resetValidation();
    }

    #[On('set_app')]
    public function set_app($app_id)
    {
        $this->app_id = $app_id;
        $app = App::findOrFail($this->app_id);

        $this->app_name = $app->name;
        $this->app_subdomain = $app->subdomain;
        $this->app_order_number = $app->order_number;
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->app_id)) {
            $app = App::create([
                'name' => $this->app_name,
                'subdomain' => $this->app_subdomain,
                'image' => Str::before($this->app_subdomain, '.'),
                'order_number' => $this->app_order_number,
            ]);
        } else {
            $app = App::findOrFail($this->app_id);

            $app->update([
                'name' => $this->app_name,
                'subdomain' => $this->app_subdomain,
                'order_number' => $this->app_order_number,
            ]);

            $this->dispatch("refresh_app_component.{$app->id}");
        }

        $this->dispatch('close_modal_app_resource');

        LivewireAlert::title('')
            ->text('Berhasil '.(is_null($this->app_id) ? 'menambah' : 'mengubah').' Aplikasi')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_app();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_app_resource"
    :title="(is_null($app_id) ? 'Tambah' : 'Edit') . ' Aplikasi'"
    :description="'Di sini, Anda dapat ' . (is_null($app_id) ? 'menambah data' : 'mengubah informasi') . ' aplikasi.'"
    :loading-targets="['set_app', 'reset_app', 'save']"
    size="lg"
>
    @csrf
    <x-ui::forms.input
        wire:model.live="app_name"
        type="text"
        label="Nama"
        placeholder="Nama"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input
        wire:model.live="app_subdomain"
        type="text"
        label="Subdomain"
        placeholder="example.your-domain.com"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input
        wire:model.live="app_order_number"
        type="text"
        label="Urutan"
        placeholder="1-100"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_app_resource', () => {
            var modalElement = document.getElementById('modal_app_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        $(document).ready(function () {
            $(document).on('click', '#btn_app_add', function () {
                $wire.reset_app();
            });

            $(document).on('click', '.btn_app_edit', function () {
                $wire.set_app($(this).attr('value'));
            });

            $(document).on('click', '.btn_app_delete', function () {
                $wire.ask_to_delete_app($(this).attr('value'))
            });
        });
    </script>
@endscript
