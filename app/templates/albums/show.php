<?php self::layout('private', ['ariane' => $ariane]); ?>


<?php self::rewrite('css') ?>
    <link href="<?= self::url('/css/libs/dropzone.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/libs/photoswipe.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/libs/photoswipe/default-skin.min.css') ?>" rel="stylesheet">
<?php self::end() ?>


<?= self::render('_modals/photoswipe', ['album' => $album, 'ctx' => $ctx]) ?>


<?php self::rewrite('ariane') ?>
    <span>/</span> <a href="<?= self::url($album->url) ?>" class="album"><?= $album->name ?></a>
<?php self::end() ?>


<?php self::rewrite('menu') ?>
    <?php if($ctx->user->isUploader()): ?>
        <?= self::render('_menu/upload-picture', ['album' => $album]) ?>
        <?= self::render('_menu/edit-album', ['album' => $album]) ?>
    <?php endif; ?>
    <?= self::render('_menu/download-album', ['album' => $album]) ?>
<?php self::end() ?>


<?php if($authors = $album->authors()): ?>
    <ul class="grid pictures row">
    <?php foreach($authors as $author): ?>
        <li class="author" id="<?= strtolower($author->name) ?>">
            <div class="item">
                <div class="image b-lazy" data-src="<?= self::url($author->random()->url_small) ?>"></div>
                <h2 class="title"><?= text('view.pics.author', ['author' => $author->name]) ?></h2>
            </div>
        </li>
        <?php foreach($author->pics() as $pic): ?>
        <li>
            <a href="<?= self::url($pic->url) ?>" data-thumbnail="<?= $pic->width() ?>x<?= $pic->height() ?>"
               data-album="<?= $album->name ?>" data-author="<?= $author->name ?>" data-filename="<?= $pic->filename ?>"
               data-caption="<?= $album->name ?>, <?= $album->date ?>, <?= text('view.pic.author', ['author' => $author->name]) ?>">
                <div class="item">
                    <div class="image b-lazy" data-src="<?= self::url($pic->url_small) ?>"></div>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>
    <div class="clearfix"></div>
<?php else: ?>
<p class="empty"><?= text('view.pics.none') ?></p>
<?php endif; ?>


<?php self::rewrite('js') ?>
<script src="<?= self::url('/js/libs/dropzone.min.js') ?>"></script>
<script src="<?= self::url('/js/libs/photoswipe.min.js') ?>"></script>
<script src="<?= self::url('/js/libs/photoswipe-ui-default.min.js') ?>"></script>
<script src="<?= self::url('/js/private-album.js') ?>"></script>
<?php self::end() ?>
