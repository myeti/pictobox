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
 * App instance
 */

use Colorium\Web;

$app = new Web\App([
    homepage => [
        http => 'GET /',
        method => 'App\Logic\AlbumList::all'
    ],

    login => [
        http => 'GET /login',
        method => 'App\Logic\UserManager::login'
    ],
    user_auth => [
        http => 'POST /user/auth',
        method => 'App\Logic\UserManager::auth'
    ],
    user_edit => [
        http => 'POST /user/edit',
        method => 'App\Logic\UserManager::edit'
    ],
    user_ping => [
        http => 'GET /user/ping',
        method => 'App\Logic\UserManager::ping'
    ],
    user_report => [
        http => 'POST /user/report',
        method => 'App\Logic\UserManager::report'
    ],
    user_feedback => [
        http => 'POST /user/feedback',
        method => 'App\Logic\UserManager::feedback'
    ],

    admin_cache => [
        http => 'GET /admin/cache',
        method => 'App\Logic\UserManager\AdminManager::cache'
    ],
    admin_cache_clear => [
        http => 'POST /admin/cache/clear',
        method => 'App\Logic\UserManager\AdminManager::cacheClear'
    ],

    album_create => [
        http => 'GET /create',
        method => 'App\Logic\AlbumEditor::create'
    ],
    album_show => [
        http => 'GET /:y/:m/:d/:album',
        method => 'App\Logic\AlbumEditor::show'
    ],
    album_upload => [
        http => 'GET /:y/:m/:d/:album/upload',
        method => 'App\Logic\AlbumEditor::upload'
    ],
    album_download => [
        http => 'GET /:y/:m/:d/:album/download',
        method => 'App\Logic\AlbumEditor::download'
    ],

    albumlist_year => [
        http => 'GET /:y',
        method => 'App\Logic\AlbumList::year'
    ],
    albumlist_month => [
        http => 'GET /:y/:m',
        method => 'App\Logic\AlbumList::month'
    ],
    albumlist_day => [
        http => 'GET /:y/:m/:d',
        method => 'App\Logic\AlbumList::day'
    ],

    cron_lastalbums => [
        method => 'App\Logic\CronTask::lastAlbums'
    ],
]);



/**
 * Template folder
 */

$app->templater->directory = __DIR__ . '/templates/';



/**
 * Http events
 */

$app->events = [
    401 => 'App\Logic\ErrorHandler::accessDenied',
    404 => 'App\Logic\ErrorHandler::notFound',
];



/**
 * Error fallbacks
 */

$app->errors = [
    Exception::class => 'App\Logic\ErrorHandler::fatal'
];