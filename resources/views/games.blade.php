@extends('layouts.app')

@section('title', 'Game Details')

@section('content')
<div class="game-detail-container">
</div>
@endsection

@section('extra-js')
<script src="{{ asset('js/gameDetails.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateNavigation();
    });
</script>
@endsection
