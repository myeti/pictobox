<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= self::url('/img/favicon-2.png') ?>">
    <link rel="icon" type="image/x-icon" href="<?= self::url('/favicon.ico') ?>" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href="<?= self::url('/css/libs/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/libs/normalize.min.css') ?>" rel="stylesheet">
    <?php if(isset($album)): ?>
        <link href="<?= self::url('/css/libs/dropzone.min.css') ?>" rel="stylesheet">
        <link href="<?= self::url('/css/libs/photoswipe.min.css') ?>" rel="stylesheet">
        <link href="<?= self::url('/css/libs/photoswipe/default-skin.min.css') ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/interface.css') ?>" rel="stylesheet">
</head>
<body>

    <?php if(isset($album)): ?>
        <?= self::render('_modals/photoswipe') ?>
    <?php endif; ?>

    <div id="modals">
        <?= self::render('_modals/profile', ['user' => $user]) ?>

        <?php if($user->isUploader()): ?>
            <?php if(isset($album)): ?>
                <?= self::render('_modals/upload', ['album' => $album]) ?>
            <?php else: ?>
                <?= self::render('_modals/create') ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(isset($album)): ?>
            <?= self::render('_modals/feedback', ['album' => $album]) ?>
        <?php else: ?>
            <?= self::render('_modals/feedback') ?>
        <?php endif; ?>
    </div>

    <header>

        <div class="switch">
            <span class="fa fa-bars do-open"></span>
            <span class="fa fa-close do-close"></span>
        </div>

        <nav>
            <a href="<?= self::url('/') ?>" id="logo"><?= strtoupper(APP_NAME) ?></a>

            <?php if($ariane): ?>

                <span class="xs-hide">/</span>

                <?php foreach($ariane as $text => $link): ?>
                <a href="<?= self::url($link) ?>" class="xs-hide"><?= $text ?></a>
                <?php endforeach; ?>

                <?php if(isset($album)): ?>
                <span>/</span> <a href="<?= self::url($album->url) ?>" class="album"><?= $album->name ?></a>
                <?php endif; ?>

            <?php endif; ?>
        </nav>

    </header>
    <div class="header-bg"></div>

    <menu>

        <ul>
            <?php if(isset($album)): ?>
                <?php if($user->isUploader()): ?>
                <li>
                    <a data-modal="#upload" href="#">
                        <span class="fa fa-image"></span> <?= text('menu.upload') ?>
                    </a>
                </li>
                <?php endif; ?>
            <li>
                <a href="<?= self::url($album->url, 'download') ?>" data-confirm="<?= text('menu.download.confirm') ?>">
                    <span class="fa fa-download"></span> <?= text('menu.download') ?>
                </a>
            </li>
            <?php elseif($user->isUploader()): ?>
            <li>
                <a data-modal="#create" href="#">
                    <span class="fa fa-plus"></span> <?= text('menu.create') ?>
                </a>
            </li>
            <?php endif; ?>

            <li>
                <a data-modal="#profile" href="#">
                    <span class="fa fa-user"></span> <?= text('menu.profile') ?>
                </a>
            </li>
            <li>
                <a data-modal="#feedback" href="#">
                    <span class="fa fa-comment"></span> <?= text('menu.feedback') ?>
                </a>
            </li>
            <li>
                <a href="<?= self::url('/logout') ?>">
                    <span class="fa fa-sign-out"></span> <?= text('menu.logout') ?>
                </a>
            </li>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/libs/blazy.min.js') ?>"></script>
    <?php if(isset($album)): ?>
        <script src="<?= self::url('/js/libs/dropzone.min.js') ?>"></script>
        <script src="<?= self::url('/js/libs/photoswipe.min.js') ?>"></script>
        <script src="<?= self::url('/js/libs/photoswipe-ui-default.min.js') ?>"></script>
    <?php endif; ?>
    <script src="<?= self::url('/js/pictobox-ui.js') ?>"></script>
    <script src="<?= self::url('/js/ajaxforms.js') ?>"></script>
    <script src="<?= self::url('/js/interface.js') ?>"></script>
    <script>
    $(function() {
        window.AjaxForms.text.error = "<?= text('js.ajax.error') ?>";
        window.PictoboxUI.text.leaveUpload = "<?= text('js.upload.leave') ?>";
        window.PictoboxUI.routes = {
            ping: "<?= self::url('/user/ping') ?>",
            login: "<?= self::url('/login') ?>"
        };
    });
    </script>

</body>
</html