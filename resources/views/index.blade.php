@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="container mb-5 mt-5">
    <h2 class="mb-4">{{ __('messages.highest_reviews') }}</h2>
    <div id="popularGamesContainer" class="position-relative"></div>
</section>

<section class="container mb-5">
    <h2 class="mb-4">{{ __('messages.recent_reviews') }}</h2>
    <div id="recentGamesContainer" class="position-relative"></div>
</section>
@endsection

@section('extra-js')
<script src="{{ asset('js/games.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', updateNavigation);
</script>
@endsection

