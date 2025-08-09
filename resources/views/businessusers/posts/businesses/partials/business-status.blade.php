<div class="row mb-3">
    <div class="col">
        <label class="h4">Business Status</label>
        <div class="d-flex flex-wrap gap-4 py-2">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status-open" value="Open" {{ old('status', $business->status ?? '') == 'Open' ? 'checked' : '' }}>
                <label class="form-check-label" for="status-open">
                    Open
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status-coming-soon" value="Coming Soon" {{ old('status', $business->status ?? '') == 'Coming Soon' ? 'checked' : '' }}>
                <label class="form-check-label" for="status-coming-soon">
                    Coming Soon
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status-unknown" value="Unknown" {{ old('status', $business->status ?? '') == 'Unknown' ? 'checked' : '' }}>
                <label class="form-check-label" for="status-unknown">
                    Unknown
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status-closed" value="Closed" {{ old('status', $business->status ?? '') == 'Closed' ? 'checked' : '' }}>
                <label class="form-check-label" for="status-closed">
                    Closed
                </label>
            </div>
        </div>
        @error('status')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-2">
    <label class="d-inline h4">Business Event period</label> 
    <div class="row mt-2">
        <div class="col">
            <label for="term_start" class="form-label d-inline">Start date</label>
            <input type="date" name="term_start" id="term_start" class="form-control @error('term_start') is-invalid @enderror" value="{{ old('term_start', $business->term_start ?? '') }}">
            @error('term_start')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label for="term_end" class="form-label d-inline">End date</label>
            <input type="date" name="term_end" id="term_end" class="form-control @error('term_end') is-invalid @enderror" value="{{ old('term_end', $business->term_end ?? '') }}">
            @error('term_end')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<!-- Special notes -->
<div class="mb-3">
    <label for="sp_notes" class="form-label d-inline">Special notes</label>
    <textarea name="sp_notes" id="sp_notes" class="form-control" rows="3">{{ old('sp_notes', $business->sp_notes ?? '') }}</textarea>
</div>