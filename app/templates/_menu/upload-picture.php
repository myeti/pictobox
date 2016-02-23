<li>
    <a data-panel="#upload" href="#">
        <span class="fa fa-image"></span> <?= text('view.menu.upload') ?>
    </a>
    <div class="panel" id="upload">
        <h4><?= text('view.panel.upload.title') ?></h4>
        <form action="<?= self::url($album->url, 'upload') ?>" method="post" enctype="multipart/form-data" class="dropzone">

            <div class="dz-message">
                <span class="fa fa-plus"></span> <?= text('view.panel.upload.accept') ?>
            </div>

            <div class="fallback">
                <input type="file" name="file" accept="image/*" multiple>
            </div>

        </form>
    </div>
</li>