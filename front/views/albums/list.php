<?php self::layout('private', ['ariane' => $ariane]); ?>

<?php if($albums): ?>
<ul class="grid albums row">
    <?php foreach($albums as $album): ?>
    <li class="col-xs-12 col-sm-6 col-md-4">
        <a href="<?= self::url($album->url) ?>">
            <div class="album" style="background-image: url(<?= self::url($album->thumbnail()->path) ?>)">
                <h2 class="title"><?= $album->name ?></h2>
                <em class="date"><?= $album->date ?></em>
            </div>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="empty">Aucun album pour le moment !</p>
<?php endif; ?>