# VVV DevDash
######Custom Dashboard for Varying Vagrants Vagrant - Forked from [@TopDown](https://github.com/topdown/VVV-Dashboard)######
---



This is a Varying Vagrant Vagrants Dashboard for the most excellent [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV).

Its purpose is to dynamically load host links to all sites created in the VVV www path.

The purpose of DevDash is to alleviate the pain points of using VVV when developing wordpress themes and plugins.  It helps with the heavy lifting of creating new hosts, dynmically displaying current hosts, and providing links to the wonderful tools provided by VVV.

---
_**Note:** DevDash has no affiliation with Varying Vagrant Vagrants or any other components listed here._



# Installation

First step is to clone DevDash into your local VVV installation.  Navigate to `/path/to/vvv/www/default` directory and run the following command in terminal.

```
$ git clone https://github.com/gfargo/VVV-DevDash.git dashboard ; build/sh/_install.sh
```

_This will clone DevDash into `/default/` directory and run `_install.sh` script._

##### All Done! Now What?

After the install script has completed you can fire up vagrant via `vagrant up` you should be able browse to [vvv.dev](http://vvv.dev) and begin using DevDash.

---


## DevDash Features

#### Dynamic Machine Display

Thanks to the hard work from @TopDown DevDash features a dynamically generated list of the _active machines_ running on your current VVV setup.  

These machines and a fuzzy search can be found on the homepage for DevDash.


#### VVV Site Wizard

A wonderful command line tool maintained by @bradp that assists in the creation of new Vagrants _(i.e. Wordpress Setups)_.

**Note:** Multiple parts of this dashboard assume you are familiar with the [VVV Site Wizard](https://github.com/aliso/vvv-site-wizard) and already have it installed. 

_If you currenty do not have VVV Site Wizard installed, you can install it [here](https://github.com/bradp/vv#installation)._



### Change Log


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