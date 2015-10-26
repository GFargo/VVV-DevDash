<?php

require_once('get_hosts.php');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>DevDash</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css" />
	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">


	<script type="text/JavaScript" src="bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/devdash.js"></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="./">Dashboard</a>
		</div>

		<ul class="nav navbar-nav">
			<li><a href="/database-admin/" target="_blank">phpMyAdmin</a></li>
			<li><a href="/memcached-admin/" target="_blank">phpMemcachedAdmin</a></li>
			<li><a href="/opcache-status/opcache.php" target="_blank">Opcache Status</a></li>
			<li><a href="/webgrind/" target="_blank">Webgrind</a></li>
			<li><a href="/phpinfo/" target="_blank">PHP Info</a></li>
		</ul>
	</div>
</div>

<div class="content_container container-fluid">
	<div class="col-sm-4 col-md-3 sidebar">
		<?php require_once 'views/sidebar.php'; ?>
	</div>
	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
		<h1 class="page-header">VVV Dashboard</h1>

		<div class="row">
			<div class="col-sm-12 hosts">
				<div class="main_content">

					<p>
					    <strong>Current Hosts = <?php echo isset( $hosts['site_count'] ) ? $hosts['site_count'] : ''; ?></strong>
					</p>
					<small>Note: To profile, <code>xdebug_on</code> must be set.</small>

					<p id="search_container" class="search-box">
					    Live Search:

					    <input type="text" class="search-input" id="text-search" />
					    <!--<input id="search" type="button" value="Search" />
					    <input id="back" type="button" value="Search Up" /> &nbsp;
					    <small>Enter, Up and Down keys are bound.</small>-->
					</p>

					<table class="sites table table-responsive table-striped">
					    <thead>
					    <tr>
					        <th>Debug Mode</th>
					        <th>Sites</th>
					        <th>Actions</th>
					    </tr>
					    </thead>
					    <?php
					    foreach ( $hosts as $key => $array ) {
					        if ( 'site_count' != $key ) { ?>
					            <tr>
					                <?php if ( 'true' == $array['debug'] ) { ?>
					                    <td><span class="label label-success">Debug On</span></td>
					                <?php } else { ?>
					                    <td><span class="label label-danger">Debug Off</span></td>
					                <?php } ?>
					                <td><?php echo $array['host']; ?></td>

					                <td>
					                    <a class="btn btn-primary btn-xs" href="http://<?php echo $array['host']; ?>/" target="_blank">Visit Site</a>

					                    <?php if ( 'true' == $array['is_wp'] ) { ?>
					                        <a class="btn btn-warning btn-xs" href="http://<?php echo $array['host']; ?>/wp-admin" target="_blank">Admin/Login</a>
					                    <?php } ?>
					                    <a class="btn btn-success btn-xs" href="http://<?php echo $array['host']; ?>/?XDEBUG_PROFILE" target="_blank">Profiler</a>
					                </td>
					            </tr>
					            <?php
					        }
					    }
					    unset( $array ); ?>
					</table>

					<?php require 'views/commands.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require 'views/footer.php'; ?>
</body>
</html>