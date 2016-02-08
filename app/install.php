<?php

/**
 * File permissions
 */

define('__ROOT__', dirname(__DIR__));
define('CACHE_DIR', __ROOT__ . '/public/img/cache/');

chmod(__ROOT__ . '/instance', 0777);
chmod(__ROOT__ . '/instance/albums', 0777);
chmod(CACHE_DIR, 0777);

echo (string)is_writable(__ROOT__ . '/instance');
echo (string)is_writable(__ROOT__ . '/instance/albums');
echo (string)is_writable(CACHE_DIR);


/**
 * Database reset
 */

use Colorium\Orm;
use App\Model\User;

$sqlite = new Orm\SQLite(DB_FILE, [
    'user' => User::class
]);

Orm\Hub::source($sqlite);

$sqlite->builder('user')->wipe();
$sqlite->builder('user')->create();

$admin = new User('Admin', sha1(PWD_SALT . 'admin'), 'you@pictobox.io', User::ADMIN);
$admin->save();