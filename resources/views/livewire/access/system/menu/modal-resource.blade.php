<?php

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $apps = [], $menus = [];

    public ?string $menu_id = null;

    #[Validate('required|string', as: 'Aplikasi')]
    public ?string $menu_app_id = null;

    #[Validate('required|string', as: 'Judul')]
    public ?string $menu_title = null;

    #[Validate('required|string', as: 'Ikon')]
    public ?string $menu_icon = null;

    #[Validate('required|string', as: 'Url')]
    public ?string $menu_url = null;

    #[Validate('required|string', as: 'Urutan')]
    public ?string $menu_order_number = null;

    #[Validate('nullable', as: 'Turunan dari')]
    public ?string $menu_parent = null;

    #[Validate('nullable', as: 'Anggota dari')]
    public ?string $menu_member_of = null;

    #[On('reset_menu')]
    public function reset_menu()
    {
        $this->reset(['menu_id', 'menu_app_id', 'menu_title', 'menu_icon', 'menu_url', 'menu_order_number', 'menu_parent', 'menu_member_of', 'menus']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    #[On('set_menu')]
    public function set_menu($menu_id)
    {
        $this->menu_id = $menu_id;
        $menu = Menu::findOrFail($this->menu_id);

        $this->set_menu_field('menu_app_id', $menu->app_id);
        $this->menu_title = $menu->title;
        $this->menu_icon = $menu->icon;
        $this->menu_url = $menu->url;
        $this->menu_order_number = $menu->order_number;
        $this->menu_parent = $menu->parent;
        $this->menu_member_of = $menu->member_of;
    }

    #[On('set_menu_field')]
    public function set_menu_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'menu_app_id') {
            $this->menus = Menu::where('app_id', $this->menu_app_id)->orderBy('order_number')->get();
        }

        $this->dispatch('re_init_select2');
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (Throwable $e) {
            $this->dispatch('re_init_select2');
            throw $e;
        }

        if(is_null($this->menu_id)) {
            $menu = Menu::create([
                'app_id' => $this->menu_app_id,
                'title' => $this->menu_title,
                'icon' => $this->menu_icon,
                'url' => $this->menu_url,
                'order_number' => $this->menu_order_number,
                'parent' => $this->menu_parent,
                'member_of' => $this->menu_member_of,
            ]);
        } else {
            $menu = Menu::findOrFail($this->menu_id);

            $menu->update([
                'app_id' => $this->menu_app_id,
                'title' => $this->menu_title,
                'icon' => $this->menu_icon,
                'url' => $this->menu_url,
                'order_number' => $this->menu_order_number,
                'parent' => $this->menu_parent,
                'member_of' => $this->menu_member_of,
            ]);
        }

        $this->dispatch('close_modal_menu_resource');
        $this->dispatch('refreshDatatable');

        LivewireAlert::title('')
            ->text('Berhasil '.(is_null($this->menu_id) ? 'menambah' : 'mengubah').' Menu')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_menu();
    }

    #[On('ask_to_delete_menu')]
    public function ask_to_delete_menu($menu_id)
    {
        $this->menu_id = $menu_id;
        $menu = Menu::find($this->menu_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus Menu '.$menu->title.' dari aplikasi '.$menu->app->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_menu')
            ->show();
    }

    public function delete_menu()
    {
        $menu = Menu::find($this->menu_id);

        if($menu) {
            $menu->delete();

            $this->dispatch('refreshDatatable');

            LivewireAlert::title('')
            ->text('Berhasil menghapus menu')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_menu();
        }
    }

    public function mount()
    {
        $this->apps = App::orderBy('order_number')->get();
    }
}; ?>

<div wire:ignore.self class="modal fade" id="modal_menu_resource" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <x-ui::elements.loading text="Mengambil Data" target="set_menu, reset_menu" />
            <x-ui::elements.loading text="Menyimpan Data" target="save" />

            <div wire:loading.remove wire:target="set_menu, reset_menu, save" class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div wire:loading.remove wire:target="set_menu, reset_menu, save" class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="mb-0">{{ (is_null($menu_id) ? 'Tambah' : 'Edit') }} Menu</h3>
                    <p>Di sini, Anda dapat {{ (is_null($menu_id) ? 'menambah data' : 'mengubah informasi') }} Menu.</p>
                </div>
                <form wire:submit="save" method="POST">
                    @csrf
                    <x-ui::forms.select
                        wire-model="menu_app_id"
                        label="Aplikasi"
                        placeholder="Pilih Aplikasi"
                        container-class="col-12 mb-6"
                        init-select2-class="select2_menu"
                        :options="$apps"
                        value-field="id"
                        text-field="name"
                    />

                    <x-ui::forms.input
                        wire:model.live="menu_title"
                        type="text"
                        label="Judul"
                        placeholder="Judul"
                        container_class="col-12 mb-6"
                    />

                    <x-ui::forms.input
                        wire:model.live="menu_icon"
                        type="text"
                        label="Ikon"
                        placeholder="Ikon"
                        container_class="col-12 mb-6"
                    >
                        <div class="form-text">
                            <a href="https://boxicons.com/" target="_blank">Lihat kode icon <i class='bx bx-link-external' style="font-size: 85%;"></i></a>
                        </div>
                    </x-ui::forms.input>

                    <x-ui::forms.input
                        wire:model.live="menu_url"
                        type="text"
                        label="Url"
                        placeholder="Url"
                        container_class="col-12 mb-6"
                    />

                    <x-ui::forms.input
                        wire:model.live="menu_order_number"
                        type="text"
                        label="Urutan"
                        placeholder="Urutan"
                        container_class="col-12 mb-6"
                    />

                    <x-ui::forms.select
                        wire-model="menu_parent"
                        label="Turunan Dari"
                        placeholder="Pilih Parent"
                        container-class="col-12 mb-6"
                        init-select2-class="select2_menu"
                        :options="$menus"
                        value-field="id"
                        text-field="title"
                    />

                    <x-ui::forms.input
                        wire:model.live="menu_member_of"
                        type="text"
                        label="Anggota Dari"
                        placeholder="Anggota Dari"
                        container_class="col-12 mb-6"
                    />

                    <div class="col-12 text-center mt-8">
                        <x-ui::elements.button type="submit" class="btn-primary me-sm-3 me-1">
                            Simpan
                        </x-ui::elements.button>
                        <x-ui::elements.button type="reset" class="btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Batalkan
                        </x-ui::elements.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Livewire.on('close_modal_menu_resource', () => {
            var modalElement = document.getElementById('modal_menu_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_menu");
            e_select2.length && e_select2.each(function () {
                var e_select2 = $(this);
                e_select2.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Select value",
                    allowClear: true,
                    dropdownParent: e_select2.parent()
                })
            })
        }

        $(document).ready(function () {
            initSelect2();

            $(document).on('change', '.select2_menu', function () {
                $wire.set_menu_field($(this).attr('id'), $(this).val())
            });

            $(document).on('click', '#btn_menu_add', function () {
                $wire.reset_menu();
            });

            $(document).on('click', '.btn_menu_edit', function () {
                $wire.set_menu($(this).attr('value'));
            });

            $(document).on('click', '.btn_menu_delete', function () {
                $wire.ask_to_delete_menu($(this).attr('value'))
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
