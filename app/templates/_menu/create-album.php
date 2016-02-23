<li>
    <a data-panel="#create-album" href="#">
        <span class="fa fa-plus"></span> <?= text('view.menu.create-album') ?>
    </a>
    <div class="panel" id="create-album">
        <form action="<?= self::url('/create') ?>" method="post" data-ajax>

            <h4><?= text('view.panel.create-album.title') ?></h4>

            <input type="text" id="album-name" name="name" placeholder="<?= text('view.panel.create-album.name') ?>" required>
            <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="<?= text('view.panel.create-album.date') ?>"
                   pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required>

            <button type="submit"><?= text('view.panel.create-album.submit') ?></button>

        </form>
    </div>
</li>