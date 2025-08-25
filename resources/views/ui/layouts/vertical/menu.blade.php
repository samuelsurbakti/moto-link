<aside id="layout-menu" class="layout-menu menu-vertical menu" >
    <div class="app-brand app-brand-vertical demo ">
        <a href="https://{{ $app->subdomain }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="/src/assets/logo/logo.svg" alt="Logo" class="h-px-34">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2 fs-4">{{ $app->name }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base bx bx-chevron-left"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @php
            $part = '';
        @endphp
        @foreach($menus as $menu)
            @if($part != $menu->member_of)
                <li class="menu-header small">
                    <span class="menu-header-text" data-i18n="{{ $menu->member_of }}">{{ $menu->member_of }}</span>
                </li>
                @php
                    $part = $menu->member_of;
                @endphp
            @endif
            <x-ui::layouts.vertical.menu-items :menu="$menu" />
        @endforeach
    </ul>
</aside>
<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="bx bx-menu icon-base"></i>
        <i class="bx bx-chevron-right icon-base"></i>
    </a>
</div>
