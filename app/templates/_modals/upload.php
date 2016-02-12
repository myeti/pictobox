<div class="modal" id="upload">

    <h4><?= text('modal.upload.title') ?></h4>

    <form action="<?= self::url($album->url, 'upload') ?>" method="post" enctype="multipart/form-data" class="dropzone">

        <div class="dz-message">
            <span class="fa fa-plus"></span> <?= text('modal.upload.accept') ?>
        </div>

        <div class="fallback">
            <input type="file" name="upload" accept="image/*" multiple>
        </div>

    </form>

</div>