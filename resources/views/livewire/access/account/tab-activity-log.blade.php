    <?php

use Carbon\Carbon;
use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Activitylog\Models\Activity;

new class extends Component {
    public $account;
    public $logs = [];
    public $options_log_name;
    public $tab_filter_start_date, $tab_filter_end_date, $tab_filter_log_name;

    public $perPage = 10; // jumlah data setiap kali load
    public $hasMore = true; // penanda apakah masih ada data
    public $page = 1;

    public function mount(User $account)
    {
        $this->account = $account;
        $this->options_log_name = Activity::select('log_name')->distinct()->get();
        $this->logs = collect();
        $this->loadLogs(true);
    }

    public function set_tab_filter_field($field, $value)
    {
        // dipanggil dari select2 (JS). Pastikan `field` adalah nama properti yang benar.
        $this->$field = $value;
        $this->resetPagingAndLoad();
    }

    public function hydrate()
    {
        $this->dispatch('re_init_select2');
        $this->dispatch('init_bootstrap_datepicker');
        $this->options_log_name = Activity::select('log_name')->distinct()->get();
    }

    public function updated($field)
    {
        if (str_starts_with($field, 'tab_filter_')) {
            $this->resetPagingAndLoad();
        }
    }

    protected function resetPagingAndLoad()
    {
        $this->page = 1;
        $this->hasMore = true;
        $this->logs = collect();
        $this->loadLogs(true);
    }

    public function loadLogs($reset = false)
    {
        if (!$this->hasMore && !$reset) return;

        $query = Activity::where('causer_type', get_class($this->account))
            ->where('causer_id', $this->account->id);

        if ($this->tab_filter_start_date) {
            $query->whereDate('created_at', '>=', $this->tab_filter_start_date);
        }
        if ($this->tab_filter_end_date) {
            $query->whereDate('created_at', '<=', $this->tab_filter_end_date);
        }
        if ($this->tab_filter_log_name) {
            $query->where('log_name', $this->tab_filter_log_name);
        }

        $query->orderBy('created_at', 'desc');

        // ambil 1 ekstra untuk cek apakah masih ada data
        $data = $query->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage + 1)
            ->get();

        $items = $data->take($this->perPage);

        if ($reset) {
            // reset berarti kita mulai dari awal, jadi data lama dibuang
            $this->logs = collect($items);
        } else {
            // loadMore berarti kita menambahkan ke bawah
            $this->logs = $this->logs->merge($items)->values();
        }

        $this->hasMore = $data->count() > $this->perPage;
        if (!$this->hasMore) {
            $this->dispatch('no-more-logs');
        }

        $this->page++;
    }

    public function loadMore()
    {
        // method yang dipanggil JS / observer
        $this->loadLogs(false);
    }
};
?>

<div class="tab-pane fade show active" id="nav-account-activity-log" role="tabpanel">
    {{-- Filter --}}
    <div class="card mb-4">
        <h5 class="card-header pb-2">Filter</h5>
        <div class="card-body">
            <div class="row g-6">
                <div class="col-lg-4">
                    <x-ui::forms.input wire:model="tab_filter_start_date" type="text" label="Awal" placeholder="2025-01-01" />
                </div>
                <div class="col-lg-4">
                    <x-ui::forms.input wire:model="tab_filter_end_date" type="text" label="Akhir" placeholder="2025-12-31" />
                </div>
                <div class="col-lg-4">
                    <x-ui::forms.select
                        wire-model="tab_filter_log_name"
                        label="Jenis Aktivitas"
                        placeholder="Pilih Jenis Aktivitas"
                        init-select2-class="select2_tab_filter"
                        :options="$options_log_name"
                        value-field="log_name"
                        text-field="log_name"
                    />
                </div>
            </div>
        </div>
    </div>

    @if($logs->isEmpty())
        <div class="card">
            <div class="card p-3">
                <div class="card-body text-center text-muted">
                    Tidak ada aktivitas untuk filter ini.
                </div>
            </div>
        </div>
    @endif

    <div class="card bg-body shadow-none">
        <ul class="timeline timeline-center">
            @php
                $currentMonth = $logs->isNotEmpty() ? $logs->first()->created_at->format('Y-m') : null;
            @endphp

            @foreach($logs as $log)
                @php $logMonth = $log->created_at->format('Y-m'); @endphp

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

                {{-- Log item (sama seperti milik Anda) --}}
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
                            @if($log->log_name === 'Akses Halaman')
                                <div class="d-flex justify-content-start justify-content-md-end">
                                    <x-ui::elements.button type="button" class="btn btn-sm btn-outline-primary to_page" page="{{ $log->getExtraProperty('url') }}">
                                        <span class="icon-base bx bx-link icon-sm me-2"></span> Kunjungi Halaman
                                    </x-ui::elements.button>
                                </div>
                            @else
                                <div class="d-flex justify-content-start">
                                    <x-ui::elements.button type="button" class="btn btn-sm btn-outline-primary open_detail">
                                        <span class="icon-base bx bx-search icon-sm me-2"></span> Lihat Detail
                                    </x-ui::elements.button>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- sentinel untuk IntersectionObserver --}}
    <div id="log-end-sentinel" style="height: 1px;"></div>

    {{-- Loading indicator --}}
    <div wire:loading wire:target="loadMore" class="text-center my-3">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
</div>


@script
    <script>
        $(document).ready(function () {
            var sentinel = document.getElementById('log-end-sentinel');
            if (sentinel && 'IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            $wire.loadMore();
                        }
                    });
                }, { root: null, rootMargin: '300px' });

                observer.observe(sentinel);

                // disconnect saat server memberi tahu tidak ada lagi
                window.addEventListener('no-more-logs', function () {
                    try { observer.disconnect(); } catch(e) {}
                });
            } else {
                // fallback: scroll
                window.onscroll = function() {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
                        $wire.loadMore();
                    }
                };
            }

            function initSelect2() {
                var e_select2 = $(".select2_tab_filter");
                e_select2.length && e_select2.each(function () {
                    var e_select2 = $(this);
                    e_select2.wrap('<div class="position-relative"></div>').select2({
                        placeholder: "Select value",
                        allowClear: true,
                        dropdownParent: e_select2.parent()
                    })
                })
            }

            function init_bootstrap_datepicker() {
                var date = $("#tab_filter_start_date, #tab_filter_end_date").datepicker({
                    todayHighlight: !0,
                    format: "yyyy-mm-dd",
                    language: 'id',
                    orientation: isRtl ? "auto right" : "auto left",
                    autoclose: true
                })
            }

            initSelect2();
            init_bootstrap_datepicker();

            $(document).on('change', '.select2_tab_filter, #tab_filter_start_date, #tab_filter_end_date', function () {
                $wire.set_tab_filter_field($(this).attr('id'), $(this).val());
            });

            $(document).on('click', '.to_page', function () {
                window.open($(this).attr('page'), '_blank');
            });

            window.Livewire.on('re_init_select2', () => {
                setTimeout(initSelect2, 0)
            })
        });
    </script>
@endscript
