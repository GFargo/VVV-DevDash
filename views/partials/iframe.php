<?php


// Very Crude Content Router ;p
if (isset($_POST['module']) && ($_POST['module'] != 'phpMyAdmin' && $_POST['module'] != 'Dashboard')) {
    $path = $_POST['module'];
    switch ($path) {
        // case 'phpMyAdmin':
        //     $path = '/database-admin/';
        //     break;
        case 'phpMemcachedAdmin':
            $path = '/memcached-admin/';
            break;
        case 'Opcache Status':
            $path = '/opcache-status/opcache.php';
            break;
        case 'Webgrind':
            $path = '/webgrind/';
            break;
        // case 'Mailcatcher':
        //     $path = 'http://vvv.dev:1080/';
        //     break;
        case 'PHP Info':
        default:
            $path = '/phpinfo/';
            break;
    }

    $html = '<iframe src="'.$path.'" height="720px" width="100%" frameborder="0"></iframe>';

    echo $html;

}


?>