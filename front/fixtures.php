<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Colorium\Orm\Mapper;
use App\Model\User;

$sqlite = Mapper::SQLite(__DIR__ . '/pictobox.db');
$sqlite->map('user', User::class);

User::builder()->wipe();
User::builder()->create();

$admin = new User('Admin', null, User::ADMIN);
$admin->add();