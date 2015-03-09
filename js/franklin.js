/*--------------------------------------------------------
 * Init script
 *
 * This is set up as an anonymous function, which avoids 
 * pollution the global namespace. 
---------------------------------------------------------*/
( function( $ ){	
	
	// Perform other actions on ready event
	$(document).ready( function() {

		$('html').removeClass('no-js');

		FRANKLIN.DropdownMenus.init();

		FRANKLIN.ResponsiveMenu.init();

		FRANKLIN.CrossBrowserPlaceholders.init();

		FRANKLIN.ImageHovers.init();

		FRANKLIN.FancySelect.init();

		FRANKLIN.LeanModal.init();	

		// if ( $.fn.accordion ) {
		// 	$('.accordion').accordion({
		// 		heightStyle: "content"
		// 	});
		// }

		if ( FRANKLIN_CROWDFUNDING ) {

			FRANKLIN.Countdown.init();		

			FRANKLIN.Pledging.init();

			$('.campaign-button').on( 'click', function() {
				$(this).toggleClass('icon-remove');
				$(this).parent().toggleClass('is-active');
			});

			// $('[name=shipping_country], [name=shipping_state_ca], [name=shipping_state_us]').on( 'change', Sofa.toggleSelectWrapper($(this)))

			// $('.atcf-multi-select .children').hide();
			// $('.atcf-multi-select input[type="checkbox"]').on( 'change', function() {
			// 	var parent_category = $(this).parent().parent('li'), 
			// 		child = parent_category.children('.children');
			// 	if ( $(this).attr("checked") ) {
			// 		child.show();
			// 		if( child.length > 0 ) {
			// 			parent_category.addClass("selected");
			// 		}
			// 	} else {
			// 		child.hide();
			// 		parent_category.removeClass("selected");
			// 		parent_category.find('input[type="checkbox"]').prop('checked', false);
			// 	}
			// });
		}	
	});
	
	if ( typeof audiojs !== 'undefined' ) {
		audiojs.events.ready(function() {
	    	var as = audiojs.createAll();
	  	});
	}

  	$(window).resize( function() {
  		if ( FRANKLIN_CROWDFUNDING ) {
  			FRANKLIN.Grid.resizeGrid();
  		}
  	});

  	$(window).load( function() {
  		if ( FRANKLIN_CROWDFUNDING ) {
  			FRANKLIN.Grid.init();
			FRANKLIN.Barometer.init();
  		}
  	});

})( jQuery );