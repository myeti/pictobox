<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h1 style="background: #1f2224; color:#fff; font-family: sans-serif; padding: 10px 14px; font-size: 18px; text-transform: uppercase"><?= APP_NAME ?></h1>

<div class="main" style="font-family: Tahoma, sans-serif; padding: 4px 14px;">
<?= self::content(); ?>
</div>

<p style="border-top: 1px dotted #333; font-family: Tahoma, sans-serif; padding: 10px 14px;">
    <strong><?= APP_HOST ?></strong>
</p>

</body>
</html>