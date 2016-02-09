<div class="modal" id="profile">
    <form action="<?= self::url('/login/edit') ?>" method="post" data-ajax>

        <h4><?= $user->username ?></h4>

        <?php if($user->isAdmin()): ?>
        <select name="id" id="user-switch">
            <?php foreach($user::fetch() as $other): ?>
            <option value="<?= $other->id ?>" data-username="<?= $other->username ?>" data-email="<?= $other->email ?>" data-rank="<?= $other->rank ?>" <?= $other->id == $user->id ? 'selected' : null ?> >
                <?= $other->username ?>
            </option>
            <?php endforeach; ?>
            <option value="0" data-username="" data-email="" data-rank="1">+ Nouvel utilisateur</option>
        </select>
        <?php endif; ?>

        <?php if($user->isAdmin()): ?>
        <input type="text" id="user-name" name="username" value="<?= $user->username ?>" placeholder="Identifiant" required>
        <input type="number" id="user-rank" min="0" max="9" name="rank" value="<?= $user->rank ?>" placeholder="Niveau d'accès" required>
        <?php endif; ?>

        <input type="email" id="user-email" name="email" value="<?= $user->email ?>" placeholder="Adresse email" required>
        <input type="password" name="password" placeholder="Mot de passe" pattern="^.{5,}$" title="5 caractères minimum">

        <button type="submit" data-loading="fa-cog" data-ok="fa-ok">Sauvegarder</button>

        <button type="reset" class="cancel">
            <span class="fa fa-close"></span>
        </button>

    </form>
</div>