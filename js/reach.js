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

		BENNY.DropdownMenus.init();

		BENNY.ResponsiveMenu.init();

		BENNY.CrossBrowserPlaceholders.init();

		BENNY.ImageHovers.init();

		BENNY.Accordion.init();	

		BENNY.Fitvids.init();	

		BENNY.LeanModal.init();

		if ( BENNY_CROWDFUNDING ) {

			BENNY.Countdown.init();		

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
  		if ( BENNY_CROWDFUNDING ) {
  			BENNY.Grid.resizeGrid();
  		}
  	});

  	$(window).load( function() {
  		if ( BENNY_CROWDFUNDING ) {
  			BENNY.Grid.init();
			BENNY.Barometer.init();
  		}
  	});

})( jQuery );