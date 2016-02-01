<div class="modal" id="user-details">
    <form action="<?= self::url('/login/edit') ?>" method="post" data-ajax>

        <h4><?= $user->username ?></h4>

        <?php if($user->rank >= 9): ?>
            <label for="user-id">Identifiant</label>
            <select name="id" id="user-id">
                <?php foreach(\App\Model\User::fetch() as $other): ?>
                    <option value="<?= $other->id ?>" data-email="<?= $other->email ?>" <?= $other->id == $user->id ? 'selected' : null ?> >
                        <?= $other->username ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <label for="user-email">Adresse email</label>
        <input type="email" id="user-email" name="email" value="<?= $self->access->user->email ?>">

        <label for="user-pwd">Mot de passe</label>
        <input type="password" id="user-pwd" name="password">

        <button type="submit" data-loading="fa-cog" data-ok="fa-ok">Sauvegarder</button>
        <button type="reset" class="cancel">Fermer</button>

    </form>
</div>