<?php
/**
 * @Author: Griffen Fargo
 * @Date:   2016-01-26 00:20:13
 * @Last Modified by:   Griffen Fargo
 * @Last Modified time: 2016-04-11 19:30:09
 */


// Load Composer Plugins
require 'vendor/autoload.php';


use AdamBrett\ShellWrapper\Command;
use AdamBrett\ShellWrapper\Command\Param;

use AdamBrett\ShellWrapper\Runners\Exec;
use AdamBrett\ShellWrapper\Runners\ShellExec;
use AdamBrett\ShellWrapper\Runners\System;
use AdamBrett\ShellWrapper\Runners\ReturnValue;

use AdamBrett\ShellWrapper\Command\Builder as CommandBuilder;


$shell = new ShellExec();
$system = new System();


$cmd = $_POST['command'];



// XDebug Stuff
if ($cmd == 'xdebug') {


    $xdebugCommand = new Command($cmd);

    // $debugCheck = new CommandBuilder('php');
    // $debugCheck->addFlag('m')
    //         ->addFlag('c |')
    //         // ->addArgument('|')
    //         ->addArgument('grep xdebug');
    //         // ->addArgument('xdebug');

    $debugCheck = new Command('php -m -c | grep xdebug');
    $check = $system->run($debugCheck);

    echo "current check: " . $check;


    $inipath = php_ini_loaded_file();

    if ($inipath) {
        echo 'Loaded php.ini: ' . $inipath;
    } else {
        echo 'A php.ini file is not loaded';
    }



    if ( empty($check) || $check == null ) {
        // echo "CHECK IS NULL - Run DEBUG ON";
        $shell->run(new Command('xdebug_on'));
    } else {
        // echo "RUN XDEBUG OFF";
        $shell->run(new Command('xdebug_off'));
    }
}

