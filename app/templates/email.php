<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h1 style="border-top: 1px dotted #333; border-bottom: 1px dotted #333;"><?= APP_NAME ?></h1>

<?= self::content(); ?>

<p style="border-top: 1px dotted #333;">
    <strong><?= $host ?></strong>
</p>

</body>
</html>