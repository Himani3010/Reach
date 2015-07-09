/*--------------------------------------------------------
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