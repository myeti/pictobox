<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= self::url('/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/public.css') ?>" rel="stylesheet">
</head>
<body>

<header><?= APP_NAME ?></header>

<main>

    <?= self::content(); ?>

</main>

</body>
</html>