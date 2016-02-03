<?php self::layout('private', ['ariane' => $ariane, 'album' => $album, 'user' => $self->access->user]); ?>

<?php if($authors = $album->pics()): ?>
    <ul class="grid pictures row">
    <?php $vowels = ['E','U','I','O','A','Y']; ?>
    <?php foreach($authors as $author => $pics): ?>
        <li>
            <div class="item author" id="<?= strtolower($author) ?>">
                <div class="image" style="background-image: url('<?= self::url($album->thumbnail($author)->cacheurl) ?>')"></div>
                <h2 class="title">
                    <?php if(in_array($author[0], $vowels)): ?>
                        Photos <br> d'<?= $author ?>
                    <?php else: ?>
                        Photos <br> de <?= $author ?>
                    <?php endif; ?>
                </h2>
            </div>
        </li>
        <?php foreach($pics as $pic): ?>
        <li>
            <a href="<?= self::url($pic->url) ?>" data-slideshow>
                <div class="item">
                    <div class="image" style="background-image: url('<?= self::url($pic->cacheurl) ?>')"></div>
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
