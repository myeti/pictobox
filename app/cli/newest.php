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
 * Execute app
 */

$context = $app->context();

$app->forward($context, 'cron_lastalbums')->end();