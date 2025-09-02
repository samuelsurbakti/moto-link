<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {

}; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    @can('Access - Akun - Melihat Daftar Data')
        <h4 class="fw-bold py-3 mb-0">Daftar Akun</h4>

        <livewire:access.accounts-table />
    @endcan
</div>
