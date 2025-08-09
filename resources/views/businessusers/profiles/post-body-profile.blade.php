@section('css')
    <link rel="stylesheet" href="{{ asset('css/post-body.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
@endsection

<div class="card p-3 profile-post ">
    <div class="card-header border-0 bg-light p-0 overflow-hidden">
        @if($post['official_certification']==3 && $post['user']->official_certification==3)
            <img src="{{ asset('images/logo/Official_Badge.png') }}" class="official" alt="official">              
        @endif

        @php
            $imageSrc = Str::startsWith($post['main_image'], 'http') || Str::startsWith($post['main_image'], 'data:')
                ? $post['main_image']
                : asset($post['main_image']);
        @endphp

        @if($post['user']->role_id == 1)
            @if($post['type'] == 'spots')
                <a href="{{ route('spot.show', $post['id']) }}">
                    <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                </a>
            @elseif($post['type'] == 'quests')
                <a href="{{ route('quest.show', $post['id']) }}">
                    <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                </a>
            @endif
        @elseif($post['user']->role_id == 2 || $post['user']->role_id == 3)
            @if($post['official_certification'] == 2)
                @if($post['type'] == 'businesses')
                    <a href="{{ route('business.show', $post['id'] )}}">
                        <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                    </a>
                @elseif($post['type'] == 'promotions')
                    <a href="{{ route('promotions.show', $post['id']) }}">
                        <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                    </a>
                @elseif($post['type'] == 'quests')
                    <a href="{{ route('quest.show', $post['id']) }}">
                        <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image opacity-50">
                    </a>
                @endif
            @else
                @if($post['type'] == 'businesses')
                    <a href="{{ route('business.show', $post['id']) }}">
                        <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                    </a>
                @elseif($post['type'] == 'promotions')
                    <a href="{{ route('promotions.show', $post['id']) }}">
                        <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                    </a>
                @elseif($post['type'] == 'quests')
                    <a href="{{ route('quest.show', $post['id']) }}">
                        <img src="{{ $imageSrc }}" alt="{{ $post['title'] }}" class="post-image">
                    </a>
                @endif
            @endif
        @endif
    </div>

        @if($post['type'] == 'businesses')
            <div class="card-body content-md"> 
        @elseif($post['type'] == 'quests')
            <div class="card-body content-md"> 
        @elseif($post['type'] == 'promotions')
            <div class="card-body">  
        @elseif($post['type'] == 'spots')
            <div class="card-body content-md">  
        @endif
            <div class="row mb-3">
                {{-- @if ($post['type']== 'businesses') --}}
                <!-- Category -->
                    <div class="col text-end">                      
                        @if($post['category_id']==1)
                            <h5 class="card-subtitle">Category: <strong>Figure Skate</strong></h5>
                        @elseif($post['category_id']==2)
                            <h5 class="card-subtitle">Category: <strong>Beer</strong></h5>
                        @elseif($post['category_id']==6)
                            <h5 class="card-subtitle">Category: <strong>Others</strong></h5>
                        @elseif($post['category_id']==7)
                            <h5 class="card-subtitle">Category: <strong>Travel</strong></h5>
                        @elseif($post['type']== "spots")
                            <h5 class="card-subtitle">Category: <strong>Spot</strong></h5>
                        @elseif($post['tab_id']==3)
                            <h5 class="card-subtitle fw-bold">{{ $post['business_name']}}</h5>
                        @endif                    
                    </div>

                <!-- Postdate -->
                {{-- <div class="col-md-auto col-sm-12 pe-0 ms-auto">
                    @if($post['updated_at'])
                        <h5 class="card-subtitle">{{ $post['updated_at']->format('H:i, M d Y')}}</h5>
                    @else
                        <h5 class="card-subtitle">{{ $post['created_at']->format('H:i, M d Y')}}</h5>
                    @endif
                </div> --}}
            </div>                

            
            <!-- Title -->
            <div class="row mb-1">
                <div class="col p-0">
                    @if($post['type'] == 'businesses')
                        <a href="{{ route('business.show', $post['id']) }}"  class="text-decoration-none">
                            <h4 class="card-title text-dark fw-bold pb-2 text-decoration-none">{{ $post['title'] }}</h4>
                        </a>
                    @elseif($post['type'] == 'promotions')
                        <a href="{{ route('promotions.show', $post['id']) }}"  class="text-decoration-none">
                            <h4 class="card-title text-dark fw-bold pb-2 text-decoration-none">{{ $post['title'] }}</h4>
                        </a>
                    @elseif($post['type'] == 'quests')
                        <a href="{{ route('quest.show', $post['id']) }}" class="text-decoration-none" >
                            <h4 class="card-title text-dark fw-bold pb-2 text-decoration-none">{{ $post['title'] }}</h4>
                        </a>
                    @elseif($post['type'] == 'spots')
                    <a href="{{ route('spot.show', $post['id']) }}" class="text-decoration-none">
                        <h4 class="card-title text-dark fw-bold pb-2">{{ $post['title'] }}</h4>
                    </a>
                    @endif
                </div>
            </div>

            <div class="row">
                {{-- Postdate --}}
                <div class="col ps-0">
                    @if ($post['term_start'] && $post['term_end'] && $post['term_start'] != $post['term_end'])
                        {{ date('M d, Y', strtotime($post['term_start'])) }} ~ {{ date('M d, Y', strtotime($post['term_end'])) }}
                    @else
                            {{ date('M d, Y', strtotime($post['term_start'])) }}
                    @endif
                    {{-- @if($post['type'] == 'quests') --}}
                        {{-- @if ($post['term_start'] && $post['term_end'] && $post['term_start'] != $post['term_end'])
                            {{ date('M d, Y', strtotime($post['term_start'])) }} ~ {{ date('M d, Y', strtotime($post['term_end'])) }}
                        @else
                            {{ date('M d, Y', strtotime($post['term_start'])) }}
                        @endif --}}
                    {{-- @else
                        {{ $post['created_at']->format('M d, Y')}}
                    @endif --}}
                </div>
            </div>

            <!--Avatar-->
            @if($section)
            <div class="row align-items-center personal_space">
                {{-- User Icon --}}
                <div class="col-md-auto col-sm-2 my-auto p-0">                   
                    <button class="btn">
                        @if($post['user']->avatar)                           
                            <a href="{{ route('profile.header', $post['user_id']) }}"><img src="{{ $post['user']->avatar }}" alt="" class="rounded-circle avatar-sm"></a>
                        @else
                        <a href="{{ route('profile.header', $post['user_id']) }}"><i class="fa-solid fa-circle-user text-secondary profile-sm text-center"></i></a>                          
                        @endif
                    </button>
                </div>
            
                {{-- User Name --}}
                <div class="col-md-auto col-sm-6 ms-2 p-0">
                    <a href="{{ route('profile.header', $post['user_id']) }}" class="text-decoration-none h5 d-inline align-items-center">
                        <p class="h5 my-auto" id="username">{{ $post['user']->name }}</p></a>                 
                </div>

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
                    @if($post['user']->official_certification == 3)
                        <img src="{{ asset('images/logo/official_personal.png')}}" class="official-personal d-inline ms-0" alt="official-personal">
                    @else
                    @endif
                </div>
            </div>
            @endif

            <!-- Duration -->
            @if($post['tab_id']==4)
                <div class="row">
                    <div class="col p-0">
                        @if($post['duration'])
                            <h5 class="fw-bold"><span>Model Duration: {{$post['duration']}}</span> {{$post['duration']==1 ? 'day' : 'days'}}</h5>
                        @elseif($post['start_date'] && $post['end_date'])
                            <h5 class="fw-bold"><span>{{date('M d Y', strtotime($post['start_date']))}}~{{date('M d Y', strtotime($post['end_date']))}}</span> </h5>
                        @else
                            <p>Quest duration: Not defined</p>
                        @endif
                    </div>  
                </div> 
            @elseif($post['tab_id']==3)
            <div class="row">
                <div class="col p-0">
                    @if($post['promotion_start'])
                        <h5 class="fw-bold">{{date('M d Y', strtotime($post['promotion_start']))}} ~ {{date('M d Y', strtotime($post['promotion_end']))}}</h5>
                    @endif
                </div>  
            </div> 
            @endif
            
            @if($post['type'] == 'businesses' || $post['type'] == 'quests' || $post['type'] == 'spots')
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
                                <i class="fa-solid fa-heart color-red {{ $post['is_liked'] ? 'text-danger' : 'text-secondary' }}" ></i>
                            </button>
                        </form>
                    @else
                        @php
                            $likeStoreRoute = $post['type'] . '.like.store'; // 例: 'quest.like.store'
                        @endphp
                        <form action="{{ route($likeStoreRoute, $post['id']) }}" method="post">
                            @csrf
                            <button type="sumbit" class="btn p-0">
                                <i class="fa-regular fa-heart"></i>
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
                        <span>{{ $post['views_sum'] ?? 0}}</span>
                    </button>
                </div> --}}
            </div>
            @endif

            {{-- Description of posts --}}
            <div class="row">
                <div class="col p-0">
                    <p class="card_description">
                        {{ $post['introduction']}}
                    </p>
                </div>    
            </div>
        </div>

        @if($user->id == Auth::user()->id && $post['user_id'] == $user->id )
                <div class="card-footer bg-white">
                    {{-- status --}}
                        <div class="row ">
                            <div class="col p-0 mb-3">
                                {{-- visibility --}}
                                @if($post['is_trashed'])
                                    Status: <i class="fa-solid fa-circle color-red"></i> Hidden
                                @else
                                    Status: <i class="fa-solid fa-circle color-green"></i> Visible
                                @endif
                            </div>    
                        </div>
                        <div class="row">
                                @php
                                    $postEditRoute = $post['type'] . '.edit';
                                @endphp
                             <div class="col-6">    
                                @if($post['official_certification']==2)
                                    <div class="btn btn-sm btn-navy mb-2 w-100">REVIEWING</div>
                                @else
                                    @if($post['type'] == 'businesses')
                                        <a href="{{ route($postEditRoute, $post['id']) }}" class="btn btn-sm btn-green fw-bold mb-2 w-100">EDIT</a>
                                    @elseif($post['type'] == 'promotions')
                                        <a href="{{ route($postEditRoute, $post['id']) }}" class="btn btn-sm btn-green fw-bold mb-2 w-100">EDIT</a>
                                    @elseif($post['type'] == 'quests')
                                        <a href="{{route('quest.edit', $post['id'])}}"class="btn btn-sm btn-green fw-bold mb-2 w-100">EDIT</a>
                                    @elseif($post['type'] == 'spots')
                                        <a href="{{ route($postEditRoute, $post['id']) }}" class="btn btn-sm btn-green fw-bold mb-2 w-100">EDIT</a>
                                    
                                    @endif
                                @endif
                            </div>
                      
                            <div class="col-6">
                                @if($post['official_certification']==2)
                                    @if($post['is_trashed'])                  
                                        <button class="btn btn-outline-green w-100" disabled>
                                            UNHIDE
                                        </button>
                                    @else
                                        <button class="btn btn-red w-100" disabled>
                                            HIDE
                                        </button>
                                    @endif
                                @else
                                    @if($post['is_trashed'])                  
                                        @php
                                            $modalId = 'activate-' . $post['type'] . $post['id'];
                                        @endphp
                                        <button class="btn btn-outline-green w-100" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                            UNHIDE
                                        </button>
                                    @else
                                        @php
                                            $modalId = 'deactivate-' . $post['type'] . $post['id'];
                                        @endphp
                                        <button class="btn btn-red w-100" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                            HIDE
                                        </button>
                                    @endif
                                    @include("businessusers.posts.{$post['type']}.modals.hide_unhide", [
                                    'post' => $post,
                                    'modalId' => $modalId,
                                ])
                                @endif
                                
                            </div>
                        </div>
                </div> 
            
        @endif
    </div>

