<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_Heading"
 */
if ( !class_exists( 'Ecome_Shortcode_Heading' ) ) {
	class Ecome_Shortcode_Heading extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'heading';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_heading', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'ecome-heading' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_heading', $atts );
			$icon         = $atts['icon_' . $atts['type']];
			// Enqueue needed icon font.
			vc_icon_element_fonts_enqueue( $atts['type'] );
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( (($atts['style'] == 'default') || ($atts['style'] == 'style1') || ($atts['style'] == 'style2')) && $icon ) : ?>
                    <div class="icon"><span class="<?php echo esc_attr( $icon ) ?>"></span></div>
				<?php endif; ?>
				<?php if ( $atts['title'] ) : ?>
                    <h4 class="ecome-title">
                        <span><?php echo esc_html( $atts['title'] ); ?></span>
                    </h4>
				<?php endif; ?>
				<?php if ( $atts['link'] && $atts['style'] != 'style1' && $atts['style'] != 'default' ) : ?>
					<?php
					$link           = vc_build_link( $atts['link'] );
					$link['target'] = $link['target'] == '' ? '_self' : $link['target'];
					$link['url']    = $link['url'] == '' ? '#' : $link['url'];
					?>
                    <a class="view-all button" href="<?php echo esc_url( $link['url'] ) ?>"
                       target="<?php echo esc_attr( $link['target'] ) ?>">
						<?php echo esc_html( $link['title'] ) ?>
                    </a>
				<?php endif; ?>
				<?php if ( $atts['desc'] && $atts['style'] == 'style3' ) : ?>
                    <p class="desc"><?php echo wp_specialchars_decode( $atts['desc'] ); ?></p>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Heading', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Heading();
}