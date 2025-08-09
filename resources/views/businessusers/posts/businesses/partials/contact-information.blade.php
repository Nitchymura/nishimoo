<!-- Contact Information Form -->
<div class="row">
    <div class="col-12">
        <p class="form-label d-inline">(<span class="color-red fw-bold">**</span> Required items to apply for official certicifation)</p>
    </div>
    <!-- Business Email -->
    <div class="col-6 mb-3">
        <label for="email" class="form-label">
            Business email<span class="text-danger">**</span>
        </label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $business->email ?? '') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Official Website -->
    <div class="col-6 mb-3">
        <label for="website_url" class="form-label">Official website URL</label>
        <input type="text" name="website_url" id="website_url" class="form-control @error('website_url') is-invalid @enderror" value="{{ old('website_url', $business->website_url ?? '') }}">
        @error('website_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <!-- Zip Code -->
    <div class="col-6 mb-3">
        <label for="zip" class="form-label">Zip Code<span class="text-danger">**</span></label>
        <input type="text" name="zip" id="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip', $business->zip ?? '') }}">
        @error('zip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Phone Number -->
    <div class="col-6 mb-3">
        <label for="phonenumber" class="form-label">
            Phone number<span class="text-danger">**</span>
        </label>
        <input type="tel" id="phonenumber" name="phonenumber" class="form-control @error('phonenumber') is-invalid @enderror" placeholder="+ Country code and phone number" value="{{ old('phonenumber', $business->phonenumber ?? '') }}">
        @error('phonenumber')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col mb-3">
        <label for="address_1" class="form-label">Address 1<span class="text-danger">**</span></label>
        <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $business->address_1 ?? '') }}">
        @error('address_1')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col mb-3">
        <label for="address_2" class="form-label">Address 2</label>
        <input type="text" name="address_2" id="address_2" class="form-control" value="{{ old('address_2', $business->address_2 ?? '') }}">
    </div>
</div>