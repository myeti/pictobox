<?php

/**
 * PHP Settings
 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require 'instance.php';



/**
 * Authentication setup
 */

use Colorium\Stateful\Auth;
use App\Model\User;

Auth::factory(function($id) {
    return User::one(['id' => $id]);
});



/**
 * Generate context
 */

use Colorium\App;

$context = App\Front::context();



/**
 * Debug mode
 */

$trusted = ['127.0.0.1', '::1', '10.0.2.2'];
if(in_array($context->request->ip, $trusted)) {
    require 'front/whoops.php';
}



/**
 * Execute default context
 */

$app->run($context)->end();