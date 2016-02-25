<?php self::layout('private', ['ariane' => $ariane]); ?>

<?php self::rewrite('css') ?>
<link href="<?= self::url('/css/libs/leaflet.min.css') ?>" rel="stylesheet">
<?php self::end() ?>


<?php self::rewrite('menu') ?>
<?php if($ctx->user->isUploader()): ?>
    <?= self::render('_menu/create-album') ?>
<?php endif; ?>
<?php self::end() ?>


<div id="map"></div>


<?php self::rewrite('js') ?>
<script src="<?= self::url('/js/libs/leaflet.min.js') ?>"></script>
<script>
$(function() {
    window.Mapbox = {
        token: "<?= MAPBOX_TOKEN ?>",
        project: "<?= MAPBOX_PROJECT ?>",
        coord: [<?= MAPBOX_COORD ?>],
        locations: [
            <?php foreach($albums as $album): ?>
            <?php $place = trim($album->meta('place'), '[]') ?>
            {
                name: "<?= $album->name ?>", date: "<?= $album->date ?>", year: <?= $album->year ?>, link: "<?= self::url($album->url) ?>",
                place: <?= $place ? '[' . $place . ']' : 'null' ?>,
                pic: "<?= self::url($album->random()->url_small) ?>"
            },
            <?php endforeach; ?>
        ]
    };
});
</script>
<script src="<?= self::url('/js/private-map.js') ?>"></script>
<?php self::end() ?>
