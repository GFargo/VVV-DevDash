<?php

// Load Composer Plugins
require 'vendor/autoload.php';

// Load Dashboard Configs
$config = require('config/DevDash/config.php');

// Initialize Dashboard
require('views/DevDash.php');

$DevDash = new DevDash($config);


?>