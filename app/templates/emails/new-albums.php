<?php self::layout('email'); ?>

<p><?= $user->username ?>, de nouveaux albums ont été ajoutés :</p>

<ul>
    <?php foreach($albums as $album): ?>
    <li>
        <a href="<?= self::url($album->url) ?>">
            <em><?= $album->date ?></em> - <?= $album->name ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
