<?php
$config = require('config/DevDash/config.php');

require('views/DevDash.php');
$DevDash = new DevDash($config);

$DevDash->run();

?>