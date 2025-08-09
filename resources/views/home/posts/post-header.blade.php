@section('css')
    <link rel="stylesheet" href="{{ asset('css/post-body.css')}}">
    <link rel="stylesheet" href="{{ asset('css/quest/view-quest.css') }}">
@endsection

<main>
    

<div class="row justify-content-center tag-category">
    {{-- <div class="col-auto">
        <a href="{{ route('posts.followings') }}" class="text-decoration-none text-dark" data-category="followings">
            <h1 class="poppins-semibold {{ request()->is('home/posts/followings*') ? 'active' : '' }}">
                <i class="fa-solid fa-bookmark"></i> Followings'
            </h1>
        </a>
    </div> --}}


    <div class="col-auto ">
        <a href="{{ route('posts.travels') }}" class="text-decoration-none text-dark" data-category="quest">
            <h1 class="poppins-semibold {{ request()->is('home/posts/quest*') ? 'active' : '' }}">
                <img src="{{ asset('images/logo/plane.png')}}" alt="" class="header-icon-lg fa-rotate-by" > Travels
            </h1>
        </a>
    </div>
    <div class="col-auto">
        <a href="{{ route('posts.locations') }}" class="text-decoration-none text-dark" data-category="location">
            <h1 class="poppins-semibold {{ request()->is('home/posts/locations*') ? 'active' : '' }}">
                <img src="{{ asset('images/logo/skate.png')}}" alt="" class="header-icon-md"> Figure Skate
            </h1>
        </a>
    </div>
    <div class="col-auto">
        <a href="{{ route('posts.events') }}" class="text-decoration-none text-dark" data-category="event">
            <h1 class="poppins-semibold {{ request()->is('home/posts/events*') ? 'active' : '' }}">
                <img src="{{ asset('images/logo/beer.png')}}" alt="" class="header-icon-md"> Beer
            </h1>
        </a>
    </div>
    <div class="col-auto">
        <a href="{{ route('posts.others') }}" class="text-decoration-none text-dark" data-category="spot">
            <h1 class="poppins-semibold {{ request()->is('home/posts/others*') ? 'active' : '' }}">
                <img src="{{ asset('images/logo/good.png')}}" alt="" class="header-icon-md"> Others
            </h1>
        </a>
    </div>
    <div class="col-auto">
        <a href="{{ route('posts.all') }}" class="text-decoration-none text-dark" data-category="all">
            <h1 class="poppins-semibold {{ request()->is('home/posts/all*') ? 'active' : '' }}">
                <img src="{{ asset('images/logo/all.png')}}" alt="" class="header-icon-md mt-1"> All
            </h1>
        </a>
    </div>

</div>
<hr>

</main>

