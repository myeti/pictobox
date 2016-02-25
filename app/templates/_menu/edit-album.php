<li>
    <a data-panel="#edit-album" href="#">
        <span class="fa fa-folder-open-o"></span> <?= text('view.menu.edit-album') ?>
    </a>
    <div class="panel" id="edit-album">
        <form action="<?= self::url($album->url, 'edit') ?>" method="post" data-ajax>

            <h4><?= text('view.panel.edit-album.title') ?></h4>

            <input type="text" id="album-name" name="name" placeholder="<?= text('view.panel.edit-album.name') ?>" required
                value="<?= $album->name ?>">
            <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="<?= text('view.panel.edit-album.date') ?>"
                   pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required
                   value="<?= $album->day ?>/<?= $album->month ?>/<?= $album->year ?>">

            <input type="text" id="meta-place" name="meta[place]" placeholder="<?= text('view.panel.edit-album.meta.place') ?>" value="<?= $album->meta('place') ?>"
                   pattern="^\[?[0-9]+\.[0-9]+ ?, ?[0-9]+\.[0-9]+\]?$" title="x.xxxxx, y.yyyyy">

            <?php if($ctx->user->isAdmin()): ?>
                <br><h4><?= text('view.panel.edit-album.authors') ?></h4>
                <?php foreach($album->authors() as $author): ?>
                <input type="text" name="authors[<?= $author->name ?>]" value="<?= $author->name ?>" required>
                <?php endforeach; ?>
            <?php endif; ?>

            <button type="submit"><?= text('view.panel.edit-album.submit') ?></button>

        </form>
    </div>
</li>