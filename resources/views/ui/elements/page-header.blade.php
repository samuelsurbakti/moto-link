@props([
    'info' => [],
])

<div class="d-grid d-sm-flex align-items-center justify-content-between">
    <x-ui::elements.page-title :pageTitle="$info['page_title']" :pageIcon="$info['page_icon']" />
    <x-ui::elements.breadcrumbs :items="$info['breadcrumbs']" />
</div>
