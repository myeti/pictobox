<div class="modal" id="profile">
    <form action="<?= self::url('/user/edit') ?>" method="post" data-ajax>

        <h4><?= text('modal.profile.title', ['name' => $user->username]) ?></h4>

        <?php if($user->isAdmin()): ?>
        <select name="id" id="user-switch">
            <?php foreach($user::fetch() as $other): ?>
            <option value="<?= $other->id ?>" data-username="<?= $other->username ?>" data-email="<?= $other->email ?>" data-rank="<?= $other->rank ?>" <?= $other->id == $user->id ? 'selected' : null ?> >
                <?= $other->username ?>
            </option>
            <?php endforeach; ?>
            <option value="0" data-username="" data-email="" data-rank="1">+ <?= text('modal.profile.newuser') ?></option>
        </select>
        <?php endif; ?>

        <?php if($user->isAdmin()): ?>
        <input type="text" id="user-name" name="username" value="<?= $user->username ?>" placeholder="<?= text('modal.profile.username') ?>" required>
        <input type="number" id="user-rank" min="0" max="9" name="rank" value="<?= $user->rank ?>" placeholder="<?= text('modal.profile.rank') ?>" required>
        <?php endif; ?>

        <input type="email" id="user-email" name="email" value="<?= $user->email ?>" placeholder="<?= text('modal.profile.email') ?>" required>
        <input type="password" name="password" placeholder="<?= text('modal.profile.password') ?>" pattern="^.{5,}$" title="<?= text('modal.profile.password.tooltip') ?>">

        <button type="submit"><?= text('modal.profile.submit') ?></button>

        <button type="reset" class="cancel">
            <span class="fa fa-close"></span>
        </button>

        <a href="<?= self::url('/logout') ?>" class="btn"><?= text('modal.profile.logout') ?></a>

    </form>
</div>