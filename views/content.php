<?php

$html = '<div class="vvv-module">';

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
    <p>
        <strong>Current Hosts <span class="badge"><?php echo isset( $hosts['site_count'] ) ? $hosts['site_count'] : ''; ?></span></strong>
    </p>
    <small>Note: To profile, <code>xdebug_on</code> must be set.</small>

    <div id="search_container" class="search-box">
        <label>live search</label>
        <input type="text" class="search-input" id="text-search" />
    </div>

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
            if ( 'site_count' != $key ) { ?>
                <tr>
                    <?php if ( 'true' == $array['debug'] ) { ?>
                        <td><span class="label label-primary">Debug <i class="fa fa-check-circle-o"></i></span></td>
                    <?php } else { ?>
                        <td><span class="label label-danger">Debug <i class="fa fa-times-circle-o"></i></span></td>
                    <?php } ?>
                    <td><?php echo $array['host']; ?></td>

                    <td>
                        <a class="btn btn-primary btn-xs" href="http://<?php echo $array['host']; ?>/" target="_blank">Visit Site <i class="fa fa-external-link"></i></a>

                        <?php if ( 'true' == $array['is_wp'] ) { ?>
                            <a class="btn btn-warning btn-xs" href="http://<?php echo $array['host']; ?>/wp-admin" target="_blank"><i class="fa fa-wordpress"></i> Admin</a>
                        <?php } ?>
                        <a class="btn btn-success btn-xs" href="http://<?php echo $array['host']; ?>/?XDEBUG_PROFILE" target="_blank">Profiler <i class="fa fa-search-plus"></i></a>
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








