/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */

(function($) {

  var DevDashUtilities = {
    // Fuzzy Search Functionality
    'search': {
      init: function() {
        $('#search_container').on('keyup change', '.search-input', function(){
          // pull in the new value
          console.log('this selector:', $(this).selector.selector);
          console.log('event:', event);

          var searchTerm = $('.search-input').val(),
              site_list = $('.sites');


          console.log('searchTerm:', searchTerm);
          console.log('site_list:', site_list);

          // remove any old highlighted terms
          $(site_list).removeHighlight();

          $('tr').removeClass('highlight');

          // disable highlighting if empty
          if (searchTerm) {
            // highlight the new term
            $(site_list).highlight(searchTerm);
          }

          // Highlight the table row
          if ($('.sites td span.highlight').length) {

            $('.sites td span.highlight').closest('tr').addClass('highlight');
          }

          if ($('.sites table tr').not('.highlight')) {
            $('.sites tr').addClass('hide');
          }

          $('.sites tr.highlight').removeClass('hide');

          if($('#text-search').val() === '') {
            $('.sites tr').removeClass('hide');
          }
        });
      },

    }
  };


  var DevDash = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
      },
      finalize: function() {
        // Create listener for search fiel
        DevDashUtilities.search.init();
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