@if(Auth::user()->role_id == 1)
    <div class="bg-navy text-white">
@else
    <div class="bg-blue text-dark">
@endif

@extends('layouts.app')

@section('title', 'Edit Profiles')

@section('content')
<link rel="stylesheet" href="{{asset('css/style.css')}}"  /> 
<div class="row justify-content-center pt-5">
    <div class="col-8">
        <div class="row">
            <div class="col">               
                <h3 class="color-navy poppins-semibold">Change Password</h3>
            </div>
        </div>
        <div class="row mt-3">
            {{-- UPDATE PASSWORD --}}
            <form action="{{route('profile.update-password')}}" method="post" class="shadow rounded-3 bg-white p-5">
                @csrf
                @method('PATCH')

                @if(session('success_password_change'))
                    <p class="mb-3 text-success fw-bold">{{session('success_password_change')}}</p>
                @endif

                <label for="old-password" class="form-label fw-bold">Old Password</label>
                <input type="password" name="old_password" id="old-password" class="form-control">
                @if(session('wrong_password_error'))
                    <p class="mb-0 text-danger small">{{session('wrong_password_error')}}</p>
                @endif

                <label for="new-password" class="form-label fw-bold mt-3">New Password</label>
                <input type="password" name="new_password" id="new-password" class="form-control">
                @if(session('same_password_error'))
                    <p class="mb-0 text-danger small">{{session('same_password_error')}}</p>
                @endif
                @error('new_password')
                    <p class="mb-0 text-danger small">{{$message}}</p>
                @enderror  

                <label for="confirm-password" class="form-label mt-3">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="confirm-password" class="form-control">
              
                {{-- <input type="submit" value="UPDATE PASSWORD" class="btn btn-warning px-5 mt-5"> --}}
                <button type="submit" class="btn btn-warning fw-bold px-5 mt-5">UPDATE PASSWORD</button>
            </form>
        </div>
    </div>
</div>

<form action="{{ route('profile.update', Auth::user()->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PATCH')


</form>
</div>
@endsection