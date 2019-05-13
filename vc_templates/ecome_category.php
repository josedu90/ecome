<?php
if (!defined('ABSPATH')) {
    die('-1');
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_Category"
 */
if (!class_exists('Ecome_Shortcode_Category')) {
    class Ecome_Shortcode_Category extends Ecome_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'category';

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('ecome_category', $atts) : $atts;
            extract($atts);
            $css_class = array('ecome-category');
            $css_class[] = $atts['el_class'];
            $class_editor = isset($atts['css']) ? vc_shortcode_custom_css_class($atts['css'], ' ') : '';
            $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_category', $atts);
            /* START */
            ob_start(); ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="banner-thumb">
                    <?php if ($atts['banner']) : ?>
                        <?php
                        $image_gallery = apply_filters('ecome_resize_image', $atts['banner'], 315, 168, true, true);
                        echo wp_specialchars_decode($image_gallery['img']);
                        ?>
                    <?php endif; ?>
                    <?php if ($atts['title']) : ?>
                        <h4 class="cat-name">
                            <?php echo esc_html($atts['title']); ?>
                        </h4>
                    <?php endif; ?>
                </div>
                <div class="cat-info">
                    <?php if ($atts['link']) :
                        $link_cat = array(
                            'title' => '',
                            'url' => '#',
                            'target' => '_self',
                        );
                        $link_cat = array_merge($link_cat, vc_build_link($atts['link']));
                        if ($link_cat['title'] != ''):
                            ?>
                            <a class="button" href="<?php echo esc_url($link_cat['url']); ?>"
                               target="<?php echo esc_attr($link_cat['target']); ?>">
                                <?php echo esc_html($link_cat['title']); ?>
                            </a>
                        <?php endif;
                    endif; ?>
                    <?php if ($atts['taxonomy']) :
                        $categories = explode(',', $atts['taxonomy']);
                        ?>
                        <ul class="cat-list">
                            <?php foreach ($categories as $category):
                                $term = get_term_by('slug', $category, 'product_cat');
                                if (!is_wp_error($term)):
                                    $link = get_term_link($term->term_id, 'product_cat');
                                    ?>
                                    <li class="cat-item">
                                        <a href="<?php echo esc_url($link); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    </li>
                                    <?php
                                endif;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
            $html = ob_get_clean();

            return apply_filters('Ecome_Shortcode_Category', $html, $atts, $content);
        }
    }

    new Ecome_Shortcode_Category();
}