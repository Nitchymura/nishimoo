@section('css')
    <link rel="stylesheet" href="{{ asset('css/post-body.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css')}}"> --}}
@endsection


    <div class="card p-3">
        <div class="card-header border-0 bg-light p-0 overflow-hidden">
            {{-- Card Image with official mark --}}
            @if($post['official_certification']==3 && $post['user']->official_certification==3)
                <img src="{{ asset('images/logo/Official_Badge.png') }}" class="official" alt="official">              
            @else
            @endif
            @if($post['type'] == 'businesses')
                <a href="{{route('business.show', $post['id'])}}" >
                    @if(Str::startsWith($post['main_image'], 'http') || Str::startsWith($post['main_image'], 'data:'))
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="post-image">
                    @else
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="post-image">
                    @endif
                </a>
            @elseif($post['type'] == 'spots')
                <a href="{{ route('spot.show', $post['id']) }}" >
                    @if(Str::startsWith($post['main_image'], 'http') || Str::startsWith($post['main_image'], 'data:'))
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="post-image">
                    @else
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="post-image">
                    @endif
                </a>
            @elseif($post['type'] == 'quests')
                <a href="{{route('quest.show', $post['id'])}}" >
                    @if(Str::startsWith($post['main_image'], 'http') || Str::startsWith($post['main_image'], 'data:'))
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="post-image" alt="image">
                    @else
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="post-image" alt="image">
                    @endif
                    {{-- @if(Str::startsWith($post['main_image'], 'http') || Str::startsWith($post['main_image'], 'data:'))
                        <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class=" post-image">
                    @else
                        <img src="{{ asset('storage/' . $post['main_image']) }}" alt="{{ $post['title'] }}" class="post-image">
                    @endif --}}
                </a>
            @endif
        </div>

        <div class="card-body content-lg">  
            <div class="row mb-3">
                @if (Request::is('home/posts/all*') || Request::is('home/posts/followings*'))
                {{-- Category --}}
                    <div class="col p-0 me-0 text-end">
                        <h5 class="card-subtitle">Category: 
                            @if($post['tab_id']==3)
                                <strong>Figure Skate</strong>
                            @elseif($post['tab_id']==4)
                                <strong>Beer</strong>
                            @elseif($post['tab_id']==6)
                                <strong>Others</strong>
                            @elseif($post['tab_id']==7)
                                <strong>Travel</strong>
                            @endif
                        </h5>
                    </div>
                @endif
                

            </div>                

            
            {{-- Title --}}
            <div class="row mb-1">
                <div class="col p-0">
                    @if($post['type'] == 'businesses')
                        <a href="{{route('business.show', $post['id'])}}"  class="text-decoration-none">
                            <h4 class="card-title text-dark fw-bold pb-1">{{ $post['title'] }}</h4>
                        </a>
                    @elseif($post['type'] == 'spots')
                        <a href="{{ route('spot.show', $post['id']) }}" class="text-decoration-none">
                            <h4 class="card-title text-dark fw-bold pb-1">{{ $post['title'] }}</h4>
                        </a>
                    @elseif($post['type'] == 'quests')
                        <a href="{{route('quest.show', $post['id'])}}"  class="text-decoration-none">
                            <h4 class="card-title text-dark fw-bold pb-1">{{ $post['title'] }}</h4>
                        </a>
                    @endif

                </div>
            </div>
            <div class="row">
                {{-- Postdate --}}
                <div class="col ps-0">
                    {{-- @if($post['type'] == 'quests') --}}
                        @if ($post['term_start'] && $post['term_end'] && $post['term_start'] != $post['term_end'])
                            {{ date('M d, Y', strtotime($post['term_start'])) }} ~ {{ date('M d, Y', strtotime($post['term_end'])) }}
                        @else
                            {{ date('M d, Y', strtotime($post['term_start'])) }}
                        @endif
                    {{-- @else
                        {{ $post['created_at']->format('M d, Y')}}
                    @endif --}}
                </div>
            </div>
            {{-- Icon & Name & Official mark --}}
            <div class="row align-items-center personal_space">
                {{-- User Icon --}}
                {{-- <div class="col-md-auto col-sm-2 my-auto p-0">                   
                    <button class="btn">
                        <a href="{{ route('profile.header', $post['user_id'] )}}" class="text-decoration-none h5 d-flex align-items-center">
                            @if($post['avatar'])
                            <img src="{{ $post['avatar'] }}" alt="#" class="rounded-circle avatar-sm">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary text-decoration-none profile-sm text-center"></i>
                            @endif
                        </a>
                    </button>
                </div> --}}
            
                {{-- User Name --}}
                {{-- <div class="col-md-auto col-sm-6 ms-2 p-0">
                    <a href="{{ route('profile.header', $post['user_id']) }}" class="text-decoration-none h5 d-inline align-items-center">
                        <p class="username h4 my-auto" id="username">{{ $post['user_name'] }}</p></a>                 
                </div> --}}

                {{-- Javascript for character limit --}}
                <script>
                    document.querySelectorAll('.username').forEach(elem => {     //変更①　idではなくclassから引っ張ってくる。
                        const text = elem.textContent;   //変更②　前まではusernameElemという変数を使っていましたが、上記の理由からただのelemに変更。
                        if (text.length > 15){
                        elem.textContent = text.substring(0, 15) + "...";　//変更③　変更②と同じ修正です。
                        }
                    });
                </script>

                {{-- User official mark --}}
                <div class="col-md-auto col-sm-1 mt-1 p-1">
                    @if($post['user_official_certification'] == 3)
                        <img src="{{ asset('images/logo/official_personal.png')}}" class="official-personal d-inline ms-0" alt="official-personal">
                    @else
                    @endif
                </div>

                {{-- Follow Button --}}
                @auth
                    @if($post['user_id'] != Auth::user()->id && Auth::user()->role_id == 1)
                        <div class="col-md-auto col-sm ms-auto p-0 mt-3">
                            @if ($post['user']->isFollowed())
                                <form method="POST" action="{{ route('delete.follow', $post['user']->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-following mb-2 w-100">Following</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('store.follow', $post['user']->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-follow mb-2 w-100">Follow</button>
                                </form>
                            @endif
                        </div> 
                    @endif
                @endauth
                
                @guest
                    
                @endguest

            </div>
            
            {{-- Heart icon & Like function --}}
            <div class="row align-items-center ">
                <div class="col-1 ms-2 p-0 mt-3">
                    {{-- like/heart button --}}
                    @if($post['is_liked'])
                        @php
                            $likeDeleteRoute = $post['type'] . '.like.delete';
                        @endphp
                        {{-- red heart/unlike --}}
                        <form action="{{ route($likeDeleteRoute, $post['id']) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn p-0">
                                <i class="fa-solid fa-heart home color-red {{ $post['is_liked'] ? 'text-danger' : 'text-secondary' }}" ></i>
                            </button>
                        </form>
                    @else
                        @php
                            $likeStoreRoute = $post['type'] . '.like.store'; // 例: 'quest.like.store'
                        @endphp
                        <form action="{{ route($likeStoreRoute, $post['id']) }}" method="post">
                            @csrf
                            <button type="sumbit" class="btn p-0">
                                <i class="fa-regular fa-heart home"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="col-2 ms-1 px-2">
                    <button class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#">
                        <span>{{ $post['likes_count'] }}</span>
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
                    <span>{{ $post['comments_count'] }}</span>
                </div>

                {{-- Number of viewers --}}
                {{-- <div class="col-1 ms-3 p-0">
                    <div>
                        <img src="{{ asset('images/chart.png') }}" alt="">
                    </div>
                </div>
                <div class="col-2 ms-1 px-0">
                    <button class="dropdown-item text-dark">
                        <span>{{ $post['views_sum'] ?? 0 }}</span>
                    </button>
                </div> --}}
            </div>

            {{-- Description of posts --}}
            <div class="row">
                <div class="col p-0">
                    <p class="card_description">
                        {{ $post['introduction']}}
                    </p>
                </div>    
            </div>
        </div>
    </div>
