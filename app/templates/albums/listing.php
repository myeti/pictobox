<?php self::layout('interface', ['ariane' => $ariane, 'user' => $ctx->user()]); ?>

<?php if($albums): ?>
<ul class="grid albums row">
    <?php foreach($albums as $album): ?>
    <li>
        <a href="<?= self::url($album->url) ?>">
            <div class="item">
                <div class="image b-lazy" data-src="<?= self::url($album->thumbnail()->cacheurl_small) ?>"></div>
                <h2 class="title"><?= $album->name ?></h2>
                <em class="info"><?= $album->date ?></em>
            </div>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty"><?= text('album.none') ?></p>
<?php endif; ?>