<?php

require_once '../settings.php';

$context = Colorium\App\Front::context();
$context->forward('App\Logic\Crons::newest');

require_once '../front.php';