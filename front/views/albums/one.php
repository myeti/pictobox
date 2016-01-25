<?php self::layout('private', ['ariane' => $ariane, 'album' => $album]); ?>

<?php if($authors = $album->pics()): ?>
<ul class="grid pictures row">
    <?php foreach($authors as $pics): ?>
        <?php foreach($pics as $pic): ?>
        <li class="col-xs-6 col-sm-3 col-md-2">
            <a href="<?= self::url($pic->url) ?>">
                <div class="picture" style="background-image: url(<?= self::url($pic->cache()) ?>)">
                    <img src="<?= self::url($pic->cache) ?>" alt="">
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty">Aucune photo pour le moment !</p>
<?php endif; ?>