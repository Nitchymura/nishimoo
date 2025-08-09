<div class="modal fade" id="deleteModal{{ $comment->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <div class="h5 modal-title text-danger">
                    <i class="fa-solid fa-circle-exclamation"></i> Delete Comment
                </div>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete your comment?</p>
            </div>

            <div class="modal-footer border-0">
                <form action="{{ route('spots.comment.destroy', ['spot_id' => $spot->id, 'comment_id' => $comment->id]) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>