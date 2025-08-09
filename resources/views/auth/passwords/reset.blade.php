@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@section('content')
    <div class="container-fluid reset-container vh-100 d-flex align-items-center">

        {{-- Form --}}
        <div class="col-lg-6 col-md col-sm d-flex align-items-center justify-content-center">
            <div class="card-reset reset-card shadow-lg p-4 m-3 w-100 mx-auto">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Reset Password
                        {{-- <img src="{{ asset('images/logo/HopQuest_logo.png') }}" alt="HopQuest Logo" class="w-25"> --}}
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="m-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>



                        <!-- Reset Button -->
                        <div class="d-grid mb-5 mt-5 pt-5 pb-5">
                            <button type="submit" class="btn btn-danger fw-bold w-75 mx-auto">RESET</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Background -->
        <div class="col-md-7 d-none d-md-block reset-bg"></div>

    </div>
@endsection
