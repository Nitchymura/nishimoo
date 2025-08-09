<link rel="stylesheet" href="{{ asset('css/comment.css') }}">

<!-- Comments Section -->
<div class="comment-section">
    <div class="w-full mt-2">
        <h5 class="font-normal comment-count">
            Comments({{ $business->businesscomments->count() }})
        </h5>

    {{-- コメント送信フォーム --}}
    @auth
        <form action="{{ route('businesses.comment.store', $business->id) }}" method="POST">
            @csrf
            <textarea name="content" class="comment-textarea" placeholder="your comment" required>{{ old('content') }}</textarea>

            {{-- 星評価 --}}
            <div id="rating" class="mt-2 mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}"><i class="fa-solid fa-star"></i></span>
                @endfor
                <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating', 0) }}">
            </div>

            {{-- エラー表示（バリデーション） --}}
            @if ($errors->has('content'))
                <div class="text-danger">{{ $errors->first('content') }}</div>
            @endif
            @if ($errors->has('rating'))
                <div class="text-danger">{{ $errors->first('rating') }}</div>
            @endif

            {{-- 送信ボタン --}}
            <div class="flex justify-center">
                <button type="submit" class="comment-send-button">SEND</button>
            </div>
        </form>
    @else
        <p>If you want to post comments and like comments, please <a href="{{ route('login') }}">login</a>.</p>
    @endauth


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.star').on('click', function() {
                    let rating = $(this).data('value');
                    $('#ratingValue').val(rating);

                    $('.star').removeClass('selected');
                    $('.star').each(function() {
                        if ($(this).data('value') <= rating) {
                            $(this).addClass('selected');
                        }
                    });
                });
            });
        </script>
    </div>

    {{-- コメント一覧 --}}
    {{-- @if($business->businesscomments->isNotEmpty()) --}}
        @forelse($business_comments as $comment)    
        <div class="comment-container profile-post">
            <div class="comment-content">
                @auth
                    {{-- ゴミ箱アイコン（本人のみ表示） --}}
                    @if(Auth::user()->id === $comment->user_id)
                        <button class="comment-trash" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $comment->id }}">
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
                        @if($comment['rating'])
                            @for($i=1; $i <= $comment['rating']; $i++)
                                <i class="fa-solid fa-star color-yellow "></i>
                            @endfor
                            @for($i=1; $i <= 5 - $comment['rating']; $i++)
                                <i class="fa-regular fa-star color-navy"></i>
                            @endfor
                        @endif
                    </div>
                    <div class="col-auto">
                        <p class="">{{ $comment->content }}</p>
                    </div>    
                {{-- </div> --}}

                    {{-- いいねなどのアクション --}}
                    <div class="col">
                        <div class="comment-actions d-flex justify-content-end gap-3">
                            <div class="comment-action-item">
                                <form action="{{ route('questcomment.toggleLike', $comment->id) }}" method="POST" data-comment-id="{{ $comment->id }}" class="like-comment-form">
                                    
                                    @csrf
                                    <button type="submit" class="btn btn-sm shadow-none comment-like-btn">
                                        <i class="{{ $comment->BusinessCommentlikes->where('user_id', Auth::id())->isNotEmpty() ? 'fa-solid text-danger' : 'fa-regular' }} fa-heart"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm p-0 text-center open-comment-likes-modal"
                                    data-bs-toggle="modal"
                                    data-bs-target="#comment-likes-modal-{{ $comment->id }}"
                                    data-comment-id="{{ $comment->id }}">
                                    <span class="count comment-like-count" data-comment-id="{{ $comment->id }}">
                                        {{ $comment->BusinessCommentlikes->count() }}
                                    </span>
                                </button>
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
    </div>
</div>
        @vite('resources/js/quest/comment/quest-comment.js')

