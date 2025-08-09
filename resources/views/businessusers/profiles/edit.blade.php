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
                @if(Auth::user()->role_id == 1)
                    <h3 class="color-white poppins-semibold">Edit Your Profile</h3>
                    <p class="">(<span class="color-red fw-bold">*</span> Required items)<p>
                @elseif(Auth::user()->role_id == 2)
                    <h3 class="color-navy poppins-semibold">Edit Your Profile</h3>
                    <p class="">(<span class="color-red fw-bold">*</span> Required items for official certification badge application)<p>
                @endif
            </div>
            <div class="col-2">
                <a href="{{ route('profile.change-password', Auth::user()->id ) }}" class="btn btn-outline-navy mt-3 w-100" >CHANGE PASSWORD</a>
            </div>
            <div class="col-2">
                <button class="btn btn-red mt-3 w-100" data-bs-toggle="modal" data-bs-target="#delete-profile{{ Auth::user()->id }}">DELETE</button>
                @include('businessusers.profiles.modals.delete')
            </div>
        </div>
    </div>
</div>

<form action="{{ route('profile.update', Auth::user()->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PATCH')


        <!-- Header image -->
<div class="row">
    <div class="pt-2 px-0">
        @if(Auth::user()->header)
            <img id="header-preview"
                src="{{ Auth::user()->header }}"
                alt="Header Image"
                class="header-image img-fluid mx-auto d-block">
        @else
            <img id="header-preview"
                src="{{ asset('images/logo/header_logo.jpg') }}"
                alt="No Header"
                class="header-image img-fluid mx-auto d-block"
                style="display:none;">
            {{-- <i id="header-icon" class="fa-solid fa-image text-secondary icon-xxl d-block text-center"></i> --}}
        @endif
    </div>
</div>


