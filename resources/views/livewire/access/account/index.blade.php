<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {

}; ?>

@push('page_styles')
    <link rel="stylesheet" href="/themes/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="/themes/vendor/libs/sweetalert2/sweetalert2.css" />

    {{-- Select2 --}}
    <link rel="stylesheet" href="/themes/vendor/libs/select2/select2.css" />
@endpush

@push('page_scripts')
    <script src="/themes/vendor/libs/sweetalert2/sweetalert2.js"></script>

    {{-- Select2 --}}
    <script src="/themes/vendor/libs/select2/select2.js"></script>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    @can('Access - Akun - Melihat Daftar Data')
        <h4 class="fw-bold py-3 mb-0">Daftar Akun</h4>

        <livewire:access.accounts-table />
    @endcan

    @can('Access - Akun - Menambah Data')
        <livewire:access.account.modal-resource />
    @endcan
</div>
