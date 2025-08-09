<label class="form-label">Weekly Schedule</label>
<div class="accordion mb-3" id="weekdayAccordion">
    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $index => $day)
        <div class="accordion-item">
            <div class="accordion-header" id="heading{{ $index }}">
                <div class="row">
                    <div class="col-2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                            {{ $day }} 
                        </button>
                    </div>
                    <div class="col-auto ms-auto me-5 my-auto">
                        <input type="checkbox" id="{{ Str::slug($day) }}_is_closed" name="business_hours[{{ $day }}][is_closed]" value="1"
                               {{ old('business_hours.'.$day.'.is_closed', 
                                  isset($businessHours[$day]['is_closed']) ? $businessHours[$day]['is_closed'] : 0) 
                                  == 1 ? 'checked' : '' }}>
                        <label class="form-check-label ms-2 align-self-end" for="{{ Str::slug($day) }}_is_closed">Closed</label>
                    </div>
                </div>
            </div>

            <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#weekdayAccordion">
                <div class="accordion-body">
                    <!-- 各曜日の入力フォーム -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="{{ strtolower($day) }}_opening_time" class="d-inline me-3">Opening time</label>
                            <input type="time" id="{{ strtolower($day) }}_opening_time" name="business_hours[{{ $day }}][opening_time]" class="form-control" 
                                   value="{{ old('business_hours.'.$day.'.opening_time', isset($businessHours[$day]['opening_time']) ? $businessHours[$day]['opening_time'] : '') }}">
                        </div>
                        <div class="col">
                            <label for="{{ strtolower($day) }}_closing_time" class="d-inline me-3">Closing time</label>
                            <input type="time" id="{{ strtolower($day) }}_closing_time" name="business_hours[{{ $day }}][closing_time]" class="form-control"
                                   value="{{ old('business_hours.'.$day.'.closing_time', isset($businessHours[$day]['closing_time']) ? $businessHours[$day]['closing_time'] : '') }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="{{ strtolower($day) }}_break_start" class="d-inline me-3">Break start</label>
                            <input type="time" id="{{ strtolower($day) }}_break_start" name="business_hours[{{ $day }}][break_start]" class="form-control"
                                   value="{{ old('business_hours.'.$day.'.break_start', isset($businessHours[$day]['break_start']) ? $businessHours[$day]['break_start'] : '') }}">
                        </div>
                        <div class="col">
                            <label for="{{ strtolower($day) }}_break_end" class="d-inline me-3">Break end</label>
                            <input type="time" id="{{ strtolower($day) }}_break_end" name="business_hours[{{ $day }}][break_end]" class="form-control"
                                   value="{{ old('business_hours.'.$day.'.break_end', isset($businessHours[$day]['break_end']) ? $businessHours[$day]['break_end'] : '') }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="{{ strtolower($day) }}_notice" class="d-inline me-3">Notes</label>
                            <input type="text" id="{{ strtolower($day) }}_notice" name="business_hours[{{ $day }}][notice]" class="form-control" 
                                   placeholder="Example: Last order 40 minutes before closing"
                                   value="{{ old('business_hours.'.$day.'.notice', isset($businessHours[$day]['notice']) ? $businessHours[$day]['notice'] : '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>