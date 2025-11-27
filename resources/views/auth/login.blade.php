@extends('layouts.app')

@section('title', 'Login')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/LoginStyle.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card login-card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="navbar-brand">
                            <a href="{{ route('home') }}"><div class="logo">PlayScore</div></a>
                        </span>
                        <p class="text-muted">Welcome back!</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="loginForm" onsubmit="return login(event)">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Log In
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-2">Don't have an account?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-light">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
