<?php ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>DevDash</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="bower_components/intro.js/minified/introjs.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css" />


	<!-- jQuery -->
	<script type="text/JavaScript" src="bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap.js -->
	<script type="text/JavaScript" src="bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>
	<!-- Intro.js -->
	<script type="text/JavaScript" src="bower_components/intro.js/minified/intro.min.js"></script>
	<!-- DevDash Javascript -->
	<script type="text/javascript" src="assets/js/devdash.js"></script>
</head>
<body>

<?php require_once 'views/header.php'; ?>

<div class="content_container container-fluid">

	<!-- sidebar -->
	<div class="col-sm-4 col-md-3 sidebar open">
		<div>
			<?php require_once 'views/sidebar.php'; ?>
		</div>
	</div>

	<!-- main content -->
	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
		<h1 class="page-header" data-step="1" data-html="true" data-intro="Welcome to the new and improved VVV Dashboard originally forked from <a href='https://github.com/topdown/VVV-Dashboard' target='_blank'>@topdown</a>.  <b>DevDash</b> strives to offer a comprehensive collection of tools to help with <i>the management of</i> <b>VVV</b> and <b>VV Site Wizard</b>.">
			VVV DevDash
		</h1>

		<div class="row">
			<div class="col-sm-12 hosts">
				<div id="main_content" class="main_content">
					<?php require'views/content.php'; ?>
				</div>
			</div>
		</div> <!-- /.row -->
	</div> <!-- /.col-sm-8 -->
</div> <!-- /.content_container -->

<?php require 'views/footer.php'; ?>
</body>
</html>