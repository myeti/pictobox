<?php self::layout('email'); ?>

<p><?= $user->username ?>, ton compte a été mis à jours :</p>

<ul>
    <li>Adresse email : <?= $user->email ?></li>
    <?php if($pwd): ?>
    <li>Mot de passe : <?= $pwd ?></li>
    <?php endif; ?>
</ul>
