<?php

// Load Dashboard Configs
$config = require('config/DevDash/config.php');

// Initialize Dashboard
require('views/DevDash.php');

$DevDash = new DevDash($config);


// $DevDash->run(); // Triggers startup of DevDash & output of HTML

// $DevDash->render();

?>