@section('css')
    <link rel="stylesheet" href="{{ asset('css/search-body.css')}}">
@endsection

{{-- @extends('layouts.app') --}}
    <div class="card p-3">

                {{-- what model is the post in? --}}
                @php
                    $class = class_basename(get_class($post));
                @endphp

        {{-- Card Image with official mark --}}
        @if ($class === 'Business' && $post->official_certification === 3)
            <img src="{{ asset('images/home/Official Badge.png') }}" class="official" alt="official">
        @endif


        {{-- Image & Routing to each show page --}}
        @if($class === 'Quest')
            <a href="{{ route('quest.show', $post->id )}}" class="">

        @elseif($class === 'Spot')
            <a href="{{ route('spot.show', $post->id )}}" class="">

        @elseif($class === 'Business' && $post->category_id === 1)
            <a href="{{ route('business.show', $post->id )}}" class="">

        @elseif($class === 'Business' && $post->category_id === 2)
            <a href="{{ route('business.show', $post->id )}}" class="">
        
        @elseif($class === 'Business' && $post->category_id === 6)
            <a href="{{ route('business.show', $post->id )}}" class="">
        

        @endif

                @if ($post->main_image)
                    <img src="{{ $post->main_image }}" class="card-img-top body-image" alt="image">
                @else
                    <img src="{{ asset('storage/app/public/images/home/noImage.jpg')}}" class="card-img-top body-image" alt="image">
                @endif
            </a>
        
        {{-- Card Body --}}
        <div class="row align-items-center card-body ps-1">  
            <div class="row justify-content-between ms-1">
                {{-- Category --}}
                <div class="col-auto p-0">
                    <h5 class="card-subtitle">Category: 
                        <strong>
                            @if ($class == 'Quest')
                                Travel
                            @elseif ($class == 'Businesses' || $post->category_id == '1')
                                Figure Skate
                            @elseif ($class == 'Businesses' || $post->category_id == '2')
                                Beer
                            @elseif ($class == 'Businesses' || $post->category_id == '6')
                                Others
                            @endif
                        </strong>
                    </h5>
                </div>
                
                {{-- Postdate --}}
                <div class="col-auto pe-0">
                    <h5 class="card-subtitle">{{ date('Y/m/d', strtotime($post->created_at)) }}</h5>
                </div>
            </div>                

            
            {{-- Title --}}
            <div class="mt-2">
                @if($class === 'Quest')
                    <a href="{{ route('quest.show', $post->id )}}" class="text-decoration-none">

                @elseif($class === 'Spot')
                    <a href="{{ route('spot.show', $post->id )}}" class="text-decoration-none">

                @elseif($class === 'Business' && $post->category_id === 1)
                    <a href="{{ route('business.show', $post->id )}}" class="text-decoration-none">

                @elseif($class === 'Business' && $post->category_id === 2)
                    <a href="{{ route('business.show', $post->id )}}" class="text-decoration-none">

                @elseif($class === 'Business' && $post->category_id === 6)
                    <a href="{{ route('business.show', $post->id )}}" class="text-decoration-none">

                @endif
                        <h4 class="card-title text-dark"><strong>{{ $post->title ?? $post->name }}</strong></h4>
                    </a>
            </div>


            
            {{-- Heart icon & Like function --}}
            <div class="row align-items-center ">
                <div class="col-1 ms-2 p-0 mt-1">
                    <button type="button"
                        class="btn btn-sm shadow-none like-button"
                        data-id = "{{ $post->id }}"
                        data-type = "{{ $class }}"
                        data-liked = "{{ $post->isLiked() ? '1' : '0' }}">
                        <i class="{{ $post->isLiked() ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                    </button>
                </div>
                    <div class="col-2 ms-1 px-2">
                    
                    <button class="btn text-dark" data-id="{{ $post->id }}">
                        <span>{{ $post->likes_count ?? $post->likes->count() }}</span>
                    </button>
                </div>
                {{-- Modal for displaying all users who liked owner of post--}}
                                        

                
                {{-- Comment icon & Number of comments --}}
                <div class="col-1 ms-3 p-0">
                    <div>
                        <i class="fa-regular fa-comment"></i>
                    </div>
                </div>
                <div class="col-2 ms-1 px-0">
                    <button class="btn p-0 text-center no-click">
                        <span>&nbsp;&nbsp;{{ $post->comments->count() }}</span>
                    </button>
                </div>

                {{-- Number of views --}}
                {{-- <div class="col-auto d-flex ms-3">
                    <div class="chart-img">
                        <img src="{{ asset('images/chart.png')}}" alt="">
                    </div>

                    <button class="btn btn-sm p-0 text-center no-click">
                        <span>&nbsp;&nbsp;{{ $post->views->sum('views') ?? 0 }}</span>
                    </button>
                </div> --}}
            </div>

            {{-- Description of posts --}}
            <div>
                <p class="card_description">
                    {{ $post->introduction }}
                </p>
            </div>
        </div>        
    </div>