<div class="pb-5 row justify-content-center pt-2">
    <div class="col-8"> 
          {{-- Header image upload --}}
        <div class="row mb-3">
            <div class="col">
                <label for="header" class="form-label mb-2">Header photo </label>
                <button type="button" class="btn btn-red delete-header" id="delete-header" >
                    <i class=" fa-solid fa-trash-can" ></i>
                </button>
                
                <input type="file" name="header" id="header" class="form-control form-control-sm w-100 p-2">
                <p class="mb-0 form-text text-danger">
                Acceptable formats: jpeg, jpg, png, gif only <br>
                Max file size is 1048 KB
                </p>
            </div>

                
            

            @error('header')
            <p class="mb-0 text-danger small">{{ $message }}</p>
            @enderror
        </div>     
        <div class="row mb-3">
            <!-- Avatar image -->

            <div class="col-auto profile-image">
       
       
                @if(Auth::user()->role_id == 1)
                    <img id="avatar-preview"
                    src="{{ Auth::user()->avatar ?? asset('images/profiles/profile-circle-tourist.jpg') }}"
                    alt=""
                    class="rounded-circle avatar-xl d-block mx-auto">
                @elseif(Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                    <img id="avatar-preview"
                    src="{{ Auth::user()->avatar ?? asset('images/profiles/profile-circle-user.jpg') }}"
                    alt=""
                    class="rounded-circle avatar-xl d-block mx-auto">
                @endif
{{--             
                <i id="default-icon"
                    class="fa-solid fa-circle-user text-secondary profile-xl rounded-circle d-block d-none">
                    </i> --}}
                <div>
                <button type="button" class="btn btn-red delete-avatar-profile" id="delete-avatar" >
                    <i class=" fa-solid fa-trash-can" ></i>
                </button>
                </div>
            </div>
            

            
            <div class="col">
                <label for="avatar" class="form-label mb-2">Avatar photo</label>
                <input type="file" name="avatar" id="avatar" class="form-control form-control-sm w-100 mb-auto p-2">
                <p class="mb-0 form-text text-danger">
                    Acceptable formats: jpeg, jpg, png, gif only <br>
                    Max file size is 1048 KB
                </p>
            </div>
                                   
            
        
            <div class="col">
                @error('avatar')
                <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>
        {{-- User information --}}
        <div class="row mb-3">
            <div class="col">
                <label for="name" class="form-label">User name<span class="color-red">*</span></label>
                <input type="text" name="name" id="name" value="{{old('name', Auth::user()->name)}}" class="form-control">
                @error('name')
                <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            @if(Auth::user()->role_id == 1)
            <div class="col">
                <label for="email" class="form-label">E-mail address<span class="color-red">*</span></label>
                <input type="email" name="email" id="email" value="{{old('email', Auth::user()->email)}}" class="form-control">
            </div>
            @else
            <div class="col-6">
                <label for="email" class="form-label">E-mail address<span class="color-red">*</span></label>
                <input type="email" name="email" id="email" value="{{old('email', Auth::user()->email)}}" class="form-control">
            </div>
            <div class="col-6">
                <label for="website_url" class="form-label">Website URL</label>
                <input type="text" name="website_url" id="website_url" value="{{old('email', Auth::user()->website_url)}}" class="form-control">
            </div>
            @endif
        </div>
        {{-- <div class="row mb-3">
            <div class="col-6">
                @if(Auth::user()->role_id == 2)
                    <label for="zip" class="form-label">ZIP code<span class="color-red">*</span></label>
                    <input type="text" name="zip" id="zip" value="{{old('zip', Auth::user()->zip)}}" class="form-control">
                @endif
                @error('zip')
                <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-6">
                @if(Auth::user()->role_id == 2)
                    <label for="phonenumber" class="form-label">Phone number<span class="color-red">*</span></label>
                    <input type="text" name="phonenumber" id="phonenumber" value="{{old('phonenumber', Auth::user()->phonenumber)}}" class="form-control">
                @endif
                @error('phonenumber')
                <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div> --}}
        {{-- <div class="row mb-3">
            <div class="col">
                @if(Auth::user()->role_id == 2)
                    <label for="address" class="form-label">Address<span class="color-red">*</span></label>
                    <input type="text" name="address" id="address" value="{{old('address', Auth::user()->address)}}" class="form-control">
                @endif
                @error('address')
                <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div> --}}
        <div class="row mb-3">
            <div class="col">
                {{-- @if(Auth::user()->role_id == 1) --}}
                    <label for="introduction" class="form-label">Introduction</label>
                    <textarea name="introduction" id="introduction" rows="5" class="form-control">{{old('introduction', Auth::user()->introduction)}}
                    </textarea>
                {{-- @elseif(Auth::user()->role_id == 2)
                    <label for="introduction" class="form-label">Introduction<span class="color-red">*</span></label>
                    <textarea name="introduction" id="introduction" rows="5" class="form-control">{{old('introduction', Auth::user()->introduction)}}
                    </textarea>
                @endif --}}
                @error('introduction')
                <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>    
        </div>

        <div class="row mb-5">
            <div class="col">
                <div class="row mb-2">
                    <div class="col">
                        <label for="sns" class="form-label">Social media</label>
                    </div>
                </div>
            </div> 
            <div class="row mb-2">
                <div class="col-6">   
                    <div class="row">
                        <div class="col-1">
                            <label for="instagram" class="form-label"><i class="fa-brands fa-instagram icon-md pe-2"></i></label>
                        </div>
                        <div class="col">
                            <input type="text" name="instagram" id="instagram" value="{{old('instagram', Auth::user()->instagram)}}" class="form-control" placeholder="Instagram account name">
                        </div>
                    </div>
                </div>  
                <div class="col-6">
                    <div class="row">
                        <div class="col-1">
                            <label for="facebook" class="form-label"><i class="fa-brands fa-facebook icon-md pe-2"></i></label>
                        </div>
                        <div class="col">
                            <input type="text" name="facebook" id="facebook" value="{{old('instagram', Auth::user()->facebook)}}" class="form-control" placeholder="facebook account name">
                        </div>
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-6">   
                    <div class="row">
                        <div class="col-1">
                            <label for="x" class="form-label"><i class="fa-brands fa-x-twitter icon-md pe-2"></i></label>
                        </div>
                        <div class="col">
                            <input type="text" name="x" id="x" value="{{old('x', Auth::user()->x)}}" class="form-control" placeholder="X account name">
                        </div>
                    </div>
                </div>  
                <div class="col-6">
                    <div class="row">
                        <div class="col-1">
                            <label for="tiktok" class="form-label"><i class="fa-brands fa-tiktok icon-md pe-2"></i></label>
                        </div>
                        <div class="col">
                            <input type="text" name="tiktok" id="tiktok" value="{{old('tiktok', Auth::user()->tiktok)}}" class="form-control" placeholder="tiktok account name">
                        </div>
                    </div>    
                </div>
            </div>
        </div>

        <div class="row mt-3 justify-content-center">
            <div class="col-8 ">                        
                <button type="submit" class="btn btn-green w-100 mb-2">SAVE</button>
                @if(Auth::user()->role_id == 2)
                <input type="checkbox" class="form-check-input mb-2" name="official_certification" id="official_certification" value="2" 
                {{ old('official_badge', Auth::user()->official_certification == 2) ? 'unchecked' : '' }}
                > Apply for Official certification badge
                @endif
            </div>
            {{-- <div class="col-2"></div>
            <div class="col-4 ">
                <a href="{{route('profile.header', Auth::user()->id)}}">
                    <button class="btn btn-red w-100 ">CANCEL</button>
                </a>
            </div> --}}
        </div>              
        </form>
    </div>
</div>
</div>
<script src="{{ asset('js/profile_image_edit.js') }}"></script>
@endsection