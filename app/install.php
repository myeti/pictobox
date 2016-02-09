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

$admin = new User(ADMIN_NAME, sha1(PWD_SALT . ADMIN_PWD), ADMIN_EMAIL, User::ADMIN);
$admin->save();