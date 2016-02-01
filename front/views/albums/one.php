<?php self::layout('private', ['ariane' => $ariane, 'album' => $album, 'user' => $self->access->user]); ?>

<?php if($authors = $album->pics()): ?>
    <?php $vowels = ['E','U','I','O','A','Y']; ?>
    <?php foreach($authors as $author => $pics): ?>
    <?php $of = 'd' .  (in_array($author[0], $vowels) ? '\'' : 'e '); ?>
    <ul class="grid pictures row" id="<?= strtolower($author) ?>" data-title="Photos <?= $of ?><?= $author ?>">
        <?php foreach($pics as $pic): ?>
        <li>
            <a href="<?= self::url($pic->url) ?>">
                <div class="item">
                    <div class="image" style="background-image: url('<?= self::url($pic->cacheurl) ?>')"></div>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <div class="clearfix"></div>
    <?php endforeach; ?>
<?php else: ?>
<p class="empty">Aucune photo pour le moment !</p>
<?php endif; ?>