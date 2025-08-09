<!-- User Profile Header -->
<section class="profile-header">
    <div class="profile-container">
        <div class="profile-left d-flex align-items-center justify-content-between">
            
            <!-- 左側（プロフィール画像と名前） -->
            <div class="profile-main d-flex align-items-center">
                <div class="col-auto my-auto p-0 profile-pic">
                    <a href="{{ route('profile.header', $quest_a->user->id) }}">                  
                        @if($quest_a->user->avatar)
                            <img src="{{ $quest_a->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                        @endif
                    </a> 
                </div>

                <div class="col-auto profile-name ms-2">{{$quest_a->user->name}}</div>
                
                @if($quest_a->user->official_certification == 3)
                <div class="col-md-1 col-sm-1 pb-1 p-1">
                    <img src="{{ asset('images/logo/official_personal.png')}}" class="official-personal d-inline ms-0 avatar-xs" alt="official-personal"> 
                </div>
                @endif
            </div>
                <!-- Followボタン -->
            {{-- @if($quest_a->user->id !== Auth::user()->id && Auth::user()->role_id == 1)
                <div class="col-auto mt-3 ">
                    @if($quest_a->user->isFollowed())
                        <!-- unfollow -->
                        <form action="{{route('follow.delete', $quest_a->user->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-following btn-sm w-100">Following</button>
                        </form>
                    @else
                        <!-- follow -->
                        <form action="{{route('follow.store', $quest_a->user->id )}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-follow btn-sm w-100">Follow</button>
                        </form>
                    @endif 
                </div>
            @endif --}}
            <!-- 右側（SNSアイコン） -->
            <div class="sns-icons d-flex align-items-center">
                @if(!empty($quest_a->user->instagram))
                    <a href="https://instagram.com/{{ $quest_a->user->instagram }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-instagram text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$quest_a->user->instagram}}</span>
                    </a>
                @endif
                @if(!empty($quest_a->user->facebook))
                    <a href="https://facebook.com/{{ $quest_a->user->facebook }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-facebook text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$quest_a->user->facebook}}</span>
                    </a>
                @endif
                @if(!empty($quest_a->user->x))
                    <a href="https://x.com/{{ $quest_a->user->x }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-x-twitter text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$quest_a->user->x}}</span>
                    </a>
                @endif
                @if(!empty($quest_a->user->tiktok))
                    <a href="https://www.tiktok.com/@{{ $quest_a->user->tiktok }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-tiktok text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$quest_a->user->tiktok}}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>