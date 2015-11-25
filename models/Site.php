<?php
/**
* Site Base Class
*/


class Site
{
    public $name;

    public $host;

    public $subdomains = array();

    public $path;

    public $git;

    public $debug = false;

    public $environment;

    public $installation = array(
            'multi'         => false,
            'subdomain'     => false,
        );

    public $db = array(
            'name'          => null,
            'host'          => null,
            'table_prefix'  => null,
            'user'          => null,
            'password'      => null,
            'charset'       => null,
        );


    function __construct($name)
    {
        if (isset($name)) {
            $this->name = $name;
        }
    }


    // Getters and Setters

    public function setInstallationType ($multi, $subdomain)
    {
        // Check if MU Installation
        if (isset($multi)) {
            $this->installation['multi'] = $multi;
        }
        // Check for Subdomain Install
        if (isset($subdomain))
        {
            $this->installation['subdomain'] = $subdomain;
        }
    }


    public function setDatabase ($name, $host, $prefix, $user, $password, $charset)
    {
        // Set Name
        if (isset($name)) {
            $this->db['name'] = $name;
        }
        // Set Host
        if (isset($host)) {
            $this->db['host'] = $host;
        }
        // Set Table Prefix
        if (isset($prefix)) {
            $this->db['table_prefix'] = $prefix;
        }
        // Set User
        if (isset($user)) {
            $this->db['user'] = $user;
        }
        // Set Password
        if (isset($password)) {
            $this->db['password'] = $password;
        }
        // Set Charset
        if (isset($charset)) {
            $this->db['charset'] = $charset;
        }
    }


    public function setHost ($host)
    {
        if (isset($host) && is_string($host)) {
            $this->host = $host;
        }
    }

    public function getHost ()
    {
        return $this->host;
    }
    public function setPath ($path)
    {
        if (isset($path) && is_string($path)) {
            $this->path = $path;
        }
    }


}





?>