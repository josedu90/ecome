<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_Slide"
 */
if ( !class_exists( 'Ecome_Shortcode_Slide' ) ) {
	class Ecome_Shortcode_Slide extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'slide';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_slide', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'ecome-slide' );
			$css_class[]  = $atts['el_class'];
			$css_class[]  = $atts['owl_rows_space'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_slide', $atts );
			$owl_settings = apply_filters( 'ecome_carousel_data_attributes', 'owl_', $atts );
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['slider_title'] ) : ?>
                    <h3 class="ecome-title"><span><?php echo esc_html( $atts['slider_title'] ); ?></span></h3>
				<?php endif; ?>
                <div class="owl-slick <?php echo esc_attr( $atts['owl_navigation_style'] ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
					<?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Slide', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Slide();
}