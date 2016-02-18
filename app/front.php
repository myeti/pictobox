<?php

/**
 * PHP Settings
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

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
 * Debug mode
 */

use Colorium\Http;

$request = Http\Request::globals();

$request->local[] = '10.0.2.2';
if($request->local()) {
//    require 'front/whoops.php';
}



/**
 * Execute default context
 */

$app->run()->end();