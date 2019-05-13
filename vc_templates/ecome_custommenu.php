<?php
if ( !class_exists( 'Ecome_Shortcode_Custommenu' ) ) {
	class Ecome_Shortcode_Custommenu extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'custommenu';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_custommenu', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class    = array( 'ecome-custommenu vc_wp_custommenu wpb_content_element' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_custommenu', $atts );
			ob_start();
			$type = 'WP_Nav_Menu_Widget';
			$args = array();
			global $wp_widget_factory;
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php
				if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[$type] ) ) {
					the_widget( $type, $atts, $args );
				} else {
					echo esc_html__( 'No content.', 'ecome' );
				}
				?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Custommenu', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Custommenu();
}