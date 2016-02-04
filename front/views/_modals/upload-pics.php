<div class="modal" id="upload-pics">
    <form action="<?= self::url($album->url, 'upload') ?>" method="post">
        <input type="file" name="upload" accept="image/*" multiple>
    </form>
    <h4>Upload <i class="state"></i></h4>
    <ul class="list"></ul>
</div>