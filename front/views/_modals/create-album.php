<div class="modal" id="create-album">
    <form action="<?= self::url('/create') ?>" method="post" data-ajax>

        <h4>Nouvel album</h4>

        <label for="album-name">Titre</label>
        <input type="text" id="album-name" name="name" required>

        <label for="album-date">Date</label>
        <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="jj/mm/aaaa"
               pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required>

        <button type="submit" data-loading="fa-cog" data-ok="fa-ok">Créer</button>
        <button type="reset" class="cancel">Fermer</button>

    </form>
</div>