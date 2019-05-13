<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ecome
 */
?>
<?php
get_header();
$term_id       = get_queried_object_id();
$sidebar_isset = wp_get_sidebars_widgets();
/* Blog Layout */
$video                   = '';
$ecome_blog_layout       = Ecome_Functions::ecome_get_option( 'ecome_sidebar_blog_layout', 'left' );
$ecome_blog_list_style   = Ecome_Functions::ecome_get_option( 'ecome_blog_list_style', 'standard' );
$ecome_blog_used_sidebar = Ecome_Functions::ecome_get_option( 'ecome_blog_used_sidebar', 'widget-area' );
$ecome_container_class   = array( 'main-container' );
if ( is_single() ) {
	/*Single post layout*/
	$ecome_blog_layout       = Ecome_Functions::ecome_get_option( 'ecome_sidebar_single_layout', 'left' );
	$ecome_blog_used_sidebar = Ecome_Functions::ecome_get_option( 'ecome_single_used_sidebar', 'widget-area' );
}
if ( isset( $sidebar_isset[$ecome_blog_used_sidebar] ) && empty( $sidebar_isset[$ecome_blog_used_sidebar] ) ) {
	$ecome_blog_layout = 'full';
}
if ( $ecome_blog_layout == 'full' ) {
	$ecome_container_class[] = 'no-sidebar';
} else {
	$ecome_container_class[] = $ecome_blog_layout . '-sidebar';
}
$ecome_content_class   = array();
$ecome_content_class[] = 'main-content ecome_blog';
if ( $ecome_blog_layout == 'full' ) {
	$ecome_content_class[] = 'col-sm-12';
} else {
	$ecome_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
}
$ecome_slidebar_class   = array();
$ecome_slidebar_class[] = 'sidebar ecome_sidebar';
if ( $ecome_blog_layout != 'full' ) {
	$ecome_slidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $ecome_container_class ) ); ?>">
    <!-- POST LAYOUT -->
    <div class="container">
		<?php
		if ( !is_front_page() ) {
			$args = array(
				'container'     => 'div',
				'before'        => '',
				'after'         => '',
				'show_on_front' => true,
				'network'       => false,
				'show_title'    => true,
				'show_browse'   => false,
				'post_taxonomy' => array(),
				'labels'        => array(),
				'echo'          => true,
			);
			do_action( 'ecome_breadcrumb', $args );
		}
		?>
		<?php if ( !is_single() ) : ?>
			<?php if ( is_home() ) : ?>
				<?php if ( is_front_page() ): ?>
                    <h1 class="page-title blog-title"><?php esc_html_e( 'Latest Posts', 'ecome' ); ?></h1>
				<?php else: ?>
                    <h1 class="page-title blog-title"><?php single_post_title(); ?></h1>
				<?php endif; ?>
			<?php elseif ( is_page() ): ?>
                <h1 class="page-title blog-title"><?php single_post_title(); ?></h1>
			<?php elseif ( is_search() ): ?>
                <h1 class="page-title blog-title"><?php printf( esc_html__( 'Search Results for: %s', 'ecome' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<?php else: ?>
                <h1 class="page-title blog-title"><?php the_archive_title( '', '' );; ?></h1>
				<?php
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			<?php endif; ?>
		<?php endif; ?>
        <div class="row">
            <div class="<?php echo esc_attr( implode( ' ', $ecome_content_class ) ); ?>">
				<?php
				if ( is_single() ) {
					while ( have_posts() ): the_post();
						ecome_set_post_views( get_the_ID() );
						get_template_part( 'templates/blog/blog', 'single' );
						/*If comments are open or we have at least one comment, load up the comment template.*/
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
					wp_reset_postdata();
				} else {
					get_template_part( 'templates/blog/blog', $ecome_blog_list_style );
				} ?>
            </div>
			<?php if ( $ecome_blog_layout != 'full' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $ecome_slidebar_class ) ); ?>">
					<?php get_sidebar(); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
	if ( function_exists( 'ecome_recent_view_product' ) )
		ecome_recent_view_product();
	?>
</div>
<?php get_footer(); ?>
