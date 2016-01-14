<?php self::layout('front') ?>

<h1><?= $album->name ?> - <i><?= $album->day ?> <?= $album->monthName ?> <?= $album->year ?></i></h1>
<ul>
    <?php foreach($album->pics() as $pic): ?>
    <li>
        <img src="<?= $pic->path ?>" alt="">
    </li>
    <?php endforeach; ?>
</ul>
