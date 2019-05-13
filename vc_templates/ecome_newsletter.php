<?php
if ( !class_exists( 'Ecome_Shortcode_Newsletter' ) ) {
	class Ecome_Shortcode_Newsletter extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'newsletter';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_newsletter', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class    = array( 'ecome-newsletter' );
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_newsletter', $atts );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ) : ?>
                    <h3 class="widgettitle">
                        <span class="title"><?php echo esc_html( $atts['title'] ); ?></span>
                    </h3>
				<?php endif; ?>
				<?php if ( $atts['desc'] ) : ?>
                    <p class="desc"><?php echo wp_specialchars_decode( $atts['desc'] ); ?></p>
				<?php endif; ?>
                <div class="newsletter-form-wrap">
                    <input class="email email-newsletter" type="email" name="email"
                           placeholder="<?php echo esc_attr( $atts['placeholder_text'] ); ?>">
                    <a href="#" class="button btn-submit submit-newsletter">
						<?php if ( $atts['button_text'] ) : ?>
                            <span><?php echo esc_attr( $atts['button_text'] ); ?></span>
						<?php else: ?>
                            <span class="fa fa-paper-plane-o" aria-hidden="true"></span>
						<?php endif; ?>
                    </a>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Newsletter', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Newsletter();
}