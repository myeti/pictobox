<?php self::layout('email'); ?>

<p><?= text('email.profile.body', ['username' => $user->username]) ?></p>

<ul>
    <li><?= text('email.profile.username') ?> : <?= $user->username ?></li>
    <li><?= text('email.profile.email') ?> : <?= $user->email ?></li>
    <?php if($password): ?>
    <li><?= text('email.profile.password') ?> : <?= $password ?></li>
    <?php endif; ?>
</ul>
