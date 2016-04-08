/*!
 * VVV DevDash
 * Custom Dashboard for Varying Vargrant Vagrants
 * https://github.com/GFargo/VVV-DevDash
 * @author GFargo <ghfargo@gmail.com>
 * @version 0.0.1
 */


/*
* @Author: gfargo
* @Date:   2016-01-26 19:08:14
* @Last Modified by:   Griffen Fargo
* @Last Modified time: 2016-01-27 13:39:38
*/



// (function ($) {

// $.expr[':'].Contains = function(a,i,m){
//     return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
// };

// $.fn.extend( {
//     fuzzySearch: function(list, list_element, search_selector) {
//     	var search = this,
//     		// form = $("<form>").attr({"class":"filterform","action":"#"}),
//     	    // input = $("<input>").attr({"class":"filterinput","type":"text"}),
//     	    list_element = (typeof list_element !== undefined ? list_element : 'li');
//     	    targetSelector = (typeof search_selector !== undefined ? search_selector : '.fuzzy-index');

//     	console.log('Fuzzy Search Started! ', this, list, search_selector);

//     	$(this)
//     	  .change( function () {
//     	    var filter = $(this).val();
//     	    if(filter) {
//     	    	console.log('anything?!');
//     	    	$(list).find(list_element).each(function(index, el) {
//     	    		console.log('this return: ', $(el).find(targetSelector + ":not(:Contains(" + filter + "))"))

//     	    		$(el).find(targetSelector + ":not(:Contains(" + filter + "))").parent().parent().slideUp();
//     	    		$(el).find(targetSelector + ":Contains(" + filter + ")").parent().parent().slideDown();
//     	    	});
//     	    } else {
//     	      $(list).find(list_element).slideDown();
//     	    }

//     	  })
//     	.keyup( function () {
//     		console.log('KeyUp!')
//     	    $(this).change();
//     	}).keydown( function () {
//     		console.log('KeyDown')
//     	    $(this).change();
//     	});
//     },
// });

// }(jQuery));


(function ($) {

	jQuery.expr[':'].Contains = function(a,i,m){
		return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	};

	function updateSiteCount(list, list_element) {
		var count = 0;
		$(list).find(list_element).each(function(index, el) {
			if ($(el).is(':visible')) {
				count++;
			}
		});
		return count;
	}

	function listFilter(input, list, list_element, search_selector) {
		var search = input,
			// form = $("<form>").attr({"class":"filterform","action":"#"}),
		    // input = $("<input>").attr({"class":"filterinput","type":"text"}),
		    list_item = (typeof list_element !== undefined ? list_element : 'li');
		    targetSelector = (typeof search_selector !== undefined ? search_selector : '.fuzzy-index');

		$(input).on("change paste keyup", function () {
		    var filter = $(input).val();
		    if(filter) {
		    	$(list).find(list_item).each(function(index, el) {
		    		$(el).find(targetSelector + ":not(:Contains(" + filter + "))").parent().parent().parent().slideUp('fast');
		    		$(el).find(targetSelector + ":Contains(" + filter + ")").parent().parent().parent().slideDown('fast');
		    	});
		    } else {
		    	$(list).find(list_item).each(function(index, el) {
		      		$(el).slideDown('fast');
		      	});
		    }

		    setTimeout(function() {
		    	$('#search_container').find('.site-count').text(updateSiteCount(list, list_item));
		    }, 150);
		});
  	}


	$(function () {
		listFilter($('#search_host'), $('.card-container'), '> div', '.fuzzy-index');
	});
}(jQuery));

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

        // $('.remove-host' ,'.sites').click(function(event) {
        //   event.preventDefault();
        //   console.log('event target', event.target);
        //   console.log('event data', $(event.target).attr('data-host'));
        //   var site = $(event.target).attr('data-host');

        //   DevDashUtilities.shell.removeSite(site);

        // });


        $('.delete-cache').click(function(event) {
          event.preventDefault();
          console.log('Clicked Clear Cache');
          DevDashUtilities.cookies.delete('DevDash_Update');
          location.reload();
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