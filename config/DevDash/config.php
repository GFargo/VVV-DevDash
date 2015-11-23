<?php

/*
* DevDash Constants
*/

// XDebug Boolean
define('DEVDASH_XDEBUG', false);
// Cache Settings
define('DEVDASH_THEMES_TTL', 86400);
define('DEVDASH_PLUGINS_TTL', 86400);
define('DEVDASH_HOSTS_TTL', 86400);
// Server Settings
define('DEVDASH_SCAN_DEPTH', 2);


/*
* DevDash Config
*/

$config = array(
    'id' => 'DashDev',
    'wd' => getcwd(),
    'routes' => array (
        'phpMyAdmin'        => array(
            'protected' => false,
            'route'     => '/database-admin/',
        ),
        'phpMemcachedAdmin' => array(
            'protected' => false,
            'route'     => '/memcached-admin/',
        ),
        'Opcache Status'    => array(
            'protected' => false,
            'route'     => '/opcache-status/opcache.php',
        ),
        'Webgrind'          => array(
            'protected' => false,
            'route'     => '/webgrind/',
        ),
        'Mailcatcher'       => array(
            'protected' => false,
            'route'     => 'http://vvv.dev:1080/',
        ),
        'PHP Info'          => array(
            'protected' => false,
            'route'     => '/phpinfo/',
        ),
    ),
    'packages' => array(

    ),
);

return $config;
?>