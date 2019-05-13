<?php
/**
 * WooCommerce Template
 *
 * Functions for the templating system.
 *
 * @author   Khanh
 * @category Core
 * @package  Ecome_Woo_Functions
 * @version  1.0.0
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! function_exists( 'ecome_action_wp_loaded' ) ) {
	function ecome_action_wp_loaded() {
		/* QUICK VIEW */
		if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
			// Class frontend
			$enable           = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
			$enable_on_mobile = get_option( 'yith-wcqv-enable-mobile' ) == 'yes' ? true : false;
			// Class frontend
			if ( ( ! wp_is_mobile() && $enable ) || ( wp_is_mobile() && $enable_on_mobile && $enable ) ) {
				remove_action( 'woocommerce_after_shop_loop_item', array(
					YITH_WCQV_Frontend::get_instance(),
					'yith_add_quick_view_button'
				), 15 );
				add_action( 'ecome_function_shop_loop_item_quickview', array(
					YITH_WCQV_Frontend::get_instance(),
					'yith_add_quick_view_button'
				), 5 );
			}
		}
		/* WISH LIST */
		if ( defined( 'YITH_WCWL' ) ) {
			add_action( 'ecome_function_shop_loop_item_wishlist', function() {
				echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
			}, 1 );
		}
		/* COMPARE */
		if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) {
			global $yith_woocompare;
			$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			if ( $yith_woocompare->is_frontend() || $is_ajax ) {
				if ( $is_ajax ) {
					if ( ! class_exists( 'YITH_Woocompare_Frontend' ) && file_exists( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' ) ) {
						require_once YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
					}
					$yith_woocompare->obj = new YITH_Woocompare_Frontend();
				}
				/* Remove button */
				remove_action( 'woocommerce_after_shop_loop_item', array(
					$yith_woocompare->obj,
					'add_compare_link'
				), 20 );
				/* Add compare button */
				if ( ! function_exists( 'ecome_wc_loop_product_compare_btn' ) ) {
					function ecome_wc_loop_product_compare_btn() {
						if ( shortcode_exists( 'yith_compare_button' ) ) {
							echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
						} else {
							if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
								echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
							}
						}
					}
				}
				add_action( 'ecome_function_shop_loop_item_compare', 'ecome_wc_loop_product_compare_btn', 1 );
			}
		}
	}
}
/* SINGLE PRODUCT */
if ( ! function_exists( 'ecome_before_main_content_left' ) ) {
	function ecome_before_main_content_left() {
		global $product;
		$class          = 'no-gallery';
		$attachment_ids = $product->get_gallery_image_ids();
		if ( $attachment_ids && has_post_thumbnail() ) {
			$class = 'has-gallery';
		}
		echo '<div class="main-contain-summary"><div class="contain-left ' . esc_attr( $class ) . '"><div class="single-left">';
	}
}
if ( ! function_exists( 'ecome_after_main_content_left' ) ) {
	function ecome_after_main_content_left() {
		echo '</div>';
	}
}
if ( ! function_exists( 'ecome_woocommerce_after_single_product_summary_1' ) ) {
	function ecome_woocommerce_after_single_product_summary_1() {
		echo '</div>';
	}
}
if ( ! function_exists( 'ecome_woocommerce_before_single_product_summary_2' ) ) {
	function ecome_woocommerce_before_single_product_summary_2() {
		echo '</div>';
	}
}
if ( ! function_exists( 'ecome_woocommerce_before_shop_loop' ) ) {
	function ecome_woocommerce_before_shop_loop() {
		echo '<div class="row auto-clear equal-container better-height ecome-products">';
	}
}
if ( ! function_exists( 'ecome_woocommerce_after_shop_loop' ) ) {
	function ecome_woocommerce_after_shop_loop() {
		echo '</div>';
	}
}
/* GALLERY PRODUCT */
if ( ! function_exists( 'ecome_gallery_product_thumbnail' ) ) {
	function ecome_gallery_product_thumbnail() {
		global $post, $product;
		// GET SIZE IMAGE SETTING
		$width  = 300;
		$height = 300;
		$crop   = true;
		$size   = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$width  = $size['width'];
			$height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		$html           = '';
		$html_thumb     = '';
		$attachment_ids = $product->get_gallery_image_ids();
		$width          = apply_filters( 'ecome_shop_pruduct_thumb_width', $width );
		$height         = apply_filters( 'ecome_shop_pruduct_thumb_height', $height );
		/* primary image */
		$image_thumb       = apply_filters( 'ecome_resize_image', get_post_thumbnail_id( $product->get_id() ), $width, $height, $crop, true );
		$thumbnail_primary = apply_filters( 'ecome_resize_image', get_post_thumbnail_id( $product->get_id() ), 136, 130, $crop, true );
		$html              .= '<figure class="product-gallery-image">';
		$html              .= $image_thumb['img'];
		$html              .= '</figure>';
		$html_thumb        .= '<figure>' . $thumbnail_primary['img'] . '</figure>';
		/* thumbnail image */
		if ( $attachment_ids && has_post_thumbnail() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$gallery_thumb   = apply_filters( 'ecome_resize_image', $attachment_id, $width, $height, $crop, true );
				$thumbnail_image = apply_filters( 'ecome_resize_image', $attachment_id, 136, 130, $crop, true );
				$html            .= '<figure class="product-gallery-image">';
				$html            .= $gallery_thumb['img'];
				$html            .= '</figure>';
				$html_thumb      .= '<figure>' . $thumbnail_image['img'] . '</figure>';
			}
		}
		?>
        <div class="product-gallery">
            <div class="product-gallery-slick">
				<?php echo wp_specialchars_decode( $html ); ?>
            </div>
            <div class="gallery-dots">
				<?php echo wp_specialchars_decode( $html_thumb ); ?>
            </div>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_single_thumbnail_addtocart' ) ) {
	function ecome_single_thumbnail_addtocart() {
		global $product;
		// GET SIZE IMAGE SETTING
		$width  = 300;
		$height = 300;
		$crop   = true;
		$size   = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$width  = $size['width'];
			$height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		$data_src                = '';
		$attachment_ids          = $product->get_gallery_image_ids();
		$gallery_class_img       = $class_img = array( 'img-responsive' );
		$thumb_gallery_class_img = $thumb_class_img = array( 'thumb-link' );
		$width                   = apply_filters( 'ecome_shop_pruduct_thumb_width', $width );
		$height                  = apply_filters( 'ecome_shop_pruduct_thumb_height', $height );
		$image_thumb             = apply_filters( 'ecome_resize_image', get_post_thumbnail_id( $product->get_id() ), $width, $height, $crop, true );
		$image_url               = $image_thumb['url'];
		$lazy_options            = Ecome_Functions::ecome_get_option( 'ecome_theme_lazy_load' );
		$default_attributes      = $product->get_default_attributes();
		if ( $lazy_options == 1 && empty( $default_attributes ) ) {
			$class_img[] = 'lazy';
			$data_src    = 'data-src=' . esc_attr( $image_thumb['url'] );
			$image_url   = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $width . "%20" . $height . "%27%2F%3E";
		}
		if ( $attachment_ids && has_post_thumbnail() ) {
			$gallery_class_img[]       = 'wp-post-image';
			$thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
		} else {
			$class_img[]       = 'wp-post-image';
			$thumb_class_img[] = 'woocommerce-product-gallery__image';
		}
		?>

        <img class="<?php echo implode( ' ', $class_img ); ?>" src="<?php echo esc_attr( $image_url ); ?>"
			<?php echo esc_attr( $data_src ); ?> <?php echo image_hwstring( $width, $height ); ?>
             alt="<?php echo esc_attr( the_title_attribute() ); ?>">
		
		<?php
	}
}
/* ADD TO CART STICKY PRODUCT */
if ( ! function_exists( 'ecome_add_to_cart_stikcy' ) ) {
	function ecome_add_to_cart_stikcy() {
		global $product;
		$enable_info_product_single = Ecome_Functions::ecome_get_option( 'enable_info_product_single' );
		if ( $enable_info_product_single == 1 ) : ?>
            <div class="sticky_info_single_product">
                <div class="container">
                    <div class="sticky-thumb-left">
						<?php
						do_action( 'single_product_addtocart_thumb' );
						?>
                    </div>
                    <div class="sticky-info-right">
                        <div class="sticky-title">
							<?php
							do_action( 'single_product_addtocart' );
							do_action( 'woocommerce_after_shop_loop_item_title' );
							?>
                        </div>
						<?php if ( $product->is_purchasable() || $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) { ?>
							<?php if ( $product->is_in_stock() ) { ?>
                                <button type="button"
                                        class="ecome-single-add-to-cart-fixed-top ecome-single-add-to-cart-btn btn button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                                </button>
							<?php } else { ?>
                                <button type="button"
                                        class="ecome-single-add-to-cart-fixed-top ecome-single-add-to-cart-btn add-to-cart-out-of-stock btn button"><?php esc_html_e( 'Out Of Stock', 'ecome' ); ?>
                                </button>
							<?php } ?>
						<?php } ?>
                    </div>
                </div>
            </div>
		<?php endif;
		
	}
}
/* ADD CATEGORIES LIST IN PRODUCT */
if ( ! function_exists( 'ecome_add_categories_product' ) ) {
	function ecome_add_categories_product() {
		$html = '';
		$html .= '<div class="cat-list">';
		$html .= wc_get_product_category_list( get_the_ID() );
		$html .= '</div>';
		echo wp_specialchars_decode( $html );
	}
}
if ( ! function_exists( 'ecome_faqs_single_product' ) ) {
	function ecome_faqs_single_product() {
		$page_faqs_product = Ecome_Functions::ecome_get_option( 'ecome_add_page_product' );
		if ( $page_faqs_product && is_product() ) {
			$posts   = get_post( $page_faqs_product );
			$content = $posts->post_content;
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]>', $content );
			echo '<div class="recent-product-woo">';
			echo wp_specialchars_decode( $content );
			echo '</div>';
		}
	}
}
if ( ! function_exists( 'ecome_recent_view_product' ) ) {
	function ecome_recent_view_product() {
		$enable_recent_product = Ecome_Functions::ecome_get_option( 'enable_recent_product' );
		if ( $enable_recent_product == 1 ) : ?>
            <div class="container">
                <div class="recent-product-woo row">
                    <div class="col-sm-4">
						<?php echo do_shortcode( '[ecome_products product_style="5" product_image_size="100x100" target="featured_products" per_page="3" boostrap_rows_space="rows-space-20" boostrap_bg_items="12" boostrap_lg_items="12" boostrap_md_items="12" boostrap_sm_items="12" boostrap_xs_items="12" boostrap_ts_items="12" ecome_custom_id="" title="' . esc_html__( 'Featured Products', 'ecome' ) . '"]' ); ?>
                    </div>
                    <div class="col-sm-4">
						<?php echo do_shortcode( '[ecome_products product_style="5" product_image_size="100x100" target="top-rated" per_page="3" boostrap_rows_space="rows-space-20" boostrap_bg_items="12" boostrap_lg_items="12" boostrap_md_items="12" boostrap_sm_items="12" boostrap_xs_items="12" boostrap_ts_items="12" ecome_custom_id="" title="' . esc_html__( 'Top Rated Products', 'ecome' ) . '"]' ); ?>
                    </div>
                    <div class="col-sm-4">
						<?php echo do_shortcode( '[ecome_products product_style="5" product_image_size="100x100" target="best-selling" per_page="3" boostrap_rows_space="rows-space-20" boostrap_bg_items="12" boostrap_lg_items="12" boostrap_md_items="12" boostrap_sm_items="12" boostrap_xs_items="12" boostrap_ts_items="12" ecome_custom_id="" title="' . esc_html__( 'Top Selling Products', 'ecome' ) . '"]' ); ?>
                    </div>
                </div>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'ecome_action_attributes' ) ) {
	function ecome_action_attributes() {
		global $product;
		if ( $product->get_type() == 'variable' ) :
			$attribute_array = array();
			$attributes = $product->get_variation_attributes();
			$attribute_keys = array_keys( $attributes );
			$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
			$available_variations = $get_variations ? $product->get_available_variations() : false;
			
			if ( is_wp_error( $available_variations ) ) {
				return;
			}
			if ( empty( $available_variations ) ) {
				return;
			}
			
			// GET SIZE IMAGE SETTING
			$width  = 300;
			$height = 300;
			$size   = wc_get_image_size( 'shop_catalog' );
			if ( $size ) {
				$width  = $size['width'];
				$height = $size['height'];
			}
			$width  = apply_filters( 'ecome_shop_pruduct_thumb_width', $width );
			$height = apply_filters( 'ecome_shop_pruduct_thumb_height', $height );
			foreach ( $available_variations as $available_variation ) {
				$image_variable                            = apply_filters( 'ecome_resize_image', $available_variation['image_id'], $width, $height, true, false );
				$available_variation['image']['src']       = $image_variable['url'];
				$available_variation['image']['url']       = $image_variable['url'];
				$available_variation['image']['full_src']  = $image_variable['url'];
				$available_variation['image']['thumb_src'] = $image_variable['url'];
				$available_variation['image']['src_w']     = $width;
				$available_variation['image']['src_h']     = $height;
				$attribute_array[]                         = $available_variation;
			}
			if ( ! empty( $attributes ) ):?>
                <form class="variations_form cart" method="post" enctype='multipart/form-data'
                      data-product_id="<?php echo absint( $product->get_id() ); ?>"
                      data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $attribute_array ) ) ?>">
                    <table class="variations">
                        <tbody>
						<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                            <tr>
                                <td class="value">
									<?php
									$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
									wc_dropdown_variation_attribute_options( array(
										                                         'options'   => $options,
										                                         'attribute' => $attribute_name,
										                                         'product'   => $product,
										                                         'selected'  => $selected
									                                         ) );
									echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'ecome' ) . '</a>' ) : '';
									?>
                                </td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
				<?php
			endif;
		endif;
	}
}
if ( ! function_exists( 'ecome_show_attributes' ) ) {
	function ecome_show_attributes() {
		global $product;
		$attribute_name = Ecome_Functions::ecome_get_option( 'ecome_attribute_product' );
		if ( ! is_woocommerce() ) {
			$attribute_name = apply_filters( 'ecome_attribute_name', $attribute_name );
		}
		$terms = wc_get_product_terms( $product->get_id(), 'pa_' . $attribute_name, array( 'fields' => 'all' ) );
		if ( ! empty( $terms ) ) : ?>
            <ul class="list-attribute">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					$args         = array(
						'filter_' . $attribute_name => $term->slug,
					);
					$shop_link    = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
					$archive_link = $shop_link . '?' . http_build_query( $args );
					?>
                    <li>
                        <a href="<?php echo esc_url( $archive_link ); ?>"><?php echo esc_html( $term->name ); ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
			<?php
		endif;
	}
}
if ( ! function_exists( 'ecome_single_show_attributes' ) ) {
	function ecome_single_show_attributes() {
		global $product;
		$attribute_name = Ecome_Functions::ecome_get_option( 'ecome_single_attribute' );
		$terms          = wc_get_product_terms( $product->get_id(), 'pa_' . $attribute_name, array( 'fields' => 'all' ) );
		if ( ! empty( $terms ) ) : ?>
            <div class="brand-product">
                <p class="title-brand"><?php echo esc_html__( 'Brand:', 'ecome' ); ?></p>
                <ul class="list-attribute">
					<?php foreach ( $terms as $term ) : ?>
						<?php
						$data_type    = get_term_meta( $term->term_id, $term->taxonomy . '_attribute_swatch_type', true );
						$data_color   = get_term_meta( $term->term_id, $term->taxonomy . '_attribute_swatch_color', true );
						$args         = array(
							'filter_' . $attribute_name => $term->slug,
						);
						$shop_link    = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
						$archive_link = $shop_link . '?' . http_build_query( $args );
						?>
                        <li class="<?php echo esc_attr( $data_type ); ?>">
							<?php if ( $data_type == 'photo' ):
								$data_photo = get_term_meta( $term->term_id, $term->taxonomy . '_attribute_swatch_photo', true );
								$photo_url = wp_get_attachment_url( $data_photo );
								?>
                                <a href="<?php echo esc_url( $archive_link ); ?>">
                                    <img src="<?php echo esc_url( $photo_url ); ?>"
                                         alt="<?php echo esc_attr( get_the_title() ); ?>">
                                </a>
							<?php elseif ( $data_type == 'color' ): ?>
                                <a href="<?php echo esc_url( $archive_link ); ?>"
                                   style="background-color: <?php echo esc_url( $data_color ); ?>">
									<?php echo esc_html( $term->name ); ?>
                                </a>
							<?php else: ?>
                                <a href="<?php echo esc_url( $archive_link ); ?>">
									<?php echo esc_html( $term->name ); ?>
                                </a>
							<?php endif; ?>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'ecome_single_show_sku' ) ) {
	function ecome_single_show_sku() {
		global $product;
		if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
            <div class="product-sku">
				<?php esc_html_e( 'SKU:', 'ecome' ); ?>
                <span class="sku">
                    <?php
                    ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'ecome' );
                    echo esc_html( $sku );
                    ?>
                </span>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'ecome_single_show_tags' ) ) {
	function ecome_single_show_tags() {
		$get_term_tag = get_the_terms( get_the_ID(), 'product_tag' );
		if ( ! is_wp_error( $get_term_tag ) && ! empty( $get_term_tag ) ) : ?>
            <div class="product-tags">
				<?php esc_html_e( 'Tags:', 'ecome' ); ?>
                <div class="tags-list">
					<?php foreach ( $get_term_tag as $item ):
						$link = get_term_link( $item->term_id, 'product_tag' );
						?>
                        <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $item->name ); ?></a>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'ecome_wc_get_template_part' ) ) {
	function ecome_wc_get_template_part( $template, $slug, $name ) {
		if ( $slug == 'content' && $name == 'product' ) {
			$template = apply_filters( 'ecome_woocommerce_content_product', plugin_dir_path( __FILE__ ) . 'content-product.php' );
		}
		
		return $template;
	}
}
if ( ! function_exists( 'ecome_woocommerce_breadcrumb' ) ) {
	function ecome_woocommerce_breadcrumb() {
		$args = array(
			'delimiter' => '<i class="fa fa-angle-right"></i>',
		);
		woocommerce_breadcrumb( $args );
	}
}
if ( ! function_exists( 'ecome_woocommerce_before_loop_content' ) ) {
	function ecome_woocommerce_before_loop_content() {
		$sidebar_isset = wp_get_sidebars_widgets();
		/*Shop layout*/
		$shop_layout  = Ecome_Functions::ecome_get_option( 'ecome_sidebar_shop_layout', 'left' );
		$shop_sidebar = Ecome_Functions::ecome_get_option( 'ecome_shop_used_sidebar', 'widget-shop' );
		if ( is_product() ) {
			$shop_layout  = Ecome_Functions::ecome_get_option( 'ecome_sidebar_product_layout', 'left' );
			$shop_sidebar = Ecome_Functions::ecome_get_option( 'ecome_single_product_used_sidebar', 'widget-product' );
		}
		if ( isset( $sidebar_isset[ $shop_sidebar ] ) && empty( $sidebar_isset[ $shop_sidebar ] ) ) {
			$shop_layout = 'full';
		}
		$main_content_class   = array();
		$main_content_class[] = 'main-content';
		if ( $shop_layout == 'full' ) {
			$main_content_class[] = 'col-sm-12';
		} else {
			$main_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12 has-sidebar';
		}
		$main_content_class = apply_filters( 'ecome_class_archive_content', $main_content_class, $shop_layout );
		echo '<div class="' . esc_attr( implode( ' ', $main_content_class ) ) . '">';
	}
}
if ( ! function_exists( 'ecome_woocommerce_after_loop_content' ) ) {
	function ecome_woocommerce_after_loop_content() {
		echo '</div>';
	}
}
if ( ! function_exists( 'ecome_woocommerce_before_main_content' ) ) {
	function ecome_woocommerce_before_main_content() {
		/*Main container class*/
		$main_container_class = array();
		$sidebar_isset        = wp_get_sidebars_widgets();
		$shop_layout          = Ecome_Functions::ecome_get_option( 'ecome_sidebar_shop_layout', 'left' );
		$shop_sidebar         = Ecome_Functions::ecome_get_option( 'ecome_shop_used_sidebar', 'widget-shop' );
		if ( is_product() ) {
			$shop_layout            = Ecome_Functions::ecome_get_option( 'ecome_sidebar_product_layout', 'left' );
			$shop_sidebar           = Ecome_Functions::ecome_get_option( 'ecome_single_product_used_sidebar', 'widget-product' );
			$thumbnail_layout       = 'vertical';
			$main_container_class[] = 'single-thumb-' . $thumbnail_layout;
		}
		if ( isset( $sidebar_isset[ $shop_sidebar ] ) && empty( $sidebar_isset[ $shop_sidebar ] ) ) {
			$shop_layout = 'full';
		}
		$main_container_class[] = 'main-container shop-page';
		if ( $shop_layout == 'full' ) {
			$main_container_class[] = 'no-sidebar';
		} else {
			$main_container_class[] = $shop_layout . '-sidebar';
		}
		$main_container_class = apply_filters( 'ecome_class_before_main_content_product', $main_container_class, $shop_layout );
		echo '<div class="' . esc_attr( implode( ' ', $main_container_class ) ) . '">';
		echo '<div class="container">';
		echo '<div class="row">';
	}
}
if ( ! function_exists( 'ecome_woocommerce_after_main_content' ) ) {
	function ecome_woocommerce_after_main_content() {
		echo '</div></div></div>';
	}
}
if ( ! function_exists( 'ecome_woocommerce_sidebar' ) ) {
	function ecome_woocommerce_sidebar() {
		$shop_layout  = Ecome_Functions::ecome_get_option( 'ecome_sidebar_shop_layout', 'left' );
		$shop_sidebar = Ecome_Functions::ecome_get_option( 'ecome_shop_used_sidebar', 'widget-shop' );
		if ( is_product() ) {
			$shop_layout  = Ecome_Functions::ecome_get_option( 'ecome_sidebar_product_layout', 'left' );
			$shop_sidebar = Ecome_Functions::ecome_get_option( 'ecome_single_product_used_sidebar', 'widget-product' );
		}
		$sidebar_class = array();
		$sidebar_isset = wp_get_sidebars_widgets();
		if ( isset( $sidebar_isset[ $shop_sidebar ] ) && empty( $sidebar_isset[ $shop_sidebar ] ) ) {
			$shop_layout = 'full';
		}
		$sidebar_class[] = 'sidebar';
		if ( $shop_layout != 'full' ) {
			$sidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
		}
		$sidebar_class = apply_filters( 'ecome_class_sidebar_content_product', $sidebar_class, $shop_layout, $shop_sidebar );
		if ( $shop_layout != "full" ): ?>
            <div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
				<?php if ( is_active_sidebar( $shop_sidebar ) ) : ?>
                    <div id="widget-area" class="widget-area shop-sidebar">
						<?php dynamic_sidebar( $shop_sidebar ); ?>
                    </div><!-- .widget-area -->
				<?php endif; ?>
            </div>
		<?php endif;
	}
}
if ( ! function_exists( 'ecome_sidebar_single_product' ) ) {
	function ecome_sidebar_single_product() {
		$shop_layout  = Ecome_Functions::ecome_get_option( 'ecome_sidebar_product_layout', 'left' );
		$shop_sidebar = Ecome_Functions::ecome_get_option( 'ecome_single_product_summary_sidebar', 'widget-summary-product' );
		if ( is_product() && is_active_sidebar( $shop_sidebar ) && $shop_layout == 'full' ) : ?>
            <div id="widget-area" class="widget-area shop-sidebar">
				<?php dynamic_sidebar( $shop_sidebar ); ?>
            </div><!-- .widget-area -->
			<?php
		endif;
	}
}
if ( ! function_exists( 'ecome_product_get_rating_html' ) ) {
	function ecome_product_get_rating_html( $html, $rating, $count ) {
		global $product;
		$rating_count = $product->get_rating_count();
		if ( 0 < $rating ) {
			$html = '<div class="rating-wapper"><div class="star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
			$html .= '<span class="review">( ' . $rating_count . ' ' . esc_html__( 'review(s)', 'ecome' ) . ' )</span>';
			$html .= '</div>';
		} else {
			$html = '';
		}
		
		return $html;
	}
}
if ( ! function_exists( 'ecome_before_shop_control' ) ) {
	function ecome_before_shop_control() {
		?>
        <div class="shop-control shop-before-control">
			<?php do_action( 'ecome_control_before_content' ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_after_shop_control' ) ) {
	function ecome_after_shop_control() {
		?>
        <div class="shop-control shop-after-control">
			<?php do_action( 'ecome_control_after_content' ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'product_display_mode_request' ) ) {
	function product_display_mode_request() {
		if ( isset( $_POST['display_mode_action'] ) ) {
			wp_redirect(
				add_query_arg(
					array(
						'shop_display_mode' => $_POST['display_mode_value'],
						// 'ecome_shop_list_style' => $_POST['display_mode_value'],
					), $_POST['display_mode_action']
				)
			);
			exit();
		}
	}
}
if ( ! function_exists( 'ecome_shop_display_mode_tmp' ) ) {
	function ecome_shop_display_mode_tmp() {
		// $shop_display_mode = Ecome_Functions::ecome_get_option( 'ecome_shop_list_style', 'grid' );
		$shop_display_mode = Ecome_Functions::ecome_get_option( 'shop_display_mode', 'grid' );
		$current_url       = home_url( add_query_arg( null, null ) );
		?>
        <div class="grid-view-mode">
            <form method="POST" action="">
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="<?php echo esc_attr( 'Shop Grid v.1' ); ?>"
                        class="modes-mode mode-grid display-mode <?php if ( $shop_display_mode == 'grid' ): ?>active<?php endif; ?>"
                        value="<?php echo esc_attr( $current_url ); ?>"
                        name="display_mode_action">
                        <span class="button-inner">
                            <?php echo esc_html__( 'Grid', 'ecome' ); ?>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <input type="hidden" value="grid" name="display_mode_value">
            </form>
            <form method="POST" action="<?php echo esc_attr( $current_url ); ?>">
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="<?php echo esc_attr( 'Shop Grid v.2' ); ?>"
                        class="modes-mode mode-grid-v2 display-mode <?php if ( $shop_display_mode == 'grid-v2' ): ?>active<?php endif; ?>"
                        value="<?php echo esc_attr( $current_url ); ?>"
                        name="display_mode_action">
                        <span class="button-inner">
                            <?php echo esc_html__( 'Grid v2', 'ecome' ); ?>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <input type="hidden" value="grid-v2" name="display_mode_value">
            </form>
            <form method="POST" action="<?php echo esc_attr( $current_url ); ?>">
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="<?php echo esc_attr( 'Shop List Mode' ); ?>"
                        class="modes-mode mode-list display-mode <?php if ( $shop_display_mode == 'list' ): ?>active<?php endif; ?>"
                        value="<?php echo esc_attr( $current_url ); ?>"
                        name="display_mode_action">
                        <span class="button-inner">
                            <?php echo esc_html__( 'List', 'ecome' ); ?>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <input type="hidden" value="list" name="display_mode_value">
            </form>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_loop_shop_per_page' ) ) {
	function ecome_loop_shop_per_page() {
		$ecome_woo_products_perpage = Ecome_Functions::ecome_get_option( 'ecome_product_per_page', '12' );
		
		return $ecome_woo_products_perpage;
	}
}
if ( ! function_exists( 'ecome_woof_products_query' ) ) {
	function ecome_woof_products_query( $wr ) {
		$ecome_woo_products_perpage = Ecome_Functions::ecome_get_option( 'ecome_product_per_page', '12' );
		$wr['posts_per_page']       = $ecome_woo_products_perpage;
		
		return $wr;
	}
}
if ( ! function_exists( 'product_per_page_request' ) ) {
	function product_per_page_request() {
		if ( isset( $_POST['perpage_action_form'] ) ) {
			wp_redirect(
				add_query_arg(
					array(
						'ecome_product_per_page' => $_POST['product_per_page_filter'],
					), $_POST['perpage_action_form']
				)
			);
			exit();
		}
	}
}
if ( ! function_exists( 'ecome_product_per_page_tmp' ) ) {
	function ecome_product_per_page_tmp() {
		$perpage     = Ecome_Functions::ecome_get_option( 'ecome_product_per_page', '12' );
		$current_url = home_url( add_query_arg( null, null ) );
		$products    = wc_get_loop_prop( 'total' );
		?>
        <form class="per-page-form" method="POST" action="">
            <label>
                <select name="product_per_page_filter" class="option-perpage" onchange="this.form.submit()">
                    <option value="<?php echo esc_attr( $perpage ); ?>" <?php echo esc_attr( 'selected' ); ?>>
						<?php echo 'Show ' . zeroise( $perpage, 2 ); ?>
                    </option>
                    <option value="5">
						<?php echo esc_html__( 'Show 05', 'ecome' ); ?>
                    </option>
                    <option value="10">
						<?php echo esc_html__( 'Show 10', 'ecome' ); ?>
                    </option>
                    <option value="12">
						<?php echo esc_html__( 'Show 12', 'ecome' ); ?>
                    </option>
                    <option value="15">
						<?php echo esc_html__( 'Show 15', 'ecome' ); ?>
                    </option>
                    <option value="<?php echo esc_attr( $products ); ?>">
						<?php echo esc_html__( 'Show All', 'ecome' ); ?>
                    </option>
                </select>
            </label>
            <label>
                <input type="hidden" name="perpage_action_form" value="<?php echo esc_attr( $current_url ); ?>">
            </label>
        </form>
		<?php
	}
}
if ( ! function_exists( 'ecome_custom_pagination' ) ) {
	function ecome_custom_pagination() {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) {
			?>
            <nav class="woocommerce-pagination pagination">
				<?php
				echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
					'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
					'format'    => '',
					'add_args'  => false,
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $wp_query->max_num_pages,
					'prev_text' => esc_html__( 'Previous', 'ecome' ),
					'next_text' => esc_html__( 'Next', 'ecome' ),
					'type'      => 'plain',
					'end_size'  => 3,
					'mid_size'  => 3,
				) ) );
				?>
            </nav>
			<?php
		}
	}
}
if ( ! function_exists( 'ecome_related_title_product' ) ) {
	add_action( 'ecome_before_related_single_product', 'ecome_related_title_product' );
	function ecome_related_title_product( $prefix ) {
		if ( $prefix == 'ecome_woo_crosssell' ) {
			$default_text = esc_html__( 'Cross Sell Products', 'ecome' );
		} elseif ( $prefix == 'ecome_woo_related' ) {
			$default_text = esc_html__( 'Related Products', 'ecome' );
		} else {
			$default_text = esc_html__( 'Upsell Products', 'ecome' );
		}
		$title = Ecome_Functions::ecome_get_option( $prefix . '_products_title', $default_text );
		$aUrl  = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
		?>
        <div class="block-title">
            <h2 class="product-grid-title">
                <span><?php echo esc_html( $title ); ?></span>
            </h2>
            <a href="<?php echo esc_url( $aUrl ); ?>">
				<?php echo esc_html__( 'Shop more', 'ecome' ); ?>
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_woocommerce_category_description' ) ) {
	function ecome_woocommerce_category_description() {
		$enable_cat = Ecome_Functions::ecome_get_option( 'ecome_woo_cat_enable' );
		$banner_cat = Ecome_Functions::ecome_get_option( 'category_banner' );
		$banner_url = Ecome_Functions::ecome_get_option( 'category_banner_url', '#' );
		if ( is_product_category() && $enable_cat == 1 ) {
			$category_html = '';
			$prefix        = 'ecome_woo_cat';
			$woo_ls_items  = Ecome_Functions::ecome_get_option( $prefix . '_ls_items', 3 );
			$woo_lg_items  = Ecome_Functions::ecome_get_option( $prefix . '_lg_items', 3 );
			$woo_md_items  = Ecome_Functions::ecome_get_option( $prefix . '_md_items', 3 );
			$woo_sm_items  = Ecome_Functions::ecome_get_option( $prefix . '_sm_items', 2 );
			$woo_xs_items  = Ecome_Functions::ecome_get_option( $prefix . '_xs_items', 1 );
			$woo_ts_items  = Ecome_Functions::ecome_get_option( $prefix . '_ts_items', 1 );
			$atts          = array(
				'owl_loop'         => 'false',
				'owl_slide_margin' => 40,
				'owl_dots'         => 'true',
				'owl_ts_items'     => $woo_ts_items,
				'owl_xs_items'     => $woo_xs_items,
				'owl_sm_items'     => $woo_sm_items,
				'owl_md_items'     => $woo_md_items,
				'owl_lg_items'     => $woo_lg_items,
				'owl_ls_items'     => $woo_ls_items,
			);
			$owl_settings  = apply_filters( 'ecome_carousel_data_attributes', 'owl_', $atts );
			// We can still render if display is forced.
			$cat_args           = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => get_queried_object_id(),
			);
			$product_categories = get_terms( $cat_args );
			if ( $banner_cat ) {
				$banner_thumb  = apply_filters( 'ecome_resize_image', $banner_cat, false, false, true, true );
				$category_html .= '<div class="product-grid col-sm-12"><a href="' . esc_url( $banner_url ) . '"><figure class="banner-cat">' . wp_specialchars_decode( $banner_thumb['img'] ) . '</figure></a></div>';
			}
			if ( ! is_wp_error( $product_categories ) && ! empty( $product_categories ) ) {
				$category_html .= '<div class="product-grid col-sm-12"><div class="owl-slick" ' . esc_attr( $owl_settings ) . '>';
				foreach ( $product_categories as $category ) {
					$cat_link      = get_term_link( $category->term_id, 'product_cat' );
					$thumbnail_id  = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$cat_thumb     = apply_filters( 'ecome_resize_image', $thumbnail_id, 300, 300, true, true );
					$category_html .= '<div><a href="' . esc_url( $cat_link ) . '"><figure>' . wp_specialchars_decode( $cat_thumb['img'] ) . '</figure><span class="name">' . esc_html( $category->name ) . '</span></a></div>';
				}
				$category_html .= '</div></div>';
			}
			?>
            <div class="categories-product-woo row">
				<?php echo wp_specialchars_decode( $category_html ); ?>
                <div class="product-grid col-sm-12">
                    <div class="block-title">
                        <h2 class="product-grid-title">
                            <span><?php echo esc_html__( 'Bestseller Products', 'ecome' ); ?></span>
                        </h2>
                        <a href="<?php echo get_permalink( get_option( 'woocommerce_shop_page_id' ) ); ?>">
							<?php echo esc_html__( 'Shop more', 'ecome' ); ?>
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
					<?php echo do_shortcode( '[ecome_products product_style="1" product_image_size="300x300" productsliststyle="owl" target="best-selling" per_page="6" owl_dots="true" owl_slide_margin="40" owl_ls_items="' . $woo_ls_items . '" owl_lg_items="' . $woo_lg_items . '" owl_md_items="' . $woo_md_items . '" owl_sm_items="' . $woo_sm_items . '" owl_xs_items="' . $woo_xs_items . '" owl_ts_items="' . $woo_ts_items . '" ecome_custom_id=""]' ); ?>
                </div>
            </div>
			<?php
		}
	}
}
if ( ! function_exists( 'ecome_carousel_products' ) ) {
	function ecome_carousel_products( $prefix, $data_args ) {
		$enable_product = Ecome_Functions::ecome_get_option( $prefix . '_enable', 'enable' );
		if ( $enable_product == 'disable' && empty( $data_args ) ) {
			return;
		}
		$classes                 = array( 'product-item' );
		$ecome_woo_product_style = apply_filters( 'ecome_single_product_style', 1 );
		$classes[]               = 'style-' . $ecome_woo_product_style;
		$classes[]               = apply_filters( 'ecome_single_product_class', '' );
		$template_style          = 'style-' . $ecome_woo_product_style;
		$woo_ls_items            = Ecome_Functions::ecome_get_option( $prefix . '_ls_items', 3 );
		$woo_lg_items            = Ecome_Functions::ecome_get_option( $prefix . '_lg_items', 3 );
		$woo_md_items            = Ecome_Functions::ecome_get_option( $prefix . '_md_items', 3 );
		$woo_sm_items            = Ecome_Functions::ecome_get_option( $prefix . '_sm_items', 2 );
		$woo_xs_items            = Ecome_Functions::ecome_get_option( $prefix . '_xs_items', 1 );
		$woo_ts_items            = Ecome_Functions::ecome_get_option( $prefix . '_ts_items', 1 );
		$atts                    = array(
			'owl_dots'     => 'true',
			'owl_loop'     => 'false',
			'owl_ts_items' => $woo_ts_items,
			'owl_xs_items' => $woo_xs_items,
			'owl_sm_items' => $woo_sm_items,
			'owl_md_items' => $woo_md_items,
			'owl_lg_items' => $woo_lg_items,
			'owl_ls_items' => $woo_ls_items,
		);
		$atts                    = apply_filters( 'ecome_carousel_related_single_product', $atts );
		$owl_settings            = apply_filters( 'ecome_carousel_data_attributes', 'owl_', $atts );
		if ( $data_args ) : ?>
            <div class="col-sm-12 col-xs-12 products product-grid <?php echo esc_attr( $prefix ); ?>-product">
				<?php do_action( 'ecome_before_related_single_product', $prefix ); ?>
                <div class="owl-slick owl-products equal-container better-height" <?php echo esc_attr( $owl_settings ); ?>>
					<?php foreach ( $data_args as $value ) : ?>
                        <div <?php post_class( $classes ) ?>>
							<?php
							$post_object = get_post( $value->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object );
							wc_get_template_part( 'product-styles/content-product', $template_style );
							?>
                        </div>
					<?php endforeach; ?>
                </div>
				<?php do_action( 'ecome_after_related_single_product', $prefix ); ?>
            </div>
		<?php endif;
		wp_reset_postdata();
	}
}
if ( ! function_exists( 'ecome_cross_sell_products' ) ) {
	function ecome_cross_sell_products( $limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc' ) {
		if ( is_checkout() ) {
			return;
		}
		$cross_sells                 = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
		$woocommerce_loop['name']    = 'cross-sells';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );
		// Handle orderby and limit results.
		$orderby     = apply_filters( 'woocommerce_cross_sells_orderby', $orderby );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$limit       = apply_filters( 'woocommerce_cross_sells_total', $limit );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;
		ecome_carousel_products( 'ecome_woo_crosssell', $cross_sells );
	}
}
if ( ! function_exists( 'ecome_related_products' ) ) {
	function ecome_related_products() {
		global $product;
		$related_products = array();
		if ( $product ) {
			$defaults                    = array(
				'posts_per_page' => 6,
				'columns'        => 6,
				'orderby'        => 'rand',
				'order'          => 'desc',
			);
			$args                        = wp_parse_args( $defaults );
			$args['related_products']    = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
			$args['related_products']    = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );
			$woocommerce_loop['name']    = 'related';
			$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $args['columns'] );
			$related_products            = $args['related_products'];
		}
		
		if ( ! is_product() ) {
			$related_products = array();
		}
		ecome_carousel_products( 'ecome_woo_related', $related_products );
	}
}
if ( ! function_exists( 'ecome_upsell_display' ) ) {
	function ecome_upsell_display( $orderby = 'rand', $order = 'desc', $limit = '-1', $columns = 4 ) {
		global $product;
		$upsells = array();
		if ( $product ) {
			$args                        = array( 'posts_per_page' => 4, 'orderby' => 'rand', 'columns' => 4, );
			$woocommerce_loop['name']    = 'up-sells';
			$woocommerce_loop['columns'] = apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns );
			$orderby                     = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
			$limit                       = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );
			// Get visible upsells then sort them at random, then limit result set.
			$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
			$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;
		}
		
		if ( ! is_product() ) {
			$upsells = array();
		}
		ecome_carousel_products( 'ecome_woo_upsell', $upsells );
	}
}
if ( ! function_exists( 'ecome_template_loop_product_title' ) ) {
	function ecome_template_loop_product_title() {
		$title_class = array( 'product-name product_title' );
		?>
        <h3 class="<?php echo esc_attr( implode( ' ', $title_class ) ); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
		<?php
	}
}
if ( ! function_exists( 'ecome_template_loop_product_thumbnail' ) ) {
	function ecome_template_loop_product_thumbnail() {
		global $product;
		// GET SIZE IMAGE SETTING
		$width  = 300;
		$height = 300;
		$crop   = true;
		$size   = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$width  = $size['width'];
			$height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		$data_src                = '';
		$attachment_ids          = $product->get_gallery_image_ids();
		$gallery_class_img       = $class_img = array( 'img-responsive' );
		$thumb_gallery_class_img = $thumb_class_img = array( 'thumb-link' );
		$width                   = apply_filters( 'ecome_shop_pruduct_thumb_width', $width );
		$height                  = apply_filters( 'ecome_shop_pruduct_thumb_height', $height );
		$image_thumb             = apply_filters( 'ecome_resize_image', get_post_thumbnail_id( $product->get_id() ), $width, $height, $crop, true );
		$image_url               = $image_thumb['url'];
		$lazy_options            = Ecome_Functions::ecome_get_option( 'ecome_theme_lazy_load' );
		$default_attributes      = $product->get_default_attributes();
		if ( $lazy_options == 1 && empty( $default_attributes ) ) {
			$class_img[] = 'lazy';
			$data_src    = 'data-src=' . esc_attr( $image_thumb['url'] );
			$image_url   = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $width . "%20" . $height . "%27%2F%3E";
		}
		if ( $attachment_ids && has_post_thumbnail() ) {
			$gallery_class_img[]       = 'wp-post-image';
			$thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
		} else {
			$class_img[]       = 'wp-post-image';
			$thumb_class_img[] = 'woocommerce-product-gallery__image';
		}
		?>
        <a class="<?php echo implode( ' ', $thumb_class_img ); ?>" href="<?php the_permalink(); ?>">
            <img class="<?php echo implode( ' ', $class_img ); ?>" src="<?php echo esc_attr( $image_url ); ?>"
				<?php echo esc_attr( $data_src ); ?> <?php echo image_hwstring( $width, $height ); ?>
                 alt="<?php echo esc_attr( get_the_title() ); ?>">
        </a>
		<?php
		if ( $attachment_ids && has_post_thumbnail() ) :
			$gallery_data_src = '';
			$gallery_thumb       = apply_filters( 'ecome_resize_image', $attachment_ids[0], $width, $height, $crop, true );
			$gallery_image_url   = $gallery_thumb['url'];
			if ( $lazy_options == 1 && empty( $default_attributes ) ) {
				$gallery_class_img[] = 'lazy';
				$gallery_data_src    = 'data-src=' . esc_attr( $gallery_thumb['url'] );
				$gallery_image_url   = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $width . "%20" . $height . "%27%2F%3E";
			}
			?>
            <div class="second-image">
                <a href="<?php the_permalink(); ?>" class="<?php echo implode( ' ', $thumb_gallery_class_img ); ?>">
                    <img class="<?php echo implode( ' ', $gallery_class_img ); ?>"
                         src="<?php echo esc_attr( $gallery_image_url ); ?>"
						<?php echo esc_attr( $gallery_data_src ); ?> <?php echo image_hwstring( $width, $height ); ?>
                         alt="<?php echo esc_attr( get_the_title() ); ?>">
                </a>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'ecome_custom_new_flash' ) ) {
	function ecome_custom_new_flash() {
		global $post, $product;
		$postdate      = get_the_time( 'Y-m-d' );
		$postdatestamp = strtotime( $postdate );
		$newness       = Ecome_Functions::ecome_get_option( 'ecome_product_newness', 7 );
		if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) :
			echo apply_filters( 'woocommerce_new_flash', '<span class="onnew"><span class="text">' . esc_html__( 'New', 'ecome' ) . '</span></span>', $post, $product );
		else:
			echo apply_filters( 'woocommerce_new_flash', '<span class="onnew hidden"></span>', $post, $product );
		endif;
	}
}
if ( ! function_exists( 'ecome_woocommerce_group_flash' ) ) {
	function ecome_woocommerce_group_flash() {
		?>
        <div class="flash">
			<?php do_action( 'ecome_group_flash_content' ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_custom_sale_flash' ) ) {
	function ecome_custom_sale_flash( $text ) {
		$percent = ecome_get_percent_discount();
		if ( $percent != '' ) {
			return '<span class="onsale"><span class="text">' . $percent . '</span></span>';
		}
		
		return '';
	}
}
if ( ! function_exists( 'ecome_get_percent_discount' ) ) {
	function ecome_get_percent_discount() {
		global $product;
		$percent = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;
				for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
					$variation_id      = $available_variations[ $i ]['variation_id'];
					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 0 );
					}
					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}
					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$percent .= '-' . $minimumper . '%';
				} else {
					$percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
				}
			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = round( ( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ), 0 );
					$percent    .= '-' . $percentage . '%';
				}
			}
		}
		
		return $percent;
	}
}
if ( ! function_exists( 'ecome_function_shop_loop_item_countdown' ) ) {
	function ecome_function_shop_loop_item_countdown() {
		global $product;
		$date = ecome_get_max_date_sale( $product->get_id() );
		if ( $date > 0 ) {
			?>
            <div class="countdown-product">
                <h5 class="title"><?php echo esc_html__( 'Deal ends in :', 'ecome' ); ?></h5>
                <div class="ecome-countdown"
                     data-datetime="<?php echo date( 'm/j/Y g:i:s', $date ); ?>">
                </div>
            </div>
			<?php
		}
	}
}
if ( ! function_exists( 'ecome_template_single_available' ) ) {
	function ecome_template_single_available() {
		global $product;
		if ( $product->is_in_stock() ) {
			$class = 'in-stock available-product';
			$text  = $product->get_stock_quantity() . ' In Stock';
		} else {
			$class = 'out-stock available-product';
			$text  = 'Out stock';
		}
		?>

        <p class="stock <?php echo esc_attr( $class ); ?>">
			<?php echo esc_html__( 'Availability:', 'ecome' ); ?>
            <span> <?php echo esc_html( $text ); ?></span>
        </p>
		<?php
	}
}
if ( ! function_exists( 'ecome_function_shop_loop_process_variable' ) ) {
	function ecome_function_shop_loop_process_variable() {
		global $product;
		$units_sold   = get_post_meta( $product->get_id(), 'total_sales', true );
		$availability = $product->get_stock_quantity();
		if ( $availability == '' ) {
			$percent = 0;
		} else {
			$total_percent = $availability + $units_sold;
			$percent       = round( ( ( $units_sold / $total_percent ) * 100 ), 0 );
		}
		?>
        <div class="process-valiable">
            <div class="valiable-text">
                <span class="text">
                    <?php
                    echo esc_attr( $percent ) . '%';
                    echo esc_html__( ' already claimed', 'ecome' );
                    ?>
                </span>
                <span class="text">
                    <?php echo esc_html__( 'Available: ', 'ecome' ) ?>
                    <span>
                        <?php
                        if ( $availability != '' ) {
	                        echo esc_html( $availability );
                        } else {
	                        echo esc_html__( 'Unlimit', 'ecome' );
                        }
                        ?>
                    </span>
                </span>
            </div>
            <span class="valiable-total total">
                <span class="process"
                      style="width: <?php echo esc_attr( $percent ) . '%' ?>"></span>
            </span>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_get_max_date_sale' ) ) {
	function ecome_get_max_date_sale( $product_id ) {
		$date_now = current_time( 'timestamp', 0 );
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => - 1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		if ( ! empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ") ORDER BY meta_value DESC LIMIT 1" );
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		if ( ! $sale_price_dates_to ) {
			$sale_price_dates_to   = get_post_meta( $product_id, '_sale_price_dates_to', true );
			$sale_price_dates_from = get_post_meta( $product_id, '_sale_price_dates_from', true );
			if ( $sale_price_dates_to == '' || $date_now < $sale_price_dates_from ) {
				$sale_price_dates_to = '0';
			}
		}
		
		return $sale_price_dates_to;
	}
}
/* MINI CART */
if ( ! function_exists( 'ecome_header_cart_link' ) ) {
	function ecome_header_cart_link() {
		?>
        <div class="shopcart-dropdown block-cart-link" data-ecome="ecome-dropdown">
            <a class="link-dropdown" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-online-shopping-cart">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_header_mini_cart' ) ) {
	function ecome_header_mini_cart() {
		?>
        <div class="block-minicart ecome-mini-cart ecome-dropdown">
			<?php
			ecome_header_cart_link();
			the_widget( 'WC_Widget_Cart', 'title=' );
			?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ecome_cart_link_fragment' ) ) {
	function ecome_cart_link_fragment( $fragments ) {
		ob_start();
		ecome_header_cart_link();
		$fragments['div.block-cart-link'] = ob_get_clean();
		
		return $fragments;
	}
}
if ( ! function_exists( 'ecome_header_wishlist' ) ) {
	function ecome_header_wishlist() {
		if ( defined( 'YITH_WCWL' ) ) :
			$yith_wcwl_wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
			$wishlist_url = get_page_link( $yith_wcwl_wishlist_page_id );
			if ( $wishlist_url != '' ) : ?>
                <div class="block-wishlist">
                    <a class="woo-wishlist-link" href="<?php echo esc_url( $wishlist_url ); ?>">
                        <span class="flaticon-heart-shape-outline"></span>
                    </a>
                </div>
			<?php endif;
		endif;
	}
}