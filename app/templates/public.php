<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
    <link href="<?= self::url('/css/libs/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/libs/normalize.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/public.css') ?>" rel="stylesheet">
</head>
<body class="<?= $class ?>">

    <header><?= APP_NAME ?></header>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/ajaxforms.js') ?>"></script>
    <script>
    $(function() {
        window.AjaxForms.text.error = "<?= text('js.ajax.error') ?>";
    });
    </script>

</body>
</html>