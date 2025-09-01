<?php

use App\Models\Sys\App;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('ui.layouts.horizontal')] class extends Component {
    public $apps;

    public function mount()
    {
        $this->apps = App::where('name', '!=', 'Access')->orderBy('order_number')->get();
    }
}; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <div class="col-lg-6 order-0">
            <div class="card h-100">
                <div class="card-body pb-0 d-sm-grid d-md-flex">
                    <div class="d-grid align-content-end">
                        <h5 class="card-title text-primary">Halo {{ auth()->user()->name }}!</h5>
                        <p class="mb-4">
                            Senang Anda kembali di <span class="fw-bold">Access</span>! Kami di sini untuk membantu Anda mengelola setiap detail pekerjaan dengan lebih mudah dan cepat. Jika ada yang bisa kami bantu, jangan ragu untuk menghubungi tim dukungan. Selamat berakselerasi!
                        </p>
                    </div>
                    <div class="d-flex">
                        <img class="h-px-250" src="/src/assets/illustrations/welcome.svg" alt="Welcome Image" />
                    </div>
                </div>
            </div>
        </div>

        @foreach ($apps as $app)
            @if (auth()->user()->hasPermissionTo($app->app_permission->name))
                <div class="col-lg-3">
                    <div class="card cursor-pointer app_link" link="{{ $app->subdomain }}">
                        <div class="card-body d-grid justify-content-center text-center">
                            <img src="/src/assets/illustrations/app/{{ $app->image }}.svg" class="rounded-start h-px-200" alt="{{ $app->name }} App Image" />

                            <h5 class="card-title mb-1 text-primary">{{ $app->name }}</h5>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

@script
    <script>
        $(document).ready(function () {
            $(document).on('click', '.app_link', function () {
                const url = $(this).attr('link');

                if (isMobileDevice()) {
                    document.location.href = 'https://'+url;
                } else {
                    window.open('https://'+url, "_blank");
                }
            });
        });
    </script>
@endscript
