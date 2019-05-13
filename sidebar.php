<?php
$ecome_blog_used_sidebar = Ecome_Functions::ecome_get_option( 'ecome_blog_used_sidebar', 'widget-area' );
if ( is_single() ) {
	$ecome_blog_used_sidebar = Ecome_Functions::ecome_get_option( 'ecome_single_used_sidebar', 'widget-area' );
}
?>
<?php if ( is_active_sidebar( $ecome_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area sidebar-blog">
		<?php dynamic_sidebar( $ecome_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>