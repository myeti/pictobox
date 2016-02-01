<?php

/**
 * File permissions
 */

chmod(__DIR__ . '/orm', 0777);
chmod(__DIR__ . '/../public/img/albums', 0777);
chmod(__DIR__ . '/../public/img/cache', 0777);


/**
 * Database reset
 */

use Colorium\Orm;
use App\Model\User;

$sqlite = new Orm\SQLite(__DIR__ . '/orm/pictobox.db', [
    'user' => User::class
]);

Orm\Hub::source($sqlite);

$sqlite->builder('user')->wipe();
$sqlite->builder('user')->create();

$admin = new User('Admin', sha1('EfiM$&5/*.w64$yPM3dadmin'), 'you@pictobox.io', User::ADMIN);
$admin->save();