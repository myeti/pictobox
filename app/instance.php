<?php

ini_set('memory_limit', '512M');

define('__ROOT__', dirname(__DIR__));
define('__APP__', __DIR__);

require __ROOT__ . '/vendor/autoload.php';
require __ROOT__ . '/files/config.php';



/**
 * App constants
 */

define('CACHE_URL', '/img/cache/');
define('CACHE_DIR', __ROOT__ . '/public/img/cache/');



/**
 * Load lang
 */

use Colorium\Text;
use App\Service\Spyc;

Text\Lang::load([
    'fr' => Spyc::YAMLLoad(__DIR__ . '/langs/french.yml')
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