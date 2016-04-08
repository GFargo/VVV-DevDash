<?php

require_once('components/html.class.php');
require_once('components/Git.php');

require_once('controllers/DashboardController.php');
require_once('models/Server.php');
require_once('views/SiteManager.php');

class DevDash
{
    /**
    * DevDash
    */


    // DevDash Controller
    public $controller;

    public $server;

    public $tutorial;

    private $layout = 'views/layouts/main.php';


    public function __construct($config)
    {

        // Setup the Controller
        $this->controller = new DashboardController($config['routes']);

        // Setup the Server
        $this->server = new Server();

        $this->tutorial = $config['tutorial_steps'];

        // Load Data and pass into Main
        $this->dashboard = new SiteManager([
            'site_count'    => $this->server->site_count,
            'sites'         => $this->server->sites,
            'default_hosts' => $this->server->default_hosts,
        ]);



        require $this->layout;
    }


}


?>