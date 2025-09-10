<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {
    public User $account;

    public function mount($id)
    {
        $this->account = User::findOrFail($id);
    }
}; ?>

@push('page_styles')
    <link rel="stylesheet" href="/themes/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="/themes/vendor/libs/sweetalert2/sweetalert2.css" />

    {{-- Select2 --}}
    <link rel="stylesheet" href="/themes/vendor/libs/select2/select2.css" />

    {{-- Boostrap Datepicker --}}
    <link rel="stylesheet" href="/themes/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css">
@endpush

@push('page_scripts')
    <script src="/themes/vendor/libs/sweetalert2/sweetalert2.js"></script>

    {{-- Select2 --}}
    <script src="/themes/vendor/libs/select2/select2.js"></script>

    {{-- Bootstrap Datepicker --}}
    <script src="/themes/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <div class="col-xl-4 col-lg-5 col-md-5 order-0 order-md-0">
            <livewire:access.account.card-detail :account="$account" />
        </div>

        <div class="col-xl-8 col-lg-7 col-md-7 order-1 order-md-1">
            @canany(['Access - Akun - Melihat Izin Khusus', 'Access - Akun - Melihat Log Aktivitas'])
                <div class="nav-align-top">
                    <ul class="nav nav-pills mb-4 nav-fill" role="tablist">
                        @can('Access - Akun - Melihat Log Aktivitas')
                            <li class="nav-item mb-1 mb-sm-0">
                                <button
                                    type="button"
                                    class="nav-link active"
                                    role="tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#nav-account-activity-log"
                                    aria-controls="nav-account-activity-log"
                                    aria-selected="true"
                                >
                                    <span class="d-none d-sm-inline-flex align-items-center">
                                        <i class="icon-base bx bx-list-ul icon-sm me-1_5"></i>Log Aktivitas
                                    </span>
                                    <i class="icon-base bx bx-list-ul icon-sm d-sm-none"></i>
                                </button>
                            </li>
                        @endcan
                        @can('Access - Akun - Melihat Izin Khusus')
                            <li class="nav-item mb-1 mb-sm-0">
                                <button
                                    type="button"
                                    class="nav-link"
                                    role="tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-justified-profile"
                                    aria-controls="navs-pills-justified-profile"
                                    aria-selected="false"
                                >
                                    <span class="d-none d-sm-inline-flex align-items-center">
                                        <i class="icon-base bx bx-shield-plus icon-sm me-1_5"></i>Izin Khusus
                                    </span>
                                    <i class="icon-base bx bx-shield-plus icon-sm d-sm-none"></i>
                                </button>
                            </li>
                        @endcan
                    </ul>
                    <div class="tab-content p-0 bg-body border-0 shadow-none">
                        <livewire:access.account.tab-activity-log :account="$account" />
                        <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                            <p>
                            Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice cream. Gummies
                            halvah tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream cheesecake fruitcake.
                            </p>
                            <p class="mb-0">
                            Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah cotton candy
                            liquorice caramels.
                            </p>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-justified-messages" role="tabpanel">
                            <p>
                            Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies cupcake gummi
                            bears cake chocolate.
                            </p>
                            <p class="mb-0">
                            Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake. Sweet roll icing
                            sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding jelly jelly-o tart brownie
                            jelly.
                            </p>
                        </div>
                    </div>
                </div>
            @endcanany
        </div>
    </div>

    @can('Access - Akun - Mengubah Data')
        <livewire:access.account.modal-resource />
    @endcan
</div>
