<!-- Submission Buttons -->
<div class="row">
    <div class="row mt-3 justify-content-center">
        <div class="col-8">
            <button type="submit" class="btn btn-green w-100 mb-2">SAVE</button>
            {{-- <div class="form-check">
                <input type="checkbox" 
                       class="form-check-input mb-2" 
                       name="official_certification" 
                       id="official_certification" 
                       value="2"
                       {{ old('official_certification', isset($business) && $business->official_certification == 2 ? 'checked' : '') }}>
                <label for="official_certification">Apply for Official certification badge</label>
            </div> --}}
        </div>
        {{-- <div class="col-2"></div>
        <div class="col-4">
            <a href="{{ route('profile.header', Auth::user()->id) }}" class="btn btn-red w-100">CANCEL</a>
        </div> --}}
    </div>
</div>