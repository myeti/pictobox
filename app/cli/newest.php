<?php

/**
 * PHP Settings
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require dirname(__DIR__) . '/instance.php';



/**
 * Cli mode
 */

chdir(__DIR__);



/**
 * Specific context
 */

use Colorium\App;

$context = App\Front::context();
$context->resource('App\Cli\Crons::newest');



/**
 * Execute app
 */

$app->run($context)->end();