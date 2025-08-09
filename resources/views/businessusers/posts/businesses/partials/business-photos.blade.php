{{-- Business/Event photos --}}
<div class="mb-4 rounded">
    <h4 class=" d-inline">Business/Event photos</h4>
    <div class="row">
        @for ($i = 1; $i <= 3; $i++)
        @php
            // 編集時には既存の写真を取得
            $existingPhoto = isset($business) && $business->photos 
                ? $business->photos->firstWhere('priority', $i) 
                : null;
        @endphp
        <div class="col-md-4 mb-3">
            <div class="position-relative">
                <div class="photo-preview" id="preview_{{ $i }}">
                    {{-- <label class="form-label d-block">Photo {{ $i }}</label> --}}

                    {{-- 既存の画像がある場合は表示 --}}
                    @if($existingPhoto && $existingPhoto->image)
                        <img src="{{ $existingPhoto->image }}" alt="Photo {{ $i }}" id="preview_img_{{ $i }}" class="img-lg img-thumbnail mb-2">

                        <button type="button"
                            class="btn btn-red delete-photo-btn"
                            data-photo-id="{{ $existingPhoto->id }}"
                            data-preview-index="{{ $i }}">
                            <i class="fa-solid fa-trash-can py-1 px-1"></i>
                        </button>

                    @else
                        <div class="mb-2" id="placeholder_{{ $i }}">
                            <i class="fa-solid fa-image text-secondary icon-xxl fa-3x mb-2"></i>
                        </div>
                    @endif

                    <input type="file"
                        id="photo_{{ $i }}"
                        name="photos[{{ $i }}]"
                        class="form-control photo-input @error('photos.' . $i) is-invalid @enderror"
                        accept="image/*">
                    <input type="hidden" name="priorities[{{ $i }}]" value="{{ $i }}">
                    <input type="hidden" name="existing_photos[{{ $i }}]" value="{{ $existingPhoto ? $existingPhoto->id : '' }}">
                    <input type="hidden" name="delete_photos[{{ $i }}]" id="delete_photo_{{ $i }}" value="0">


                    @error('photos.'.$i)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>