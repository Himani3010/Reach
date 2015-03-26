/*--------------------------------------------------------
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