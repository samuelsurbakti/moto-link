<?php

use Carbon\Carbon;
use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public $account;

    public function mount(User $account)
    {
        $this->account = $account;
    }
}; ?>

<div class="card mb-4">
    <div class="card-body">
        <div class="user-avatar-section">
            <div class=" d-flex align-items-center flex-column">
                <img src="{{ asset('src/img/user/'.$account->avatar) }}" alt="User Image" class="img-fluid rounded my-4" width="110" height="110" />
                <div class="user-info text-center">
                    <h4 class="mb-2">{{ $account->name }}</h4>
                    <span class="badge rounded-pill" style="background-color: color-mix(in sRGB, #fff 84%, #{{ $account->roles->first()->badge_color}}) !important; color: #{{ $account->roles->first()->badge_color}} !important;">{{ $account->getRoleNames()->first() ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-start flex-wrap my-4 py-3">
            <div class="d-flex align-items-start me-4 mt-3 gap-3">
                <span class="badge bg-label-primary p-2 rounded"><i class="bx bx-calendar-check bx-sm"></i></span>
                <div>
                    <h5 class="mb-0">{{ Carbon::parse($account->created_at)->isoFormat('D MMMM Y') }}</h5>
                    <span>{{ Carbon::parse($account->created_at)->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        <h5 class="pb-2 border-bottom mb-4">Detail</h5>
        <div class="info-container">
            <ul class="list-unstyled">
                <li class="mb-3">
                    <span class="fw-bold me-2">Username:</span>
                    <span>{{ $account->username }}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-bold me-2">Email:</span>
                    <span>{{ $account->email }}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-bold me-2">Status:</span>
                    <span class="badge rounded-pill {{($account->account_status == 1 ? 'bg-label-success' : 'bg-label-danger')}}">{{($account->account_status == 1 ? 'Aktif' : 'Tidak Aktif')}}</span>
                </li>
            </ul>
            <div class="d-grid gap-2">
                <button value="{{ $account->id }}" class="btn btn-label-primary btn_account_edit" data-bs-target="#modal_account_resource" data-bs-toggle="modal">Ubah Informasi</button>
                <button value="{{ $account->id }}" class="btn btn-label-secondary btn_account_edit" data-bs-target="#modal_account_resource" data-bs-toggle="modal">Ubah Password</button>
                <a href="javascript:;" class="btn btn-block btn-label-danger suspend-user">Nonaktifkan Akun</a>
            </div>
        </div>
    </div>
</div>
