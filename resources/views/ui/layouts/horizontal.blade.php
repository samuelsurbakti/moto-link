<!DOCTYPE html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact customizer-hide" dir="ltr" data-skin="default" data-assets-path="/themes/" data-template="horizontal-menu-template" data-bs-theme="light">
	<head>
		@include('ui.layouts.parts.head')
	</head>
	<body>
		<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
			<div class="layout-container">
				@include('ui.layouts.horizontal.navbar')

                <div class="layout-page">
					<div class="content-wrapper">
						@include('ui.layouts.horizontal.menu')

                        {{ $slot }}

                        @include('ui.layouts.parts.footer')
					</div>
				</div>
			</div>
		</div>

        <div class="layout-overlay layout-menu-toggle"></div>

        @include('ui.layouts.parts.scripts')
	</body>
</html>
