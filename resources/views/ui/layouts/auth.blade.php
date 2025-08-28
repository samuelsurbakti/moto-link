<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-assets-path="/themes/" data-template="vertical-menu-template" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }} - Auth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('ui.partials.favicon')
    @include('ui.partials.default-font')
    @include('ui.partials.default-css')
    <link rel="stylesheet" href="/themes/vendor/css/pages/page-auth.css" />
    @stack('page_styles')
    @include('ui.partials.themescripts')
</head>
<body>
    <div class="authentication-wrapper authentication-cover">

        {{-- Brand --}}
        <a href="#" disabled class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
                <img src="/src/assets/logo/logo.svg" alt="Logo" class="h-px-34">
            </span>
            <span class="app-brand-text demo text-heading fw-bold text-primary">Access</span>
        </a>

        {{-- Main auth layout --}}
        <div class="authentication-inner row m-0">
            {{-- Left illustration --}}
            @if (!empty($image))
                <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                    <div class="w-100 d-flex justify-content-center">
                        <img src="{{ asset($image) }}" class="img-fluid" style="height: 80vh">
                    </div>
                </div>
            @endif

            {{-- Auth form --}}
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @include('ui.layouts.parts.scripts')
</body>
</html>
