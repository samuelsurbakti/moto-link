<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>{{ $app->name }} | {{ $page_title }}</title>
@include('ui.partials.favicon')
@include('ui.partials.default-font')
@include('ui.partials.default-css')
@stack('page_styles')
@include('ui.partials.themescripts')
