/*--------------------------------------------------------
 * Campaign Grid
---------------------------------------------------------*/
REACH.Grid = ( function( $ ) {

	var $grids = $('.campaign-grid');

	var initGrid = function($grid) {
		$grid.masonry({
			itemSelector: '.campaign',
			horizontalOrder: true,
			// fitWidth: true,
			// gutter: 28
		});
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