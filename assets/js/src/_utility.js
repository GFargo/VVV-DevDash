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
