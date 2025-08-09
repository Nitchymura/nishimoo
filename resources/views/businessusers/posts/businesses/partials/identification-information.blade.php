{{-- Identification Information --}}
<div class="mb-4 p-4 border rounded bg-light">
    <h4 class="form-label d-inline">Identification Information</h4>
    <div class="d-inline me-3">
        <p class="text-muted">
            <span style="color: #D24848;">*</span>
            Please provide official Location of business or Event identification information.<br>
            This helps verify your business or event and build trust with customers.
        </p>
    </div>
    <input type="text"
        id="identification_number"
        name="identification_number"
        class="form-control @error('identification_number') is-invalid @enderror"
        value="{{ old('identification_number', isset($business) && $business->identification_number ? $business->identification_number : '') }}">
    @error('identification_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>