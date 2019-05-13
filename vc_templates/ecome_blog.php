<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_Blog"
 */
if ( !class_exists( 'Ecome_Shortcode_Blog' ) ) {
	class Ecome_Shortcode_Blog extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'blog';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_blog', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'ecome-blog' );
			$css_class[]  = $atts['blog_style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_blog', $atts );
			/* START */
			$data_loop     = vc_build_loop_query( $atts['loop'] )[1];
			$owl_settings  = apply_filters( 'ecome_carousel_data_attributes', 'owl_', $atts );
			$template_blog = apply_filters( 'ecome_template_blog_style', 'templates/blog/blog-styles/content-blog' );
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['blog_title'] ) : ?>
                    <h4 class="ecome-title">
                        <span><?php echo esc_html( $atts['blog_title'] ); ?></span>
                    </h4>
				<?php endif; ?>
				<?php if ( $data_loop->have_posts() ) : ?>
                    <div class="blog-list-owl owl-slick" <?php echo esc_attr( $owl_settings ); ?>>
						<?php while ( $data_loop->have_posts() ) : $data_loop->the_post(); ?>
                            <article <?php post_class( 'post-item' ); ?>>
                                <div class="post-item-wrap">
                                    <div class="post-thumb">
                                        <a href="<?php the_permalink(); ?>">
											<?php
											$image_thumb = apply_filters( 'ecome_resize_image', get_post_thumbnail_id(), 443, 347, true, true );
											echo wp_specialchars_decode( $image_thumb['img'] );
											?>
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <div class="block-title">
                                            <div class="post-date">
                                                <span class="date"><?php echo get_the_date( 'd' ); ?></span>
                                                <span class="month"><?php echo get_the_date( 'M' ); ?></span>
                                            </div>
											<?php ecome_post_title(); ?>
                                        </div>
                                        <div class="post-content">
											<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 15, esc_html__( '...', 'ecome' ) ); ?>
                                        </div>
										<?php ecome_post_author(); ?>
                                    </div>
                                </div>
                            </article>
						<?php endwhile; ?>
                    </div>
				<?php else :
					get_template_part( 'content', 'none' );
				endif; ?>
            </div>
			<?php
			$array_filter = array(
				'template' => $template_blog,
				'carousel' => $owl_settings,
				'query'    => $data_loop,
			);
			wp_reset_postdata();
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Blog', $html, $atts, $content, $array_filter );
		}
	}

	new Ecome_Shortcode_Blog();
}