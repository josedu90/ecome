<?php
global $post;
$ecome_enable_popup            = Ecome_Functions::ecome_get_option( 'ecome_enable_popup' );
$ecome_popup_title             = Ecome_Functions::ecome_get_option( 'ecome_popup_title', 'Sign up & connect to Ecome' );
$ecome_popup_desc              = Ecome_Functions::ecome_get_option( 'ecome_popup_desc', '' );
$ecome_popup_input_submit      = Ecome_Functions::ecome_get_option( 'ecome_popup_input_submit', '' );
$ecome_popup_input_placeholder = Ecome_Functions::ecome_get_option( 'ecome_popup_input_placeholder', 'Email address here...' );
$ecome_popup_background        = Ecome_Functions::ecome_get_option( 'ecome_popup_background' );
$ecome_page_newsletter         = Ecome_Functions::ecome_get_option( 'ecome_select_newsletter_page' );
if ( isset( $post->ID ) )
	$id = $post->ID;
if ( isset( $post->post_type ) )
	$post_type = $post->post_type;
if ( is_array( $ecome_page_newsletter ) && in_array( $id, $ecome_page_newsletter ) && $post_type == 'page' && $ecome_enable_popup == 1 ) :?>
    <!--  Popup Newsletter-->
    <div class="modal fade" id="popup-newsletter" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="pe-7s-close"></i>
                </button>
                <div class="modal-inner">
					<?php if ( $ecome_popup_background ) : ?>
                        <div class="modal-thumb">
							<?php
							$image_thumb = wp_get_attachment_image_src( $ecome_popup_background, 'full' );
							$img_lazy    = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $image_thumb[1] . "%20" . $image_thumb[2] . "%27%2F%3E";
							?>
                            <img class="lazy" src="<?php echo esc_attr( $img_lazy ); ?>"
                                 data-src="<?php echo esc_url( $image_thumb[0] ) ?>"
								<?php echo image_hwstring( $image_thumb[1], $image_thumb[2] ); ?>
                                 alt="<?php echo esc_attr__( 'Newsletter', 'ecome' ); ?>">
                        </div>
					<?php endif; ?>
                    <div class="modal-info">
						<?php if ( $ecome_popup_title ): ?>
                            <h2 class="title"><?php echo esc_html( $ecome_popup_title ); ?></h2>
						<?php endif; ?>
						<?php if ( $ecome_popup_desc ): ?>
                            <p class="des"><?php echo wp_specialchars_decode( $ecome_popup_desc ); ?></p>
						<?php endif; ?>
                        <div class="newsletter-form-wrap">
                            <input class="email" type="email" name="email"
                                   placeholder="<?php echo esc_html( $ecome_popup_input_placeholder ); ?>">
                            <button type="submit" name="submit_button" class="btn-submit submit-newsletter">
								<?php echo esc_html( $ecome_popup_input_submit ); ?>
                            </button>
                        </div>
                        <div class="checkbox btn-checkbox">
                            <label>
                                <input class="ecome_disabled_popup_by_user" type="checkbox">
                                <span><?php echo esc_html__( 'Don&rsquo;t show this popup again!', 'ecome' ); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--  Popup Newsletter-->
<?php endif;