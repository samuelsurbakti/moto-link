<?php

use App\Models\Sys\App;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public App $app;

    public function mount()
    {
        //
    }

    #[On('refresh_app_component.{app.id}')]
    public function refresh_app_component()
    {
        //
    }
}; ?>

<div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-grid">
                <div class="d-flex justify-content-center">
                    <img src="/src/assets/illustrations/app/{{ $app->image }}.svg" class="img-fluid h-px-100" alt="{{ $app->name }}" />
                </div>
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-grid">
                        <h5 class="mb-0">{{ $app->name }}</h5>
                        <a title="{{ $app->name }}" href="https://{{ $app->subdomain }}" target="_blank"><small>{{ $app->subdomain }}</small></a>
                    </div>
                    <a href="javascript:;" class="btn btn_app_edit p-0 pt-1" value="{{ $app->id }}" data-bs-toggle="modal" data-bs-target="#modal_app_resource"><i class="icon-base bx bx-edit icon-sm"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
