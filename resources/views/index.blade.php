@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="container mb-5 mt-5">
    <h2 class="mb-4">Highest Reviews</h2>
    <div id="popularGamesContainer" class="position-relative"></div>
</section>

<section class="container mb-5">
    <h2 class="mb-4">Recent Reviews</h2>
    <div id="recentGamesContainer" class="position-relative"></div>
</section>
@endsection

@section('extra-js')
<script src="{{ asset('js/games.js') }}"></script>
<script>
    $(document).ready(function() {
        updateNavigation();
    });
</script>
@endsection
