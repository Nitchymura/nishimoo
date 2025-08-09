@extends('layouts.app')

@section('title', 'Edit_Spot')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/map.css')}}">
    <link rel="stylesheet" href="{{ asset('css/addspot.css')}}">
@endsection

@section('content')
<div class="bg-green pt-5 w-100 d-flex justify-content-center">
    @if(session('error'))
    <div class="container">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    </div>
    @endif
        <div class="col-9">
            <h3 class="color-navy poppins-semibold pb-5 pt-2">Edit Spot</h3>
                {{-- <div class="justify-content-center align-items-center text-center"> --}}
                    {{-- first row: form content --}}
                    <div class="row row-cols-1 row-cols-md-2">
                        {{-- left side --}}
                        <div class="col-12 col-md-6 add-spot-container">
                            <form action="{{ route('spots.update', $spot->id) }}" method="POST" class="add-spot-form px-0" id="spot-form" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                {{-- title --}}
                                <div class="form-group mb-2">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" id="title" name="title" placeholder="What unique spot did you find?" class="input-box form-input" value="{{ old('title', $spot->title) }}">
                                    @error('title')
                                        <p class="mb-0 text-danger small">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- main image --}}
                                <div class="form-group mb-2">
                                    <label for="main_image" class="form-label fw-bold">Cover Image</label>
                                    <input type="file" id="main_image" name="main_image"  class="form-control input-box" aria-describedby="main_image-info">
                                    @php
                                        $mainImageName = basename($spot->main_image); // ファイル名だけ取り出す
                                    @endphp

                                    <div class="xsmall" id="main_image-info">
                                        Current file: <strong>{{ $mainImageName }}</strong><br>
                                        <span class="text-secondary">The acceptable formats are jpeg, jpg, png and gif only.<br>
                                        Max file is 1048Kb.</span> 
                                    </div>
                                    @error('main_image')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                {{-- introduction --}}
                                <div class="form-group mb-2">
                                    <label for="introduction" class="form-label">Introduction</label>
                                    <textarea id="introduction" name="introduction" placeholder="This photo viewing introduction on spot page" class="text-area" rows="8"> {{ old('introduction', $spot->introduction) }} </textarea>
                                    @error('introduction')
                                        <p class="mb-0 text-danger small">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Images --}}
                                <div class="form-group mb-2">
                                    <label for="images" class="form-label">Images</label>
                                    <input type="file" id="images" name="images[]" class="form-control input-box" multiple>
                                
                                    {{-- プレビュー全体ラッパー --}}
                                    <div id="all-image-previews" class="d-flex flex-wrap gap-2 mt-3">
                                        {{-- 既存画像 --}}
                                        <div id="existing-images-wrapper" class="d-flex flex-wrap gap-2">
                                            @php $images = json_decode($spot->images, true) ?? []; @endphp
                                            @foreach ($images as $image)
                                                <div class="image-preview-box position-relative" data-image="{{ $image }}">
                                                    <img src="{{ $image }}" class="img-thumbnail" style="width: 150px;">
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute bottom-0 end-0 m-1 remove-existing-image">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                
                                        {{-- 新規追加画像 --}}
                                        <div id="image-preview" class="d-flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                                {{-- 削除していない既存画像だけ保持する hidden --}}
                                <div id="hidden-existing-images"></div>


                                
                                {{-- hidden input about location --}}
                                <input type="hidden" name="geo_lat" id="geo_lat" value="{{ old('geo_lat', $spot->geo_lat) }}">
                                <input type="hidden" name="geo_lng" id="geo_lng" value="{{ old('geo_lng', $spot->geo_lng) }}">
                                <input type="hidden" name="geo_location" id="geo_location" value="{{ old('geo_location', $spot->geo_location) }}">
                        </div>

                        {{-- right side --}}
                        <div class="col-12 col-md-6 map-container">    
                            <label for="address" class="form-label">Search Symbol</label>
                            <div class="search-container d-flex pb-3 align-items-center">
                                <input type="text" id="address" placeholder="Input address " class="input-box me-2">
                                <button type="button" onclick="geocodeAddress()" class="btn btn-green px-1">Search</button>
                            </div>
                            <div id="map" 
                                class="spot-map-container" 
                                data-lat="{{ old('geo_lat', $spot->geo_lat) }}" 
                                data-lng="{{ old('geo_lng', $spot->geo_lng) }}">
                            </div>


                            <div id="place-photo" class="place-photo mb-5 w-100 text-center"></div>
                        </div>

                    </div>

                    {{-- submit button --}}
                        <div class="row my-4">
                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-navy w-50" form="spot-form">Update</button>
                            </div>
                        </div>
                    </div>

                    {{-- Add Google Maps info --}}
                    <script src="{{ asset('js/spot/edit/edit-map.js') }}"></script>
                    <script defer
                        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap">
                    </script>

                    {{-- Add images --}}
                    <script src="{{ asset('js/spot/edit/edit-images.js') }}"></script>
                {{-- </div> --}}
        </div>
    
</div>

@endsection