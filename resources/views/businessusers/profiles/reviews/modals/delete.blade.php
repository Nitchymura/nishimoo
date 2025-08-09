<div class="modal fade" id="delete-review{{$business_comment->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h4 text-danger"><i class="fa-regular fa-trash-can"></i> Delete Review</h3>
            </div>
            <div class="modal-body">
                <p class="color-red">Are you sure you want to delete this Review?</p>

                <p class="text-dark">FROM: {{$business_comment->name}}</p>

                <p class="text-dark">SPOT: {{$business_comment->business->name}}</p>
            </div>
            <div class="modal-footer border-0">
                <form action="{{route('profile.delete.review', $business_comment->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-red">CANCEL</button>
                    <button type="submit" class="btn btn-sm btn-red">DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>