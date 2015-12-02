<?php
/**
* DevDash
*/

require 'components/html.class.php';
require 'components/Git.php';
require 'models/Server.php';
require 'views/SiteManager.php';
require 'controllers/DashboardController.php';

class DevDash
{

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


        // echo "Route: ";
        // var_dump($this->controller->getRoute());

    }

    public function run ()
    {

        /// Determine Route Information

        // Load Data and pass into Main
        $Dashboard = new SiteManager([
            'site_count'    => $this->server->site_count,
            'sites'         => $this->server->sites,
            'default_hosts' => $this->server->default_hosts,
        ]);

        require $this->layout;


    }

}


?>