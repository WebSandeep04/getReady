<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Get Ready')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <!-- Header and Footer CSS -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    @include('layouts.header')

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- jQuery (Full version for AJAX) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Header Filters JS -->
    <script src="{{ asset('js/header-filters.js') }}"></script>
    
    <!-- Cart JS -->
    <script src="{{ asset('js/cart.js') }}"></script>
    
    <!-- Notifications JS -->
    <script src="{{ asset('js/notifications.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/home.js') }}"></script>
    @yield('scripts')
</body>
</html> 