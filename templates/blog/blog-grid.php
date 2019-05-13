<?php
// Custom columns
$classes[] = 'post-item';
$classes[] = 'col-bg-' . Ecome_Functions::ecome_get_option('ecome_blog_bg_items', 4);
$classes[] = 'col-lg-' . Ecome_Functions::ecome_get_option('ecome_blog_lg_items', 4);
$classes[] = 'col-md-' . Ecome_Functions::ecome_get_option('ecome_blog_md_items', 4);
$classes[] = 'col-sm-' . Ecome_Functions::ecome_get_option('ecome_blog_sm_items', 6);
$classes[] = 'col-xs-' . Ecome_Functions::ecome_get_option('ecome_blog_xs_items', 6);
$classes[] = 'col-ts-' . Ecome_Functions::ecome_get_option('ecome_blog_ts_items', 12);
$classes[] = apply_filters('ecome_blog_content_class', '');
if (have_posts()) : ?>
    <?php do_action('ecome_before_blog_content'); ?>
    <div class="row blog-grid content-post auto-clear">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class($classes); ?>>
                <div class="post-item-inner">
                    <?php
                    /**
                     * Functions hooked into ecome_post_content action
                     *
                     * @hooked ecome_post_thumbnail          - 10
                     * @hooked ecome_post_info               - 20
                     */
                    do_action('ecome_post_content'); ?>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata(); ?>
    </div>
    <?php
    /**
     * Functions hooked into ecome_after_blog_content action
     *
     * @hooked ecome_paging_nav               - 10
     */
    do_action('ecome_after_blog_content'); ?>
<?php else :
    get_template_part('content', 'none');
endif;