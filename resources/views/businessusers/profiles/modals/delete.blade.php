<div class="modal fade" id="delete-profile{{ Auth::user()->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h4 text-danger"><i class="fa-regular fa-trash-can"></i> Delete Profile</h3>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure you want to delete this Profile?</p>
                <img src="{{Auth::user()->avatar}}" alt="" class="rounded-circle avatar-sm" mb-2>
                <span class="fw-bold text-dark">{{Auth::user()->name}}</span>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('profile.deactivate', Auth::user()->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-red">CANCEL</button>
                    <button type="submit" class="btn btn-sm btn-red">DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>