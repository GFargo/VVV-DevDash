<?php

require 'views/DashboardView.php';



/**
* SiteManager
*/
class SiteManager extends DashboardView
{
    public $count;
    public $sites;
    public $default_hosts;

    function __construct($params)
    {
        $this->count = $params['site_count'];
        $this->sites = $params['sites'];
        $this->default_hosts = $params['default_hosts'];
    }

    public function buildDashboard ()
    {
        $SiteDashboard = '';

        $SiteDashboard .= $this->searchBox();
        // Loop through all sites
        $SiteDashboard .= '<div class="card-container row">';

        foreach ( $this->sites as $key => $site ) {
            $SiteDashboard .= $this->buildSiteCard($site);
        }

        $SiteDashboard .= '</div>';

        echo $SiteDashboard;
    }

    private function buildSiteCard ($site)
    {
        $siteCard  = "<div class='col-sm-12 col-md-6'>";
        $siteCard .= "<div class='site-card'>";
        $siteCard .= "<h2 class='lock'>" . $this->btnSiteLock($site) . "</h2>";
        $siteCard .= "<h4 class='title'>" . $this->getHost($site) . "</h4>";

        $siteCard .= '<ul class="list-inline">';

        $siteCard .= '<li>' . $this->labelDebug($site) . '</li>';
        $siteCard .= '<li>' . $this->btnXDebug($site) . '</li>';
        $siteCard .= ($this->btnSubdomains($site) ? '<li>' . $this->btnSubdomains($site) . '</li>' : '');
        $siteCard .= '<li>' . $this->btnWordpressAdmin($site) . '</li>';
        $siteCard .= '<li>' . $this->btnSite($site) . '</li>';

        $siteCard .= '</ul>';

        $siteCard .= "</div>"; //site-card
        $siteCard .= "</div>"; //col-sm-12 col-md-6

        return $siteCard;
    }


    private function searchBox ()
    {
        $search = '';
        $search .= '<div id="search_container" class="input-group search-box">';
        $search .=     '<span class="input-group-addon">';
        $search .=         '<i class="fa fa-search"></i>';
        $search .=     '</span>';
        $search .=     '<input type="text" class="form-control search-input" id="text-search" placeholder="Search active machines..."/>';
        $search .=     '<span class="input-group-addon">';
        $search .=         'Hosts <span class="badge">' . (isset( $hosts['site_count'] ) ? $hosts['site_count'] : '') . '</span>';
        $search .=     '</span>';
        $search .= '</div><!-- /input-group -->';

        return $search;

    }

    private function getHost ($site)
    {
        return $site->host;
    }
    private function labelDebug ($site)
    {
        $label = '';
        if ( 'true' == $site->debug ) {
            $label .= '<span class="label-card">Debug <i class="fa fa-check-circle-o"></i></span>';
        } else {
            $label .= '<span class="label-card">Debug <i class="fa fa-times-circle-o"></i></span>';
        }
        return $label;
    }
    private function btnSubdomains ($site)
    {
        // Collect Subdomains
        $subdomains = '';
        for ($count=0; $count < sizeof($site->subdomains); $count++) {
            if (!empty($site->subdomains[$count])) {
                $subdomains .= '<li><a href=\'http://'. $site->subdomains[$count] .'\' target=\'_blank\'> <i class=\'fa fa-globe\'></i> ' . $site->subdomains[$count] . '</a></li>';
            }
        }
        $btnHtml  = '';
        if (!empty($subdomains)) {
            $subdomains = "<ul class='list-unstyled'>" . $subdomains . "</ul>";
            $btnHtml .= '<a href="#" class="btn-card tip pop"';
            $btnHtml .=         'data-container="body"';
            $btnHtml .=         'data-toggle="popover"';
            $btnHtml .=         'data-html="true"';
            $btnHtml .=         'data-placement="top"';
            $btnHtml .=         'title="Subdomains for' . $site->host  . '"';
            $btnHtml .=         'data-content="' . $subdomains. '">';
            $btnHtml .=     '<i class="fa fa-sticky-note"></i>';
            $btnHtml .= '</a>';
        }
        return $btnHtml;
    }

    private function btnSite ($site)
    {
        return '<a class="btn-card" href="http://' . $site->host . '/" target="_blank">Visit Site <i class="fa fa-external-link"></i></a>';
    }
    private function btnWordpressAdmin ($site)
    {
        return '<a class="btn-card" href="http://' . $site->host . '/wp-admin" target="_blank"><i class="fa fa-wordpress"></i> Admin</a>';
    }

    private function btnXDebug ($site)
    {
        $btnHtml  = '<a class="btn-card tip tool" href="http://' . $site->host . '/?XDEBUG_PROFILE" target="_blank"';
        $btnHtml .=    'data-toggle="tooltip" title="`xdebug_on` must be turned on in VM" data-placement="top">';
        $btnHtml .=     'Profiler <i class="fa fa-search-plus"></i>';
        $btnHtml .= '</a>';
        return $btnHtml;
    }


    private function btnSiteLock ($site)
    {
        $btnHtml = '';
        if ( !in_array($site->host, $this->default_hosts) ) {
            $btnHtml .= '<button class="btn-card btn-lock tip pop remove-host" data-container="body" data-toggle="popover" data-html="true" data-placement="top"';
            $btnHtml .= 'title="Personal Install" data-content="Removed via command line via:</br> <code>$ vv remove ' . $site->host . '</code>">';
            $btnHtml .= '<i class="fa fa-unlock"></i>';
            $btnHtml .= '</button>';
        } else {
            $btnHtml .= '<button class="btn-card btn-lock tip pop" data-container="body" data-toggle="popover" data-html="true" data-placement="top"';
            $btnHtml .= 'title="Core Install" data-content="Part of VVV core installation, these sites cannot be removed or deleted.">';
            $btnHtml .= '<i class="fa fa-lock"></i>';
            $btnHtml .= '</button>';
        }
        return $btnHtml;
    }

}

$siteManager = new SiteManager([
    'site_count'    => $this->server->site_count,
    'sites'         => $this->server->sites,
    'default_hosts' => $this->server->default_hosts,
]);

$siteManager->buildDashboard();

// var_dump($this->server);

require 'commands.php';


?>








