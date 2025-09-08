<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

new class extends Component {
    public User $account;
    public Collection $groupedActivities;

    public function mount(User $account)
    {
        $this->account = $account;
        $this->groupedActivities = collect();
        $this->loadActivities();
    }

    public function loadActivities()
    {
        try {
            // Query yang lebih aman
            $activities = Activity::query()
                ->where('causer_id', $this->account->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $this->groupedActivities = $activities->groupBy(function ($activity) {
                return $activity->created_at->format('F Y');
            });

        } catch (\Exception $e) {
            // Fallback jika masih error
            $this->groupedActivities = collect();
        }
    }

    public function getIcon($logName)
    {
        return match(true) {
            str_contains($logName, 'Akses') => 'bx-link',
            str_contains($logName, 'Mengelola') => 'bx-data',
            default => 'bx-info-circle'
        };
    }

    public function getIndicatorClass($logName)
    {
        return match(true) {
            str_contains($logName, 'Akses') => 'timeline-indicator-primary',
            str_contains($logName, 'Mengelola') => 'timeline-indicator-success',
            default => 'timeline-indicator-info'
        };
    }

    public function getTimelinePosition($logName)
    {
        return str_contains($logName, 'Akses') ? 'left' : 'right';
    }
}; ?>

<div class="tab-pane fade show active" id="nav-account-activity-log" role="tabpanel">
    <ul class="timeline timeline-center">
        @forelse($groupedActivities as $monthYear => $monthActivities)
            <!-- Month Header -->
            <li class="timeline-item timeline-item-right">
                <span class="timeline-indicator timeline-indicator-primary bg-gradient-secondary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="text-white icon-base bx bx-calendar"></i>
                </span>
                <div class="card p-0 bg-gradient-secondary" data-aos="fade-right">
                    <h5 class="card-header text-white py-3 px-2">{{ $monthYear }}</h5>
                </div>
            </li>

            <!-- Activities for this month -->
            @foreach($monthActivities as $activity)
                @php
                    $position = $this->getTimelinePosition($activity->log_name);
                    $icon = $this->getIcon($activity->log_name);
                    $indicatorClass = $this->getIndicatorClass($activity->log_name);
                @endphp

                <li class="timeline-item timeline-item-{{ $position }}">
                    <span class="timeline-indicator {{ $indicatorClass }}" data-aos="zoom-in" data-aos-delay="200">
                        <i class="icon-base bx {{ $icon }}"></i>
                    </span>
                    <div class="timeline-event card p-0" data-aos="fade-{{ $position }}">
                        <div class="card-header pb-2 d-grid">
                            <h5 class="card-title mb-0">{{ $activity->log_name }}</h5>
                            <small>
                                <span class="text-body-secondary">
                                    {{ $activity->created_at->format('d F Y, H:i') }}
                                </span>
                            </small>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">{{ $activity->description }}</p>

                            @if($activity->log_name === 'Akses Halaman')
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
        @empty
            <li class="text-center py-4">
                <div class="text-muted">
                    <i class="bx bx-time-five icon-xl"></i>
                    <p class="mt-2">Tidak ada activity log</p>
                </div>
            </li>
        @endforelse
    </ul>
</div>
