<?php

use App\Models\Sys\App;
use App\Models\Slp\Role;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $options_app = [];

    public ?string $role_id = null;

    #[Validate('required|string', as: 'Nama')]
    public string $role_name;

    #[Validate('required', as: 'Aplikasi Pengalihan Default')]
    public string $role_default_app_id;

    #[Validate(['required', 'regex:/^([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'], as: 'Warna Badge')]
    public string $role_badge_color;

    #[On('set_role')]
    public function set_role($role_id)
    {
        $this->role_id = $role_id;

        $role = Role::where('uuid', $this->role_id)->firstOrFail();
        $this->role_name = $role->name;
        $this->set_role_field('role_default_app_id', $role->default_app_id);
        $this->role_badge_color = $role->badge_color;
    }

    #[On('reset_role')]
    public function reset_role()
    {
        $this->reset(['role_id', 'role_name']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    public function set_role_field($field, $value)
    {
        $this->$field = $value;
    }

    public function save()
    {
        $this->validate();

        if (is_null($this->role_id)) {
            $role = Role::create([
                'name' => $this->role_name,
                'guard_name' => 'web',
            ]);

            $this->dispatch("re_render_roles_container");
        } else {
            $role = Role::where('uuid', $this->role_id)->firstOrFail();
            $role->update(['name' => $this->role_name]);

            $this->dispatch("refresh_role_component{$role->uuid}");
        }

        $this->dispatch('close_modal_role_resource');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->role_id) ? 'menambah' : 'mengubah') . ' peran')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_role();
    }

    public function mount()
    {
        $this->options_app = App::orderBy('order_number')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_role_resource"
    :title="(is_null($role_id) ? 'Tambah' : 'Edit') . ' Peran'"
    :description="'Di sini, Anda dapat ' . (is_null($role_id) ? 'menambah data' : 'mengubah informasi') . ' peran.'"
    :loading-targets="['set_role', 'reset_role', 'save']"
    size="md"
>
    @csrf
    <x-ui::forms.input
        wire:model.live="role_name"
        type="text"
        label="Nama"
        placeholder="Admin"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input-group
        wire:model="role_badge_color"
        type="text"
        label="Warna Badge"
        placeholder="0D47A1"
        container_class="col-12 mb-6"
        front="#"
    />

    <x-ui::forms.select
        wire-model="role_default_app_id"
        label="Aplikasi Pengalihan Default"
        placeholder="Pilih Aplikasi"
        container-class="col-12 mb-6"
        init-select2-class="select2_role"
        :options="$options_app"
        value-field="id"
        text-field="name"
    />
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_role_resource', () => {
            var modalElement = document.getElementById('modal_role_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_role");
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

            $(document).on('change', '.select2_role', function () {
                $wire.set_role_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '.btn_role_edit', function () {
                $wire.set_role($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
