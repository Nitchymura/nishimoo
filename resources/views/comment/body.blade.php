<link rel="stylesheet" href="{{ asset('css/comment.css') }}">

<!-- Comments Section -->
<div class="comment-section">
    <div class="w-full mt-2">
        <h5 class="font-normal">
        Comments(1)
        </h5>
        <textarea class="comment-textarea" placeholder="your comment"></textarea>
        <div class="flex justify-center">
            <button class="comment-send-button">SEND</button>
        </div>
    </div>
    

    {{-- comment section --}}
    <div class="comment-container">
        <div class="comment-content">

            {{-- trash icon --}}
            <button class="comment-trash" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fa-solid fa-trash"></i>
            </button>
            {{-- INCLUDE MODAL HERE --}}
            @include('comment.modals.delete')

            {{-- Icon & Name & Post Date --}}
            <div class="comment-header">
                {{-- User Icon --}}
                <div class="comment-user-icon" id="usericon">
                    <a href="#" class="text-decoration-none d-flex align-items-center">
                    <img src="{{ asset('images/spot_sample/image.png')}}" class="avatar-xs" alt="card-icon">
                    </a>
                </div>
                {{-- User Name --}}
                <div class="comment-username" id="touristname">
                    <a href="#" class="text-decoration-none h5 d-flex align-items-center">
                    <h1 class="username h6" id="username"><strong>Bruno Mars</strong></h1>
                    </a>
                </div>
                {{-- Post Date --}}
                <div class="comment-date" id="date">
                    <p class="text-decoration-none d-flex align-items-center">
                    2025.02.11&nbsp;&nbsp;&nbsp;&nbsp;17:25
                    </p>
                </div>
            </div>   
            

            {{-- content of comment --}}
            <div class="comment-text" id="content">
                <p class="text-decoration-none">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eligendi tempore libero distinctio nesciunt quisquam doloremque commodi! Quae dolore repudiandae optio iste veritatis et odio, labore consequuntur tenetur, sint a beatae!
                </p>
            </div>

            {{-- content of indicate --}}
            <div class="comment-actions">
                {{-- Heart icon & Like function --}}
                <div class="comment-action-item">
                    <form action="#" method="post">
                        @csrf
                        <button type="submit" class="btn btn-sm shadow-none">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </form>
                    <button class="btn btn-sm p-0 text-center">
                        <span class="count">10</span>
                    </button>
                </div>                                     
                {{-- View icon &  Number of view --}}
                <div class="comment-action-item">
                    <div>
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <button class="btn btn-sm p-0 text-center">
                        <span class="count">201</span>
                    </button>
                </div>
            </div>
            
        </div>
    </div>

</div>
