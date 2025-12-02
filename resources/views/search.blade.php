@extends('layouts.app')

@section('title', 'Search Games')

@section('content')
<main>
    <!-- Search Header -->
    <div class="search-header py-4 bg-dark">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="search-title mb-0">{{ __('messages.search_results') }}</h1>
                    <p class="search-subtitle mb-0 text-muted" id="searchQueryDisplay"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section py-3 border-bottom">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" id="ratingFilter">
                        <option value="">{{ __('messages.rating_all') }}</option>
                        <option value="high">{{ __('messages.high_rated') }}</option>
                        <option value="mid">{{ __('messages.mid_rated') }}</option>
                        <option value="low">{{ __('messages.low_rated') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortFilter">
                        <option value="relevance">{{ __('messages.sort_by') }}: {{ __('messages.relevance') }}</option>
                        <option value="rating">{{ __('messages.highest_rated') }}</option>
                        <option value="date">{{ __('messages.most_recent') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="results-section py-4">
        <div class="container">
            <div id="searchStatus" class="alert alert-info" style="display: none;">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    {{ __('messages.searching') }}
                </div>
            </div>

            <div id="noResults" class="alert alert-info" style="display: none;">
                {{ __('messages.no_results') }}
            </div>

            <div id="searchResultsContainer">
                <!-- Search results will be dynamically inserted here -->
            </div>
        </div>
    </div>
</main>
@endsection

@section('extra-js')
<script src="{{ asset('js/search.js') }}"></script>
<script>
    $(document).ready(function() {
        updateNavigation();
    });
</script>
@endsection
