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

		REACH.DropdownMenus.init();

		REACH.ResponsiveMenu.init();

		REACH.CrossBrowserPlaceholders.init();

		REACH.ImageHovers.init();

		REACH.Accordion.init();	

		REACH.Fitvids.init();	

		REACH.LeanModal.init();

		if ( REACH_CROWDFUNDING ) {

			REACH.Countdown.init();		

			$('.campaign-button').on( 'click', function() {
				$(this).toggleClass('icon-remove');
				$(this).parent().toggleClass('is-active');
			});
		}	
	});
	
	if ( typeof audiojs !== 'undefined' ) {
		audiojs.events.ready(function() {
	    	var as = audiojs.createAll();
	  	});
	}

  	$(window).resize( function() {
  		if ( REACH_CROWDFUNDING && $.fn.masonry ) {
  			REACH.Grid.resizeGrid();
  		}
  	});

  	$(window).load( function() {
  		if ( REACH_CROWDFUNDING ) {

            if ( $.fn.masonry ) {
                REACH.Grid.init();    
            }

			REACH.Barometer.init();
  		}
  	});

})( jQuery );