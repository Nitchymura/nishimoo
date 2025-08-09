<link rel="stylesheet" href="{{ asset('css/comment.css') }}">

<!-- Comments Section -->
<div class="comment-section">
    <div class="w-full mt-2">
        <h5 class="font-normal">
            Comments({{ $spot->comments->count() }})
        </h5>

        {{-- add comment section --}}
        @auth
            <form action="{{ route('spots.comment.store', $spot->id) }}" method="post">
                @csrf
                <input type="hidden" name="spot_id" value="{{ $spot->id }}">
                <textarea name="content" class="comment-textarea" placeholder="your comment" required></textarea>
                <div class="flex justify-center">
                    <button type="submit" class="comment-send-button">SEND</button>
                </div>
            </form>
            @if ($errors->has('content'))
                <div class="text-danger">
                    {{ $errors->first('content') }}
                </div>
            @endif
        @else
            <p>If you want to post comments and like comments, please <a href="{{ route('login') }}">login</a>.</p>
        @endauth

        {{-- view comment section --}}
        @forelse($spot->comments as $comment)    
        <div class="comment-container profile-post">
            <div class="comment-content">
                @auth
                    {{-- ゴミ箱アイコン（本人のみ表示） --}}
                    @if(Auth::user()->id === $comment->user_id)
                        <button class="comment-trash" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $comment->id }}">
                            <i class="fa-solid fa-trash color-red"></i>
                        </button>
                        {{-- Include Delete Modal --}}
                        @include('businessusers.posts.businesses.modals.delete', ['comment' => $comment, 'business' => $business])
                    @endif
                @endauth

                {{-- コメント投稿者の情報 --}}
                <div class="comment-header my-2 d-flex align-items-center">

                    {{-- アバターリンク --}}
                    <a href="{{route('profile.header', $comment->user_id)}}" class="text-decoration-none d-flex align-items-center me-2">
                        @if($comment->user->avatar)
                            <img src="{{ $comment->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                        @endif
                    </a>

                    {{-- ユーザー名リンク --}}
                    <div class="d-flex align-items-center flex-wrap">
                        <a href="{{route('profile.header', $comment->user_id)}}" class="text-decoration-none">
                            <span class="username h6 mb-0 text-decoration-none text-dark"><strong>{{ $comment->user->name }}</strong></span>
                        </a>
                        {{-- 認証バッジ（任意） --}}
                        {{-- @if(optional($comment->user)->official_certification == 2)
                            <img src="{{ asset('images/logo/official_personal.png') }}" class="avatar-xs ms-2" alt="official badge">
                        @endif --}}
                    </div>

                    {{-- 日付 --}}
                    <div class="ms-auto text-muted small">
                        {{ date('H:i, M d Y', strtotime($comment->created_at)) }}
                    </div>
                </div>


                {{-- コメント内容 --}}
                <div class="row comment-text col-auto px-2">
                    <div class="col-auto">
                        <p class="">{{ $comment->content }}</p>
                    </div>    
                {{-- </div> --}}

                    {{-- いいねなどのアクション --}}
                    <div class="col">
                        <div class="comment-actions d-flex justify-content-end gap-3">
                            <div class="comment-action-item">
                                @if($comment->isLiked())
                                    {{-- red heart/unlike --}}
                                    <form action="{{route('spots.comment.like.delete', $comment->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0">
                                            <i class="fa-solid fa-heart text-danger"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{route('spots.comment.like.store', $comment->id)}}" method="post">
                                        @csrf
                                        <button type="sumbit" class="btn p-0">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                    </form>
                                @endif
                           
                                @if($comment->likes->count()>0)
                                    <button class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#show-likes{{$comment->id}}">
                                        {{$comment->likes->count()}}
                                    </button>
                        
                                @else
                                    0
                                @endif
                                
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
            {{-- @include('quests.comment.modals.like') --}}
        </div> 
        @empty
            <h4 class="h4 text-center text-secondary">No comments yet</h4> 
        @endforelse  

        @if ($spot->comments->count() == 0)
            <div class="text-center mt-3">
                <p>There is no comment yet.</p>
            </div>
        @endif
    </div>
</div>
@foreach ($spot->comments as $comment)
    <!-- コメント表示部分 -->
    @include('spots.comment.modals.spot-comment-likes', ['id' => $comment->id])
@endforeach
{{-- view images --}}
<script src="{{ asset('js/spot/view/comment.js') }}"></script>
