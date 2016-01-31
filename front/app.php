<?php

/**
 * Config definitions
 */

define('__ROOT__', dirname(__DIR__));

define('APP_NAME', 'Pictobox');
define('ALBUMS_DIR', __ROOT__ . '/public/img/albums/');
define('ALBUMS_URL', '/img/albums/');
define('CACHE_DIR', __ROOT__ . '/public/img/cache/');
define('CACHE_URL', '/img/cache/');



/**
 * Generate HTTP context
 */

use Colorium\App;

$context = App\Front::context();



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
 * Dev mode
 */

$trusted = ['127.0.0.1', '::1', '10.0.2.2'];
if(in_array($context->request->ip, $trusted)) {
    require 'development.php';
}



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
    'GET  /cc'                      => 'App\Logic\Crons::cache',

    'GET  /login'                   => 'App\Logic\Users::login',
    'POST /login/auth'              => 'App\Logic\Users::auth',
    'POST /login/edit'              => 'App\Logic\Users::edit',
    'GET  /login/ping'              => 'App\Logic\Users::ping',
    'GET  /logout'                  => 'App\Logic\Users::logout',

    'POST /create'                  => 'App\Logic\Albums::create',
    'GET  /'                        => 'App\Logic\Albums::all',
    'GET  /:y'                      => 'App\Logic\Albums::year',
    'GET  /:y/:m'                   => 'App\Logic\Albums::month',
    'GET  /:y/:m/:d'                => 'App\Logic\Albums::day',
    'GET  /:y/:m/:d/:album'         => 'App\Logic\Albums::one',
    'POST /:y/:m/:d/:album/upload'  => 'App\Logic\Albums::upload',
    'GET  /:y/:m/:d/:album/download'=> 'App\Logic\Albums::download',
]);



/**
 * App instance
 */

$app = new App\Front($router, $templater);



/**
 * Http event
 */

$app->events([
    401 => 'App\Logic\Errors::unauthorized',
    404 => 'App\Logic\Errors::notfound',
    Exception::class => 'App\Logic\Errors::fatal'
]);


/**
 * Finally, run app
 */

$app->run($context)->end();