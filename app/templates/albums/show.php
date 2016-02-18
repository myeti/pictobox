<?php self::layout('interface', ['ariane' => $ariane, 'album' => $album, 'user' => $ctx->user]); ?>

<?php if($authors = $album->authors()): ?>
    <ul class="grid pictures row">
    <?php foreach($authors as $author): ?>
        <li class="author" id="<?= strtolower($author->name) ?>">
            <div class="item">
                <div class="image b-lazy" data-src="<?= self::url($album->thumbnail($author)->cacheurl_small) ?>"></div>
                <h2 class="title"><?= text('pics.author', ['author' => $author->name]) ?></h2>
            </div>
        </li>
        <?php foreach($author->pics() as $pic): ?>
        <li>
            <a href="<?= self::url($pic->cacheurl) ?>" data-thumbnail="<?= $pic->width ?>x<?= $pic->height ?>"
               data-caption="<?= $album->name ?>, <?= $album->date ?>, <?= text('pic.author', ['author' => $author->name]) ?>">
                <div class="item">
                    <div class="image b-lazy" data-src="<?= self::url($pic->cacheurl_small) ?>"></div>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>
    <div class="clearfix"></div>
<?php else: ?>
<p class="empty"><?= text('pics.none') ?></p>
<?php endif; ?>
