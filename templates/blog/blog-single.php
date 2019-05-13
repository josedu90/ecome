<?php
do_action( 'ecome_before_single_blog_content' );
add_action( 'ecome_post_info_content', 'ecome_post_single_meta', 30 );
remove_action( 'ecome_post_info_content', 'ecome_post_author', 30 );
?>
    <article <?php post_class( 'post-item post-single' ); ?>>
        <div class="post-item-inner">
			<?php
			/**
			 * Functions hooked into ecome_single_post_content action
			 *
			 * @hooked ecome_post_thumbnail          - 10
			 * @hooked ecome_post_info               - 20
			 * @hooked ecome_post_single_author      - 30
			 */
			do_action( 'ecome_single_post_content' ); ?>
        </div>
		<?php do_action( 'ecome_single_post_bottom_content' ); ?>
    </article>
<?php
do_action( 'ecome_after_single_blog_content' );
add_action( 'ecome_post_info_content', 'ecome_post_author', 30 );
remove_action( 'ecome_post_info_content', 'ecome_post_single_meta', 30 );