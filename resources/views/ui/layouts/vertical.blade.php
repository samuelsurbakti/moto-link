<!DOCTYPE html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact customizer-hide" dir="ltr" data-skin="default" data-assets-path="/themes/" data-template="vertical-menu-template" data-bs-theme="light">
	<head>
		@include('ui.layouts.parts.head')
	</head>
	<body>
		<!-- Layout wrapper -->
		<div class="layout-wrapper layout-content-navbar  ">
			<div class="layout-container">
				@include('ui.layouts.vertical.menu')
				<!-- Layout container -->
				<div class="layout-page">
					@include('ui.layouts.vertical.navbar')
					<!-- Content wrapper -->
					<div class="content-wrapper">
						<!-- Content -->
						{{ $slot }}
						<!-- / Content -->
						@include('ui.layouts.parts.footer')
					</div>
				</div>
			</div>
		</div>

        <div class="layout-overlay layout-menu-toggle"></div>

        @include('ui.layouts.parts.scripts')
	</body>
</html>
