<?php
/**
 * Server class created to assist with interacting with the VVV Installation
 *
 * @author      GFargo <https://github.com/GFargo/>
 *
 */



$path = '../../';


/**
* Server Utilities
*/
class Server
{

    public $hosts = array();

    public $sites = array();

    public $search_config = array();

    public $site_count;

    public $working_directory;

    private $default_hosts;

    public $_COOKIE;


    function __construct()
    {
        // Search Config Setup
        $this->search_config = array(
            // 'scan_depth' => (DEVDASH_SCAN_DEPTH ? DEVDASH_SCAN_DEPTH : '2'),
            'scan_depth' => '2',
            'blacklist' => array(),
            'whitelist' => array( 'vvv-hosts', 'wp-config.php'),
        );

        // Setup Default Hosts
        $this->default_hosts = array(
            'wordpress-develop/build',
            'wordpress-develop/src',
            'wordpress-trunk',
            'wordpress-default'
        );

        $this->getEnvironment();
    }

    //
    public function getEnvironment ()
    {
        $this->parseFiles( $this->scanFiles() );
    }

    private function parseHosts ($hosts)
    {

    }

    private function parseWpConfig ($config)
    {

    }


    public function scanFiles ( $path = '../../' )
    {
        $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS) );
        if (!is_object($files)) {
            return null;
        }
        // max depth for the recursive iterator through child directories
        $files->setMaxDepth($this->search_config['scan_depth']);

        return $files;
    }

    public function parseFiles ( RecursiveIteratorIterator $files, $config = null )
    {
        if (!isset($files)) {
            return;
        }

        $config = (isset($config) ? $config : $this->search_config);

        // Parse files returned from scanFiles()
        foreach ( $files as $file_path => $file ) {
            // Check if file is in search config whitelist
            if ( $this->checkSearchResult($file) ) {
                $lines = file( $file_path ); // Create array from each line of the file
                $file_path  = str_replace( array( '../../', '/vvv-hosts' ), array(), $file_path );

                // // {SplFileInfo} $file
                // echo "<hr>";
                echo "<h4>File: " . $file->getFileName() . "</h4>";
                // var_dump($lines);
                if (true) {
                    # code...
                }
            }
        }

    }

    private function checkSearchResult ( $file, $config = null )
    {
        $config = (isset($config) ? $config : $this->search_config);
        $passCheck = false;
        // Ignore Directories
        if ($file->isDir()) {
            return $passCheck;
        }
        // Check Blacklist
        if (isset($config['blacklist'])) {
            foreach ($config['blacklist'] as $key => $blacklist_entry) {
                if(strstr($file->getFileName(), $blacklist_entry)) {
                    $passCheck = false;
                }
            }
        }
        // Check Whitelist
        if (isset($config['whitelist'])) {
            foreach ($config['whitelist'] as $key => $whitelist_entry) {
                if(strstr($file->getFileName(), $whitelist_entry)) {
                    $passCheck = true;
                    return $passCheck;
                } else {
                    $passCheck = false;
                }
            }
        }
        return $passCheck;
    }

    public function getSiteCount ()
    {

    }

    public function setHosts  ()
    {

    }

    public function getHosts ()
    {

    }

}


/**
 * Create an array of the hosts from all of the VVV host files
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2014 ValidWebs.com
 *
 * Created:    5/23/14, 12:57 PM
 *
 * @param $path
 *
 * @return array
 */
function get_hosts( $path ) {

    $array = array();
    $debug = array();
    $hosts = array();
    $wp    = array();
    $depth = 2;
    $site  = new RecursiveDirectoryIterator( $path, RecursiveDirectoryIterator::SKIP_DOTS );
    $files = new RecursiveIteratorIterator( $site );


    if ( ! is_object( $files ) ) {
        return null;
    }


    $files->setMaxDepth( $depth );

    // Loop through the file list and find what we want
    foreach ( $files as $name => $object ) {


        if ( strstr( $name, 'vvv-hosts' ) && ! is_dir( 'vvv-hosts' ) ) {
            echo "<hr><h4>File:</h4>";
            var_dump($object);

            $lines = file( $name ); // Extra lines from file
            echo "<h5>Name:</h5>";
            var_dump($name);
            $name  = str_replace( array( '../../', '/vvv-hosts' ), array(), $name );


            // read through the lines in our host files
            foreach ( $lines as $num => $line ) {
                // skip both comment lines and empty lines
                if ( ! strstr( $line, '#' ) && 'vvv.dev' != trim( $line ) && strlen($line) > 1 ) {
                    if ( 'vvv-hosts' == $name ) {
                        switch ( trim( $line ) ) {
                            case 'local.wordpress.dev' :
                                $hosts['wordpress-default'] = array( 'host' => trim( $line ) );
                                break;
                            case 'local.wordpress-trunk.dev' :
                                $hosts['wordpress-trunk'] = array( 'host' => trim( $line ) );
                                break;
                            case 'src.wordpress-develop.dev' :
                                $hosts['wordpress-develop/src'] = array( 'host' => trim( $line ) );
                                break;
                            case 'build.wordpress-develop.dev' :
                                $hosts['wordpress-develop/build'] = array( 'host' => trim( $line ) );
                                break;
                        }
                    }
                    if ( 'vvv-hosts' != $name && !empty($name)) {
                        if (empty($hosts[ $name ])) {
                            // array_push($hosts[ $name ], array( 'subdomain' => trim( $line )));
                            $hosts[ $name ]['host'] =  trim( $line );
                        } else {
                            $hosts[ $name ]['subdomain'.$num] = trim( $line );
                        }
                    }
                }
            }
        }

        if ( strstr( $name, 'wp-config.php' ) ) {

            $config_lines = file( $name );
            $name         = str_replace( array( '../../', '/wp-config.php', '/htdocs' ), array(), $name );

            // read through the lines in our host files
            foreach ( $config_lines as $num => $line ) {

                // skip comment lines
                if ( strstr( $line, "define('WP_DEBUG', true);" )
                     || strstr( $line, 'define("WP_DEBUG", true);' )
                     || strstr( $line, 'define( "WP_DEBUG", true );' )
                     || strstr( $line, "define( 'WP_DEBUG', true );" )
                ) {
                    $debug[ $name ] = array(
                        'path'  => $name,
                        'debug' => 'true',
                    );
                }
            }

            $wp[ $name ] = 'true';
        }
    }

    foreach ( $hosts as $key => $val ) {

        if ( array_key_exists( $key, $debug ) ) {
            if ( array_key_exists( $key, $wp ) ) {
                $array[ $key ] = $val + array( 'debug' => 'true', 'is_wp' => 'true' );
            } else {
                $array[ $key ] = $val + array( 'debug' => 'true', 'is_wp' => 'false' );
            }
        } else {
            if ( array_key_exists( $key, $wp ) ) {
                $array[ $key ] = $val + array( 'debug' => 'false', 'is_wp' => 'true' );
            } else {
                $array[ $key ] = $val + array( 'debug' => 'false', 'is_wp' => 'false' );
            }
        }
    }

    $array['site_count'] = count( $hosts );

    return $array;
}


?>