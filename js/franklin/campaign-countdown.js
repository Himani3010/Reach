/*--------------------------------------------------------
 * Campaign Countdown
---------------------------------------------------------*/
FRANKLIN.Countdown = ( function( $ ) {

	// Start the countdown script
	var startCountdown = function() {
		var $countdown = $('.countdown');

		if ($countdown.length) {
			
			$countdown.countdown({
				until: $.countdown.UTCDate( FRANKLIN_CROWDFUNDING.timezone_offset, new Date( $countdown.data().enddate ) ), 
				format: 'dHMS', 
				labels : [FRANKLIN_CROWDFUNDING.years, FRANKLIN_CROWDFUNDING.months, FRANKLIN_CROWDFUNDING.weeks, FRANKLIN_CROWDFUNDING.days, FRANKLIN_CROWDFUNDING.hours, FRANKLIN_CROWDFUNDING.minutes, FRANKLIN_CROWDFUNDING.seconds],
				labels1 : [FRANKLIN_CROWDFUNDING.year, FRANKLIN_CROWDFUNDING.month, FRANKLIN_CROWDFUNDING.week, FRANKLIN_CROWDFUNDING.day, FRANKLIN_CROWDFUNDING.hour, FRANKLIN_CROWDFUNDING.minute, FRANKLIN_CROWDFUNDING.second]
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