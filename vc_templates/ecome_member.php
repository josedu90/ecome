<?php
if ( !class_exists( 'Ecome_Shortcode_Member' ) ) {
	class Ecome_Shortcode_Member extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'member';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_member', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class     = array( 'ecome-member' );
			$css_class[]   = $atts['el_class'];
			$class_editor  = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]   = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_blog', $atts );
			$member_avatar = apply_filters( 'ecome_resize_image', $atts['avatar_member'], 320, 348, true, true );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['avatar_member'] ): ?>
                    <div class="member-image">
						<?php echo wp_specialchars_decode( $member_avatar['img'] ); ?>
                    </div>
				<?php endif; ?>
                <div class="member-info">
					<?php if ( $atts['name'] ): ?>
                        <h4><?php echo esc_html( $atts['name'] ); ?></h4>
					<?php endif; ?>
					<?php if ( $atts['position'] ): ?>
                        <p class="positions"><?php echo esc_html( $atts['position'] ); ?></p>
					<?php endif; ?>
					<?php if ( $atts['desc'] ): ?>
                        <p class="desc"><?php echo wp_specialchars_decode( $atts['desc'] ); ?></p>
					<?php endif; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'ecome_toolkit_shortcode_member', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Member();
}