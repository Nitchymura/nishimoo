{{-- items --}}
    <!--Post-->
    <div class="col-auto">
        @if($user->role_id == 1)                          
            @if($user->id == Auth::user()->id)
                <a href="{{ route('profile.header', $user->id) }}" class="text-decoration-none text-white"><span class="fw-bold">{{$user->quests->count()+$user->spots->count()}}</span> {{$user->quests->count()+$user->spots->count()==1 ? 'post' : 'posts'}}</a>
            @elseif($user->id != Auth::user()->id)
                <a href="{{ route('profile.header', $user->id) }}" class="text-decoration-none text-white"><span class="fw-bold">{{$user->questsVisible->count()+$user->spotsVisible->count()}}</span> {{$user->questsVisible->count()+$user->spotsVisible->count()==1 ? 'post' : 'posts'}}</a>
            @endif
        @else
            @if($user->id == Auth::user()->id)
                <a href="{{ route('profile.header', $user->id) }}" class="text-decoration-none text-dark"><span class="fw-bold">{{$user->businessPromotions->count()+$user->businesses->count()+$user->quests->count()}}</span> {{$user->businessPromotions->count()+$user->businesses->count()+$user->quests->count()==1 ? 'post' : 'posts'}}</a>
            @elseif($user->id != Auth::user()->id)
                <a href="{{ route('profile.header', $user->id) }}" class="text-decoration-none text-dark"><span class="fw-bold">{{$user->businessPromotionsVisible->count()+$user->businessesVisible->count()+$user->questsVisible->count()}}</span> {{$user->businessPromotionsVisible->count()+$user->businessesVisible->count()+$user->questsVisible->count()==1 ? 'post' : 'posts'}}</a>
            @endif
        @endif
    </div>
    <!--Follower-->
    @if($user->role_id == 1)  
        <div class="col-auto">
            <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'followers']) }}" class="text-decoration-none text-white"><span class="fw-bold">{{$user->followers->count()}}</span> {{$user->followers->count()==1 ? 'follower' : 'followers'}}</a>
        </div>
        <div class="col-auto">
            <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'follows']) }}" class="text-decoration-none text-white"><span class="fw-bold">{{$user->follows->count()}}</span> following</a>
        </div>
    @else  
        <div class="col-auto">
            <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'followers']) }}" class="text-decoration-none text-dark"><span class="fw-bold">{{$user->followers->count()}}</span> {{$user->followers->count()==1 ? 'follower' : 'followers'}}</a>
        </div>
        {{-- <div class="col-auto">
            <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'follows']) }}" class="text-decoration-none text-dark"><span class="fw-bold">{{$user->follows->count()}}</span> following</a>
        </div> --}}
    @endif
    @if($user->id == Auth::user()->id && $user->role_id == 2)
        <div class="col-auto">
            @if($user->id == Auth::user()->id)                             
                <a href="{{ route('business.reviews.all', $user->id)}}" class="text-decoration-none text-dark"><span class="fw-bold">{{$business_comments->count()}}</span> {{$business_comments->count()==1 ? 'review' : 'reviews'}}</a>
            @endif
        </div>
    @endif

@if($user->role_id == 1)       
    {{-- SNS icons --}}
    <div class="sns-icons d-flex align-items-center col-auto ms-auto">
        @if(!empty($user->instagram))
            <a href="https://instagram.com/{{ $user->instagram }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-instagram text-white icon-md mx-1"></i><span class="text-white ms-1">{{$user->instagram}}</span>
            </a>
        @endif
        @if(!empty($user->facebook))
            <a href="https://facebook.com/{{ $user->facebook }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-facebook text-white icon-md mx-1"></i><span class="text-white ms-1">{{$user->facebook}}</span>
            </a>
        @endif
        @if(!empty($user->x))
            <a href="https://x.com/{{ $user->x }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-x-twitter text-white icon-md mx-1"></i><span class="text-white ms-1">{{$user->x}}</span>
            </a>
        @endif
        @if(!empty($user->tiktok))
            <a href="https://www.tiktok.com/@{{ $user->tiktok }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-tiktok text-white icon-md mx-1"></i><span class="text-white ms-1">{{$user->tiktok}}</span>
            </a>
        @endif
    </div>
@elseif($user->role_id == 2)
    <div class="sns-icons d-flex align-items-center col-auto ms-auto">
        @if(!empty($user->instagram))
            <a href="https://instagram.com/{{ $user->instagram }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-instagram text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$user->instagram}}</span>
            </a>
        @endif
        @if(!empty($user->facebook))
            <a href="https://facebook.com/{{ $user->facebook }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-facebook text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$user->facebook}}</span>
            </a>
        @endif
        @if(!empty($user->x))
            <a href="https://x.com/{{ $user->x }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-x-twitter text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$user->x}}</span>
            </a>
        @endif
        @if(!empty($user->tiktok))
            <a href="https://www.tiktok.com/@{{ $user->tiktok }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                <i class="fa-brands fa-tiktok text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$user->tiktok}}</span>
            </a>
        @endif
    </div>
    @endif
