<div class="modal" id="create">
    <form action="<?= self::url('/create') ?>" method="post" data-ajax>

        <h4>Nouvel album</h4>

        <input type="text" id="album-name" name="name" placeholder="Titre" required>
        <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="jj/mm/aaaa"
               pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required>

        <button type="submit">Créer</button>
        <button type="reset" class="cancel">Fermer</button>

    </form>
</div>