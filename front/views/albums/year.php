<?php self::layout('front') ?>

<h1><a href="<?= self::url($collection->year) ?>"><?= $collection->year ?></a></h1>
<ul>
    <?php foreach($collection->months() as $month => $list): ?>
    <li>
        <a href="<?= self::url($collection->year, $month) ?>"><?= $list->monthName ?> <?= $year ?> <i>(<?= $list->count() ?> albums)</i></a>
        <ul>
            <?php foreach($list->albums as $album): ?>
            <li>
                <a href="<?= self::url($album->url()) ?>"><?= $album->day ?> <?= $list->monthName ?> - <?= $album->name ?> <i>(<?= $album->count() ?> photos)</i></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </li>
    <?php endforeach; ?>
</ul>
