<div class="modal" id="upload-pics">
    <h4>Upload</h4>
    <form action="<?= self::url($album->url, 'upload') ?>" method="post" enctype="multipart/form-data" class="dropzone">
        <div class="dz-message">
            <span class="fa fa-plus"></span>
            Clic / Drag'n'drop
        </div>
        <div class="fallback">
            <input type="file" name="upload" accept="image/*" multiple>
        </div>
    </form>
</div>