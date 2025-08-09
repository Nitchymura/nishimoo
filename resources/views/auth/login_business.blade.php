@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@section('content')
    <div class="container-fluid vh-100 d-flex">
        <!-- Login form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="card-login shadow-lg login-card p-5 w-75">
                <div class="container-login w-100 mx-auto">
                    <div class="text-center mb-4">
                        <h2 class="mt-2 poppins-bold">Login<span class="h4"> for Business Users
                                <img src="{{ asset('images/logo/HopQuest_logo.png') }}" alt="HopQuest Logo"
                                    class="logo-small"></span>
                        </h2>

                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="xxxxxx@email.com" required class="form-control">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                            </div>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-5">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input id="password" type="password" name="password" required class="form-control">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock text-primary"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-5">
                            <button type="submit" class="btn btn-danger">LOGIN</button>
                        </div>

                        <!-- Extra Links -->
                        <div class="m-4 text-end">
                            <a href="{{ route('register.business') }}"
                                class="text-decoration-none text-dark poppins-bold">Create Account
                                for
                                Business</a>
                        </div>
                        <div class="m-4 text-end">
                            <a href="{{ route('password.request') }}"
                                class="text-decoration-none text-dark text-end poppins-bold">Forgot
                                Password?</a>
                        </div>
                        <div class="m-4 text-end">
                            <a href="{{ route('register') }}" class="text-decoration-none text-dark poppins-bold">Create
                                Account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection
