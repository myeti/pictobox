<div class="modal" id="feedback">
    <form action="<?= self::url('/user/feedback') ?>" method="post" data-ajax>

        <h4><?= text('modal.feedback.title') ?></h4>

        <?php if(isset($album)): ?>
        <input type="hidden" name="album" value="<?= $album->name ?>, <?= $album->date ?>">
        <?php endif; ?>

        <textarea name="message" cols="30" rows="10" placeholder="<?= text('modal.feedback.message') ?>" required></textarea>

        <button type="submit"><?= text('modal.feedback.submit') ?></button>

    </form>
</div>