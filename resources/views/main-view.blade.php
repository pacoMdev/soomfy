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
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
   
    {{-- Icon --}}
    <link rel="icon" href="/images/logo-whiteground.svg">
    <!-- Fonts MAIN-->
    <link rel="alternate" href="atom.xml" type="application/atom+xml" title="Atom">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!--<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">-->
    <script>
        window.config = @json($config);
    </script>

    <!-- Google Maps API -->
    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdFFlc6UvNFI251Z9BeD_4rxyUBHberx0&libraries=places"
            async
            defer
    ></script>

</head>
<body>
    <div id="app">
        <router-view></router-view>
    </div>
</body>
</html>
