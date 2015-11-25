<?php
/**
 * Server class created to assist with interacting with the VVV Installation
 *
 * @author      GFargo <https://github.com/GFargo/>
 *
 */



require 'models/Site.php';

$path = '../../';


/**
* Server Utilities
*/
class Server
{
    public $sites_path = '../../';

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
            'dashboard'                 => 'vvv.dev',
            'wordpress-develop/build'   => 'build.wordpress-develop.dev',
            'wordpress-develop/src'     => 'src.wordpress-develop.dev',
            'wordpress-trunk'           => 'local.wordpress-trunk.dev',
            'wordpress-default'         => 'local.wordpress.dev'
        );

        $this->getEnvironment();
    }

    //
    public function getEnvironment ()
    {

        $this->parseFiles( $this->scanFiles() );


        echo "<h3>this->sites</h3>";
        var_dump($this->sites);

        echo "<h3>this->hosts</h3>";
        var_dump($this->hosts);



        // Store Site Count
        $this->site_count = count( $this->sites );
        echo "<h3>Count: $this->site_count</h3>";
    }

    private function parseHost ($host)
    {
        $lines = file( $host->getPathname() ); // Create array from each line of the file
        $file_path  = str_replace( array( '../../', '/vvv-hosts' ), array(), $host->getPathname() );

        $foundHosts = 0;
        // read through the lines in our host files
        foreach ( $lines as $num => $line ) {

            // skip both comment lines and empty lines
            if ( !strstr( $line, '#' ) && strlen($line) > 1 ) {
                // Sk
                if ( 'vvv.dev' != trim( $line )) {
                    // echo "<h1>VVV.DEV FOUND</h1>";
                }

                //
                //
                // ToDo: Design Flaw - Assumes Host is first entry found in file
                //
                //

                if (isset($this->sites[$file_path])) {
                    if ($foundHosts < 1) {
                        $this->sites[$file_path]->host = trim( $line );
                    } else {
                        $this->sites[$file_path]->subdomains[$foundHosts-1] = trim( $line );
                    }
                } elseif( in_array(trim( $line ), $this->default_hosts)) {
                    echo "WE GOT A DEFAULT: $file_path <br>";
                    echo "$num \ $line <br>";
                } else {
                    echo "WTF IS THIS: $file_path <br>";
                    echo "$num \ $line <br>";
                }

                ++$foundHosts;
            }
        }
    }

    private function parseWpConfig ($config)
    {
        $config_lines = file( $config->getPathname() );
        $name         = str_replace( array( '../../', '/wp-config.php', '/htdocs' ), array(), $config->getPathname() );

        // Passing only the name will create new site class if it doesn't already exist
        $site = $this->siteHandler($name);

        // WP Config Checks
        // Regex generated with http://txt2re.com/
        /////////////////////
        $lineRegexFilters = array(
            'wp_define_value_string' => '.*?(?:[a-z][a-z]+).*?(?:[a-z][a-z]+).*?(?:[a-z][a-z]+).*?((?:[a-z][a-z]+))',
            'wp_define_value_var'    => '.*?(?:[a-z][a-z0-9_]*).*?(?:[a-z][a-z0-9_]*).*?((?:[a-z][a-z0-9_]*))',
            'table_prefix_value'    => '.*?(?:[a-z][a-z0-9_]*).*?((?:[a-z][a-z0-9_]*))',
        );

        // Set Hosts on Default Sites √
        if (isset($this->sites[$name]) && isset($this->default_hosts[$name])) {
            $this->sites[$name]->host = $this->default_hosts[$name];
        }

        // Set path √
        $this->sites[$name]->path = $config->getPathname();

        // Set Git √
        $this->sites[$name]->git = $this->checkWpContentGit($this->sites_path, $name);

        // read through the lines in our host files
        foreach ( $config_lines as $num => $line ) {

            // Skip blank lines and comments
            if ( ! strstr( $line, '#' ) && strlen($line) > 1 ) {

                // Environment Check √
                if ( strstr( $line, "\"WP_ENV\"" ) || strstr( $line, "'WP_ENV'" ) || strstr( $line, "\"WP_ENVIRONMENT\"" ) || strstr( $line, "'WP_ENVIRONMENT'" )  ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['wp_define_value_string']."/is", $line, $environmnt_matches)) {
                        $this->sites[$name]->environment = $environmnt_matches[1][0];
                    }
                }

                // Database Check
                //// Database Name √
                if ( strstr( $line, "DB_NAME" ) ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['wp_define_value_string']."/is", $line, $matches)) {
                        $this->sites[$name]->db['name'] = $matches[1][0];
                    }
                }
                //// Database Host √
                if ( strstr( $line, "DB_HOST" ) ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['wp_define_value_string']."/is", $line, $matches)) {
                        $this->sites[$name]->db['host'] = $matches[1][0];
                    }
                }
                //// Database Table Prefix √
                if ( strstr( $line, "table_prefix" ) ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['table_prefix_value']."/is", $line, $matches)) {
                        $this->sites[$name]->db['table_prefix'] = $matches[1][0];
                    }
                }
                //// Database User √
                if ( strstr( $line, "DB_USER" ) ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['wp_define_value_string']."/is", $line, $matches)) {
                        $this->sites[$name]->db['user'] = $matches[1][0];
                    }
                }
                //// Database Password √
                if ( strstr( $line, "DB_PASSWORD" ) ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['wp_define_value_string']."/is", $line, $matches)) {
                        $this->sites[$name]->db['password'] = $matches[1][0];
                    }
                }
                //// Database Charset √
                if ( strstr( $line, "DB_CHARSET" ) ) {
                    if ($c = preg_match_all ("/".$lineRegexFilters['wp_define_value_string']."/is", $line, $matches)) {
                        $this->sites[$name]->db['charset'] = $matches[1][0];
                    }
                }

                // Wordpress Installation Type
                //// Check Multisite √
                if (strstr($line, "\"MULTISITE\"") || strstr($line, "'MULTISITE'")) {
                    $this->sites[$name]->installation['multi'] = true;
                }
                //// Check Subdomain Install √
                if (strstr($line, "\"SUBDOMAIN_INSTALL\"") || strstr($line, "'SUBDOMAIN_INSTALL'")) {
                   if (stristr( $line, "true" )) {
                       $this->sites[$name]->installation['subdomain'] = true;
                   }
                }

                // WPDebug Check √
                if ( strstr( $line, "\"WP_DEBUG\"" ) || strstr( $line, "'WP_DEBUG'" )  ) {
                    if (stristr( $line, "true" )) {
                        $this->sites[$name]->debug = true;
                    } elseif (stristr( $line, "false" )) {
                        $this->sites[$name]->debug = false;
                    } else {
                        $this->sites[$name]->debug = "invalid";
                    }
                }
            }
        }
    }

    // Create and handle Site()'s
    public function siteHandler ($name)
    {
        if (!isset($this->sites[$name])) {
            $newSite = new Site($name); // Create new site
            $this->sites[$name] = $newSite; // Store new site
            return $this->sites[$name]; // Return newly created site
        } else {
            // Site already exists
            return $this->sites[$name];


        }
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

    public function checkWpContentGit ( $path = '../../', $key )
    {
        if (isset($path) && isset($key)) {
            $git_wp_content = $path . $key . '/htdocs/wp-content/.git';
            if (file_exists($git_wp_content)) {
                return true;
            }
            return false;
        }
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

                // {SplFileInfo} $file
                echo "<h4>File Name: " . $file->getFileName() . "</h4>";
                echo "<h5>Path: " . $file->getPathname() . "</h5>";

                if ( $file->getFileName() == 'vvv-hosts' ) {
                    $this->parseHost($file);
                } elseif ( $file->getFileName() == 'wp-config.php' ) {
                    $this->parseWpConfig($file);
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