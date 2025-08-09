<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal{{ $comment->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h5 text-danger"><i class="fa-solid fa-trash"></i> Delete Business</h3>
            </div>
            <div class="modal-body">
                <div class="fw-bold mb-3">   
                    Are you sure you want to delete this Business?
                </div>
                <div>
                    <p class="text-dark">{{ $business->name }}</p>
                </div>
                <div>
                    <p class="text-dark">{{ $business->introduction }}</p>
                </div>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> This action cannot be undone. All data related to this business will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('businesses.deactivate', $business->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-secondary">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete Permanently</button>
                </form>
            </div>
        </div>
    </div>
</div>
