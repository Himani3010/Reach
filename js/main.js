// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){	

	// Start Foundation.
	if ( typeof Foundation !== 'undefined' ) {	
		Foundation.global.namespace = '';
		
		$(document).foundation();
	}
	
	// Perform other actions on ready event
	$(document).ready( function() {

		Sofa.init();

		$('.menu-button').on( 'click', function() {			
			$(this).children().toggleClass('icon-th-list').toggleClass('icon-remove'); 
			$(this).parent().toggleClass('is-active');			
		});

		if ( $.fn.accordion ) {
			$('.accordion').accordion({
				heightStyle: "content"
			});
		}

		if ( Franklin.using_crowdfunding ) {

			Franklin.Countdown.init();		

			Franklin.Pledging.init();

			$('.campaign-button').on( 'click', function() {
				$(this).toggleClass('icon-remove');
				$(this).parent().toggleClass('is-active');
			});

			$('[name=shipping_country], [name=shipping_state_ca], [name=shipping_state_us]').on( 'change', Sofa.toggleSelectWrapper($(this)))

			$('.atcf-multi-select .children').hide();
			$('.atcf-multi-select input[type="checkbox"]').on( 'change', function() {
				var parent_category = $(this).parent().parent('li'), 
					child = parent_category.children('.children');
				if ( $(this).attr("checked") ) {
					child.show();
					if( child.length > 0 ) {
						parent_category.addClass("selected");
					}
				} else {
					child.hide();
					parent_category.removeClass("selected");
					parent_category.find('input[type="checkbox"]').prop('checked', false);
				}
			});
		}	
	});
	
	if ( typeof audiojs !== 'undefined' ) {
		audiojs.events.ready(function() {
	    	var as = audiojs.createAll();
	  	});
	}

  	$(window).resize( function() {
  		Sofa.responsiveHide();

  		if ( Franklin.using_crowdfunding ) {
  			Franklin.Grid.resizeGrid();
  		}
  	});

  	$(window).load( function() {
  		if ( Franklin.using_crowdfunding ) {
  			Franklin.Grid.init();
			Franklin.Barometer.init();
  		}
  	})

})( jQuery );