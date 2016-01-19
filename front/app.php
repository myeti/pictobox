<?php

/**
 * Config definitions
 * Be careful when changing it
 */

define('__ROOT__', dirname(__DIR__));

define('PICS_DIR', __ROOT__ . '/public/pics/');
define('CACHE_DIR', __ROOT__ . '/public/cache/');



/**
 * Database setup
 * Define drive connector and model classes
 */

use Colorium\Orm;
use App\Model\User;

$sqlite = new Orm\SQLite(__DIR__ . '/orm/pictobox.db', [
    'user' => User::class
]);

Orm\Hub::source($sqlite);



/**
 * Authentication setup
 * Define user factory when login in
 */

use Colorium\Stateful\Auth;

Auth::factory(function($ref) {
    return User::one(['id' => $ref]);
});



/**
 * App instance setup
 */

$app = new Colorium\App\Front;



/**
 * Template setup
 */

$app->templater->directory = __DIR__ . '/views/';



/**
 * Routes setup
 */

$app->on('GET /login',                      'App\Logic\Users::login');
$app->on('POST /authenticate',              'App\Logic\Users::authenticate');
$app->on('GET /logout',                     'App\Logic\Users::logout');

$app->on('GET /',                          'App\Logic\Albums::all');
$app->on('GET /:y',                        'App\Logic\Albums::year');
$app->on('GET /:y/:m',                     'App\Logic\Albums::month');
$app->on('GET /:y/:m/:d',                  'App\Logic\Albums::day');
$app->on('GET /:y/:m/:d/:album',           'App\Logic\Albums::one');
$app->on('POST /:y/:m/:d/:album/create',   'App\Logic\Albums::create');
$app->on('POST /:y/:m/:d/:album/upload',   'App\Logic\Albums::upload');


/**
 * Env mode
 */

$app->context->request->local[] = '10.0.2.2';
$env = $app->context->request->local() ? 'development.php' : 'production.php';
require $env;


/**
 * Finally, run app
 */

$app->run()->end();