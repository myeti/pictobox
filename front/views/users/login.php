<?php self::layout('public') ?>

<div id="login">

    <?php if($error): ?>
    <p class="bg-danger text-danger"><?= $error ?></p>
    <?php endif; ?>

    <form action="<?= self::url('/login/auth') ?>" method="post">
        <input type="hidden" name="referer" value="<?= $referer ?>">
        <input type="text" name="username" placeholder="Identifiant" pattern="[a-zA-Z]+" required autocomplete="off">
        <input type="password" name="password" placeholder="Mot de passe" pattern=".{5,}" required title="Doit contenir 5 caractère au minimum." >
        <button type="submit">LOG IN</button>
    </form>

</div>