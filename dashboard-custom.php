<?php
/**
 * DevDash - Custom Dashboard for VVV
 *
 * @author Griffen Fargo <ghfargo@gmail.com>
 *
 * @param {string}  $url
 * @param {int}     $status_code
 */


// // Load Dashboard Configs
// $config = require( __DIR__ . '/dashboard/config/DevDash/config.php');

// // Initialize Dashboard
// require( __DIR__ . '/dashboard/views/DevDash.php');

// $DevDash = new DevDash($config);





function DevDash_DashboardRedirect( $url, $status_code) {
	header('Location: ' . $url, true, (isset($status_code) ? $status_code : '301'));
	die();
}

DevDash_DashboardRedirect( '/dashboard/', 302 );