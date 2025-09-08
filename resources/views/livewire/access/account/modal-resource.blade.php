<?php

use App\Models\User;
use App\Models\Slp\Role;
use App\Helpers\FileHelper;
use Livewire\Volt\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Laravolt\Avatar\Facade as Avatar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    public $options_role = [];

    public $account_id;

    #[Validate('required|string', as: 'Peran')]
    public $account_role;

    #[Validate('required|string', as: 'Nama')]
    public $account_name;

    #[Validate(as: 'Username')]
    public $account_username;

    #[Validate(as: 'Email')]
    public $account_email;

    #[Validate(as: 'Password')]
    public $account_password;

    #[Validate(as: 'Konfirmasi Password')]
    public $account_re_password;

    public function rules(): array
    {
        return [
            'account_username' => [
                'required',
                'string',
                Rule::unique('users', 'username')
                    ->ignore($this->account_id),
            ],
            'account_email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($this->account_id),
            ],
            'account_password' => [
                'nullable',
                'string',
                Rule::requiredIf(!$this->account_id),
            ],
            'account_re_password' => [
                'nullable',
                'string',
                'same:account_password',
                Rule::requiredIf(!$this->account_id),
            ],
        ];
    }

    public function set_account($account_id)
    {
        $this->reset_account();
        $this->account_id = $account_id;
        $account = User::findOrFail($this->account_id);

        $this->set_account_field('account_role', $account->getRoleNames()->first());
        $this->account_name = $account->name;
        $this->account_username = $account->username;
        $this->account_email = $account->email;
    }

    public function reset_account()
    {
        $this->reset(['account_id', 'account_role', 'account_name', 'account_username', 'account_email', 'account_password', 'account_re_password']);
        $this->resetValidation();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
    }

    public function set_account_field($field, $value)
    {
        $this->$field = $value;
    }

    public function save()
    {
        $this->validate();

        if(is_null($this->account_id)) {
            $avatar = md5($this->account_name);
            FileHelper::ensure_folder_exists('img/user/', 'src');
            Avatar::create($this->account_name)->save(storage_path('app/src/img/user/'.$avatar.'.png'), $quality = 100);

            $account = User::create([
                'name' => $this->account_name,
                'username' => $this->account_username,
                'email' => $this->account_email,
                'password' => $this->account_password,
                'avatar' => $avatar.'.png',
                'account_status' => 1,
            ]);

            $account->assignRole($this->account_role);
        } else {
            $account = User::findOrFail($this->account_id);

            $account->update([
                'name' => $this->account_name,
                'username' => $this->account_username,
                'email' => $this->account_email,
            ]);
        }

        $this->dispatch('close_modal_account_resource');
        $this->dispatch('refreshDatatable');

        LivewireAlert::title('')
            ->text('Berhasil ' . (is_null($this->account_id) ? 'menambah' : 'mengubah') . ' Akun')
            ->success()
            ->toast()
            ->position('bottom-end')
            ->show();

        $this->reset_account();
    }

    public function mount()
    {
        $this->options_role = Role::orderBy('name')->get();
    }
}; ?>

<x-ui::elements.modal-form
    id="modal_account_resource"
    :title="(is_null($account_id) ? 'Tambah' : 'Edit') . ' Akun'"
    :description="'Di sini, Anda dapat ' . (is_null($account_id) ? 'menambah data' : 'mengubah informasi') . ' akun.'"
    :loading-targets="['set_account', 'reset_account', 'save']"
    size="lg"
>
    @csrf
    <x-ui::forms.select
        wire-model="account_role"
        label="Peran"
        placeholder="Pilih Peran"
        container-class="col-12 mb-6"
        init-select2-class="select2_account"
        :options="$options_role"
        value-field="name"
        text-field="name"
    />

    <x-ui::forms.input
        wire:model="account_name"
        type="text"
        label="Nama"
        placeholder="Agus Budi Santoso"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input
        wire:model="account_username"
        type="text"
        label="Username"
        placeholder="aguskeren123"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input
        wire:model="account_email"
        type="text"
        label="Email"
        placeholder="agusbudi@mail.com"
        container_class="col-12 mb-6"
    />

    <x-ui::forms.input-toggle
        id="password"
        name="password"
        label="Password"
        placeholder="••••••••"
        wire:model="account_password"
        container_class="form-password-toggle col-12 mb-6"
    >
        @if ($account_id)
            <div class="form-text"> Biarkan ini kosong jika tidak ingin diubah. </div>
        @endif
    </x-ui::forms.input-toggle>

    <x-ui::forms.input-toggle
        id="re_password"
        name="re_password"
        label="Konfirmasi Password"
        placeholder="••••••••"
        wire:model="account_re_password"
        container_class="form-password-toggle col-12 mb-6"
    >
        @if ($account_id)
            <div class="form-text"> Biarkan ini kosong jika tidak ingin diubah. </div>
        @endif
    </x-ui::forms.input-toggle>
</x-ui::elements.modal-form>

@script
    <script>
        Livewire.on('close_modal_account_resource', () => {
            var modalElement = document.getElementById('modal_account_resource');
            var modal = bootstrap.Modal.getInstance(modalElement)
            modal.hide();
        });

        function initSelect2() {
            var e_select2 = $(".select2_account");
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

            $(document).on('change', '.select2_account', function () {
                $wire.set_account_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '#btn_account_add', function () {
                $wire.reset_account();
            });

            $(document).on('click', '.btn_account_edit', function () {
                $wire.set_account($(this).attr('value'));
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript

