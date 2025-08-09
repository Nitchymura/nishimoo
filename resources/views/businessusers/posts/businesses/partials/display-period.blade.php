<div class="mb-4 p-4 border rounded bg-light">
    <h4 class="form-label d-inline">Display Period</h4>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="display_start" class="d-inline me-3">Start date</label>
            <input type="date" 
                   id="display_start" 
                   name="display_start" 
                   class="form-control @error('display_start') is-invalid @enderror"
                   value="{{ old('display_start', isset($business) && !empty($business->display_start) ? \Carbon\Carbon::parse($business->display_start)->format('Y-m-d') : '') }}">
            @error('display_start')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="display_end" class="d-inline me-3">End date</label>
            <input type="date" 
                   id="display_end" 
                   name="display_end" 
                   class="form-control @error('display_end') is-invalid @enderror"
                   value="{{ old('display_end', isset($business) && !empty($business->display_end) ? \Carbon\Carbon::parse($business->display_end)->format('Y-m-d') : '') }}">
            @error('display_end')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="text-danger" style="color:#D24848; line-height: 1.2;">
        <p class="mb-1">* No setting start date will be "no publish to user."</p>
        <p class="mb-1">* No setting start and end date will be "no publish to user."</p>
        <p class="mb-0">* Setting start and no end date will be "no limit date to publish to user."</p>
    </div>
</div>
