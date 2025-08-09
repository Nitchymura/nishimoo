<div class="row">

    <div class="row pb-3 px-0 mx-0 mt-3">
        <label for="spot-images" class="form-label">Photos</label>
        <div class="col-9 ps-0">
            <input type="file" name="spot-images" id="spot-images" class="custom-file-input form-control ms-0" multiple>
        </div>
        <div class="col-3">
            <button class="btn btn-green custom-file-label w-100" id="upload-btn">
                <i class="fa-solid fa-plus icon-xs d-inline"></i>Photo
            </button>
        </div>
        <p class="mt-0 ps-0 xsmall">
            Acceptable formats: jpeg, jpg, png, gif only <br>
            Max fole size is 1048 KB
        </p>
    </div>
    
    <!-- 📌 アップロードした画像のファイル名を表示するエリア -->
    <div id="uploaded-file-names" class="mt-2"></div>
</div>