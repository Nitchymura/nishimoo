<div class="form-photo-container">
    <div class="photo-upload-group">
        <label for="spot-images" class="form-label">Photos</label>
        <div class="upload-controls">
            <input type="file" name="spot-images" id="spot-images" class="custom-file-input" multiple>
            <button class="btn btn-green custom-file-label" id="upload-btn">
                <i class="fa-solid fa-plus icon-xs"></i>Photo
            </button>
        </div>
        <p class="xsmall">
            Acceptable formats: jpeg, jpg, png, gif only <br>
            Max file size is 1048 KB
        </p>
    </div>
    
    <!-- 📌 アップロードした画像のファイル名を表示するエリア -->
    <div id="uploaded-file-names"></div>
</div>