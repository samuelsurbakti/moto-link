@props([
    'pageTitle' => $page_title ?? 'Tidak Diketahui',
    'pageIcon' => $page_icon ?? 'bx bx-question-mark',
])

<h5 class="fw-bold d-flex align-items-center gap-2">
    <i class="{{ $pageIcon }} fs-4"></i> {{ $pageTitle }}
</h5>
