{{-- EDIT --}}
<div class="modal fade" id="edit-detail{{$detail->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-warning">
            <div class="modal-header border-warning">
                <h3 class="h3 text-dark"><i class="fa-regular fa-pen-to-square"></i> Edit Detail</h3>
            </div>
            <form action="{{ route('admin.details.update', $detail->id) }}" method="post">
                @csrf
                @method('PATCH')
            
                <div class="modal-body text-dark">
                    {{-- nameの編集 --}}
                    <input type="text" name="info_name{{ $detail->id }}" value="{{ old('info_name'.$detail->id, $detail->name) }}" class="form-control mb-2">
                    @error('info_name'.$detail->id)
                        <p class="mb-0 text-danger small">{{ $message }}</p>
                    @enderror
            
                    {{-- categoryの選択 --}}
                    <select name="business_info_category_id" class="form-select mt-2">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('business_info_category_id', $detail->business_info_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_info_category_id')
                        <p class="mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                </div>
            
                <div class="modal-footer border-0">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-warning">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning">Update</button>
                </div>
            </form>
                     
        </div>
    </div>
</div>

{{-- DELETE --}}
<div class="modal fade" id="delete-detail{{$detail->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-trash-can"></i> Delete Item</h3>
            </div>
            <div class="modal-body text-dark text-start">
                <div>   
                    Are you sure you want to delete <span class="fw-bold">{{$detail->name}}</span>?
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.details.delete', $detail->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>