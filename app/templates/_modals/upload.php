<div class="modal" id="upload">

    <h4>Upload</h4>

    <form action="<?= self::url($album->url, 'upload') ?>" method="post" enctype="multipart/form-data" class="dropzone">

        <div class="dz-message">
            <span class="fa fa-plus"></span>
        </div>

        <div class="fallback">
            <input type="file" name="upload" accept="image/*" multiple>
        </div>

        <button type="reset" class="cancel">Fermer</button>

    </form>

</div>