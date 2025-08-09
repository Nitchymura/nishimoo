<label class="h4">Details</label>
<div class="accordion mb-3" id="detailsAccordion">
    @foreach ($categories as $index => $category)
        <div class="accordion-item">
            <div class="accordion-header" id="heading{{ $index }}">
                <div class="row">
                    <div class="col-2">
                        <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $index }}">
                            {{ $category->name }}
                        </button>
                    </div>
                    <div class="col-auto ms-auto me-5 my-auto">
                        {{-- 空欄のままでOK --}}
                    </div>
                </div>
            </div>

            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                aria-labelledby="heading{{ $index }}" data-bs-parent="#detailsAccordion">
                <div class="accordion-body">
                    <div class="row">
                        @foreach ($category->businessInfos as $info)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           id="info_{{ $info->id }}"
                                           name="business_info[]"
                                           value="{{ $info->id }}"
                                           {{ isset($business) && $business->hasBusinessInfoId($info->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="info_{{ $info->id }}">
                                        {{ $info->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
