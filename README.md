# VVV DevDash
######Custom Dashboard for Varying Vagrants Vagrant - Forked from [@TopDown](https://github.com/topdown/VVV-Dashboard)######
---



This is a Varying Vagrant Vagrants Dashboard for the excellent [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV)

Its purpose is to dynamically load host links to all sites created in the VVV www path.

It also suggests the wonderful add-on bash script [VVV Site Wizard](https://github.com/aliso/vvv-site-wizard) for creating new sites.


Instructions
-
Clone this repo to your VVV/www/default/ directory (`git clone https://github.com/topdown/VVV-Dashboard.git dashboard`)

Copy the dashboard-custom.php to VVV/www/default/dashboard-custom.php

---
### UPDATE Instructions 
#### TODO: Make this automatic via Grunt/Shell Script
From your dashboard directory ```git pull```

You no longer need to copy the style.css anywhere.

Delete the old VVV/www/default/dashboard-custom.php and copy the new version to VVV/www/default/dashboard-custom.php

Now move your dashboard directory so it is inside VVV/www/default/

---

After running the install script and `vagrant up` you should be able browse to [vvv.dev](http://vvv.dev) and start using DevDash.

---
_**NOTE:** This Dashboard project has no affiliation with Varying Vagrant Vagrants or any other components listed here._

---

### Change Log

---
10/27/2015

**Forked TopDown VVV Dashboard**

* Major Design Refresh
* externalize parts of HTML into php files that are `required` via the main `index.php`
* Adds functionality to collapse sidebar
* Displays all possible tools linked in header nav as Iframes in `content.php`  _the one exception being phpMyAdmin_.
* Converts sidebar content into collapsing lists
* Updates `sidebar.php` offering additional links and decscriptions for vagrant commands.