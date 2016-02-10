<?php self::layout('interface', ['ariane' => $ariane, 'user' => $self->access->user]); ?>

<?php if($albums): ?>
<ul class="grid albums row">
    <?php foreach($albums as $album): ?>
    <li>
        <a href="<?= self::url($album->url) ?>">
            <div class="item">
                <div class="image" style="background-image: url('<?= self::url($album->thumbnail()->cacheurl_small) ?>')"></div>
                <h2 class="title"><?= $album->name ?></h2>
                <em class="info"><?= $album->date ?></em>
            </div>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty">Aucun album pour le moment !</p>
<?php endif; ?>