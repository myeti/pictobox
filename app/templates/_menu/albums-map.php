<?php if(MAPBOX_TOKEN and MAPBOX_PROJECT): ?>
<li>
    <a href="<?= self::url('/map') ?>">
        <span class="fa fa-map-o"></span> <?= text('view.menu.map') ?>
    </a>
</li>
<?php endif; ?>