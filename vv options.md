OPTIONS:
    --help, -h          Show this help and usage
    --version           Show current vv version
    --about             Show project info
    --debug-vv          Outputs all debugging info needed for bug reporting
    --path, -p          Path to VVV installation
    --force-path, -fp       Override vv auto-VVV locating
    --force-sites-folder, -fsf  Override sites folder directory locating
    --use_defaults      Accept all default options and skip the wizard

 COMMANDS:

    list            List all VVV sites
    create          Create a new site
    remove          Remove a site
    vagrant, v          Pass vagrant command through to VVV
    deployment-create       Create a deployment
    deployment-remove       Remove a deployment
    deployment-config       Manually configure deployment
    blueprint-init      Initialize blueprint file

 SITE OPTIONS:
    --domain, -d        Domain of new site
    --live-url, -u      Live URL of site
    --files, -f         Do not provision Vagrant, just create the site directory and files
    --images, -i        Load images by proxy from the live site
    --name, -n          Desired name for the site directory (e.g. mysite)
    --web-root, -wr         Subdirectory used for web server root
    --wp-version, -wv       Version of WordPress to install
    --debug, -x         Turn on WP_DEBUG and WP_DEBUG_LOG
    --multisite, -m         Install as a multisite
    --sample-content, -sc   Add sample content to site
    --username          Admin username
    --password          Admin password
    --email             Admin email
    --prefix            Database prefix to use
    --git-repo, -gr         Git repo to clone as wp-content
    --bedrock, -bed         Creates Roots.io Bedrock install
    --blueprint, -b         Name of blueprint to use
    --blank         Creates blank VVV site, with no WordPress
    --blank-with-db     Adds a blank VVV site, with a database
    --wpskeleton, --skel    Creates a new site with the structure of WP Skeleton
    --database, -db         Import a local database file
    --remove-defaults, -rd  Remove default themes and plugins
    --language,--locale,    Install WP in another locale.

 EXAMPLE:
    vv create --domain mysite.dev --name mysite --debug
    vv create -d mysite.dev -n mysite -x