<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_Socials"
 */
if ( !class_exists( 'Ecome_Shortcode_Socials' ) ) {
	class Ecome_Shortcode_Socials extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'socials';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_socials', $atts ) : $atts;
			extract( $atts );
			$css_class       = array( 'ecome-socials widget-socials' );
			$css_class[]     = $atts['el_class'];
			$class_editor    = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]     = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_socials', $atts );
			$all_socials     = Ecome_Functions::ecome_get_option( 'user_all_social' );
			$get_all_socials = explode( ',', $atts['socials'] );
			ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <?php if ( $atts['title'] ) : ?>
                    <h3 class="widgettitle">
                        <span class="title"><?php echo esc_html( $atts['title'] ); ?></span>
                    </h3>
				<?php endif; ?>
                <div class="content-socials">
					<?php if ( !empty( $get_all_socials ) ) : ?>
                        <ul class="socials-list">
							<?php foreach ( $get_all_socials as $value ) : ?>
								<?php if ( isset( $all_socials[$value] ) ) :
									$array_socials = $all_socials[$value]; ?>
                                    <li>
                                        <a href="<?php echo esc_url( $array_socials['link_social'] ) ?>"
                                           target="_blank">
                                            <span class="<?php echo esc_attr( $array_socials['icon_social'] ); ?>"></span>
											<?php echo esc_html( $array_socials['title_social'] ); ?>
                                        </a>
                                    </li>
								<?php endif; ?>
							<?php endforeach; ?>
                        </ul>
					<?php endif; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Socials', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Socials();
}