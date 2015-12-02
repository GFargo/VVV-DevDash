<?php
/**
* DashboardController
*/

class DashboardController
{
    public $action;

    public $route;


    function __construct($routes)
    {
        // Set current route
        if (isset($_POST['module'])) {
            $action = $_POST['module'];
            $this->route = $this->routes[$action];
        } else {
            $this->route = '/';
        }
    }

    public function getRoute () {
        return $this->route;
    }




}