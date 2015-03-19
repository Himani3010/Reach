<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'search label', 'benny' ) ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'search placeholder', 'benny' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'search label' ) ?>" />
	</label>
	<button class="search-submit icon" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" data-icon="&#xf002;"></button>
</form>