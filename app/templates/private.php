<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="<?= self::url('/css/libs/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/libs/normalize.min.css') ?>" rel="stylesheet">
    <?php if($album): ?>
        <link href="<?= self::url('/css/libs/dropzone.min.css') ?>" rel="stylesheet">
        <link href="<?= self::url('/css/libs/photoswipe.min.css') ?>" rel="stylesheet">
        <link href="<?= self::url('/css/libs/photoswipe/default-skin.min.css') ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/private.css') ?>" rel="stylesheet">

    <script>
    var routes = {
        ping: "<?= self::url('/login/ping') ?>",
        login: "<?= self::url('/login') ?>"
    }
    </script>
</head>
<body>

    <?php if($album): ?>
        <?= self::render('_modals/photoswipe') ?>
    <?php endif; ?>

    <div id="modals">
        <?= self::render('_modals/profile', ['user' => $user]) ?>

        <?php if($user->isUploader()): ?>
            <?php if($album): ?>
                <?= self::render('_modals/upload', ['album' => $album]) ?>
            <?php else: ?>
                <?= self::render('_modals/create') ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <header>

        <nav>
            <a href="<?= self::url('/') ?>" id="logo"><?= strtoupper(APP_NAME) ?></a>

            <?php if($ariane): ?>

                <span class="xs-hide">/</span>

                <?php foreach($ariane as $text => $link): ?>
                <a href="<?= self::url($link) ?>" class="xs-hide"><?= $text ?></a>
                <?php endforeach; ?>

                <?php if($album): ?>
                <span>/</span> <a href="<?= self::url($album->url) ?>" class="album"><?= $album->name ?></a>
                <?php endif; ?>

            <?php endif; ?>
        </nav>

    </header>

    <menu>

        <span class="switch fa fa-bars"></span>

        <ul>
            <li>
                <a data-modal="#profile" href="#">
                    <span class="fa fa-user" title="Modifier mes informations"></span>
                </a>
            </li>
            <?php if($album): ?>
                <?php if($user->isUploader()): ?>
                <li>
                    <a data-modal="#upload" href="#">
                        <span class="fa fa-upload" title="Ajouter des photos"></span>
                    </a>
                </li>
                <?php endif; ?>
            <li>
                <a href="<?= self::url($album->url, 'download') ?>" title="Télécharger l'album">
                    <span class="fa fa-download"></span>
                </a>
            </li>
            <?php elseif($user->isUploader()): ?>
            <li>
                <a data-modal="#create" data-autofocus href="#">
                    <span class="fa fa-plus-circle" title="Créer un nouvel album"></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/common.js') ?>"></script>
    <script src="<?= self::url('/js/private.js') ?>"></script>

    <?php if($album): ?>
        <script src="<?= self::url('/js/libs/dropzone.min.js') ?>"></script>
        <script src="<?= self::url('/js/libs/photoswipe.min.js') ?>"></script>
        <script src="<?= self::url('/js/libs/photoswipe-ui-default.min.js') ?>"></script>
        <script src="<?= self::url('/js/private-album.js') ?>"></script>
    <?php endif; ?>

</body>
</html