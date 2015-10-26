/*!
 * VVV DevDash
 * Custom Dashboard for Varying Vargrants Vagrant
 * https://github.com/GFargo/VVV-DevDash
 * @author GFargo <ghfargo@gmail.com>
 * @version 0.0.1
 */


/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */

(function($) {

  var DevDash = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
      },
      finalize: function() {
        $('#text-search').bind('keyup change', fuzzySearch(event));
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


jQuery.fn.highlight = function (pat) {
	function innerHighlight(node, pat) {
		var skip = 0;
		if (node.nodeType === 3) {
			var pos = node.data.toUpperCase().indexOf(pat);
			if (pos >= 0) {
				var spannode = document.createElement('span');
				spannode.className = 'highlight';
				var middlebit = node.splitText(pos);
				var endbit = middlebit.splitText(pat.length);
				var middleclone = middlebit.cloneNode(true);
				spannode.appendChild(middleclone);
				middlebit.parentNode.replaceChild(spannode, middlebit);
				skip = 1;
			}
		}
		else if (node.nodeType === 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
			for (var i = 0; i < node.childNodes.length; ++i) {
				i += innerHighlight(node.childNodes[i], pat);
			}
		}
		return skip;
	}

	return this.each(function () {
		innerHighlight(this, pat.toUpperCase());
	});
};

jquery.fn.fuzzySearch = function (ev) {
	// pull in the new value
	var searchTerm = $(this).val(),
		site_list = $('.sites');

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

};

jQuery.fn.removeHighlight = function () {
	function newNormalize(node) {
		for (var i = 0, children = node.childNodes, nodeCount = children.length; i < nodeCount; i++) {
			var child = children[i];
			if (child.nodeType === 1) {
				newNormalize(child);
				continue;
			}
			if (child.nodeType !== 3) {
				continue;
			}
			var next = child.nextSibling;
			if (next == null || next.nodeType !== 3) {
				continue;
			}
			var combined_text = child.nodeValue + next.nodeValue;
			new_node = node.ownerDocument.createTextNode(combined_text);
			node.insertBefore(new_node, child);
			node.removeChild(child);
			node.removeChild(next);
			i--;
			nodeCount--;
		}
	}

	return this.find("span.highlight").each(function () {
		var thisParent = this.parentNode;
		thisParent.replaceChild(this.firstChild, this);
		newNormalize(thisParent);
	}).end();
};

$.fn.scrollViewUp = function () {
	return this.each(function () {
		$('.sites').animate({
			scrollTop: $(this).offset().top
		}, 1000);
	});
};

$.fn.scrollViewDown = function () {
	var sites_list = $('.sites');

	var scrollBottom = $(sites_list).height() - $(sites_list).height() - $(sites_list).scrollTop();
	return this.each(function () {
		$('.sites').animate({
			scrollTop: scrollBottom
		}, 1000);
	});
};
