<?php

/**
 * Debug stacktrace using WHOOPS
 */

$app->catch = false;

$handler = new Whoops\Handler\PrettyPageHandler;
$handler->addDataTableCallback('App Uri', function() use ($app) {
    return (array)$app->context->request->uri;
});
$handler->addDataTableCallback('App Route', function() use ($app) {
    return (array)$app->context->route;
});
$handler->addDataTableCallback('App Invokable', function() use ($app) {
    $invokable = $app->context->invokable;
    $params = (array)$invokable->params;
    array_shift($params);
    return (array)$invokable;
});
$handler->addDataTableCallback('App Access', function() use ($app) {
    return (array)$app->context->access;
});
$handler->addDataTableCallback('App Response', function() use ($app) {
    return (array)$app->context->response;
});

$whoops = new Whoops\Run;
$whoops->pushHandler($handler)->register();
