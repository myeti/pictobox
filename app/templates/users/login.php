<?php self::layout('public', ['class' => 'login']) ?>

<div id="login">

    <form action="<?= self::url('/login/auth') ?>" method="post" data-ajax="<?= $redirect ?>">

        <label>
            <span class="fa fa-user"></span>
            <input type="text" name="username" placeholder="Identifiant" pattern="[a-zA-Z]+" required autocomplete="off">
        </label>

        <label>
            <span class="fa fa-key"></span>
            <input type="password" name="password" placeholder="Mot de passe" pattern=".{5,}" required title="Doit contenir 5 caractère au minimum." >
        </label>

        <button type="submit" data-loading="fa-cog" data-ok="fa-ok">LOG IN</button>
    </form>

</div>