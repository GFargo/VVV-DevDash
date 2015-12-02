<?php
require 'views/DashboardView.php';



/**
* SiteManager
*/
class SiteManager extends DashboardView
{
    public $site_count;
    public $sites;
    public $default_hosts;

    function __construct($params)
    {
        $this->site_count = $params['site_count'];
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
        $outerContainer = new html('div', array( 'class' => 'col-sm-12 col-md-6' ));
        $innerContainer = new html('div', array( 'class' => 'site-card' ));

        $lock = new html('h2', array(
            'class' => 'lock',
            'text' =>  $this->btnSiteLock($site)
        ));

        $title = new html('h4', array(
            'class' => 'title',
            'text' =>  $this->getHost($site)
        ));


        $ul = new html('ul', array( 'class' => 'list-inline' ));

        $debugButton = new html('li', array( 'text' => $this->labelDebug($site) ));
        $XdebugButton = new html('li', array( 'text' => $this->btnXDebug($site) ));
        $wpAdminButton = new html('li', array( 'text' => $this->btnWordpressAdmin($site) ));
        $siteButton = new html('li', array( 'text' =>  $this->btnSite($site) ));
        $subdomainsButton = ($this->btnSubdomains($site) ? new html('li', array( 'text' => $this->btnSubdomains($site) )) : '');

        $ul->append($debugButton)
            ->append($XdebugButton)
            ->append($subdomainsButton)
            ->append($wpAdminButton)
            ->append($siteButton);

        $innerContainer->append($lock)
                        ->append($title)
                        ->append($ul);

        $outerContainer->append($innerContainer);
        return $outerContainer;
    }


    private function searchBox ()
    {
        $searchContainer = new html('div', array(
            'id' => 'search_container',
            'class' => 'input-group search-box'
        ));
        $searchIcon = new html('span', array(
            'class' => 'input-group-addon',
            'text'  => '<i class="fa fa-search"></i>',
        ));

        $searchInput = new html('input', array(
            'id' => 'text-search',
            'class' => 'form-control search-input',
            'type' => 'text',
            'placeholder' => 'Search active machines...',
        ));

        $totalHosts = new html('span', array(
            'class' => 'input-group-addon',
            'text'  => 'Hosts <span class="badge">' . (isset( $this->site_count ) ? $this->site_count : '') . '</span>',
        ));

        $searchContainer->append($searchIcon)
                        ->append($searchInput)
                        ->append($totalHosts);

        return $searchContainer;

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

    private function btnDebug ($site)
    {


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
        $siteLinkBtn = new html('a', array(
            'class' => 'btn-card',
            'href'  => 'http://' . $site->host . '/',
            'target' => '_blank',
            'text'  => '<i class="fa fa-external-link"></i>'
        ));

        return $siteLinkBtn;
    }
    private function btnWordpressAdmin ($site)
    {
        $wordpressAdminBtn = new html('a', array(
            'class' => 'btn-card',
            'href'  => 'http://' . $site->host . '/wp-admin',
            'target' => '_blank',
            'text'  =>  '<i class="fa fa-wordpress"></i> Admin'
        ));

        return $wordpressAdminBtn;
    }

    private function btnXDebug ($site)
    {
        $xDebugBtn = new html('a', array(
            'class' => 'btn-card tip tool',
            'href' => 'http://' . $site->host . '/?XDEBUG_PROFILE',
            'target' => '_blank',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'title' => '`xdebug_on` must be turned on in VM',
            'text' => 'Profiler <i class="fa fa-search-plus"></i>',
        ));

        return $xDebugBtn;
    }


    private function btnSiteLock ($site)
    {
        if ( !in_array($site->host, $this->default_hosts) ) {
            $lockBtn = new html('button', array(
                'class'             => 'btn-card btn-lock tip pop remove-host',
                'data-container'    => 'body',
                'data-toggle'       => 'popover',
                'data-html'         => 'true',
                'data-placement'    => 'top',
                'title'             => 'Personal Install',
                'data-content'      => 'Removed via command line via:</br> <code>$ vv remove ' . $site->host . '</code>',
            ));
            $unlockIcon = new html('i', array( 'class' => 'fa fa-unlock'));
            $lockBtn->append($unlockIcon);
        } else {
            $lockBtn = new html('button', array(
                'class'             => 'btn-card btn-lock tip pop',
                'data-container'    => 'body',
                'data-toggle'       => 'popover',
                'data-html'         => 'true',
                'data-placement'    => 'top',
                'title'             => 'Core Install',
                'data-content'      => 'Part of VVV core installation, these sites cannot be removed or deleted.',
            ));
            $lockIcon = new html('i', array( 'class' => 'fa fa-lock'));
            $lockBtn->append($lockIcon);
        }
        return $lockBtn;
    }

}


?>