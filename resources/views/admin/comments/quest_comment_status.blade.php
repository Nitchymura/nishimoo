@if(!$comment['is_trashed'])
{{-- DEACTIVATE --}}
<div class="modal fade" id="deactivate-comment{{ $comment['id'] }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-user-slash"></i> Deactivate Comment</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to deactivate this comment?
                {{-- @if($comment['user_avatar'])
                    <img src="{{$comment['user_avatar']}}" alt="" class="rounded-circle avatar-sm">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm align-middle"></i>
                @endif --}}
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.deactivate.quest.comment', $comment['id'])}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                </form>
            </div>
        </div>
    </div>
</div>

@else
{{-- ACTIVATE --}}
<div class="modal fade" id="activate-comment{{$comment['id']}}">
    <div class="modal-dialog">
        <div class="modal-content border-success">
            <div class="modal-header border-success">
                <h3 class="h3 text-success"><i class="fa-solid fa-user-check"></i> Activate Comment</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to activate this comment?
                {{-- @if($comment['user_avatar'])
                    <img src="{{$comment['user_avatar']}}" alt="" class="rounded-circle avatar-sm">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm align-middle"></i>
                @endif --}}
            </div>
            <div class="modal-footer border-0">
                <form action="{{route('admin.activate.quest.comment', $comment['id'])}}" method="post">
                    @csrf
                    @method('PATCH')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-success">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Activate</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endif