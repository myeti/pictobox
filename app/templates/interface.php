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
    <link href="<?= self::url('/css/interface.css') ?>" rel="stylesheet">
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

        <?= self::render('_modals/feedback') ?>
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

        <?php
        $parts = $album ? 3 : 2;
        if($user->isUploader()) {  $parts++; }
        ?>

        <ul>
            <li class="col-<?= $parts ?>">
                <a data-modal="#profile" href="#">Profil</a>
            </li>

            <?php if($album): ?>
                <?php if($user->isUploader()): ?>
                <li class="col-<?= $parts ?>">
                    <a data-modal="#upload" href="#">Ajouter photos</a>
                </li>
                <?php endif; ?>
            <li class="col-<?= $parts ?>">
                <a href="<?= self::url($album->url, 'download') ?>" data-confirm="<?= 'Attention, le téléchargement d\'un album entier est lourd, long
                                                                                       et peut entrainer un surcoût si vous êtes sur le réseau mobile !
                                                                                       Continuer ?' ?>'">
                    Télécharger
                </a>
            </li>
            <?php elseif($user->isUploader()): ?>
            <li class="col-<?= $parts ?>">
                <a data-modal="#create" href="#">Créer album</a>
            </li>
            <?php endif; ?>

            <li class="col-<?= $parts ?>">
                <a data-modal="#feedback" href="#">Feedback</a>
            </li>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
    <?php if($album): ?>
        <script src="<?= self::url('/js/libs/dropzone.min.js') ?>"></script>
        <script src="<?= self::url('/js/libs/photoswipe.min.js') ?>"></script>
        <script src="<?= self::url('/js/libs/photoswipe-ui-default.min.js') ?>"></script>
    <?php endif; ?>
    <script src="<?= self::url('/js/pictobox-ui.js') ?>"></script>
    <script src="<?= self::url('/js/ajaxforms.js') ?>"></script>
    <script src="<?= self::url('/js/interface.js') ?>"></script>
    <script>
    $(function() {
        window.PictoboxUI.routes.ping = "<?= self::url('/user/ping') ?>";
        window.PictoboxUI.routes.login = "<?= self::url('/login') ?>";
    });
    </script>

</body>
</html