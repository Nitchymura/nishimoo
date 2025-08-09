@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/viewspot.css') }}">

@section('title', 'Confirm_Spot')

@section('content')
<div class="bg-green pb-5">
    <p class="attention color-navy poppins-semibold text-center mt-4 py-3 fs-3">
        Not yet published. This page is for confirmation only.
    </p>

    @php
        $data = session('spot_edit_data', []);
        $title = $data['title'] ?? $spot->title;
        $introduction = $data['introduction'] ?? $spot->introduction;
        $geo_lat = $data['geo_lat'] ?? $spot->geo_lat;
        $geo_lng = $data['geo_lng'] ?? $spot->geo_lng;
        $geo_location = $data['geo_location'] ?? $spot->geo_location;
        $cover_image = $data['main_image_path'] ?? $spot->main_image;
        $existing_images = $data['existing_images'] ?? json_decode($spot->images, true) ?? [];
        $new_images = $data['image_paths'] ?? [];
        $all_images = array_merge($existing_images, $new_images);
    @endphp

    <div class="container-fluid pt-5 d-flex justify-content-center">
        <div class="col-11 col-md-9">
            <div class="py-4 position-relative w-100">
                <div class="spot-main-image text-center px-0 rounded-3">
                    <img src="{{ Str::startsWith($cover_image, '/storage') ? asset($cover_image) : asset('storage/' . ltrim($cover_image, '/')) }}" alt="{{ $title }}" class="h-auto-md-down rounded-3">
                    <h5 class="spot-image-caption w-100 px-3">{{ $title }}</h5>
                </div>
            </div>

            {{-- spot introduction + map --}}
            <div class="row text-center w-100 px-0 mx-0 pt-5">
                <div class="col-md-4 spot-detail text-center d-flex flex-column ps-md-0 px-sm-0">
                    <h5 class="poppins-semibold fs-4">Detail</h5>
                    <hr class="dashed-hr">
                    <p class="text-start rounded-3 p-3 flex-grow-1 mb-2">{{ $introduction }}</p>
                    <hr class="dashed-hr">
                </div>
                <div class="col-md-8 spot-map pe-md-0 ps-md-3 px-sm-0 py-0">
                    <h5 class="poppins-semibold fs-4 pb-3">Map</h5>
                    <div id="map" class="spot-map-container w-100 rounded-3" 
                        data-lat="{{ $geo_lat }}" 
                        data-lng="{{ $geo_lng }}">
                    </div>
                </div>
            </div>

            <h5 class="poppins-semibold fs-4 text-center pt-5 mt-3">Photos</h5>
            <div class="row spot-photos-grid px-0 justify-content-center">
                @if (count($all_images))
                    @foreach ($all_images as $image)
                        <div class="col-6 col-sm-4 col-md-3 mb-4">
                            <div class="w-100">
                                <img src="{{ $image }}" alt="{{ $title }}" class="w-100 rounded-3">
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center">No images uploaded.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="row text-center py-3 d-flex justify-content-center">
       <div class="col-5 text-end">
            @if (session('spot_mode') === 'update' && session('spot_id'))
                <form action="{{ route('spots.edit', ['spot_id' => session('spot_id')]) }}" method="GET" class="w-100">
                    <button type="submit" class="btn btn-outline-navy w-75">Edit</button>
                </form>
            @endif
            @if (session('spot_mode') === 'store')
                <a href="{{ route('spots.create') }}" class="btn btn-outline-navy w-75">Back</a>

            @endif

        
        </div>
        <div class="col-5 text-start">
            @php
                $mode = session('spot_mode');
                $action = $mode === 'update' 
                    ? route('spots.update', session('spot_id'))
                    : route('spots.store');
                $method = $mode === 'update' ? 'PATCH' : 'POST';
            @endphp

            <form action="{{ $action }}" method="POST" class="w-100">
                @csrf
                @if($mode === 'update') @method('PATCH') @endif
                <input type="hidden" name="from_confirm" value="true">
                <button type="submit" class="btn btn-navy w-75">Confirm</button>
            </form>

        </div>
    </div>
</div>

{{-- Google Maps --}}
<script src="{{ asset('js/spot/view/show-map.js') }}"></script>
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap">
</script>

{{-- <script src="{{ asset('js/spot/view/show-spot.js') }}"></script> --}}
@endsection
