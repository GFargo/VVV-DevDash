<?php
/**
 * DevDash - Custom Dashboard for VVV
 *
 * @author Griffen Fargo <ghfargo@gmail.com>
 *
 * @param {string}  $url
 * @param {int}     $status_code
 */


function DevDash_DashboardRedirect( $url, $status_code) {
	header('Location: ' . $url, true, (isset($status_code) ? $status_code : '301'));
	die();
}

DevDash_DashboardRedirect( '/dashboard/index.php', 302 );