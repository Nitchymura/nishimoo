<div class="modal fade" id="deleteModal-{{ $comment->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-quest-red">
            <div class="modal-header border-0">
                <div class="h5 modal-title poppins-semibold">
                    <i class="fa-solid fa-circle-exclamation"></i> Delete Comment
                </div>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete this comment?</p>
                <p class="text-secondary">{{ $comment->content }}</p>
            </div>

            <div class="modal-footer border-0">
                <form action="{{ route('questcomment.delete', $comment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-red btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-red btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

