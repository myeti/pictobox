<div class="modal" id="feedback">
    <form action="<?= self::url('/user/feedback') ?>" method="post" data-ajax>

        <h4><?= text('modal.feedback.title') ?></h4>

        <textarea name="message" cols="30" rows="10" placeholder="<?= text('modal.feedback.message') ?>" required></textarea>

        <button type="submit"><?= text('modal.feedback.submit') ?></button>

        <button type="reset" class="cancel">
            <span class="fa fa-close"></span>
        </button>

    </form>
</div>