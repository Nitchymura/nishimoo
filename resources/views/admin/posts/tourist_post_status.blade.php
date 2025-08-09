@if(!$post['is_trashed'])
{{-- DEACTIVATE --}}
<div class="modal fade" id="deactivate-post{{ $post['id']}}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-eye-slash"></i> Deactivate Post</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to deactivate <strong>{{$post['title']}}</strong>?
         
                @if($post['main_image'])
                    <img src="{{$post['main_image']}}" alt="" class="img-md d-block">
                @else
                    <i class="fa-solid fa-image text-secondary image-sm align-middle"></i>
                @endif
                
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.deactivate', $post['id'])}}" method="post">
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
<div class="modal fade" id="activate-post{{$post['id']}}">
    <div class="modal-dialog">
        <div class="modal-content border-success">
            <div class="modal-header border-success">
                <h3 class="h3 text-success"><i class="fa-solid fa-eye"></i> Activate Post</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to activate <strong>{{$post['title']}}</strong> ?
                @if($post['main_image'])
                    <img src="{{$post['main_image']}}" alt="" class="img-md d-block">
                @else
                    <i class="fa-solid fa-image text-secondary image-sm align-middle"></i>
                @endif
                
            </div>
            <div class="modal-footer border-0">
                <form action="{{route('admin.posts.activate', $post['id'])}}" method="post">
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