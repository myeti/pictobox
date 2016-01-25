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
</head>
<body>

    <header>

        <div id="user">
            <a href="#" data-modal="#user-details"><?= $self->user->username ?></a>
        </div>

        <nav>
            <a href="<?= self::url('/') ?>" id="logo"><?= strtoupper(APP_NAME) ?></a> <span>/</span>

            <?php foreach($ariane as $text => $link): ?>
            <a href="<?= self::url($link) ?>"><?= $text ?></a>
            <?php endforeach; ?>

            <?php if($album): ?>
            <span>/</span> <a href="<?= self::url($album->url) ?>" class="album"><?= $album->name ?></a>
            <?php endif; ?>
        </nav>

    </header>

    <main>
        <?= self::content(); ?>
    </main>

</body>
</html