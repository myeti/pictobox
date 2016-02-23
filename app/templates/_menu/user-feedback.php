<li>
    <a data-panel="#feedback" href="#">
        <span class="fa fa-comment"></span> <?= text('view.menu.feedback') ?>
    </a>
    <div class="panel" id="feedback">
        <form action="<?= self::url('/user/feedback') ?>" method="post" data-ajax>

            <h4><?= text('view.panel.feedback.title') ?></h4>

            <?php if(isset($album)): ?>
                <input type="hidden" name="album" value="<?= $album->name ?>, <?= $album->date ?>">
            <?php endif; ?>

            <textarea name="message" cols="30" rows="10" placeholder="<?= text('view.panel.feedback.message') ?>" required></textarea>

            <button type="submit"><?= text('view.panel.feedback.submit') ?></button>

        </form>
    </div>
</li>