<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Ecome
 * @since 1.0
 * @version 1.0
 */
get_header();
$ecome_page_404 = Ecome_Functions::ecome_get_option( 'ecome_page_404' );
?>
<?php if ( $ecome_page_404 ) : ?>
	<?php
	$post_id = get_post( $ecome_page_404 );
	$content = $post_id->post_content;
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]>', $content );
	echo wp_specialchars_decode( $content );
	?>
<?php else: ?>
    <div class="main-container">
        <div class="inner-page-banner">
            <div class="container">
                <h1 class="page-title">Error 404</h1>
            </div>
        </div>
        <div class="container">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <section class="error-404 not-found">
                        <h1 class="title"><?php echo esc_html__( 'Opps! This page Could Not Be Found!', 'ecome' ); ?></h1>
                        <p class="subtitle"><?php echo esc_html__( 'Sorry bit the page you are looking for does not exist, have been removed or name changed', 'ecome' ); ?></p>
						<?php get_search_form(); ?>
                        <!-- .page-content -->
                        <span class="separation-text"><?php echo esc_html__( 'or', 'ecome' ); ?></span>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                           class="button"><?php echo esc_html__( 'Back to hompage', 'ecome' ); ?></a>
                    </section><!-- .error-404 -->
                </main><!-- #main -->
            </div><!-- #primary -->
        </div>
    </div>
<?php endif;
get_footer();
