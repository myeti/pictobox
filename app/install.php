<?php

require 'instance.php';



/**
 * File permissions
 */

chmod(__ROOT__ . '/instance', 0777);
chmod(__ROOT__ . '/instance/albums', 0777);
chmod(CACHE_DIR, 0777);

echo (string)is_writable(__ROOT__ . '/instance');
echo (string)is_writable(__ROOT__ . '/instance/albums');
echo (string)is_writable(CACHE_DIR);



/**
 * Database reset
 */

use Pictobox\Model\User;

User::builder()->wipe();
User::builder()->create();

$admin = new User(ADMIN_NAME, sha1(PWD_SALT . ADMIN_PWD), ADMIN_EMAIL, User::ADMIN);
$admin->save();