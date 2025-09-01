<?php

use App\Models\Slp\Role;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Role $role;
    public int $roleUsersCount;
    public $users = [];

    public function mount()
    {
        $this->role->loadCount('users');
        $this->role->load('users');

        $this->roleUsersCount = $this->role->users_count;
        $this->users = $this->role->users;
    }

    #[On('refresh_role_component{role.uuid}')]
    public function refreshComponent()
    {
        $this->role->loadCount('users');
        $this->role->load('users');
        $this->roleUsersCount = $this->role->users_count;
        $this->users = $this->role->users;
    }
}; ?>

<div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h6 class="fw-normal">Total {{ $roleUsersCount }} akun</h6>
                @if($roleUsersCount != 0)
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                    @php $no = 1; @endphp
                    @foreach($users as $user)
                        @if($no <= 5)
                            <li class="avatar avatar-sm pull-up">
                                <img class="rounded-circle" src="{{ asset('src/img/user/'.$user->avatar) }}" alt="{{ $user->name }} Avatar" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $user->name }}">
                            </li>
                        @endif
                        @php $no++; @endphp
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="grid justify-content-between align-items-end">
                <div class="role-heading">
                    <h4 class="mb-1">{{ $role->name }}</h4>
                </div>
                <div class="d-flex justify-content-between">
                    @can('Access - Otorisasi - Mengelola Otoritas')
                        <x-ui::elements.button
                            class="bg-primary-subtle text-primary btn_provide"
                            title="{{ $role->name }}"
                            value="{{ $role->uuid }}"
                            data-bs-toggle="modal"
                            data-bs-target="#modal_provide_permission"
                        >
                            <small>Kelola Izin</small>
                        </x-ui::elements.button>
                    @endcan

                    @can('Access - Otorisasi - Peran - Mengubah Data')
                        <x-ui::elements.button
                            class="bg-success-subtle text-success btn_role_edit"
                            title="{{ $role->name }}"
                            value="{{ $role->uuid }}"
                            data-bs-toggle="modal"
                            data-bs-target="#modal_role_resource"
                        >
                            <small>Edit Peran</small>
                        </x-ui::elements.button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
