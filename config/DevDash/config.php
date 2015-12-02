<?php

/*
* DevDash Constants
*/

// XDebug Boolean
define('DEVDASH_XDEBUG', "false");

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
    'tutorial_steps' => array(
        ['content' => "Welcome to DevDash a thoughtfully crafted VVV Dashboard.  <b>DevDash</b> strives to offer a comprehensive collection of tools to help with <i>the management of</i> <b>VVV</b>, <b>VV Site Wizard</b>, and wp_content folders managed by <b>git</b>."],
        ['content' => "The sidebar is now <b>collapsible</b> allowing you to focus on whats important. </br><i>The sidebar also will automatically close when viewing a service page in header</i>."],
    ),
);

return $config;
?>