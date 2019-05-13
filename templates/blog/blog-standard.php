<?php
if (have_posts()) : ?>
    <?php do_action('ecome_before_blog_content'); ?>
    <div class="content-post">
        <?php while (have_posts()) : the_post();
            remove_action('ecome_post_info_content', 'ecome_post_author', 30);
            add_action('ecome_post_info_content', 'ecome_post_calendar', 30);
            ?>
            <article <?php post_class('post-item'); ?>>
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
            <?php
            add_action('ecome_post_info_content', 'ecome_post_author', 30);
            remove_action('ecome_post_info_content', 'ecome_post_calendar', 30);
        endwhile;
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
endif; ?>