# VVV DevDash

This is a Varying Vagrant Vagrants Dashboard for the most excellent [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV).

The purpose of DevDash is to offer a collection of tools that assit in the learning curve and alleviate many of the pain points when using VVV for development.  DevDash does the heavy lifting.

### Goals:
 - [ ] Creating new Hosts using VV Site Wizard [#13](https://github.com/GFargo/VVV-DevDash/issues/13)
 - [ ] Management Panels
   - [ ] Git Panel
     - [ ] Run Git Pull from DevDash
     - [x] Display recent commit history
     - [x] Display current branch
   - [ ] Debug Panel
     - [ ] Activate XDebug from DevDash
     - [x] Display status of WP_Debug
     - [x] Display status of XDebug
   - [ ] WPMU Panel
     - [ ] Add new subdomains from DevDash
     - [x] Display current Subdomains
 - [ ] **Dynamically Display Hosts**.
   - [x] Refactor current code in `content.php` to be in class [#21](https://github.com/GFargo/VVV-DevDash/issues/21)
   - [x] Add support for multiple domains [#16](https://github.com/GFargo/VVV-DevDash/issues/16) 
 - [x] Seemless experience when using system tools provided by VVV. _note: two pages restrict iframe privelages, currently in the process of finding a workaround [#23](https://github.com/GFargo/VVV-DevDash/issues/23)_
 - [x] Rewrite entire dashboard to follow MVC ideology
 - [x] Create Site Cache
   - [x] Automatic Site Detection
   - [x] Create 'Refresh Cache' Button



---
_**Note:** DevDash has no affiliation with Varying Vagrant Vagrants and was originally a fork from [@TopDown](https://github.com/topdown/VVV-Dashboard)._



# Installation

First step is to clone DevDash into your local VVV installation.  Navigate to `/path/to/vvv/www/default` directory and run the following command in terminal.

```
$ git clone https://github.com/gfargo/VVV-DevDash.git dashboard ; dashboard/build/sh/_install.sh
```

_This will clone DevDash into `/default/` directory and run `_install.sh` script to move the required files into their appropriate locations._

##### All Done! Now What?

After the install script has completed you can fire up vagrant via `vagrant up` you should be able browse to [vvv.dev](http://vvv.dev) and begin using DevDash.

![image](http://cdn.griffenfargo.com/wp-content/uploads/sites/4/2016/03/DevDash-Dashboard.jpg)





---


## DevDash Features

#### Fuzzy Search

![image](http://griffenfargo.com/wp-content/uploads/sites/4/2016/03/DevDash-FuzzySearch.gif)


#### Dynamic Machine Display

Thanks to the hard work from @TopDown DevDash features a dynamically generated list of the _active machines_ running on your current VVV setup.  

These machines and a fuzzy search can be found on the homepage for DevDash.


#### VVV Site Wizard


A wonderful command line tool maintained by @bradp that assists in the creation of new Vagrants _(i.e. Wordpress Setups)_.

**Note:** Multiple parts of this dashboard assume you are familiar with the [VVV Site Wizard](https://github.com/aliso/vvv-site-wizard) and already have it installed. 

_If you currenty do not have VVV Site Wizard installed, you can install it [here](https://github.com/bradp/vv#installation)._



### Change Log


---
4/8/2016

 * Added 'Clear Cache' button next to Site Count
 * Added Cookie handler functions to DevDashUtilities Javascript object.

---
1/12/2016

 * Added Live Fuzzy Search 
 * Redesigned UI for Site Display
 * Refactors significant core code to closer resemble an MVC structure.

---
11/21/2015

 * Officially moved away from 'Fork' status.


---
11/11/2015

 * Adds screenshot of dashboard showing the subdomains available under `testmu.dev`.
 * Adds Mailcatcher to navigation [#20](https://github.com/GFargo/VVV-DevDash/issues/20)
 * Adds elastic animation when collapsing sidebar
 * Refactors hosts display code to elimate any empty lines and display any subdomains within a popover next to the main domain.
 * Using the git hook `git checkout` worked to install the script however hooks do not automatically install when checking out the repo - so one has to go back in and 'recheckout' the master branch.  Seemed like using hooks would overcomplicate the process so instead I updated the install command to include a command to fire the `_install.sh` shell script.
 * Updates Readme with new images & goals section.
 * _Update: Put the vagrant shell execution scripts on hold after hitting wall with delays involved with calling vagrant commands.  Will investigate further asap._


---
10/31/2015

 * WIP: Create Vagrant shell executer bash script `_vagrant.sh`
 * Create new hook to run after `git checkout` and `git clone` that runs `_install.sh` script.
 * Updates Readme


---
10/28/2015

 * New Install Script! `_install.sh` now handles copying & updating various npm packages.
 * Updates Readme with new install instructions using `_install.sh`




---
10/26/2015

**Forked TopDown VVV Dashboard**

* Major Design Refresh
* externalize parts of HTML into php files that are `required` via the main `index.php`
* Adds functionality to collapse sidebar
* Displays all possible tools linked in header nav as Iframes in `content.php`  _the one exception being phpMyAdmin_.
* Converts sidebar content into collapsing lists
* Updates `sidebar.php` offering additional links and decscriptions for vagrant commands.