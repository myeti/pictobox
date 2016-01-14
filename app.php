<?php

/**
 * Config setup
 */

define('PICS_DIR', __DIR__ . '/public/pics/');
define('CACHE_DIR', __DIR__ . '/public/cache/');

function vd($param, ...$params) { var_dump($param, ...$params); }
function dd($param, ...$params) { die(var_dump($param, ...$params)); }


/**
 * Orm setup
 */

use App\Model\User;

$sqlite = Colorium\Orm\Mapper::SQLite(__DIR__ . '/files/pictobox.db');
$sqlite->map('user', User::class);
$sqlite->builder('user')->create();


/**
 * Auto login
 */

use Colorium\Stateful\Auth;

$admin = new User('Admin', null, User::ADMIN);
Auth::login($admin->rank, $admin);


/**
 * App setup
 */

$app = new Colorium\App\Front;

// template definition
$app->templater->root = __DIR__ . '/files/views/';

// routes definition
$app->on('GET /login',         'App\Logic\Users::login');
$app->on('POST /authenticate', 'App\Logic\Users::authenticate');
$app->on('GET /logout',        'App\Logic\Users::logout');

$app->on('GET /',                          'App\Logic\Albums::all');
$app->on('GET /:y',                        'App\Logic\Albums::year');
$app->on('GET /:y/:m',                     'App\Logic\Albums::month');
$app->on('GET /:y/:m/:d',                  'App\Logic\Albums::day');
$app->on('GET /:y/:m/:d/:album',           'App\Logic\Albums::one');
$app->on('POST /:y/:m/:d/:album/create',   'App\Logic\Albums::create');
$app->on('POST /:y/:m/:d/:album/upload',   'App\Logic\Albums::upload');

// events definition
$app->when(401, 'App\Logic\Errors::unauthorized');
$app->when(404, 'App\Logic\Errors::notfound');
$app->when(\Exception::class, 'App\Logic\Errors::error');


/**
 * Debug setup
 */

$app->prod = false;
if(!$app->prod) {
    $handler = new Whoops\Handler\PrettyPageHandler;
    $handler->addDataTableCallback('App Request', function() use ($app) {
        return (array)$app->context->request;
    });
    (new Whoops\Run)->pushHandler($handler)->register();
}


/**
 * Finally, run app
 */

$app->run();