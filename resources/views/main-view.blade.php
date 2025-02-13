@php
    $config = [
        'appName' => config('app.name'),
        'locale' => $locale = app()->getLocale(),
        'locales' => config('app.locales'),
    ];
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Soomfy</title>
    {{-- Font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- Icon --}}
    <link rel="icon" href="/images/logo-whiteground.svg">
    <!-- Fonts MAIN-->
    <link rel="alternate" href="atom.xml" type="application/atom+xml" title="Atom">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <!--<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">-->
    <script>
        window.config = @json($config);
    </script>
    <!-- Scripts -->
{{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased" id="app">
    <router-view></router-view>
</body>
</html>
