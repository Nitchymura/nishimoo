<div class="card promotion-card mb-4">
    <div class="card-header border-0 bg-light p-3">
        <!-- Business Name -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-subtitle mb-0">{{ $promotion->business->name }}</h5>
            <h6 class="text-muted mb-0">{{ date('M d Y', strtotime($promotion->created_at)) }}</h6>
        </div>
        
        <!-- Promotion Title -->
        <h4 class="fw-bold">{{ $promotion->title }}</h4>
        
        <!-- Promotion Period -->
        @if((!$promotion->promotion_start || !$promotion->promotion_end))
            <!-- No date specified -->
        @elseif($promotion->promotion_start == $promotion->promotion_end)
            <h6 class="fw-bold">{{ date('M d Y', strtotime($promotion->promotion_start)) }}</h6>
        @elseif($promotion->promotion_start && $promotion->promotion_end)
            @if(($promotion->promotion_start < $promotion->promotion_end))
                <h6 class="fw-bold">{{ date('M d Y', strtotime($promotion->promotion_start)) }} ~ {{ date('M d Y', strtotime($promotion->promotion_end)) }}</h6>     
            @else
                <h6 class="fw-bold">{{ date('M d Y', strtotime($promotion->promotion_end)) }} ~ {{ date('M d Y', strtotime($promotion->promotion_start)) }}</h6> 
            @endif                              
        @endif
    </div>
    
    <!-- Promotion Image -->
    <div class="position-relative">
        <a href="{{ route('promotions.show', $promotion->id) }}">
            <img src="{{ $promotion->image }}" class="card-img-top promotion-img" alt="{{ $promotion->title }}">
        </a>
    </div>
    
    <!-- Promotion Description -->
    <div class="card-body">
        <p class="card-text promotion-description">{{ Str::limit($promotion->introduction, 100) }}</p>
        <a href="{{ route('promotions.show', $promotion->id) }}" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
    </div>
</div> 