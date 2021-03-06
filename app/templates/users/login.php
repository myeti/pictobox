<?php self::layout('public', ['class' => 'login']) ?>

<div id="login">

    <form action="<?= self::url('/user/auth') ?>" method="post" data-ajax="<?= $redirect ?>">

        <label>
            <span class="fa fa-user"></span>
            <input type="text" name="username" placeholder="<?= text('view.login.username') ?>" required autocomplete="off">
        </label>

        <label>
            <span class="fa fa-key"></span>
            <input type="password" name="password" placeholder="<?= text('view.login.password') ?>" required>
        </label>

        <button type="submit" data-loading="fa-cog" data-ok="fa-ok"><?= text('view.login.submit') ?></button>
    </form>

</div>