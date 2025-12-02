<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PlayScore') - PlayScore</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    @yield('extra-css')
</head>
<body>
    <!-- Navigation -->
    @include('layouts.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
    <script>
        // Pass translations to JavaScript
        window.translations = {
            login: "{{ __('messages.login') }}",
            logout: "{{ __('messages.logout') }}",
            admin_dashboard: "{{ __('messages.admin_dashboard') }}",
            release_date: "{{ __('messages.release_date') }}",
            genres: "{{ __('messages.genres') }}",
            platforms: "{{ __('messages.platforms') }}",
            developers: "{{ __('messages.developers') }}",
            review: "{{ __('messages.review') }}",
            reviewed_at: "{{ __('messages.reviewed_at') }}",
            reviewed_by: "{{ __('messages.reviewed_by') }}",
            no_review_available: "{{ __('messages.no_review_available') }}",
            error_loading_game: "{{ __('messages.error_loading_game') }}",
            tba: "{{ __('messages.tba') }}",
            na: "{{ __('messages.na') }}",
            read_review: "{{ __('messages.read_review') }}",
            no_popular_games: "{{ __('messages.no_popular_games') }}",
            no_recent_games: "{{ __('messages.no_recent_games') }}"
        };
        
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('extra-js')
</body>
</html>
