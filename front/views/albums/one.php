<?php self::layout('private', ['ariane' => $ariane, 'album' => $album, 'user' => $self->access->user]); ?>

<?php if($authors = $album->pics()): ?>
<ul class="grid pictures row">
    <?php foreach($authors as $author => $pics): ?>
        <?php foreach($pics as $pic): ?>
        <li>
            <a href="<?= self::url($pic->url) ?>">
                <div class="item">
                    <div class="image" style="background-image: url('<?= self::url($pic->cacheurl) ?>')"></div>
                    <em class="info">Par <?= $author ?></em>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty">Aucune photo pour le moment !</p>
<?php endif; ?>