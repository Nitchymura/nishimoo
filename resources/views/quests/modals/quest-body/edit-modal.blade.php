<!-- resources/views/quests/modals/edit-modal.blade.php -->

<div class="modal fade edit-questbody-modal" id="edit-questbody-{{ $questbody->id }}"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    aria-hidden="true">
    @php
    $existingImages = [];
    if (!empty($questbody->image)) {
        $decoded = json_decode($questbody->image, true);
        if (is_array($decoded)) {
            $existingImages = $decoded;
        }
    }
    @endphp
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-green border-4 p-3">
            <button type="button" class="btn-close position-absolute top-0 end-0" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-header border-0">
                <h4 class="h2 poppins-bold"><i class="fa-solid fa-pen-to-square"></i> Edit Spot</h3>
            </div>
            <div class="modal-body">
                <form action="{{ route('questbody.update', $questbody->id) }}" method="POST" enctype="multipart/form-data" class="px-3 py-2" id="edit-form-{{ $questbody->id }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label for="day_number_{{ $questbody->id }}" class="form-label">Choose the day</label>
                        </div>
                        <div class="col-md-8">
                            <select name="day_number" id="day_number_{{ $questbody->id }}" class="form-select input-box w-100">
                                @foreach ($dayList as $day)
                                    <option value="{{ $day['number'] }}" {{ $questbody->day_number == $day['number'] ? 'selected' : '' }}>
                                        @if(Auth::user()->role_id == 2)
                                            {{ ordinalSuffix($day['number']) }}
                                        @else
                                            Day {{ $day['number'] }} ({{ $day['date'] }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if (Auth::user()->role_id === 2)
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="business_title_{{ $questbody->id }}" class="form-label">Place Name</label>
                            </div>
                            <div class="col-md-8">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-4">
                                            <label for="business_title_{{ $questbody->id }}" class="form-label">Place Name</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input 
                                                type="text" 
                                                name="business_title" 
                                                id="business_title_{{ $questbody->id }}" 
                                                class="input-box form-control w-100 mb-0" 
                                                value="{{ old('business_title', $questbody->business_title) }}"
                                                placeholder="Souvenir Shop"
                                            >
                                            @error('business_title')
                                                <p class="text-danger small">{{ $message }}</p>
                                            @else
                                                <p id="business-title-error-{{ $questbody->id }}" class="text-danger small d-none">Please enter a place name</p>
                                            @enderror
                                        </div>
                                    </div>
                            </div>
                        </div>
                     @endif
                    <div class="mb-3">
                        <label for="introduction_{{ $questbody->id }}" class="form-label" id="introduction">Description</label>
                        <textarea name="introduction" id="introduction_{{ $questbody->id }}" class="form-control text-area" rows="4">{{ old('introduction' ,$questbody->introduction) }} </textarea>
                        <p id="intro-error-{{ $questbody->id }}" class="text-danger small d-none">Please enter a description</p>
                    </div>

                    <div class="row pb-3 pe-0">
                        <label for="edit-image-{{ $questbody->id }}" class="form-label p-2">Photos</label>
                        <div class="col-12">
                            <input type="file" name="images[]" id="edit-image-{{ $questbody->id }}" class="custom-file-input form-control w-100" multiple>
                            <p id="image-error-{{ $questbody->id }}" class="text-danger small d-none">Please upload at least one image</p>
                            <div class="hidden-image-inputs" id="hidden-inputs-{{ $questbody->id }}">
                            </div>
                        </div>
                        <script>
                            window.questBodyImages = window.questBodyImages || {};
                            window.questBodyImages[{{ $questbody->id }}] = @json($existingImages);
                        </script>
                        
                        {{-- <div class="col-3 pe-0">
                            <button type="button" class="btn btn-green custom-file-label w-100" id="upload-btn-{{ $questbody->id }}">
                                <i class="fa-solid fa-plus icon-xs d-inline"></i> Photo
                            </button>
                        </div> --}}
                        <div class="row">
                            <p class="mt-0 xsmall">
                                Acceptable formats: jpeg, jpg, png, gif only.<br>Max size is 1048 KB
                            </p>
                        </div>
                        <div id="uploaded-file-names-{{ $questbody->id }}" class="mt-2"></div>

                    </div>

                    <div class="d-flex flex-column-reverse px-4">
                        <div class="ps-3 text-end">
                            <!-- Agendaスイッチ、ボタン -->
                        
                            {{-- ✅ ラベルとスイッチを左右に並べる --}}
                            <div class="d-flex justify-content-end mb-3">
                                <div class="form-check form-switch d-flex flex-row-reverse align-items-center">
                                    <label class="form-check-label ms-2" for="agendaSwitch">Add to Agenda</label>
                                    <input class="form-check-input" type="checkbox" id="agendaSwitch" name="is_agenda" value="1" {{ $questbody->is_agenda ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        {{-- 画像が存在する場合のみ表示 --}}
                        @if (count($existingImages))
                            <div class="existing-image-wrapper row flex-nowrap overflow-auto mt-2" id="image-list-wrapper-{{ $questbody->id }}">
                                @foreach ($existingImages as $img)
                                    <div class="col-auto text-center me-2 position-relative">
                                        <img src="{{ asset($img) }}" alt="画像" class="img-thumbnail" style="width: 150px; height: auto;">
                                        <button type="button"
                                            class="remove-existing-image btn btn-sm btn-red position-absolute bottom-0 end-0 m-1 text-white"
                                            data-img="{{ $img }}"
                                            data-id="{{ $questbody->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                                {{-- ←ここに新しい画像をJSで追加 --}}
                            </div>
                        @endif
                        <script>
                            window.questbodyImageDeleteUrl = "{{ route('questbody.image.delete') }}";
                        </script>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-green" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-green update-btn" data-id="{{ $questbody->id }}">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/quest/quest-body/edit-questbody-modal.js'])

