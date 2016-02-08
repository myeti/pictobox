<?php

ini_set('memory_limit', '512M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
require '../instance/config.php';
require '../app/install.php';