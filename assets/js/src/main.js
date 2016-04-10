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

    // // Fuzzy Search Functionality
    // 'search': {
    //   init: function() {
    //     $('#search_container').on('keyup change', '.search-input', function(){
    //       // pull in the new value
    //       console.log('this selector:', $(this).selector.selector);
    //       console.log('event:', event);

    //       var searchTerm = $('.search-input').val(),
    //           site_list = $('.sites');


    //       console.log('searchTerm:', searchTerm);
    //       console.log('site_list:', site_list);

    //       // remove any old highlighted terms
    //       $(site_list).removeHighlight();

    //       $('tr').removeClass('highlight');

    //       // disable highlighting if empty
    //       if (searchTerm) {
    //         // highlight the new term
    //         $(site_list).highlight(searchTerm);
    //       }

    //       // Highlight the table row
    //       if ($('.sites td span.highlight').length) {

    //         $('.sites td span.highlight').closest('tr').addClass('highlight');
    //       }

    //       if ($('.sites table tr').not('.highlight')) {
    //         $('.sites tr').addClass('hide');
    //       }

    //       $('.sites tr.highlight').removeClass('hide');

    //       if($('#text-search').val() === '') {
    //         $('.sites tr').removeClass('hide');
    //       }
    //     });
    //   },
    // },


    // Header Nav - Pulling in other services on the server via Ajax & Iframe
    'header': {
      init: function() {
        $('.header-nav').find('.nav-link').on('click', function(event) {
          // Iframe is restricted on phpMyAdmin
          // console.log('name:', event.target.name);

          if (! _.contains(['phpMyAdmin','Mailcatcher','Help' ], event.target.name) ) {
            event.preventDefault();
            if (!$('.main').hasClass('full')) {
              $('.sidebar-control.close' ,'.sidebar-controls').click();
            }

            var content = 'http://vvv.dev/dashboard/views/partials/iframe.php',
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
          } else {
            console.log('else derp');
          }
        });
      },
    },
    'shell': {
      run: function (Data) {

          console.log('Running Something! Data:', Data);

          var command = Data.command,
              options = Data.options;

          $.ajax({
            url: 'http://vvv.dev/dashboard/sh.php',
            type: 'POST',
            dataType: 'html',
            data: {
              command: command,
              options: options,
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

      toggleXDebug: function () {
        var self = this;
        console.log('-- Toggling XDebug --');

        $('.cmd-xdebug').click(function(event) {
          /* Act on the event */

          console.log('clicked', event);
          var state = $(this).attr('data-state');

          var removeQuery = {
            command: 'xdebug',
          };

          console.log('RemoveQuery: ', removeQuery);

          self.run(removeQuery);

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


    'git': {
      init: function() {

        $('.git-pull').click(function(event) {

          console.log('çlicked', event);
          var path = $(this).attr('data-git-path');

          if (!$(this).attr('disabled')) {

            $.ajax({
              url: '/dashboard/components/GitAjax.php',
              type: 'POST',
              data: { path: path },
            })
            .done(function(data) {
              console.log("success", data);
            })
            .fail(function(data) {
              console.log("error", data);
            })
            .always(function(data) {
              console.log("complete", data);
            });

          }
        });
      }
    },


    'cookies': {

      delete: function( name ) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      },
      createCookie: function(name,value,days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            expires = "; expires="+date.toUTCString();
        } else {
          expires = "";
        }
        document.cookie = name+"="+value+expires+"; path=/";
      },
      readCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)===' ') {
              c = c.substring(1,c.length);
            }
            if (c.indexOf(nameEQ) === 0) {
              return c.substring(nameEQ.length,c.length);
            }
        }
        return null;
      },
      eraseCookie: function(name) {
        createCookie(name,"",-1);
      },

    },


    'notify': {
      init: function () {
        // Setup Notify basic Settings
        $.notify.defaults({
          type: "info",
          className: 'info',
          autoHide: false,
          animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
          },
          onShow: null,
          onShown: null,
          onClose: null,
          onClosed: null,
          gap: 4,
        });

        $.notify.addStyle('devdash', {
          html: "<div> <span class='notify-icon'><i class='fa fa-info-circle'></i></span> <h5><span data-notify-text='title'></h5> <span class='text' data-notify-html='message'/> </div>",
          classes: {
            base: {
              "position": "relative",
              "white-space": "nowrap",
              "background-color": "white",
              "border": "1px solid",
              "padding": "12px 16px"
            },
            basic: {
              "color": "#4E5C99",
              "border-color": "#cce"
            }
          }
        });
      },

      basic: function (title, message, icon) {
        var notify_title   = (typeof title !== "undefined" ? title : 'notify'),
            notify_message = (typeof message !== "undefined" ? message : 'Something Happened'),
            notify_icon    = (typeof icon !== "undefined" ? icon : 'fa fa-info-circle');

            console.log('Title: ', notify_title, ' Message: ', notify_message, ' Icon: ', notify_icon);

            $.notify({
              title: notify_title,
              icon:  notify_icon,
              message: notify_message,
            }, {
              style: 'devdash',
              className: 'basic',
            });
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

        // $('.remove-host' ,'.sites').click(function(event) {
        //   event.preventDefault();
        //   console.log('event target', event.target);
        //   console.log('event data', $(event.target).attr('data-host'));
        //   var site = $(event.target).attr('data-host');

        //   DevDashUtilities.shell.removeSite(site);

        // });

        DevDashUtilities.notify.init();

        $('.delete-cache').click(function(event) {
          event.preventDefault();
          DevDashUtilities.cookies.delete('DevDash_Update');
          DevDashUtilities.notify.basic('Refreshing Hosts', 'Please wait while we clear the host cache...');

          setTimeout(function() {
            location.reload();
          }, 1200);
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
        // Create listener for search fiel

        DevDashUtilities.header.init();
        // DevDashUtilities.git.init();
        DevDashUtilities.shell.toggleXDebug();

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