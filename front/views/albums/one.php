<?php self::layout('private', ['ariane' => $ariane, 'album' => $album]); ?>

<?php if($authors = $album->pics()): ?>
<ul class="grid pictures row">
    <?php foreach($authors as $author => $pics): ?>
        <?php foreach($pics as $pic): ?>
        <li class="col-xs-6 col-sm-3 col-md-2">
            <a href="<?= self::url($pic->url) ?>">
                <div class="picture">
                    <div class="bg" style="background-image: url('<?= self::url($pic->cacheurl) ?>')"></div>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty">Aucune photo pour le moment !</p>
<?php endif; ?>