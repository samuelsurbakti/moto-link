<?php

use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Activitylog\Models\Activity;

new class extends Component {
    public $account;
    public $logs;

    public function mount(User $account)
    {
        $this->account = $account;

        // Ambil data aktivitas user urut terbaru
        $this->logs = Activity::where('causer_type', User::class)
            ->where('causer_id', $account->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
};
?>

<div class="tab-pane fade show active" id="nav-account-activity-log" role="tabpanel">
    <ul class="timeline timeline-center">
        @php
            $currentMonth = $logs->isNotEmpty()
                ? $logs->first()->created_at->format('Y-m')
                : null;
        @endphp

        @foreach($logs as $log)
            @php
                $logMonth = $log->created_at->format('Y-m');
            @endphp

            {{-- Jika ada perubahan bulan, tampilkan pembatas bulan dulu --}}
            @if($logMonth !== $currentMonth)
                <li class="timeline-item timeline-item-right">
                    <span class="timeline-indicator timeline-indicator-primary bg-gradient-secondary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="text-white icon-base bx bx-calendar"></i>
                    </span>
                    <div class="card p-0 bg-gradient-secondary" data-aos="fade-right">
                        <h5 class="card-header text-white py-3 px-2">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->translatedFormat('F Y') }}
                        </h5>
                    </div>
                </li>

                @php $currentMonth = $logMonth; @endphp
            @endif

            {{-- Data log --}}
            <li class="timeline-item {{ $log->log_name == 'Akses Halaman' ? 'timeline-item-left' : 'timeline-item-right' }}">
                <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="icon-base {{ $log->log_name == 'Akses Halaman' ? 'bx bx-link' : 'bx bx-data' }}"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header pb-2 d-grid">
                        <h5 class="card-title mb-0">{{ $log->log_name }}</h5>
                        <small>
                            <span class="text-body-secondary">{{ $log->created_at->format('d F Y, H:i') }}</span>
                        </small>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">{{ $log->description }}</p>
                        @if($log->log_name == 'Akses Halaman')
                            <div class="d-flex justify-content-start justify-content-md-end">
                                <x-ui::elements.button type="button" class="btn btn-sm btn-outline-primary">
                                    <span class="icon-base bx bx-link icon-sm me-2"></span> Kunjungi Halaman
                                </x-ui::elements.button>
                            </div>
                        @else
                            <div class="d-flex justify-content-start">
                                <x-ui::elements.button type="button" class="btn btn-sm btn-outline-primary">
                                    <span class="icon-base bx bx-search icon-sm me-2"></span> Lihat Detail
                                </x-ui::elements.button>
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach

        {{-- Tampilkan pembatas bulan untuk bulan terakhir --}}
        @if($currentMonth)
            <li class="timeline-item timeline-item-right">
                <span class="timeline-indicator timeline-indicator-primary bg-gradient-secondary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="text-white icon-base bx bx-calendar"></i>
                </span>
                <div class="card p-0 bg-gradient-secondary" data-aos="fade-right">
                    <h5 class="card-header text-white py-3 px-2">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->translatedFormat('F Y') }}
                    </h5>
                </div>
            </li>
        @endif
    </ul>
</div>
