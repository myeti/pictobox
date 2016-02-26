<?php self::layout('public') ?>

<h1 class="error">
    <?= $message ?: text('error.503') ?>
</h1>