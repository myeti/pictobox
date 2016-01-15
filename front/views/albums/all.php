<?php self::layout('front') ?>

<?php if($years = $collection->years()): ?>
<ul>
    <?php foreach($years as $year => $subcollection): ?>
    <li>
        <a href="<?= self::url($year) ?>"><?= $year ?> <i>(<?= $subcollection->count() ?> albums)</i></a>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p>Il n'y a aucun album pour le moment, <a href="#" data-modal="#create-album">en ajouter un ?</a></p>
<?php endif; ?>
