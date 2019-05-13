<?php
if ( !class_exists( 'Ecome_Shortcode_Iconbox' ) ) {
	class Ecome_Shortcode_Iconbox extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'iconbox';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_iconbox', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class    = array( 'ecome-iconbox' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_iconbox', $atts );
            $label_image = apply_filters( 'ecome_resize_image', $atts['label_image'], 41, 21, true, true );
			$icon         = $atts['icon_' . $atts['type']];
			// Enqueue needed icon font.
			vc_icon_element_fonts_enqueue( $atts['type'] );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="iconbox-inner">
                     <?php if ( $atts['label_image'] ) : ?>
                         <div class="label-image">
                             <?php echo wp_specialchars_decode( $label_image['img'] ); ?>
                         </div>
                     <?php endif; ?>
					<?php if ( $atts['title'] && $atts['style'] == 'style4' ) : ?>
						<?php $link_icon = vc_build_link( $atts['link'] );
						if ( $link_icon['url'] ) : ?>
                            <h4 class="title">
                                <a href="<?php echo esc_url( $link_icon['url'] ); ?>"
                                   target="<?php echo esc_attr( $link_icon['target'] ); ?>">
									<?php echo esc_html( $atts['title'] ); ?>
                                </a>
                            </h4>
						<?php else: ?>
                            <h4 class="title"><?php echo esc_html( $atts['title'] ); ?></h4>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $icon ): ?>
                        <div class="icon"><span class="<?php echo esc_attr( $icon ) ?>"></span></div>
					<?php endif; ?>
                    <div class="content">
						<?php if ( $atts['title'] && $atts['style'] != 'style4' ): ?>
							<?php $link_icon = vc_build_link( $atts['link'] );
							if ( $link_icon['url'] ) : ?>
                                <h4 class="title">
                                    <a href="<?php echo esc_url( $link_icon['url'] ); ?>"
                                       target="<?php echo esc_attr( $link_icon['target'] ); ?>">
										<?php echo esc_html( $atts['title'] ); ?>
                                    </a>
                                </h4>
							<?php else: ?>
                                <h4 class="title"><?php echo esc_html( $atts['title'] ); ?></h4>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( $atts['text_content'] ): ?>
                            <p class="text"><?php echo wp_specialchars_decode( $atts['text_content'] ); ?></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Iconbox', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Iconbox();
}