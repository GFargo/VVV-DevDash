<?php

require_once( __DIR__ . '/DashboardView.php');


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

        return $SiteDashboard;
    }

    private function buildSiteCard ($site)
    {
        $outerContainer = new html('div', array( 'class' => 'col-sm-12 col-md-6' ));
        $innerContainer = new html('div', array( 'class' => 'site-card' ));

        $visibleContent = new html('div', array( 'class' => '' ));

        $lock = new html('h2', array(
            'class' => 'lock',
            'text' =>  $this->btnSiteLock($site)
        ));

        $title = new html('h4', array(
            'class' => 'title fuzzy-index',
            'data-title' => $this->getHost($site),
            'text' =>  $this->getHost($site),
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

        $visibleContent->append($lock)
                        ->append($title)
                        ->append($ul);

        // Control Panels
        $gitPanel = $this->gitPanel($site);
        $debugPanel = $this->debugPanel($site);

        // Inner Content
        $innerContainer->append($visibleContent)
                        ->append($gitPanel)
                        ->append($debugPanel);

        $outerContainer->append($innerContainer);
        return $outerContainer;
    }


    private function searchBox ()
    {
        $searchContainer = new html('div', array(
            'id' => 'search_container',
            'class' => 'search-box'
        ));
        $searchIcon = new html('span', array(
            'class' => 'search-icon',
            'text'  => '<i class="fa fa-search"></i>',
        ));

        $searchInput = new html('input', array(
            'id' => 'search_host',
            'class' => 'search-input',
            'type' => 'text',
            'placeholder' => 'Search active machines...',
        ));

        $totalHosts = new html('span', array(
            'class' => 'search-badge badge'
        ));

        $hostCount = new html('span', array(
            'class' => 'site-count',
            'text'  => ( $this->site_count ? $this->site_count : ''),
        ));

        $hostIcons = new html('span', array(
            'class' => 'controls',
            'text'  => '<i class="fa fa-cubes"></i>',
        ));

        $clearCacheButton = new html('a', array(
            'class' => 'delete-cache',
            'alt'   => 'Clear Site Cache',
            'text'  => '<i class="fa fa-refresh"></i>',
        ));

        $hostIcons->append($clearCacheButton);

        $totalHosts->append($hostCount)
                    ->append($hostIcons);


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
            $title = 'Git Control Panel';

            $gitButton = new html('a', array(
                'class'             => 'btn-card',
                'role'              => 'button',
                'data-toggle'       => 'collapse',
                'href'              => '#git_'.$site->name,
                'aria-expanded'     => 'false',
                'aria-controls'     => 'collapseGitContainer',
                'title'             => htmlentities($title),
                'text'              => $text
            ));

        }
        return $gitButton;
    }

    private function gitPanel ($site)
    {
        $gitContentContainer = '';
        // var_dump($site);
        if ($site->git) {
            $text = '<i class="fa fa-git"></i>';
            $title = 'Git Controlled WP_Content';

            $gitContentContainer = new html('div', array(
                'class' => 'collapse ',
                'id'    => 'git_'.$site->name,
            ));

            $innerContainer = new html('div', array(
                'class' => 'options-tab',
            ));


            $repo = Git::open('../../'.$site->name.'/htdocs/wp-content');  // -or- Git::create('/path/to/repo')

            // $status = $repo->status();

            // Git Active Branch
            $activeBranch = new html('code', array(
                'text' => 'current branch: ' . $repo->active_branch(),
                'class' => 'options-label git-branch'
            ));

            // Git Repo Controls
            $repoControls = new html('div', array(
                'class' => ''
            ));

            $pullBtn = new html('a', array(
                'class' => 'btn git-pull btn-primary btn-sm',
                'text' => 'Pull',
                'disabled' => 'disabled',
                'data-git-path' => '../../../'.$site->name.'/htdocs/wp-content'
            ));

            // $refreshBtn = new html('a', array(
            //     'class' => 'btn btn-info btn-sm',
            //     'text' => 'Refresh'
            // ));



            $repoControls->append($pullBtn);


            // Git Commit Log
            $commitLog = explode(PHP_EOL, $repo->run('log -6 --oneline'));

            array_pop($commitLog);

            $commitsHtml = new html('ul', array(
                'class' => 'list-unstyled compact'
            ));

            foreach ($commitLog as $key => $logEntry) {
                $entry = new html('li', array(
                    'text' => $logEntry,
                    'class' => 'compact'
                ));
                $commitsHtml->append($entry);
            }


            // $repo->add('.');
            // $repo->commit('Some commit message');
            // $repo->push('origin', 'master');


            // Add Elements to Git HTML Container
            $innerContainer->append($activeBranch)
                            ->append($repoControls)
                            ->append($commitsHtml);

            $gitContentContainer->append($innerContainer);

        }
        return $gitContentContainer;
    }

    private function getHost ($site)
    {
        return $site->host;
    }

    private function btnDebug ($site)
    {
        $debugButton = '';
        // var_dump($site);

        $text = '<i class="fa fa-bug"></i>';
        $title = 'Debug Control Panel';

        $debugButton = new html('a', array(
            'class'             => 'btn-card',
            'role'              => 'button',
            'data-toggle'       => 'collapse',
            'href'              => '#debug_'.$site->name,
            'aria-expanded'     => 'false',
            'aria-controls'     => 'collapseDebugContainer',
            'title'             => htmlentities($title),
            'text'              => $text
        ));

        return $debugButton;
    }


    private function debugPanel ($site)
    {

        if ( 'true' == $site->debug ) {
            // Wordpress Debug Settings
            $wpDebugLabel = new html('code', array(
                'text' => '<i class="fa fa-check-circle-o"></i> WP_DEBUG Enabled',
                'class' => 'options-label enabled'
            ));

            $text = '<i class="fa fa-check-circle-o"></i> xdebug';
        } else {
            // Wordpress Debug Settings
            $wpDebugLabel = new html('code', array(
                'text' => '<i class="fa fa-times-circle-o"></i> WP_DEBUG Disabled',
                'class' => 'options-label'
            ));

            $text = '<i class="fa fa-times-circle-o"></i> xdebug';
        }

        $debugContentContainer = new html('div', array(
            'class' => 'collapse ',
            'id'    => 'debug_'.$site->name,
        ));

        $innerContainer = new html('div', array(
            'class' => 'options-tab',
        ));

        $debugButtonContainer = new html('div', array(
            'class' => 'btn-group',
            'role' => 'group',
            'aria-label' => 'Debugging Options Button Group'
        ));


        $xDebugBtn = new html('a', array(
            'class' => 'btn btn-primary btn-sm cmd-xdebug',
            'text' => $text,
            'data-state' => 'on'
        ));

        $xDebugProfile = new html('a', array(
            'class' => 'btn btn-success btn-sm',
            'href' => 'http://' . $site->host . '/?XDEBUG_PROFILE',
            'target' => '_blank',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'title' => 'View Site Debug Profile',
            'text' => '<i class="fa fa-search-plus"></i> Profiler View',
        ));

        $debugButtonContainer->append($xDebugBtn)
                             ->append($xDebugProfile);

        $innerContainer->append($wpDebugLabel)
                        ->append($debugButtonContainer);

        $debugContentContainer->append($innerContainer);

        return $debugContentContainer;
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