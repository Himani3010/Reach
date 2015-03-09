/*--------------------------------------------------------
 * Lean Modal
---------------------------------------------------------*/
FRANKLIN.LeanModal = ( function( $ ){
	return {
		init : function() {
			$('[data-trigger-modal]').leanModal({
				closeButton	: ".close-modal"
			});
		}
	}
})( jQuery );