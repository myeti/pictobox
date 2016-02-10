<div class="modal" id="feedback">
    <form action="<?= self::url('/user/feedback') ?>" method="post" data-ajax>

        <h4>Feedback</h4>

        <textarea name="message" cols="30" rows="10" placeholder="Votre suggestion, rapport d'erreur..." required></textarea>

        <button type="submit">Envoyer</button>

        <button type="reset" class="cancel">
            <span class="fa fa-close"></span>
        </button>

    </form>
</div>