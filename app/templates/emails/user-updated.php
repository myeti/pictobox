<?php self::layout('email'); ?>

<p><?= $user->username ?>, votre compte a été mis à jours :</p>

<ul>
    <li>Identifiant : <?= $user->username ?></li>
    <li>Adresse email : <?= $user->email ?></li>
    <?php if($password): ?>
    <li>Mot de passe : <?= $password ?></li>
    <?php endif; ?>
</ul>
