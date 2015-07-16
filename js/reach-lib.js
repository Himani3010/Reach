/*! reach - v2.0.0 - 2015-07-16 */

/*--------------------------------------------------------
 * BENNY is the core object containing all components.
---------------------------------------------------------*/
var BENNY = {};

;/*--------------------------------------------------------
 * Accordion
---------------------------------------------------------*/
BENNY.Accordion = ( function( $ ){
	return {
		init : function() {
			if ( $.fn.accordion ) {
				$('.accordion').accordion({
					heightStyle: "content"
				});
			}
		}
	}
})( jQuery );



;/*--------------------------------------------------------
 * Campaign Barometer
---------------------------------------------------------*/
BENNY.Barometer = ( function($) {

	// Barometers collection
	var $barometers = $('.barometer'), 

	// Check whether the element is in view
	isInView = function($el) {
	    var docViewTop = $(window).scrollTop(), 
	    	docViewBottom = docViewTop + $(window).height(), 
			elemTop = $el.offset().top,
			elemBottom = elemTop + $el.height();

	    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
  			&& (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
	}, 

	// A custom arc to apply to the barometer's paths.
	// @see http://stackoverflow.com/questions/5061318/drawing-centered-arcs-in-raphael-js
	customArc = function (xloc, yloc, value, total, R) {
		var alpha = 360 / total * value,
			a = (90 - alpha) * Math.PI / 180,
			x = xloc + R * Math.cos(a),
			y = yloc - R * Math.sin(a),
			path;

		if (total == value) {
			path = [
				["M", xloc, yloc - R],
				["A", R, R, 0, 1, 1, xloc - 0.01, yloc - R]
			];
		} else {
			path = [
				["M", xloc, yloc - R],
				["A", R, R, 0, +(alpha > 180), 1, x, y]
			];
		}
		return {
			path: path
		};
	}

	// Draws a barometer
	drawBarometer = function($barometer, r, width, height, progress_val) {			
		var progress;

		// Draw the percentage filled arc
		if ( progress_val > 0 ) {
			progress = r.path().attr({ 
				stroke: $barometer.data('progress-stroke'), 
				'stroke-width' : $barometer.data('strokewidth')+1, 
				arc: [width/2, height/2, 0, 100, (width/2)-8]
			});

			// Animate it
			progress.animate({
				arc: [width/2, height/2, progress_val, 100, (width/2)-8]
			}, 1500, "easeInOut", function() {
				$barometer.find('span').animate( { opacity: 1}, 300, 'linear');
			});
		}			
	}, 

	// Init barometer
	initBarometer = function($barometer) {
		var width = $barometer.data('width'), 
			height = $barometer.data('height'),					
			r = Raphael( $barometer[0], width, height),
			drawn = false,							
			progress_val = $barometer.data('progress') > 100 ? 100 : $barometer.data('progress'),
			circle;

		// @see http://stackoverflow.com/questions/5061318/drawing-centered-arcs-in-raphael-js
		r.customAttributes.arc = customArc;

		// Draw the main circle
		circle = r.path().attr({
			stroke: $barometer.data('stroke'), 
			'stroke-width' : $barometer.data('strokewidth'), 
			arc: [width/2, height/2, 0, 100, (width/2)-8]
		});

		// Fill the main circle
		$barometer.parent().addClass('barometer-added');
		circle.animate({ arc: [width/2, height/2, 100, 100, (width/2)-8] }, 1000, function() {
			if ( progress_val === 0 ) {
				$barometer.find('span').animate( { opacity: 1}, 500, 'linear' );
			}					
		});

		if ( isInView($barometer) ) {
			drawBarometer($barometer, r, width, height, progress_val);

			drawn = true;
		}
		else {
			$(window).scroll( function() {
				if ( drawn === false && isInView($barometer) ) {
					drawBarometer($barometer, r, width, height, progress_val);

					drawn = true;
				}
			});
		}
	};

	return {
		init : function() {
			$barometers.each( function() {
				initBarometer( $(this) );
			});					
		},
		getBarometers : function() {
			return $barometers;
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Campaign Countdown
---------------------------------------------------------*/
BENNY.Countdown = ( function( $ ) {

	// Start the countdown script
	var startCountdown = function() {
		var $countdown = $('.countdown');

		if ($countdown.length) {
			
			$countdown.countdown({
				until: $.countdown.UTCDate( BENNY_CROWDFUNDING.timezone_offset, new Date( $countdown.data().enddate ) ), 
				format: 'dHMS', 
				labels : [BENNY_CROWDFUNDING.years, BENNY_CROWDFUNDING.months, BENNY_CROWDFUNDING.weeks, BENNY_CROWDFUNDING.days, BENNY_CROWDFUNDING.hours, BENNY_CROWDFUNDING.minutes, BENNY_CROWDFUNDING.seconds],
				labels1 : [BENNY_CROWDFUNDING.year, BENNY_CROWDFUNDING.month, BENNY_CROWDFUNDING.week, BENNY_CROWDFUNDING.day, BENNY_CROWDFUNDING.hour, BENNY_CROWDFUNDING.minute, BENNY_CROWDFUNDING.second]
			});
		}		

		return $countdown;
	}

	return {
		init : function() {
			startCountdown();
		}	
	};
})( jQuery );

;/*--------------------------------------------------------
 * Campaign Grid
---------------------------------------------------------*/
BENNY.Grid = ( function( $ ) {

	var $grids = $('.masonry-grid');

	var initGrid = function($grid) {
		$grid.masonry();
	};

	return {

		init : function() {

			if ( $(window).width() > 400 ) {
				$grids.each( function() {
					initGrid( $(this) );
				});
			}
						
		}, 

		getGrids : function() {
			return $grids;
		}, 

		resizeGrid : function() {
			$grids.each( function(){
				initGrid( $(this) );
			})
		}			
	}
})( jQuery );

;/*--------------------------------------------------------
 * Campaign Pledges
---------------------------------------------------------*/
BENNY.Pledging = ( function( $ ) {

	var $scope 		= $('#charitable-edd-pledge-form'),
		$form 		= $scope.find('.edd_download_purchase_form'),
		$price 		= $scope.find('input[name=atcf_custom_price]'),
		$pledges 	= $scope.find('.edd_download_purchase_form .pledge-level').sort( function( a, b ) {
			return parseInt( $(a).data('price') ) - parseInt( $(b).data('price') );
		}), 
		$button 	= $scope.find('.pledge-button a'),
		$minpledge 	= $pledges.first(), 
		$maxpledge;

	var priceChange = function() {
		var new_pledge = parseInt( $price.val() );

		if ( $minpledge.length === 0 ) {
			return;
		}	

		if ( $pledges.length === 0 ) {
			return;
		}

		// The pledge has to equal or exceed the minimum pledge amount
		if ( parseInt( $minpledge.data('price') ) > new_pledge ) {

			// Explain that the pledge has to be at least the minimum
			alert( BENNY.need_minimum_pledge );

			// Select the minimum pledge amount
			$minpledge.find('input').prop('checked', true);
			$minpledge.change();

			// Exit
			return;
		}			

		$pledges.each( function() {

			if ( $(this).data('price') <= new_pledge && $(this).hasClass('not-available') === false ) {
				$maxpledge = $(this);
			} 
			// This pledge's amount is greater than the amount set
			else {										
				return false;
			}
		});

		// Select the maximum pledge
		$maxpledge.find('input').prop('checked', true);
	}

	return {
		init : function() {
			// Set up event handlers
			$button.on( 'click', function() {
				var price = $(this).data('price');				
				console.log( $(this) );
				$form.find('[data-price="' + price + '"] input').prop('checked', true).trigger('change');
			});

			$form.on( 'change', '.pledge-level', function() {
				$price.val( $(this).data().price );
			})
			.on( 'change', 'input[name=atcf_custom_price]', function() {
				priceChange();
			});
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Cross-Browser Placeholders
 *
 * Ensure that browsers which don't support the placeholder 
 * attribute will still display the placeholder value as a 
 * preset value inside the element.
---------------------------------------------------------*/
BENNY.CrossBrowserPlaceholders = ( function( $ ) {	
	return {
		init : function() {
			var $form_elements = $(':text,textarea');

			// Make sure there are text inputs
			if ( $form_elements.length ) {

				// Only proceed if placeholder isn't supported
				if ( ! ( 'placeholder' in $form_elements.first()[0] ) ) {
					var active = document.activeElement;

					$form_elements.focus( function() {
						if ( $(this).attr('placeholder') != null ) {
							$(this).val('');
							if ( $(this).val() !== $(this).attr('placeholder') ) {
								$(this).removeClass('hasPlaceholder');
							}
						}
					}).blur( function() {
						if ( $(this).attr('placeholder') != null && ($(this).val() === '' || $(this).val() === $(this).attr('placeholder'))) {
							$(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
						}
					});
					$form_elements.blur();
					$(active).focus();
					$('form').submit(function () {
						$(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
					});
				}
			}
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Dropdown Menus
---------------------------------------------------------*/
BENNY.DropdownMenus = ( function( $ ){	
	return {
		init : function() {
			$('.menu li')
			.on( 'mouseover', function() {
				$(this).addClass( 'hovering' );
			})
			.on( 'mouseout', function() {
				$(this).removeClass( 'hovering' );
			});
		}
	};
})( jQuery );

;/*--------------------------------------------------------
 * Fancy Select Elements
---------------------------------------------------------*/
BENNY.FancySelect = ( function( $ ){
	return {
		init : function() {
			var $select = $('select'), 
				toggleWrapper = function($el) {
					$el.parent().css('display', $el.css('display'))
				};

			if ( ($select).parent().hasClass('select-wrapper') ) {
				return toggleWrapper;
			}

			$select.wrap('<div class="select-wrapper" />')
			.on('change', function() {
				toggleWrapper($(this))
			});

			$select.each( function() {
				toggleWrapper($(this)); 
			});

			return toggleWrapper;
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Fitvids
---------------------------------------------------------*/
BENNY.Fitvids = ( function( $ ){
	return {
		init : function() {
			$( '.fit-video, .video-player' ).fitVids();
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Image Hovers
---------------------------------------------------------*/
BENNY.ImageHovers = ( function( $ ) {
	return {
		init : function() {
			$('.on-hover').each( function() {
				var $parent = $(this).parent(), 
					$image = $parent.find('img');

				// Set the width and offset of the hover to match the image
				$(this).css({ width : $image.width(), left : $image.position().left });
				
				// Set up the parent, along with its event handlers
				$parent
				.addClass('hover-parent')
				.on( 'mouseover', function() {
					$(this).addClass('hovering');
				})
				.on('mouseout', function() {
					$(this).removeClass('hovering');
				});
			});
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Lean Modal
---------------------------------------------------------*/
BENNY.LeanModal = ( function( $ ){
	return {
		init : function() {
			$('[data-trigger-modal]').leanModal({
				closeButton	: ".close-modal"
			});
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Responsive Menus
---------------------------------------------------------*/
BENNY.ResponsiveMenu = ( function( $ ) {
	return {
		init : function() {
			var $container, 
				$button, 
				$menu;

			$container = $( '.site-navigation' );
			if ( ! $container ) {
				return;
			}

			$button = $container.find( '.menu-toggle' );
			if ( ! $button ) {
				return;
			}

			$menu = $container.find( 'ul' );

			// Hide menu toggle button if menu is empty and return early.
			if ( ! $menu ) {
				$button.hide();
				return;
			}

			$menu.attr( 'aria-expanded', 'false' );

			if ( ! $menu.hasClass( 'nav-menu' ) ) {
				$menu.addClass( 'nav-menu' );
			}
		 
			$button.on( 'click', function() {
				if ( $container.hasClass( 'toggled' ) ) {
					$container.removeClass( 'toggled' );
					$button.attr( 'aria-expanded', 'false' );
					$menu.attr( 'aria-expanded', 'false' );
				} else {
					$container.addClass( 'toggled' );
					$button.attr( 'aria-expanded', 'true' );
					$menu.attr( 'aria-expanded', 'true' );
				}
			});
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Skip Link Focus Fix 
 *
 * Credit: https://github.com/Automattic/_s
---------------------------------------------------------*/
BENNY.SkipLinkFocusFix = ( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
					element.tabIndex = -1;

				element.focus();
			}
		}, false );
	}
})();