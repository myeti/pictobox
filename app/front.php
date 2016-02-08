<?php

/**
 * Config definitions
 */

define('__ROOT__', dirname(__DIR__));
define('CACHE_URL', '/img/cache/');
define('CACHE_DIR', __ROOT__ . '/public/img/cache/');



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

$sqlite = new Orm\SQLite(DB_FILE, [
    'user' => User::class
]);

Orm\Hub::source($sqlite);



/**
 * Authentication setup
 * Define user factory when login in
 */

use Colorium\Stateful\Auth;

Auth::factory(function($id) {
    return User::one(['id' => $id]);
});



/**
 * Template setup
 */

use Colorium\Templating\Templater;

$templater = new Templater(__DIR__ . '/templates/');



/**
 * Router setup
 */

use Colorium\Routing\Router;

$router = new Router([
    'GET  /cache'                   => 'App\Logic\Cache::listing',
    'POST /cache/clear'             => 'App\Logic\Cache::clear',

    'GET  /login'                   => 'App\Logic\Users::login',
    'POST /login/auth'              => 'App\Logic\Users::auth',
    'POST /login/edit'              => 'App\Logic\Users::edit',
    'GET  /login/ping'              => 'App\Logic\Users::ping',
    'GET  /logout'                  => 'App\Logic\Users::logout',

    'POST /create'                  => 'App\Logic\Albums::create',
    'GET  /'                        => 'App\Logic\Albums::listing',
    'GET  /:y'                      => 'App\Logic\Albums::listingYear',
    'GET  /:y/:m'                   => 'App\Logic\Albums::listingMonth',
    'GET  /:y/:m/:d'                => 'App\Logic\Albums::listingDay',
    'GET  /:y/:m/:d/:album'         => 'App\Logic\Albums::show',
    'POST /:y/:m/:d/:album/upload'  => 'App\Logic\Albums::upload',
    'GET  /:y/:m/:d/:album/download'=> 'App\Logic\Albums::download',
]);



/**
 * App instance
 */

$app = new App\Front($router, $templater);



/**
 * Dev mode
 */

$trusted = ['127.0.0.1', '::1', '10.0.2.2'];
if(in_array($context->request->ip, $trusted)) {
    require 'whoops.php';
}



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