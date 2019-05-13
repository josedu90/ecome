<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ecome_Tabs"
 */
if ( !class_exists( 'Ecome_Shortcode_Tabs' ) ) {
	class Ecome_Shortcode_Tabs extends Ecome_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'tabs';

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ecome_tabs', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'ecome-tabs' );
			$css_class[]  = $atts['style'];
			$css_class[]  = $atts['el_class'];
			$css_class[]  = $atts['tab_align'];
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ecome_tabs', $atts );
			$sections     = self::get_all_attributes( 'vc_tta_section', $content );
			$rand         = uniqid();
			ob_start(); ?>
            <div class="<?php echo implode( ' ', $css_class ); ?>">
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
                    <div class="tab-head <?php if($atts['title'] == '') { ?> no-link <?php }?>">
						<?php if ( $atts['tab_title'] && $atts['style'] == 'style2' ): ?>
                            <h2 class="ecome-title">
                                <span class="text"><?php echo esc_html( $atts['tab_title'] ); ?></span>
                            </h2>
						<?php endif; ?>
                        <?php
                        $using_loop = '0';
                        if ( $atts['using_loop'] == 1 ) {
                            $using_loop = '1';
                        }
                        ?>
                        <ul class="tab-link" data-loop="<?php echo esc_attr($using_loop); ?>">
							<?php foreach ( $sections as $key => $section ) : ?>
								<?php
								/* Get icon from section tabs */
								$section['i_type'] = isset( $section['i_type'] ) ? $section['i_type'] : 'fontawesome';
								$add_icon          = isset( $section['add_icon'] ) ? $section['add_icon'] : '';
								$position_icon     = isset( $section['i_position'] ) ? $section['i_position'] : '';
								$icon_html         = $this->constructIcon( $section );
								$class_load        = '';
								if ( $key == $atts['active_section'] )
									$class_load = 'loaded';
								?>
                                <li class="<?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>">
                                    <a class="<?php echo esc_attr( $class_load ); ?>"
                                       data-ajax="<?php echo esc_attr( $atts['ajax_check'] ) ?>"
                                       data-animate="<?php echo esc_attr( $atts['css_animation'] ); ?>"
                                       data-section="<?php echo esc_attr( $section['tab_id'] ); ?>"
                                       data-id="<?php echo get_the_ID(); ?>"
                                       href="#<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
										<?php if ( isset( $section['title_image'] ) ) : ?>
                                            <figure>
												<?php
												$image_thumb = apply_filters( 'ecome_resize_image', $section['title_image'], false, false, true, true );
												echo wp_specialchars_decode( $image_thumb['img'] );
												?>
                                            </figure>
										<?php endif; ?>
										<?php if ( $atts['style'] != 'style4' ): ?>
											<?php echo ( 'true' === $add_icon && 'right' !== $position_icon ) ? $icon_html : ''; ?>
                                            <span><?php echo esc_html( $section['title'] ); ?></span>
											<?php echo ( 'true' === $add_icon && 'right' === $position_icon ) ? $icon_html : ''; ?>
										<?php endif; ?>
                                    </a>
                                </li>
							<?php endforeach; ?>
                        </ul>
						<?php if ( $atts['link'] && $atts['style'] == 'style3' ): ?>
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
                    </div>
                    <div class="tab-container">
						<?php foreach ( $sections as $key => $section ): ?>
                            <div class="tab-panel <?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>"
                                 id="<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
								<?php if ( $atts['ajax_check'] == '1' ) {
									if ( $key == $atts['active_section'] )
										echo do_shortcode( $section['content'] );
								} else {
									echo do_shortcode( $section['content'] );
								} ?>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ecome_Shortcode_Tabs', $html, $atts, $content );
		}
	}

	new Ecome_Shortcode_Tabs();
}