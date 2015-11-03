<?php

$path = '../../';
$default_hosts = array( 'wordpress-develop/build',
                        'wordpress-develop/src',
                        'wordpress-trunk',
                        'wordpress-default'
                    );

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

            $lines = file( $name );
            $name  = str_replace( array( '../../', '/vvv-hosts' ), array(), $name );

            // read through the lines in our host files
            foreach ( $lines as $num => $line ) {

                // skip comment lines
                if ( ! strstr( $line, '#' ) && 'vvv.dev' != trim( $line ) ) {
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
                    if ( 'vvv-hosts' != $name ) {
                        $hosts[ $name ] = array( 'host' => trim( $line ) );
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