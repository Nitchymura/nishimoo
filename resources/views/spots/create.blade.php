@extends('layouts.app')

@section('title', 'Add_Spot')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/map.css')}}">
    <link rel="stylesheet" href="{{ asset('css/addspot.css')}}">
@endsection

@section('content')
@php
    $spot = $spot ?? new \App\Models\Spot();
@endphp

<div class="bg-green pt-4 w-100 d-flex justify-content-center">
    @if(session('error'))
    <div class="container">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    </div>
    @endif
    <div class="col-9 pt-4">
        <h3 class="color-navy poppins-semibold pb-5 pt-2">Add Spot</h3>

        <div class="row row-cols-1 row-cols-md-2">
            {{-- left side --}}
            <div class="col-12 col-md-6 add-spot-container">
                <form action="{{ route('spots.store') }}" method="POST" class="add-spot-form" id="spot-form" enctype="multipart/form-data">
                    @csrf

                    {{-- title --}}
                    <div class="form-group mb-2">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="input-box form-input" value="{{ old('title', $spot->title) }}" required>
                    </div>

                    {{-- main image --}}
                    <div class="form-group mb-2">
                        <label for="main_image" class="form-label fw-bold">Cover Image</label>
                        <input type="file" id="main_image" name="main_image" class="form-control input-box" aria-describedby="main_image-info">
                        
                        @php
                            $mainImagePath = session('spot_edit_data.main_image_path') ?? $spot->main_image;
                        @endphp
                        
                        @if (!empty($mainImagePath))
                            <div class="mb-2">
                                <p class="text-secondary xsmall py-0">Current image:
                                    <span class="text-dark xsmall raleway-semibold">
                                        {{ basename($mainImagePath) }}
                                    </span>
                                </p>
                            </div>
                        @endif
                        
                        {{-- プレビュー表示 --}}
                        <div class="mt-2">
                            <img id="main-image-preview" src="{{ $mainImagePath ?? '' }}" alt="Preview" class="img-fluid rounded"
                                 style="{{ empty($mainImagePath) ? 'display:none;' : 'max-width: 300px; height: auto;' }}">
                        </div>

                        <div class="xsmall text-danger" id="main_image-info">
                            The acceptable formats are jpeg, jpg, png and gif only.<br>
                            Max file is 1048Kb.
                        </div>
                        @error('main_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                
                    {{-- introduction --}}
                    <div class="form-group mb-2">
                        <label for="introduction" class="form-label">Introduction</label>
                        <textarea id="introduction" name="introduction" class="text-area" rows="8" required>{{ old('introduction', $spot->introduction) }}</textarea>
                    </div>

                    {{-- Images --}}
                    <div class="form-group mb-2">
                        <label for="images" class="form-label">Images</label>
                        <input type="file" id="images" name="images[]" class="form-control input-box" multiple>

                        <div id="all-image-previews" class="d-flex flex-wrap gap-2 mt-3">
                            {{-- 既存画像 --}}
                            <div id="existing-images-wrapper" class="d-flex flex-wrap gap-2">
                                @php $images = json_decode($spot->images, true) ?? []; @endphp
                                @foreach ($images as $image)
                                    <div class="image-preview-box position-relative" data-image="{{ $image }}">
                                        <img src="{{ asset($image) }}" class="img-thumbnail" style="width: 150px;">
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
                    <div id="hidden-existing-images"></div>

                    {{-- hidden input about location --}}
                    <input type="hidden" name="geo_lat" id="geo_lat" value="{{ old('geo_lat', $spot->geo_lat) }}">
                    <input type="hidden" name="geo_lng" id="geo_lng" value="{{ old('geo_lng', $spot->geo_lng) }}">
                    <input type="hidden" name="geo_location" id="geo_location" value="{{ old('geo_location', $spot->geo_location) }}">
                </form>
            </div>

            {{-- right side --}}
            <div class="col-12 col-md-6 map-container">
                <label for="address" class="form-label">Search Symbol</label>
                <div class="search-container d-flex pb-3 align-items-center">
                    <input type="text" id="address" placeholder="Input address " class="input-box me-2">
                    <button type="button" onclick="geocodeAddress()" class="btn btn-green px-1">Search</button>
                </div>
                @php
                    $geoLat = old('geo_lat', $spot->geo_lat ?? '');
                    $geoLng = old('geo_lng', $spot->geo_lng ?? '');
                @endphp

                <div id="map" 
                    class="spot-map-container" 
                    data-lat="{{ $geoLat }}" 
                    data-lng="{{ $geoLng }}">
                </div>

                <div id="place-photo" class="place-photo mb-5 w-100 text-center"></div>
            </div>
        </div>

        {{-- submit button --}}
        <div class="row mt-2">
            <div class="col-12 d-flex justify-content-center">
                <button type="submit" class="btn btn-navy w-50" form="spot-form">Confirm</button>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="{{ asset('js/spot/create/add-map.js') }}"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap"></script>
<script src="{{ asset('js/spot/create/add-images.js') }}"></script>
@endsection
