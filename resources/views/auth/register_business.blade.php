@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

@section('content')
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <!-- Register form -->
        <div class="card-register shadow-lg p-3 w-50 d-flex justify-content-center align-items-center">
            <div class="container-register w-100 p-5">
                <div class="text-center mx-2 my-4">
                    <h3 class="my-4 poppins-bold">
                        Registration for Business User
                        {{-- <img src="{{ asset('images/logo/HopQuest_logo.png') }}" alt="HopQuest Logo" class="logo-small"> --}}
                    </h3>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.business.submit') }}">
                    @csrf

                    <div class="row justify-content-center">
                        <!-- Company or Username -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label text-start w-100">Company or Username</label>
                            <div class="input-group">
                                <input id="name" type="text" name="name" value="{{ old('company') }}"
                                    placeholder="Enter company name or username" required class="form-control">
                                <span class="input-group-text">
                                    <i class="fas fa-user text-primary"></i>
                                </span>
                            </div>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email for Business -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label text-start w-100">Email for Business</label>
                            <div class="input-group">
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="business@email.com" required class="form-control">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                            </div>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-start w-100">Password</label>
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

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label text-start w-100">Confirm Password</label>
                            <div class="input-group">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    class="form-control">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock text-primary"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6 mt-auto">
                            <label for="phonenumber" class="form-label text-start w-100">Phone Number</label>
                            <div class="input-group">
                                <input id="phonenumber" type="text" name="phonenumber" value="{{ old('phone') }}"
                                    placeholder="Enter phone number" required class="form-control">
                                <span class="input-group-text">
                                    <i class="fas fa-phone text-primary"></i>
                                </span>
                            </div>
                            @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- REGISTER Button -->
                        <div class="col-md-6 mt-auto">
                            <button type="submit" class="btn btn-danger w-100">REGISTER</button>
                        </div>
                    </div>
                    <!-- Extra Links -->
                    <div class="text-end mt-5">
                        <a href="{{ route('register.tourist') }}"
                            class="text-decoration-none text-dark text-end poppins-bold">Stop to create account for
                            Business</a>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none text-dark text-end poppins-bold">Already
                            have an account?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
