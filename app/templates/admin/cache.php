<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400' rel='stylesheet' type='text/css'>
    <link href="<?= self::url('/css/libs/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/libs/normalize.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/public.css') ?>" rel="stylesheet">
    <style>
        #cache {
            max-width: 600px;
            margin: auto;
        }
        #cache ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        #cache .progress {
            position: relative;
            width: 100%;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
        }
        #cache .progress .bar {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 0;
            background: #E14938;
        }
        #cache .done {
            color: green;
        }
        #cache .failed {
            color: red;
        }
    </style>
</head>
<body>

<header><?= APP_NAME ?></header>

<main>
    <div id="cache">

        <h1>Clear cache</h1>

        <ul>
            <li>Skipped <span class="skipped">0</span></li>
            <li>Done <span class="done">0</span></li>
            <li>Failed <span class="failed">0</span></li>
            <li>Total <span class="completed">0</span> / <span class="total">0</span></li>
        </ul>

        <div class="progress">
            <div class="bar"></div>
        </div>

        <div class="output"></div>

    </div>
</main>

<script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
<script>
    $(function()
    {
        var skipped = 0;
        var done = 0;
        var failed = 0;
        var total = <?= count($files) ?>;
        var files = [
            <?php foreach($files as $file): ?>
            "<?= $file ?>",
            <?php endforeach; ?>
        ];

        $('.total').text(total);

        function next()
        {
            var proceed = [];
            if(files.length) { proceed.push(files.pop()); }
            if(files.length) { proceed.push(files.pop()); }
            if(files.length) { proceed.push(files.pop()); }

            if(!proceed.length) {
                return;
            }

            $.post('<?= self::url('/admin/cache/clear') ?>', {files: proceed.join(',')})
            .done(function(json)
            {
                $.each(json, function(i, state)
                {
                    if (state[1] === 'skipped') {
                        skipped++;
                        $('.skipped').text(skipped);
                        $('.output').prepend('skipped: ' + state[0] + '<br/>');
                    }
                    else if (state[1] === 'done') {
                        done++;
                        $('.done').text(done);
                        $('.output').prepend('done: ' + state[0] + '<br/>');
                    }
                    else {
                        failed++;
                        $('.failed').text(failed);
                        $('.output').prepend('failed: ' + state[0] + '<br/>');
                    }

                    var completed = skipped + done + failed;
                    var percent = (completed * 100) / total;

                    $('.completed').text(skipped + done + failed);
                    $('.progress .bar').css('width', percent + '%');
                });

                next();
            });
        }

        next();
    });
</script>

</body>
</html>