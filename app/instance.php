<?php

ini_set('memory_limit', '512M');

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/instance/config.php';



/**
 * App constants
 */

define('__ROOT__', dirname(__DIR__));
define('CACHE_URL', '/img/cache/');
define('CACHE_DIR', __ROOT__ . '/public/img/cache/');



/**
 * Load lang
 */

use Colorium\Text;

Text\Lang::load([
    'fr' => require 'langs/french.php'
]);



/**
 * Database setup
 */

use Colorium\Orm;
use App\Model\User;

$sqlite = new Orm\SQLite(DB_FILE, [
    'user' => User::class
]);

Orm\Hub::source($sqlite);



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
    'GET  /admin/cache'             => 'App\Logic\Admin::cache',
    'POST /admin/cache/clear'       => 'App\Logic\Admin::cacheclear',

    'GET  /login'                   => 'App\Logic\Users::login',
    'GET  /logout'                  => 'App\Logic\Users::logout',
    'POST /user/auth'               => 'App\Logic\Users::auth',
    'POST /user/edit'               => 'App\Logic\Users::edit',
    'GET  /user/ping'               => 'App\Logic\Users::ping',
    'POST /user/report'             => 'App\Logic\Users::report',
    'POST /user/feedback'           => 'App\Logic\Users::feedback',

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

use Colorium\App;

$app = new App\Front($router, $templater);



/**
 * Http event
 */

$app->events([
    401 => 'App\Logic\Errors::unauthorized',
    404 => 'App\Logic\Errors::notfound',
    Exception::class => 'App\Logic\Errors::fatal'
]);