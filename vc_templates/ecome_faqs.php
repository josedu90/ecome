<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_FAQs"
 */
if ( !class_exists( 'Ecome_Shortcode_FAQs' ) ) {
	class Ecome_Shortcode_FAQs extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'faqs';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_faqs', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'ecome-faqs' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_faqs', $atts );
			/* START */
			$array_id    = array();
			$count_posts = wp_count_posts( 'faqs' );
			$total_posts = $count_posts->publish;
			$args        = array(
				'post_type'      => 'faqs',
				'post_status'    => 'publish',
				'orderby'        => $atts['orderby'],
				'order'          => $atts['order'],
				'posts_per_page' => $atts['faqs_items'],
			);
			$data_loop   = new wp_query( $args );
			/* class items */
			$faqs_list_class   = array( 'response-faqs' );
			$faqs_list_class[] = 'faqs-list-grid row auto-clear equal-container better-height ';
			$faqs_item_class[] = 'equal-elem';
			$faqs_item_class[] = $atts['boostrap_rows_space'];
			$faqs_item_class[] = 'col-bg-' . $atts['boostrap_bg_items'];
			$faqs_item_class[] = 'col-lg-' . $atts['boostrap_lg_items'];
			$faqs_item_class[] = 'col-md-' . $atts['boostrap_md_items'];
			$faqs_item_class[] = 'col-sm-' . $atts['boostrap_sm_items'];
			$faqs_item_class[] = 'col-xs-' . $atts['boostrap_xs_items'];
			$faqs_item_class[] = 'col-ts-' . $atts['boostrap_ts_items'];
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ) : ?>
                    <h4 class="ecome-title">
                        <span><?php echo esc_html( $atts['title'] ); ?></span>
                    </h4>
				<?php endif; ?>
				<?php if ( $data_loop->have_posts() ) : ?>
                    <div class="<?php echo esc_attr( implode( ' ', $faqs_list_class ) ); ?>">
						<?php while ( $data_loop->have_posts() ) : $data_loop->the_post();
							$array_id[] = get_the_ID(); ?>
                            <div <?php post_class( $faqs_item_class ); ?>>
                                <div class="question">
                                    <span class="icon"><?php echo esc_html__( 'Q', 'ecome' ); ?></span>
                                    <div class="text"><?php the_title(); ?></div>
                                </div>
                                <div class="answer">
                                    <span class="icon"><?php echo esc_html__( 'A', 'ecome' ); ?></span>
                                    <div class="text"><?php the_content(); ?></div>
                                </div>
                            </div>
						<?php endwhile; ?>
                    </div>
					<?php if ( $total_posts > $atts['faqs_items'] ) : ?>
                        <div class="loadmore-faqs"
                             data-id="<?php echo json_encode( $array_id ); ?>"
                             data-query="<?php echo esc_attr( json_encode( $args ) ); ?>"
                             data-class=<?php echo esc_attr( json_encode( $faqs_item_class ) ); ?>>
                            <a href="#"><?php echo esc_html( $atts['button_load'] ); ?></a>
                        </div>
					<?php endif; ?>
				<?php endif; ?>
            </div>
			<?php
			wp_reset_postdata();
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_FAQs', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_FAQs();
}