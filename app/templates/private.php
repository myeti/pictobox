<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,300italic" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
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

        <div class="switch">
            <span class="fa fa-bars"></span>
        </div>

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

        <ul>
            <li>
                <a data-modal="#profile" href="#">
                    <span class="fa fa-user"></span> <em>Profil</em>
                </a>
            </li>
            <?php if($album): ?>
                <?php if($user->isUploader()): ?>
                    <li>
                        <a data-modal="#upload" href="#">
                            <span class="fa fa-upload"></span> <em>Compléter</em>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= self::url($album->url, 'download') ?>">
                        <span class="fa fa-download"></span>  <em>Télécharger</em>
                    </a>
                </li>
            <?php elseif($user->isUploader()): ?>
            <li>
                <a data-modal="#create" href="#">
                    <span class="fa fa-plus-circle"></span> <em>Créer album</em>
                </a>
            </li>
            <?php endif; ?>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/MenuModals.js') ?>"></script>
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