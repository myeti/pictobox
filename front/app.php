<?php

/**
 * Config definitions
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
 * Template setup
 */

use Colorium\Templating\Templater;

$templater = new Templater(__DIR__ . '/views/');



/**
 * Router setup
 */

use Colorium\Routing\Router;

$router = new Router([
    'GET  /login'                   => 'App\Logic\Users::login',
    'POST /authenticate'            => 'App\Logic\Users::authenticate',
    'GET  /logout'                  => 'App\Logic\Users::logout',
    'GET  /'                        => 'App\Logic\Albums::all',
    'GET  /:y'                      => 'App\Logic\Albums::year',
    'GET  /:y/:m'                   => 'App\Logic\Albums::month',
    'GET  /:y/:m/:d'                => 'App\Logic\Albums::day',
    'GET  /:y/:m/:d/:album'         => 'App\Logic\Albums::one',
    'POST /:y/:m/:d/:album/create'  => 'App\Logic\Albums::create',
    'POST /:y/:m/:d/:album/upload'  => 'App\Logic\Albums::upload',
]);



/**
 * App instance
 */

$app = new Colorium\App\Front($router, $templater);



/**
 * Http event
 */

$app->events([
    401 => 'App\Logic\Errors::unauthorized',
    404 => 'App\Logic\Errors::notfound',
    Exception::class => 'App\Logic\Errors::fatal'
]);



/**
 * Dev mode
 */

$trusted = ['127.0.0.1', '::1', '10.0.2.2'];
if(in_array($app->context->request->ip, $trusted)) {
    require 'development.php';
}


/**
 * Finally, run app
 */

$app->run()->end();