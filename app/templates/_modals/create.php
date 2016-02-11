<div class="modal" id="create">
    <form action="<?= self::url('/create') ?>" method="post" data-ajax>

        <h4><?= text('modal.create.title') ?></h4>

        <input type="text" id="album-name" name="name" placeholder="<?= text('modal.create.name') ?>" required>
        <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="<?= text('modal.create.date') ?>"
               pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required>

        <button type="submit"><?= text('modal.create.submit') ?></button>

        <button type="reset" class="cancel">
            <span class="fa fa-close"></span>
        </button>

    </form>
</div>