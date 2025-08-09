<!-- Social Media -->
<div class="mb-3">
    <label class="form-label d-inline">Social Media</label>

    <div class="row mb-2">
        <!-- Instagram -->
        <div class="col-md-6 mb-2 mb-md-0">
            <div class="d-flex align-items-center">
                <div class="pe-0">
                    <i class="fa-brands fa-square-instagram fa-2x"></i>
                </div>
                <div class="flex-grow-1 ps-2">
                    <input type="text" name="instagram" class="form-control" placeholder="Instagram URL" value="{{ old('instagram', $business->instagram ?? '') }}">
                </div>
            </div>
        </div>
        <!-- Facebook -->
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="pe-0">
                    <i class="fa-brands fa-square-facebook fa-2x"></i>
                </div>
                <div class="flex-grow-1 ps-2">
                    <input type="text" name="facebook" class="form-control" placeholder="Facebook URL" value="{{ old('facebook', $business->facebook ?? '') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <!-- Twitter/X -->
        <div class="col-md-6 mb-2 mb-md-0">
            <div class="d-flex align-items-center">
                <div class="pe-0">
                    <i class="fa-brands fa-square-x-twitter fa-2x"></i>
                </div>
                <div class="flex-grow-1 ps-2">
                    <input type="text" name="x" class="form-control" placeholder="X/Twitter URL" value="{{ old('x', $business->x ?? '') }}">
                </div>
            </div>
        </div>
        <!-- TikTok -->
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="pe-0">
                    <i class="fa-brands fa-tiktok fa-2x"></i>
                </div>
                <div class="flex-grow-1 ps-2">
                    <input type="text" name="tiktok" class="form-control" placeholder="TikTok URL" value="{{ old('tiktok', $business->tiktok ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>