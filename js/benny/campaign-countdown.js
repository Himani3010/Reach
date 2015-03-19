/*--------------------------------------------------------
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