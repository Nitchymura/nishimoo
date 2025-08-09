@section('css')
    <link rel="stylesheet" href="{{ asset('css/post-body.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
@endsection

<div class="card p-3">
    <div class="card-header border-0 bg-light p-0 overflow-hidden">
        <a href="{{ route('promotions.show', $post->id) }}">
            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="post-image">
        </a>
    </div>
    <div class="card-body">  
        <div class="row mb-3">
            <!-- Postdate -->
            <div class="col-md-auto col-sm-12 pe-0 ms-auto">
                @if($post->updated_at)
                    <h5 class="card-subtitle">{{ $post->updated_at->format('H:i, M d Y')}}</h5>
                @else
                    <h5 class="card-subtitle">{{ $post->created_at->format('H:i, M d Y')}}</h5>
                @endif
            </div>
        </div>                

            
        <!-- Title -->
        <div class="row mb-1">
            <h4 class="card-title text-dark fw-bold pb-2">{{ $post->title }}</h4>
        </div>

        <!-- Duration -->
        <div class="row">
            <div class="col p-0">
                @if($post->promotion_start)
                    <h5 class="fw-bold">{{date('M d Y', strtotime($post->promotion_start))}} ~ {{date('M d Y', strtotime($post->promotion_end))}}</h5>
                @endif
            </div>  
        </div> 
        
        {{-- Description of posts --}}
        <div class="row">
            <div class="col p-0">
                <p class="card_description">
                    {{ $post->introduction}}
                </p>
            </div>    
        </div>
    </div>
</div>

