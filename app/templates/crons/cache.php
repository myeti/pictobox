<script src="<?= self::url('/js/libs/jquery-2.2.0.min.js') ?>"></script>
<script>
    var files = [
        <?php foreach($files as $file): ?>
        "<?= $file ?>",
        <?php endforeach; ?>
    ];

    function next()
    {
        var file = files.pop();

        if(!file) {
            var li = $('<li></li>').text('OK');
            $('ul').append(li);
            return;
        }

        $.post('<?= self::url('/cron/cacheclear') ?>', {file: file})
        .done(function(json)
        {
            var li = $('<li></li>').text(file).css('color', (json == true) ? 'green' : 'red');
            $('ul').append(li);
            next();
        });
    }

    next();
</script>

<ul></ul>
