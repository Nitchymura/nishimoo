<div class="row justify-content-center">
    {{-- Spots --}}
    <div class="row mb-1 mt-4">
        @forelse($spots as $post)
            <div class="col-lg-4 col-md-6 col-sm">
                @include('businessusers.profiles.post-body-profile')
            </div>         
        @empty
            <h4 class="h4 text-center text-secondary">No posts yet</h4>
        @endforelse
    </div>
    <div class="d-flex justify-content-end mb-5">
        {{ $spots->links() }}
    </div>
</div>

