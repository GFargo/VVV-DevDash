<?php
/**
* DevDash
*/

require 'models/Server.php';
require 'controllers/DevDashController.php';

class DevDash
{

    // DevDash Controller
    public $controller;

    public $server;


    function __construct($config)
    {
        // Var Dump Variables
        var_dump($config);

        // Setup the Controller
        $this->controller = new DashboardController($config['routes']);

        $this->server = new Server();


        echo "Route: ";
        var_dump($this->controller->getRoute());

    }

    function run ()
    {
        echo "Pie: " . DEVDASH_XDEBUG . " --- " . DEVDASH_SCAN_DEPTH;
    }





}


?>