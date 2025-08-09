@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('css/home-cards.css')}}"> --}}
@endsection

@section('js')
    <script src="{{ asset('js/like.js')}}"></script>
    <script src="{{ asset('js/follow.js')}}"></script>
@endsection


    {{-- @guest --}}
    {{-- Header video --}}
    
    <div class="wrapper-header position-relative overflow-hidden d-flex align-items-center justify-content-center">
        <video autoplay muted loop playsinline class="header_video">
            <source src="{{ asset('videos/header-video-1.mp4') }}" type="video/mp4">
        </video>
    
        {{-- Header video's title --}}
        <div class="title">
            @guest
                <h3 class="text-light z-1 title-text poppins-bold">Hello, Guest user !</h3>
            @else
                <h3 class="text-light z-1 title-text poppins-bold">Hello, <span>{{Auth::user()->name}}</span>!</h3>
            @endguest
            <h3 class="text-light z-1 title-text poppins-bold">Welcome to my page</h3>
        </div>        
    </div>


@vite(['resources/js/home/home.js'])
