<?php


$html = '<div class="vvv-module">';

// Very Crude Content Router ;p
if (isset($_POST['module']) && ($_POST['module'] != 'phpMyAdmin' && $_POST['module'] != 'Dashboard')) {
    $path = $_POST['module'];
    switch ($path) {
        // case 'phpMyAdmin':
        //     $path = '/database-admin/';
        //     break;
        case 'phpMemcachedAdmin':
            $path = '/memcached-admin/';
            break;
        case 'Opcache Status':
            $path = '/opcache-status/opcache.php';
            break;
        case 'Webgrind':
            $path = '/webgrind/';
            break;
        // case 'Mailcatcher':
        //     $path = 'http://vvv.dev:1080/';
        //     break;
        case 'PHP Info':
        default:
            $path = '/phpinfo/';
            break;
    }

    $html = '<iframe src="'.$path.'" height="720px" width="100%" frameborder="0"></iframe>';

    echo $html;

} else {

    echo $html;

    require('get_hosts.php');

    $hosts = get_hosts( '../../' );

?>


    <div id="search_container" class="input-group search-box">
        <span class="input-group-addon">
            <i class="fa fa-search"></i>
        </span>
        <input type="text" class="form-control search-input" id="text-search" placeholder="Search active machines..."/>
        <span class="input-group-addon">
            Hosts <span class="badge"><?php echo isset( $hosts['site_count'] ) ? $hosts['site_count'] : ''; ?></span>
        </span>
    </div><!-- /input-group -->

    <table class="sites table table-responsive table-striped">
        <thead>
        <tr>
            <th>Debug Mode</th>
            <th>Sites</th>
            <th>Actions</th>
        </tr>
        </thead>
        <?php

        foreach ( $hosts as $key => $array ) {
            // List of Subdomains
            $subdomains = '';
            if ( 'site_count' != $key ) { ?>

                <tr>
                    <?php if ( 'true' == $array['debug'] ) { ?>
                        <td><span class="label label-primary">Debug <i class="fa fa-check-circle-o"></i></span></td>
                    <?php } else { ?>
                        <td><span class="label label-danger">Debug <i class="fa fa-times-circle-o"></i></span></td>
                    <?php } ?>
                    <td class="domains">
                        <?php
                        // Echo Main Domain
                        echo $array['host'];

                        // Collect Subdomains
                        for ($count=0; $count < sizeof($array); $count++) {
                            if (!empty($array['subdomain'.$count])) {
                                $subdomains .= '<li><i class=\'fa fa-globe\'></i> ' . $array['subdomain'.$count] . '</li>';
                            }
                        }

                        if (!empty($subdomains)) {
                            $subdomains = "<ul class='list-unstyled'>".$subdomains."</ul>";
                        ?>
                            <button type="button" class="btn btn-inline btn-xs tip pop" data-container="body" data-toggle="popover" data-html="true" data-placement="top"
                                    title="Subdomains for <?= $array['host']; ?>" data-content="<?= $subdomains ?>">
                                <i class="fa fa-sticky-note"></i>
                            </button>
                        <?php } ?>
                    </td>

                    <td>
                        <a class="btn btn-primary btn-xs" href="http://<?php echo $array['host']; ?>/" target="_blank">Visit Site <i class="fa fa-external-link"></i></a>

                        <a class="btn btn-danger btn-xs tip tool" href="http://<?php echo $array['host']; ?>/?XDEBUG_PROFILE" target="_blank"
                            data-toggle="tooltip" title="`xdebug_on` must be turned on in VM" data-placement="top">
                            Profiler <i class="fa fa-search-plus"></i>
                        </a>

                        <?php if ( 'true' == $array['is_wp'] ) { ?>
                            <a class="btn btn-success btn-xs" href="http://<?php echo $array['host']; ?>/wp-admin" target="_blank"><i class="fa fa-wordpress"></i> Admin</a>

                            <?php if ( !in_array($key, $default_hosts) ) { ?>
                                <button class="btn btn-warning btn-xs tip pop remove-host" data-container="body" data-toggle="popover" data-html="true" data-placement="top"
                                        title="Personal Install" data-content="Removed via command line via:</br> <code>$ vv remove <?php echo $array['host']; ?> </code>">
                                    <i class="fa fa-unlock"></i>
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-warning btn-xs tip pop" data-container="body" data-toggle="popover" data-html="true" data-placement="top"
                                        title="Core Install" data-content="Part of VVV core installation, these sites cannot be removed or deleted.">
                                    <i class="fa fa-lock"></i>
                                </button>
                            <?php }
                        } ?>
                    </td>
                </tr>
                <?php
            }
        }
        unset( $array ); ?>
    </table>

<?php
require 'commands.php';

}

echo '</div>';;
?>








