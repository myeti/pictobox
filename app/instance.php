<?php

ini_set('memory_limit', '512M');

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/instance/config.php';



/**
 * App constants
 */

define('__ROOT__', dirname(__DIR__));
define('__APP__', __DIR__);
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
 * Pictobox instance
 */

$app = new App\Pictobox;