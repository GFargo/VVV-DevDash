
<div class="sidebar-controls">
  <a href="#" alt="open sidebar" class="sidebar-control open"><i class="fa fa-caret-square-o-right"></i></a>
  <a href="#" alt="close sidebar" class="sidebar-control close"><i class="fa fa-caret-square-o-left"></i></a>
</div>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h3 class="panel-title sidebar-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Vagrant Commands
        </a>
      </h3>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">

        <div class="list-group">
          <li class="list-group-item">
            <h4 class="list-group-item-heading">vagrant up <small><a href="https://docs.vagrantup.com/v2/cli/up.html" alt="Vagrant Documentation" target="_blank"><i class="fa fa-book"></i></a></small></h4>
            <div class="list-group-item-text">
              Creates and configures machine according to vagrant file.
            </div>
          </li>

          <li class="list-group-item">
            <h4 class="list-group-item-heading">vagrant ssh <small><a href="https://docs.vagrantup.com/v2/cli/ssh.html" alt="Vagrant Documentation" target="_blank"><i class="fa fa-book"></i></a></small></h4>
            <p class="list-group-item-text">
              This will SSH into a running Vagrant machine and give you access to a shell.
            </p>
          </li>

          <li class="list-group-item">
            <h4 class="list-group-item-heading">vagrant provision <small><a href="https://docs.vagrantup.com/v2/cli/provision.html" alt="Vagrant Documentation" target="_blank"><i class="fa fa-book"></i></a></small></h4>
            <p class="list-group-item-text">
              Runs any configured <a href="https://docs.vagrantup.com/v2/provisioning/" alt="Vagrant Provisioners" target="_blank">provisioners</a> against the running Vagrant managed machine.
            </p>
          </li>

          <li class="list-group-item">
            <h4 class="list-group-item-heading">vagrant halt <small><a href="https://docs.vagrantup.com/v2/cli/halt.html" alt="Vagrant Documentation" target="_blank"><i class="fa fa-book"></i></a></small></h4>
            <p class="list-group-item-text">
              Attempts to gracefully shutdown. If this fails use <code>--force</code> flag.
            </p>
          </li>

          <li class="list-group-item">
            <h4 class="list-group-item-heading">vagrant suspend/resume <small><a href="https://docs.vagrantup.com/v2/cli/suspend.html" alt="Vagrant Documentation" target="_blank"><i class="fa fa-book"></i></a></small></h4>
            <p class="list-group-item-text">
              <code>suspend</code> effectively saves the exact point-in-time state of the machine, so that when you <code>resume</code> it later, it begins running immediately from that point, rather than a full boot.
            </p>
          </li>


        </div>


      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h3 class="panel-title sidebar-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          References &amp; Extras
        </a>
      </h3>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">

        <ul class="nav">
            <li><a href="https://github.com/varying-vagrant-vagrants/vvv/#now-what" target="_blank">Commands Link</a></li>
            <li><a href="https://github.com/Varying-Vagrant-Vagrants/VVV/wiki/Code-Debugging#turning-on-xdebug" target="_blank">Turning on xDebug</a></li>
        </ul>


        <ul class="nav">
            <li><a target="_blank" href="https://github.com/bradp/vv">Variable VVV (newest)</a></li>
            <li><a href="https://github.com/varying-vagrant-vagrants/vvv/" target="_blank">Varying Vagrant Vagrants</a>
            </li>
            <li><a href="https://github.com/topdown/VVV-Dashboard" target="_blank">VVV Dashboard Repo</a></li>
            <li><a href="https://github.com/topdown/VVV-Dashboard/issues" target="_blank">VVV Dashboard Issues</a></li>
            <li>
                <a href="https://github.com/aubreypwd/wordpress-themereview-vvv" target="_blank">VVV WordPress ThemeReview</a>
            </li>
        </ul>
      </div>
    </div>
  </div>
</div>