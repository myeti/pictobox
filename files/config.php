<?php

define('APP_LIVE', true);

define('APP_NAME', 'Pictobox');
define('APP_EMAIL', 'noreply@domain.tld');
define('APP_HOST', 'domain.tld');

define('ALBUMS_DIR', __DIR__ . '/albums/');
define('LOGS_DIR', __DIR__ . '/logs/');
define('DB_FILE', __DIR__ . '/database.sqlite');

define('PWD_SALT', '%oeSnF#HUdKc1Thimca8ZOcA58ojIqh_H$LGrB-&');
define('COOKIE_SALT_KEY', '2nUNboXgKuCr4cn4flvD3YrmodNb1'); // copy in ./public/.htaccess
define('COOKIE_SALT_VALUE', '85MS4wx'); // copy in ./public/.htaccess

define('ADMIN_NAME', 'Admin');
define('ADMIN_PWD', 'admin');
define('ADMIN_EMAIL', 'admin@domain.tld');

define('SLACK_WEBHOOK', null);
define('SLACK_CHANNEL', null);
define('SLACK_BOTNAME', null);

define('MAPBOX_TOKEN', null);
define('MAPBOX_PROJECT', null);
define('MAPBOX_COORD', null);