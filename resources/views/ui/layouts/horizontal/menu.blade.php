<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0"  >
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            @foreach($menus as $menu)
                <x-ui::layouts.horizontal.menu-items :menu="$menu" />
            @endforeach
        </ul>
    </div>
</aside>
