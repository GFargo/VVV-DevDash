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

        $debugButton = new html('li', array( 'text' => $this->btnDebug($site) ));
        $wpAdminButton = new html('li', array( 'text' => $this->btnWordpressAdmin($site) ));
        $siteButton = new html('li', array( 'text' =>  $this->btnSite($site) ));
        $gitButton = ($this->btnGit($site) ? new html('li', array( 'text' => $this->btnGit($site) )) : '');
        $subdomainsButton = ($this->btnSubdomains($site) ? new html('li', array( 'text' => $this->btnSubdomains($site) )) : '');

        $ul->append($debugButton)
            ->append($subdomainsButton)
            ->append($wpAdminButton)
            ->append($gitButton)
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

    private function btnGit ($site)
    {
        $gitButton = '';
        // var_dump($site);
        if ($site->git) {
            $text = '<i class="fa fa-git"></i>';
            $title = 'Git Controlled WP_Content';

            $gitContentContainer = new html('div', array(
                'class' => '',
            ));


            $repo = Git::open('../../'.$site->name.'/htdocs/wp-content');  // -or- Git::create('/path/to/repo')

            // $status = $repo->status();
            print_r($repo->list_branches(true));
            // $repo->add('.');
            // $repo->commit('Some commit message');
            // $repo->push('origin', 'master');

            $gitContentContainer->append();

            $gitButton = new html('a', array(
                'class'             => 'btn-card tip pop',
                'data-container'    => 'body',
                'data-toggle'       => 'popover',
                'data-html'         => 'true',
                'data-placement'    => 'top',
                'href'              => '#',
                'title'             => htmlentities($title),
                'data-content'      => htmlentities($gitContentContainer),
                'text'              => $text
            ));

        }
        return $gitButton;
    }

    private function getHost ($site)
    {
        return $site->host;
    }

    private function btnDebug ($site)
    {

        if ( 'true' == $site->debug ) {
            $title = '<i class="fa fa-check-circle-o"></i> WP_DEBUG Enabled';
            $text = '<i class="fa fa-check-circle-o"></i>';
        } else {
            $title = '<i class="fa fa-times-circle-o"></i> WP_DEBUG Disabled';
            $text = '<i class="fa fa-times-circle-o"></i>';
        }

        $debugContentContainer = new html('div', array(
            'class' => '',
        ));

        $debugContentContainer->append($this->btnXDebug($site));

        $debugButton = new html('a', array(
            'class'             => 'btn-card tip pop',
            'data-container'    => 'body',
            'data-toggle'       => 'popover',
            'data-html'         => 'true',
            'data-placement'    => 'top',
            'href'              => '#',
            'title'             => htmlentities($title),
            'data-content'      => htmlentities($debugContentContainer),
            'text'              => $text
        ));

        return $debugButton;
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
            'text' => 'Site Profiler <i class="fa fa-search-plus"></i>',
        ));

        return $xDebugBtn;
    }


    private function btnSubdomains ($site)
    {
        // Collect Subdomains
        $subdomainContentContainer = new html('ul', array( 'class' => 'list-unstyled' ));
        $subdomainsExist = false;
        $subdomainsButton = '';
        for ($count=0; $count < sizeof($site->subdomains); $count++) {
            if (!empty($site->subdomains[$count])) {
                $subdomain = new html('li');
                $link = new html ('a', array(
                    'href'      => 'http://'. $site->subdomains[$count],
                    'target'    => '_blank',
                    'text'      => '<i class=\'fa fa-globe\'></i> ' . $site->subdomains[$count],
                ));
                $subdomain->append($link);
                $subdomainContentContainer->append($subdomain);
                $subdomainsExist = true;
            }
        }

        if ($subdomainsExist) {
            $subdomainsButton = new html('a', array(
                'class'             => 'btn-card tip pop',
                'data-container'    => 'body',
                'data-toggle'       => 'popover',
                'data-html'         => 'true',
                'data-placement'    => 'top',
                'title'             => 'Subdomains for ' . $site->host  . '"',
                'data-content'      => htmlentities($subdomainContentContainer),
                'text'              => '<i class="fa fa-sticky-note"></i>',
            ));
        }
        return $subdomainsButton;
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
            'text'  =>  '<i class="fa fa-wordpress"></i>'
        ));

        return $wordpressAdminBtn;
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