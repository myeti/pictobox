<li>
    <a data-panel="#profile" href="#">
        <span class="fa fa-user"></span> <?= text('view.menu.profile') ?>
    </a>
    <div class="panel" id="profile">
        <form action="<?= self::url('/user/edit') ?>" method="post" data-ajax>

            <h4><?= $user->username ?></h4>

            <?php if($user->isAdmin()): ?>

                <select name="id" id="user-switch">
                    <?php foreach($user::fetch() as $other): ?>
                        <option value="<?= $other->id ?>" data-username="<?= $other->username ?>" data-email="<?= $other->email ?>" data-rank="<?= $other->rank ?>" <?= $other->id == $user->id ? 'selected' : null ?> >
                            <?= $other->username ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="0" data-username="" data-email="" data-rank="1">+ <?= text('view.panel.profile.new') ?></option>
                </select>

                <input type="text" id="user-name" name="username" value="<?= $user->username ?>" placeholder="<?= text('view.panel.profile.username') ?>" required>
                <input type="number" id="user-rank" min="0" max="9" name="rank" value="<?= $user->rank ?>" placeholder="<?= text('view.panel.profile.rank') ?>" required>
            <?php endif; ?>

            <input type="email" id="user-email" name="email" value="<?= $user->email ?>" placeholder="<?= text('view.panel.profile.email') ?>" required>
            <input type="password" name="password" placeholder="<?= text('view.panel.profile.password') ?>" pattern="^.{5,}$" title="<?= text('view.panel.profile.password.tooltip') ?>">

            <button type="submit"><?= text('view.panel.profile.submit') ?></button>

        </form>
    </div>
</li>