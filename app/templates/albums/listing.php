<?php self::layout('private', ['ariane' => $ariane]); ?>

<?php self::rewrite('menu') ?>
    <?php if($ctx->user->isUploader()): ?>
        <?= self::render('_menu/create-album') ?>
    <?php endif; ?>
<?php self::end() ?>

<?php if($albums): ?>
<ul class="grid albums row">
    <?php foreach($albums as $album): ?>
    <li>
        <a href="<?= self::url($album->url) ?>">
            <div class="item">
                <div class="image b-lazy" data-src="<?= self::url($album->random()->url_small) ?>"></div>
                <h2 class="title"><?= $album->name ?></h2>
                <em class="info"><?= $album->date ?></em>
            </div>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty"><?= text('view.albums.none') ?></p>
<?php endif; ?>