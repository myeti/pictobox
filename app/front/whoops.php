<?php

/**
 * PHP Settings
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);



/**
 * Debug stacktrace using WHOOPS
 */

$handler = new Whoops\Handler\PrettyPageHandler;
$handler->addDataTableCallback('App Request', function() use ($request) {
    return (array)$request;
});

$whoops = new Whoops\Run;
$whoops->pushHandler($handler)->register();
