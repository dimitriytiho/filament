<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ empty($title) ? config('app.name') : $title . ' | ' . config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? ' ' }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    {{--<link rel="apple-touch-icon" href="{{ asset('touch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('touch-icon-ipad-retina.png') }}">--}}
    @if(app()->environment('production'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('app.recaptcha_public_key') }}"></script>
        <script>
            const recaptchaPublicKey = '{{ config('app.recaptcha_public_key') }}';
        </script>
    @endif
    {{-- Google Material Symbols Outlined --}}
    {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">--}}
    {{-- Fontawesome icons --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.6.0/css/all.css">
    @vite(['resources/sass/us/index.sass', 'resources/js/us/index.js'])
    {{-- Здесь можно добавить файлы css --}}
    @yield('css')
</head>
<body>
<div id="backdrop"></div>
{{-- Панель администратора --}}
@include('us.inc.panel_dashboard')
{{-- Меню --}}
<div id="app">

    @yield('header')
    @include('us.inc.message')

    <div class="content-block">
        <div class="content" id="content">
            @yield('content')
        </div>
        <div id="bottom-block"></div>
    </div>

    <div class="footer-block">
        @yield('footer')
    </div>
</div>
{{-- Стрелка вверх --}}
<div class="scale-out" id="btn-up" title="@lang('move_to_top')">
    <i class="fa-solid fa-arrow-up"></i>
</div>
<script>
    const _token = '{{ session()->token() }}',
        spinner = '<i class="fa-solid fa-spinner fa-spin-pulse mt-1 ms-2"></i>'
</script>
{{-- Здесь можно добавить файлы js --}}
@yield('js')
@if(config('app.env') === 'production' && !auth()?->user()?->isAdmin())
    @include('us.inc.analytics')
@endif
</body>
</html>
