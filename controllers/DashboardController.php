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
            $this->route = '';
        }


        // $this->redirect_to('/dashboard/' . $this->route, 302 );

    }

    public function redirect_to($url, $status_code) {
        header('Location: ' . $url, true, (isset($status_code) ? $status_code : '301'));
        die();
    }

    public function getRoute () {
        return $this->route;
    }




}