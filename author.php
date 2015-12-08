<?php 
/**
 * The template for displaying public user profiles.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * 
 * @package     Reach
 */

$donor      = new Charitable_User( reach_get_current_author() );
$campaigns  = Charitable_Campaigns::query( array( 'author' => $donor->ID ) );
$avatar     = $donor->get_avatar( 140 );
$first_name = strlen( $donor->first_name ) ? $donor->first_name : $donor->display_name;

get_header();

?>
<main id="main" class="site-main site-content cf" role="main">  
    <div class="layout-wrapper">
        <div id="primary" class="content-area">
            <?php get_template_part( 'partials/banner', 'author' ) ?>
            <div class="entry-block block">
                <div class="entry cf">
                    <div class="author-description">
                        <div class="author-avatar">
                            <?php echo $avatar ?>
                        </div><!-- .author-avatar -->
                        <div class="author-facts">
                            <h2><?php printf( __( 'About %s', 'about person', 'reach' ), $first_name ) ?></h2>
                            <p><?php printf( __( 'Joined %s', 'reach' ), date('F Y', strtotime( $donor->user_registered ) ) ) ?></p>
                            <?php if ( get_current_user_id() == $donor->ID && get_theme_mod( 'edit_profile_page', false ) ) : ?>
                                <a href="<?php echo get_theme_mod( 'edit_profile_page' ) ?>" class="button"><?php _e( 'Edit Profile', 'reach' ) ?></a>
                            <?php endif ?>
                        </div><!-- .author-facts -->            
                        <div class="author-bio">                
                            <h3><?php _e( 'Bio', 'reach' ) ?></h3>
                            <?php echo apply_filters( 'the_content', $donor->description ) ?>
                        </div><!-- .author-bio -->
                        <ul class="author-links">
                            <?php if ( $donor->user_url ) : ?>
                                <li class="with-icon" data-icon="&#xf0c1;">
                                    <a target="_blank" href="<?php echo $donor->user_url ?>" title="<?php printf( __("Visit %s's website", 'reach'), $donor->display_name ) ?>"><?php echo reach_condensed_url( $donor->user_url ) ?></a>
                                </li>
                            <?php endif ?>

                            <?php if ( $donor->twitter ) : ?>
                                <li class="with-icon" data-icon="&#xf099;">
                                    <a target="_blank" href="<?php echo $donor->twitter ?>" title="<?php printf( __("Visit %s's Twitter profile", 'reach'), $donor->display_name ) ?>"><?php echo reach_condensed_url( $donor->twitter ) ?></a>
                                </li>
                            <?php endif ?>

                            <?php if ( $donor->facebook ) : ?>
                                <li class="with-icon" data-icon="&#xf09a;">
                                    <a target="_blank" href="<?php echo $donor->facebook ?>" title="<?php printf( __("Visit %s's Facebook profile", 'reach'), $donor->display_name ) ?>"><?php echo reach_condensed_url( $donor->facebook ) ?></a>
                                </li>
                            <?php endif ?>
                        </ul><!-- .author-links -->
                    </div><!-- .author-description -->      
                    <div class="author-activity">                        
                        <?php get_template_part( 'partials/author', 'activity' ) ?>
                    </div><!-- .author-activity -->
                </div><!-- .entry -->
            </div><!-- .entry-block -->
        </div><!-- #primary -->            
    </div><!-- .layout-wrapper -->
</main><!-- #main -->   
<?php 

get_footer();