<?php

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Slp\Permission;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $types = [], $apps = [], $menus = [];

    public ?string $permission_id = null;

    #[Validate('required|string', as: 'Jenis')]
    public ?string $permission_type = null;

    #[Validate('required|string', as: 'Aplikasi')]
    public ?string $permission_app_id = null;

    #[Validate('required_unless:permission_type,App|nullable|string', as: 'Menu')]
    public ?string $permission_menu_id = null;

    #[Validate('required|string', as: 'Izin')]
    public ?string $permission_name = null;

    #[Validate('required|numeric', as: 'Urutan')]
    public ?string $permission_number = null;

    #[On('set_permission')]
    public function set_permission($permission_id)
    {
        $this->reset_permission();
        $this->permission_id = $permission_id;
        $permission = Permission::where('uuid', $this->permission_id)->first();

        $this->permission_type = $permission->type;
        $this->permission_app_id = $permission->app_id;
        $this->menus = Menu::where('app_id', $this->permission_app_id)->orderBy('order_number')->get();
        $this->permission_menu_id = $permission->menu_id;

        $this->permission_name = $permission->name;
        $this->permission_number = $permission->number;
    }

    public function reset_permission()
    {
        $this->reset(['permission_id', 'permission_type', 'permission_app_id', 'permission_menu_id', 'permission_name', 'permission_number']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    public function set_permission_field($field, $value)
    {
        $this->$field = $value;

        if($field == 'permission_app_id') {
            $this->menus = Menu::where('app_id', $this->permission_app_id)->orderBy('order_number')->get();
        }
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->permission_id)) {
            $menu = Permission::create([
                'type' => $this->permission_type,
                'app_id' => $this->permission_app_id,
                'menu_id' => $this->permission_menu_id,
                'name' => $this->permission_name,
                'guard_name' => 'web',
                'number' => $this->permission_number,
            ]);
        } else {
            $menu = Permission::findOrFail($this->permission_id);

            $menu->update([
                'type' => $this->permission_type,
                'app_id' => $this->permission_app_id,
                'menu_id' => $this->permission_menu_id,
                'name' => $this->permission_name,
                'guard_name' => 'web',
                'number' => $this->permission_number,
            ]);
        }

        $this->dispatch('close_modal_permission_resource');
        $this->dispatch('refreshDatatable');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->permission_id) ? 'menambah' : 'mengubah') . ' Izin')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_permission();
    }

    #[On('ask_to_delete_permission')]
    public function ask_to_delete_permission($permission_id)
    {
        $this->permission_id = $permission_id;
        $permission = Permission::find($this->permission_id);
        LivewireAlert::title('Peringatan')
            ->text('Perintah ini akan menghapus Izin '.$permission->name.', Anda yakin ingin melanjutkan?')
            ->asConfirm()
            ->withConfirmButton('Lanjutkan')
            ->withDenyButton('Batalkan')
            ->onConfirm('delete_permission')
            ->show();
    }

    public function delete_permission()
    {
        $permission = Permission::find($this->permission_id);

        if($permission) {
            $permission->delete();

            $this->dispatch('refreshDatatable');

            LivewireAlert::title('')
            ->text('Berhasil menghapus Izin')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

            $this->reset_permission();
        }
    }

    public function mount()
    {
        $this->apps = App::orderBy('order_number')->get();
        $this->types = ['App', 'Menu', 'Permission'];
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_permission_resource"
    :title="(is_null($permission_id) ? 'Tambah' : 'Edit') . ' Izin'"
    :description="'Di sini, Anda dapat ' . (is_null($permission_id) ? 'menambah data' : 'mengubah informasi') . ' izin.'"
    :loading-targets="['set_permission', 'reset_permission', 'save']"
    size="lg"
>
    @csrf
    <x-ui::forms.select
        wire-model="permission_type"
        label="Jenis"
        placeholder="Pilih Jenis"
        container-class="col-12 mb-6"
        init-select2-class="select2_permission"
    >
        @forelse($types as $type)
            <option value="{{ $type }}">{{ $type }}</option>
        @empty
        @endforelse
    </x-ui::forms.select>

    <x-ui::forms.select
        wire-model="permission_app_id"
        label="Aplikasi"
        placeholder="Pilih Aplikasi"
        container-class="col-12 mb-6"
        init-select2-class="select2_permission"
        :options="$apps"
        value-field="id"
        text-field="name"
    />

    <x-ui::forms.select
        wire-model="permission_menu_id"
        label="Menu"
        placeholder="Pilih Menu"
        container-class="col-12 mb-6"
        init-select2-class="select2_permission"
        :options="$menus"
        value-field="id"
        text-field="title"
    />

    <x-ui::forms.input
        wire:model.blur="permission_name"
        type="text"
        label="Izin"
        placeholder="Izin"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input
        wire:model.blur="permission_number"
        type="text"
        label="Urutan"
        placeholder="Urutan"
        container_class="col-12 mb-6"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_permission_resource', () => {
            var modalElement = document.getElementById('modal_permission_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_permission");
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

            $(document).on('change', '.select2_permission', function () {
                $wire.set_permission_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_permission_add', function () {
                $wire.reset_permission();
            });

            $(document).on('click', '.btn_permission_edit', function () {
                $wire.set_permission($(this).attr('value'));
            });

            $(document).on('click', '.btn_permission_delete', function () {
                $wire.ask_to_delete_permission($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
