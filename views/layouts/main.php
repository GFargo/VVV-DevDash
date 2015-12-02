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
    <script type="text/JavaScript" src="bower_components/underscore/underscore-min.js"></script>
    <!-- DevDash Javascript -->
    <script type="text/javascript" src="assets/js/devdash.js"></script>
</head>
<body>

<?php $Dashboard->render('partials/navigation.php'); ?>

<div class="content_container container-fluid">

    <!-- sidebar -->
    <div class="col-sm-4 col-md-3 sidebar open">
        <div>
            <?php require ('views/partials/sidebar.php'); ?>
        </div>
    </div>

    <!-- main content -->
    <div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
        <div class="hosts">
            <div id="main_content" class="main_content">
                <div class="vvv-module">
                    <?php //require 'views/partials/content.php'; ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div> <!-- /.col-sm-8 -->
</div> <!-- /.content_container -->

<?php $Dashboard->render('partials/footer.php'); ?>
</body>
</html>