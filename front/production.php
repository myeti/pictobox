<?php

/**
 * PRODUCTION MODE
 */

/**
 * Http event setup
 */

$app->when(401, 'App\Logic\Errors::unauthorized');
$app->when(404, 'App\Logic\Errors::notfound');
$app->when(\Exception::class, 'App\Logic\Errors::error');