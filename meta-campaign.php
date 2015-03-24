<?php $campaign_author = new WP_User( charitable_get_current_campaign()->get_campaign_creator() ) ?>
<div class="meta meta-below">	
	<p class="center"><?php printf( _x( 'By %s', 'by author', 'franklin' ), 
		'<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . $campaign_author->display_name . '</a>' ) ?>
	</p>
</div>