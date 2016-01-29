<?php self::layout('public', ['class' => 'login']) ?>

<div id="login">

    <?php if($error): ?>
    <p class="bg-danger text-danger"><?= $error ?></p>
    <?php endif; ?>

    <form action="<?= self::url('/login/auth') ?>" method="post" data-ajax="<?= $redirect ?>">
        <input type="text" name="username" placeholder="Identifiant" pattern="[a-zA-Z]+" required autocomplete="off">
        <input type="password" name="password" placeholder="Mot de passe" pattern=".{5,}" required title="Doit contenir 5 caractère au minimum." >
        <button type="submit" data-loading="glyphicon-cog" data-ok="glyphicon-ok">LOG IN</button>
    </form>

</div>