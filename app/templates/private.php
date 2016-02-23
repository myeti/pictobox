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
    <?php self::block('css'); ?>
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/private.css') ?>" rel="stylesheet">
</head>
<body>

    <div id="page-loader">
        <div class="spinner"></div>
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

                <?php self::block('ariane')  ?>

            <?php endif; ?>
        </nav>

    </header>
    <div class="header-bg"></div>

    <menu>

        <ul>
            <?php self::block('menu'); ?>
            <?= self::render('_menu/user-profile', ['user' => $ctx->user]) ?>
            <?= self::render('_menu/user-feedback', ['album' => $album]) ?>
            <?= self::render('_menu/user-logout') ?>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/libs/blazy.min.js') ?>"></script>
    <script src="<?= self::url('/js/common.js') ?>"></script>
    <script src="<?= self::url('/js/pictobox-ui.js') ?>"></script>
    <script src="<?= self::url('/js/ajaxforms.js') ?>"></script>
    <script src="<?= self::url('/js/private.js') ?>"></script>
    <?php self::block('js') ?>
    <script>
    $(function() {
        window.AjaxForms.text.error = "<?= text('view.js.ajax.error') ?>";
        window.PictoboxUI.text.leaveUpload = "<?= text('view.js.upload.leave') ?>";
        window.PictoboxUI.routes = {
            ping: "<?= self::url('/user/ping') ?>",
            login: "<?= self::url('/login') ?>"
        };
    });
    </script>

</body>
</html