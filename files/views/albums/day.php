<?php self::layout('front') ?>

<h1><a href="<?= self::url($collection->year) ?>"><?= $collection->year ?></a></h1>
<ul>
    <li>
        <a href="<?= self::url($collection->year, $collection->month) ?>">
            <?= $collection->monthName ?> <?= $collection->year ?> <i>(<?= $collection->count() ?> albums)</i>
        </a>
        <ul>
            <?php foreach($collection->albums as $album): ?>
                <li>
                    <a href="<?= self::url($album->url()) ?>"><?= $album->day ?> <?= $collection->monthName ?> - <?= $album->name ?> <i>(<?= $album->count() ?> photos)</i></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>
