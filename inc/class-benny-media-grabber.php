<?php
/**
 * A thin wrapper around Hybrid_Media_Grabber that adds support for grabbing media from post meta.
 *
 * @package		Benny/Classes/Benny_Media_Grabber
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Benny_Media_Grabber' ) ) : 

/**
 * Benny_Media_Grabber
 *
 * @since 		2.0.0
 */
class Benny_Media_Grabber extends Hybrid_Media_Grabber {

	/**
	 * Sets the media for the post. 
	 *
	 * @return 	void	 
	 * @access 	public	 
	 * @since  	2.0.0
	 */
	public function set_media() {
		if ( isset( $this->args['meta_key'] ) ) {
			$url = get_post_meta( get_the_ID(), $this->args['meta_key'], true );
			$this->media = do_shortcode( "[{$this->type} src='{$url}']" );
		}

		parent::set_media();
	}
}

endif; // End class_exists check