/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */


//TODO: Make all Ajax Calls relying on static hosts dynamic

(function($) {

  var DevDashUtilities = {
    // Header Nav - Pulling in other services on the server via Ajax & Iframe
    'header': {
      init: function() {
        $('.header-nav').find('.nav-link').on('click', function(event) {
          // Iframe is restricted on phpMyAdmin
          console.log('name:', event.target.name);
          if (!$.inArray(event.target.name, ['phpMyAdmin','Mailcatcher','Help' ])) {
          // if (event.target.name !== 'phpMyAdmin' && event.target.name !==  && event.target.name !== ) {
            event.preventDefault();
            if (!$('.main').hasClass('full')) {
              $('.sidebar-control.close' ,'.sidebar-controls').click();
            }

            var content = 'http://vvv.dev/dashboard/views/content.php',
                moduleName = event.target.name;
            $.ajax({
              url: content,
              type: 'POST',
              dataType: 'html',
              data: {
                module: moduleName
              }
            })
            .done(function(response) {
              console.log("success");
              $('.main_content').html(response);
            })
            .fail(function() {
              console.log("error");
            })
            .always(function() {
              console.log("complete");
            });
          }
        });
      },
    },
    'shell': {
      run: function (Data) {

          console.log('Running Something! Data:', Data);

          var command = Data.command,
              target = Data.target,
              options = Data.options;

          $.ajax({
            url: 'http://vvv.dev/dashboard/sh.php',
            type: 'POST',
            dataType: 'html',
            data: {
              command_target: target,
              shell_command: command,
              command_options: options,
            }
          })
          .done(function(response) {
            console.log("success", response);
          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });

      },

      removeSite: function (site_name) {
        console.log('Removing ' + site_name + ' now');

        var removeQuery = {
          target: '_vagrant.sh',
          command: 'remove',
          options: site_name,
        };

        this.run(removeQuery);
      }




    },

    'notify': {
      basic: function () {
        // $.notify({
        //   // options
        //   icon: 'glyphicon glyphicon-warning-sign',
        //   title: 'Bootstrap notify',
        //   message: 'Turning standard Bootstrap alerts into "notify" like notifications',
        //   url: 'https://github.com/mouse0270/bootstrap-notify',
        //   target: '_blank'
        // },{
        //   // settings
        //   element: 'body',
        //   position: null,
        //   type: "info",
        //   allow_dismiss: true,
        //   newest_on_top: false,
        //   showProgressbar: false,
        //   placement: {
        //     from: "top",
        //     align: "right"
        //   },
        //   offset: 20,
        //   spacing: 10,
        //   z_index: 1031,
        //   delay: 5000,
        //   timer: 1000,
        //   url_target: '_blank',
        //   mouse_over: null,
        //   animate: {
        //     enter: 'animated fadeInDown',
        //     exit: 'animated fadeOutUp'
        //   },
        //   onShow: null,
        //   onShown: null,
        //   onClose: null,
        //   onClosed: null,
        //   icon_type: 'class',
        //   template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        //     '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        //     '<span data-notify="icon"></span> ' +
        //     '<span data-notify="title">{1}</span> ' +
        //     '<span data-notify="message">{2}</span>' +
        //     '<div class="progress" data-notify="progressbar">' +
        //       '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        //     '</div>' +
        //     '<a href="{3}" target="{4}" data-notify="url"></a>' +
        //   '</div>'
        // });
      }
    }
  };


  var DevDash = {
    // All pages
    'common': {
      init: function() {
        var $sidebar = $('.sidebar'),
            $content = $('.main');

        // <3 jQuery
        $('.sidebar-control' ,'.sidebar-controls').click(function(event) {
          event.preventDefault();
          $sidebar.toggleClass('open closed');
          $content.toggleClass('full');
        });

        $('.remove-host' ,'.sites').click(function(event) {
          event.preventDefault();
          console.log('event target', event.target);
          console.log('event data', $(event.target).attr('data-host'));
          var site = $(event.target).attr('data-host');

          DevDashUtilities.shell.removeSite(site);

        });

        // Activate Tooltips
        $('.tip.tool').tooltip();
        // Activate PopOvers
        $('.tip.pop').popover();

        // Causes bug in code where have to click on button twice
        // $('.tip.pop').on('click', function (e) {
        //     $('.tip.pop').not(this).popover('hide');
        // });


      },
      finalize: function() {

        DevDashUtilities.header.init();

      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
  };

  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = DevDash;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery);

// Fully reference jQuery after this point