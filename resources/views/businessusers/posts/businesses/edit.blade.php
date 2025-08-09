@extends('layouts.app')

@section('title', 'Edit A Business - Location or Event')

@section('content')
    <link rel="stylesheet" href="{{asset('css/style.css')}}"  /> 
    <div class="bg-blue">
        <main>
            <div class="row justify-content-center pb-5">
                <div class="col-md-10 col-lg-8 box-border mx-auto" style="background-color: #B4D5F4; border-radius: 0px;">
                    <div class="d-flex mb-3">
                        <div class="col">
                            <h3 class="color-navy poppins-semibold">Edit Favorite</h3>
                            <p class="form-label d-inline ">(<span class="color-red fw-bold">*</span> Required items to register)<p>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-sm btn-red ms-auto w-100" data-bs-toggle="modal" data-bs-target="#deleteBusinessModal">
                                DELETE
                            </button>
                        </div>
                    </div>
                    {{-- @include('businessusers.posts.businesses.modals.delete') --}}

                    <form action="{{ route('businesses.update', $business, Auth::user()->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Location or Event Details -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>

                            <select class="form-control w-25" id="category_id" name="category_id">
                                <option value="1" {{ (old('category_id', $business->category_id) == 1) ? 'selected' : '' }}>Figure Skate</option>
                                <option value="2" {{ (old('category_id', $business->category_id) == 2) ? 'selected' : '' }}>Beer</option>
                                <option value="6" {{ (old('category_id', $business->category_id) == 6) ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>

                        <!-- Location or Event Details -->
                        <div class="mb-3">
                            <!-- 現在の選択に基づいて初期表示 -->
                            <label for="name" class="form-label d-inline" id="name-label">
                                {{ (old('category_id', $business->category_id) == 2) ? 'Event' : 'Location' }} Name<span style="color: #D24848;">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $business->name ?? '') }}" class="form-control">
                        </div>

                        <script src="{{ asset('js/business.js') }}"></script>

                        <!-- Contact Information Form -->
                        {{-- @include('businessusers.posts.businesses.partials.contact-information') --}}

                        {{-- main_image --}}
                        <div class="row mb-3">
                            <div class="col-md-4 business-main-image">
                                <label for="business-main-image" class="form-label">Upload Main Photo<span style="color: #D24848;">**</span></label>
                           
                                    @if ($business->main_image)
                                        <img id="main-image-preview"
                                            src="{{ $business->main_image }}"
                                            class="img-lg img-thumbnail mb-2 d-block"
                                           >
                                    @else
                                        <div id="business-main-image-icon" class="">
                                            <i class="fa-solid fa-image text-secondary icon-xxl"></i>
                                        </div>
                                    @endif
                                
                                    {{-- <div id="delete-business-main-image-wrapper" style="display: {{ $business->main_image ? 'block' : 'none' }};">
                                        <button type="button" class="btn btn-red delete-business-main-image" id="delete-business-main-image">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div> --}}
                            
                                <input type="file" name="main_image" id="business-main-image" class="form-control form-control-sm w-100 mb-auto p-2">
                            
                                @error('main_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                                            
                        </div>

                        <!-- social-media -->
                        {{-- @include('businessusers.posts.businesses.partials.social-media') --}}

                        <!-- Introduction -->
                        <div class="mb-5">
                            <label for="introduction" class="form-label d-inline">
                                Introduction<span style="color: #D24848;">**</span>
                            </label>
                            <textarea name="introduction" class="form-control">{{ old('introduction', $business->introduction ?? '') }}</textarea>
                        </div>

                        {{-- <h4 class="text-xl font-bold mb-3">Business Hours & Event Time Periods</h4> --}}

                        <!-- Business Location or Event Term Info and S.P.Notes -->
                        {{-- @include('businessusers.posts.businesses.partials.business-status') --}}

                        <!-- Weekly Business Schedule' -->
                        {{-- @include('businessusers.posts.businesses.partials.weekly-schedule') --}}

                        <!-- Facility information -->
                        {{-- @include('businessusers.posts.businesses.partials.business-details', ['businessDetail' => $businessDetail ?? null, 'oldValues' => old('details')]) --}}

                        <!-- Identification Information -->
                        {{-- @include('businessusers.posts.businesses.partials.identification-information', ['business' => $business ?? null]) --}}

                        <!-- business-photos -->
                        @include('businessusers.posts.businesses.partials.business-photos')


                        <!-- Term for display to public this location/event -->
                        {{-- @include('businessusers.posts.businesses.partials.display-period', ['business' => $business ?? null]) --}}

                        <!-- Submission Buttons -->
                        @include('businessusers.posts.businesses.partials.submission-buttons', [
                            'business' => $business ?? null,
                            'submitButtonText' => isset($business) ? 'UPDATE' : 'SAVE'
                        ])
                    </form>   
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/business_image_edit.js') }}"></script>
@endsection
