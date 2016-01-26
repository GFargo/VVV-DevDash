<?php
/**
 * Server class created to assist with interacting with the VVV Installation
 *
 * @author      GFargo <https://github.com/GFargo/>
 *
 */
require_once('models/Site.php');


/**
* Server Utilities
*/
class Server
{
    public $sites_path = '../../';

    public $sites = array();

    public $search_config = array();

    public $site_count;

    public $working_directory;

    public $default_hosts;

    private $cache_path = '.devdash-cache/site_data.json';

    public $_COOKIE;



    public function __construct()
    {
        // Search Config Setup
        $this->search_config = array(
            'scan_depth' => (DEVDASH_SCAN_DEPTH ? DEVDASH_SCAN_DEPTH : '2'),
            'blacklist' => array(),
            'whitelist' => array( 'vvv-hosts', 'wp-config.php'),
        );

        // Setup Default Hosts
        $this->default_hosts = array(
            'dashboard'                 => 'vvv.dev',
            'wordpress-default'         => 'local.wordpress.dev',
            'wordpress-trunk'           => 'local.wordpress-trunk.dev',
            'wordpress-develop/src'     => 'src.wordpress-develop.dev',
            'wordpress-develop/build'   => 'build.wordpress-develop.dev'
        );

        $this->getEnvironment();

        // Store Site Count
        $this->site_count = sizeof( json_decode(json_encode( $this->sites ), true) );
    }

    //
    public function getEnvironment ()
    {
        // Check for Cache
        if (!file_exists($this->cache_path) || !isset($_COOKIE['DevDash_Update'])) {
            $this->parseFiles( $this->scanFiles() );

            // Save Site Cache
            $this->saveCache('DevDash_Update', $this->sites, false);
        } else {
            $this->sites = $this->getCache();
        }

    }


    private function saveCache ($name = 'DevDash_Update', $data, $force = false)
    {
        // echo "<br> ....saving data....<br>";
        if (!isset($data) || !isset($name)) {
            throw new Exception("Missing argument to store", 1);
        }
        // Save data if forced or cookie currently not present
        if ($force || !isset($_COOKIE['DevDash_Update'])) {
            setcookie('DevDash_Update', time(), time()+60*60*24*3); // expire in 3 days
            // Save Json to Cache File
            $fp_site_cache = fopen($this->cache_path, 'w');
            fwrite($fp_site_cache, json_encode($data));
            fclose($fp_site_cache);
        }
    }


    public function getCache()
    {
        if (file_exists($this->cache_path)) {
            // Read Json from Cache File
            $fp_site_cache = fopen($this->cache_path, 'r');
            $cache_contents = fread($fp_site_cache, filesize($this->cache_path));
            fclose($fp_site_cache);

            return json_decode($cache_contents);
        } else {
            return false;
        }
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
                // ToDo: Design Flaw - Assumes Host is first entry found in file
                //

                if (isset($this->sites[$file_path])) {
                    if ($foundHosts < 1) {
                        $this->sites[$file_path]->host = trim( $line );
                    } else {
                        $this->sites[$file_path]->subdomains[$foundHosts-1] = trim( $line );
                    }
                } elseif( in_array(trim( $line ), $this->default_hosts)) {
                    // echo "WE GOT A DEFAULT: $file_path <br>";
                    // echo "$num \ $line <br>";
                } else {
                    // echo "WTF IS THIS: $file_path <br>";
                    // echo "$num \ $line <br>";
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




                // TODO: Refactor into appropriate dialog message
                echo "<h4>File Name: " . $file->getFileName() . " | Path: " . $file->getPathname() . "</h4>"; // {SplFileInfo} $file

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


}

?>