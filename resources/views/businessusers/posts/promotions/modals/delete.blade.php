<div class="modal fade" id="delete-business_promotion{{$business_promotion->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h4 text-danger"><i class="fa-regular fa-trash-can"></i> Delete Promotion</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Promotion?</p>
            </div>
            <div class="modal-footer border-0">
                <form action="{{route('promotions.delete', $business_promotion->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-red">CANCEL</button>
                    <button type="submit" class="btn btn-sm btn-red">DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>