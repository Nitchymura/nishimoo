<div class="row justify-content-center ">
    {{-- business --}}
    {{-- <div class="row mb-1"> --}}
        <div class="col-lg-12 col-md-10 col-sm-11 mb-5 ">
            <div class="row">
        @forelse($businesses as $post)
            @if(!$post['is_trashed'] || (Auth::check() && $post['user_id'] == Auth::id()))
                <div class="col-lg-4 col-md-6 col-sm">
                    @include('businessusers.profiles.post-body-profile')
                </div>
            @endif
        @empty
            <h4 class="h4 text-center text-secondary">No posts yet</h4>
        @endforelse
        </div>
    {{-- </div> --}}
</div>
    
    <div class="d-flex justify-content-end mb-5">
        {{ $businesses->links() }}
    </div>
</div>

