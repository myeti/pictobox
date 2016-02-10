<?php self::layout('interface', ['ariane' => $ariane, 'album' => $album, 'user' => $self->access->user]); ?>

<?php if($authors = $album->authors()): ?>
    <ul class="grid pictures row">
    <?php $vowels = ['E','U','I','O','A','Y']; ?>
    <?php foreach($authors as $author): ?>
        <li>
            <div class="item author" id="<?= strtolower($author->name) ?>">
                <h2 class="title">
                    <?php if(in_array($author->name[0], $vowels)): ?>
                        Photos <br> d'<?= $author->name ?>
                    <?php else: ?>
                        Photos <br> de <?= $author->name ?>
                    <?php endif; ?>
                </h2>
            </div>
        </li>
        <?php foreach($author->pics() as $pic): ?>
        <li>
            <a href="<?= self::url($pic->cacheurl) ?>" data-thumbnail="<?= $pic->width ?>x<?= $pic->height ?>">
                <div class="item">
                    <div class="image" style="background-image: url('<?= self::url($pic->cacheurl_small) ?>')"></div>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>
    <div class="clearfix"></div>
<?php else: ?>
<p class="empty">Aucune photo pour le moment !</p>
<?php endif; ?>
