<?php self::layout('email'); ?>

<p><?= text('email.newest.body', ['username' => $user->username]) ?></p>

<ul>
    <?php foreach($albums as $album): ?>
    <li>
        <a href="<?= self::url($album->url) ?>">
            <em><?= $album->date ?></em> - <?= $album->name ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
