<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= self::url('/css/bootstrap.min.css') ?>" rel="stylesheet">
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

    <div id="modals">
        <?= self::render('_modals/user-details', ['user' => $user]) ?>

        <?php if($album): ?>
            <?= self::render('_modals/upload-pics') ?>
        <?php else: ?>
            <?= self::render('_modals/create-album') ?>
        <?php endif; ?>
    </div>

    <header>

        <nav>
            <a href="<?= self::url('/') ?>" id="logo"><?= strtoupper(APP_NAME) ?></a> <span>/</span>

            <?php foreach($ariane as $text => $link): ?>
            <a href="<?= self::url($link) ?>" class="xs-hide"><?= $text ?></a>
            <?php endforeach; ?>

            <?php if($ariane and $album): ?>
            <span class="xs-hide">/</span> <a href="<?= self::url($album->url) ?>" class="album"><?= $album->name ?></a>
            <?php endif; ?>
        </nav>

    </header>

    <menu>

        <span class="switch glyphicon glyphicon-menu-hamburger"></span>

        <ul>
            <li>
                <a data-modal="#user-details" href="#">
                    <span class="glyphicon glyphicon-user" title="Modifier mes informations"></span>
                </a>
            </li>
            <?php if($album): ?>
            <li>
                <a data-modal="#upload-pics" href="#">
                    <span class="glyphicon glyphicon-open" title="Ajouter des photos"></span>
                </a>
            </li>
            <li>
                <a href="<?= self::url($album->url, 'download') ?>" title="Télécharger l'album">
                    <span class="glyphicon glyphicon-save"></span>
                </a>
            </li>
            <?php else: ?>
            <li>
                <a data-modal="#create-album" href="#">
                    <span class="glyphicon glyphicon-plus-sign" title="Créer un nouvel album"></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/common.js') ?>"></script>
    <script src="<?= self::url('/js/private.js') ?>"></script>

</body>
</html