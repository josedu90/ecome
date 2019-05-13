<?php
if ( !function_exists( 'ecome_custom_inline_css' ) ) {
	function ecome_custom_inline_css()
	{
		$css     = ecome_theme_color();
		$css     .= ecome_vc_custom_css_footer();
		$content = preg_replace( '/\s+/', ' ', $css );
		wp_add_inline_style( 'ecome-style', $content );
	}
}
add_action( 'wp_enqueue_scripts', 'ecome_custom_inline_css', 999 );
if ( !function_exists( 'ecome_theme_color' ) ) {
	function ecome_theme_color()
	{
		$main_color              = Ecome_Functions::ecome_get_option( 'ecome_main_color', '#e5534c' );
		$gradient_color_1        = Ecome_Functions::ecome_get_option( 'ecome_gradient_color_1', '#c467f5' );
        $gradient_color_1        = str_replace('#','',$gradient_color_1);
        $gradient_color_1        = '#'.$gradient_color_1;
		$gradient_color_2        = Ecome_Functions::ecome_get_option( 'ecome_gradient_color_2', '#53f3ff' );
        $gradient_color_2        = str_replace('#','',$gradient_color_2);
        $gradient_color_2        = '#'.$gradient_color_2;
		$ecome_page_404          = Ecome_Functions::ecome_get_option( 'ecome_page_404' );
		$page_faqs_product       = Ecome_Functions::ecome_get_option( 'ecome_add_page_product' );
		$ecome_enable_typography = Ecome_Functions::ecome_get_option( 'ecome_enable_typography' );
		$ecome_typography_group  = Ecome_Functions::ecome_get_option( 'typography_group' );
		$css                     = '';
		if ( $ecome_enable_typography == 1 && !empty( $ecome_typography_group ) ) {
			foreach ( $ecome_typography_group as $item ) {
				$css .= '
				    .phone-header .phone-number p:last-child,
                    .product_list_widget li .quantity,
                    .woocommerce-mini-cart__total,
                    .woocommerce-mini-cart__buttons .button,
                    .box-header-nav .main-menu > .menu-item > a,
                    .block-nav-category .block-title .text-title,
                    .block-nav-category .view-all-category a,
                    .box-header-menu .gradient-menu .menu-item a,
                    .footer-hotline h3,
                    .footer-hotline h6,
                    .post-thumb .quote,
                    .post-title,
                    .post-single-author .author-info .name,
                    .post-single-author .author-info a,
                    .comments-area .comments-title span,
                    .comment-text .comment-author,
                    .comment-respond .comment-reply-title,
                    body.tax-product_cat .product-grid.col-sm-12 .slick-slide > a .name,
                    .ecome-products.style-5 .ecome-title,
                    .onnew, .onsale,
                    .product-name,
                    .countdown-product .title,
                    .countdown-product .ecome-countdown .number,
                    .countdown-product .ecome-countdown .text,
                    .product-item.style-3 .price,
                    .product-item.style-6 h3.title,
                    .product-item.style-6 .price,
                    .product-item.style-7 h3.title,
                    .product-item.style-7 .price,
                    .product-item.style-8 .price,
                    .product-item.style-9 .deal-title,
                    .product-item.style-9 .price,
                    .product-360-button a,
                    .product-video-button a,
                    .entry-summary .in-stock span,
                    .entry-summary .out-of-stock,
                    div.quantity .input-qty,
                    .product-type-woosb .entry-summary .cart,
                    .product-type-variable .entry-summary .woocommerce-variation-add-to-cart,
                    .product-type-simple .entry-summary .cart,
                    .product-type-external .entry-summary .cart .single_add_to_cart_button,
                    .product-type-grouped .entry-summary .cart .single_add_to_cart_button,
                    .wc-tabs li a,
                    .woocommerce-Tabs-panel .show-all,
                    .product-grid .product-grid-title,
                    .product-grid .block-title a,
                    #shipping_method input[type="radio"]:checked + label,
                    body.woocommerce-cart .cart-empty,
                    body.woocommerce-cart .return-to-shop a,
                    .shop_table .product-name a:not(.button),
                    .woocommerce-cart-form .shop_table .actions button.button,
                    .cart_totals > h2,
                    .cart_totals .shop_table tr th,
                    .cart_totals .shop_table tr.order-total td,
                    .shipping-calculator-form .button,
                    .wc-proceed-to-checkout .checkout-button,
                    .woocommerce-form__label-for-checkbox .woocommerce-form__input-checkbox:checked + span,
                    #payment .input-radio:checked + label,
                    body.woocommerce-checkout .woocommerce > .woocommerce-info a,
                    .checkout_coupon .button,
                    .woocommerce-billing-fields > h3,
                    .form-row > label,
                    .woocommerce-shipping-fields #ship-to-different-address,
                    #order_review_heading,
                    #order_review .shop_table tr th,
                    #order_review .shop_table thead tr th,
                    #order_review .shop_table tfoot .order-total td,
                    #place_order,
                    #customer_login > div > h2,
                    form.woocommerce-form-login .button,
                    form.register .button,
                    .woocommerce-Address .woocommerce-Address-title > h3,
                    .wishlist-title h2,
                    .woocommerce table.wishlist_table tbody tr td.wishlist-empty,
                    .woocommerce table.wishlist_table tr td.product-stock-status span,
                    .woocommerce table.wishlist_table td.product-add-to-cart a,
                    section.error-404 > a.button,
                    .ecome-faqs .ecome-title,
                    .ecome-faqs .question p,
                    .ecome-faqs .question .icon,
                    .ecome-faqs .answer .icon,
                    .loadmore-faqs a,
                    .page-title,
                    .woocommerce-products-header .page-title,
                    body.woocommerce-cart .page-title,
                    #widget-area .widgettitle,
                    .widget_product_categories .cat-item a,
                    .woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit,
                    .widget_price_filter .button,
                    .ecome-tabs.default .tab-link li.active a,
                    .ecome-tabs.style1 .tab-link li a span,
                    .ecome-tabs.style2 .ecome-title,
                    .ecome-tabs.style2 .tab-link li.active a,
                    .ecome-tabs.style3 .tab-link li.active a,
                    .ecome-tabs.style3 .view-all,
                    .ecome-iconbox.default .iconbox-inner .title,
                    .ecome-iconbox.style1 .iconbox-inner .title,
                    .ecome-iconbox.style3 .iconbox-inner .title,
                    .ecome-iconbox.style4 .iconbox-inner .text,
                    .ecome-iconbox.style5 .iconbox-inner .title,
                    .ecome-iconbox.style6 .iconbox-inner .title,
                    .ecome-iconbox.style7 .iconbox-inner .title,
                    .ecome-heading.default .ecome-title,
                    .ecome-heading.style1 .ecome-title,
                    .ecome-heading.style2 .ecome-title,
                    .ecome-heading.style2 .view-all,
                    .ecome-heading.style3 .ecome-title,
                    .ecome-heading.style3 .view-all,
                    .ecome-heading.style4 .ecome-title,
                    .ecome-heading.style4 .view-all,
                    .ecome-heading.style6 .ecome-title,
                    .ecome-custommenu .widgettitle,
                    .ecome-socials .widgettitle,
                    .ecome-newsletter .widgettitle,
                    .newsletter-form-wrap .submit-newsletter,
                    .ecome-member .member-info h4,
                    .ecome-category .cat-name,
                    .ecome-category .button,
                    #popup-newsletter .newsletter-form-wrap .submit-newsletter,
                    .vc_btn3.vc_btn3-size-lg,
                    .custom-heading-menu,
                    .title-lt,
                    .title-bd,
                    .separation-text { 
                        font-family: ' . $item['ecome_typography_font_family']['family'] . ';
                    }
					' . $item['ecome_element_tag'] . '{
						font-family: ' . $item['ecome_typography_font_family']['family'] . ';
						font-weight: ' . $item['ecome_typography_font_family']['variant'] . ';
						font-size: ' . $item['ecome_typography_font_size'] . 'px;
						line-height: ' . $item['ecome_typography_line_height'] . 'px;
						color: ' . $item['ecome_body_text_color'] . ';
					}
				';
			}
		}
		$css .= '
			a:hover, a:focus, a:active,
			.header-top-inner .top-bar-menu > .menu-item:hover > a > span,
			.header-top-inner .top-bar-menu > .menu-item:hover > a,
			.wcml-dropdown .wcml-cs-submenu li:hover > a,
			.box-header-nav .main-menu .menu-item .submenu .menu-item:hover > a,
			.box-header-nav .main-menu .menu-item:hover > .toggle-submenu,
			.box-header-nav .main-menu > .menu-item.menu-item-right:hover > a,
			.box-header-menu .gradient-menu .menu-item:hover > a,
			.shop_table .product-name a:not(.button):hover,
			.woocommerce-MyAccount-navigation > ul li.is-active a,
			.box-header-nav .main-menu .menu-item:hover > a {
				 color: ' . $main_color . ';
			}
			blockquote, q,
			.list-attribute li:not(.photo) a:hover::before {
				border-left-color:' . $main_color . ';
			}
			.product-item.style-5 .product-thumb:hover {
                border-color:' . $main_color . ';
            }
			.block-menu-bar .menu-bar:hover span {
				background-color: ' . $main_color . ';
			}
			.ecome-live-search-form.loading .search-box::before {
				border-top-color: ' . $main_color . ';
			}
			
			.woocommerce-product-gallery .flex-control-nav.flex-control-thumbs li img.flex-active,
			.product-gallery .gallery-dots .slick-slide.slick-current img {
				border-color: #e5534c;
			}
			.ecome-share-socials a:hover,
			.product-360-button a:hover,
			.product-video-button a:hover,
			.product-grid .block-title a:hover,
			.ecome-heading.style2 .view-all:hover,
			.ecome-heading.style3 .view-all:hover,
			.ecome-heading.style4 .view-all:hover {
				color: ' . $main_color . ';
				border-color: ' . $main_color . ';
			}
			.ecome-products.style-9 .product-inner::before,
			.header.style5 .block-nav-category .block-title::before,
			button:not(.pswp__button):hover,
			input[type="submit"]:hover,
			button:not(.pswp__button):focus,
			input[type="submit"]:focus,
			.ecome-live-search-form .keyword-current,
			.woocommerce-mini-cart__buttons .button.checkout,
			.woocommerce-mini-cart__buttons .button:not(.checkout):hover,
			.block-nav-category .block-title,
			.box-header-menu,
			a.backtotop,
			.post-date,
			.post-title::after,
			.post-single-author .author-info a:hover,
			.comment-form .form-submit #submit,
			.ecome-products.style-7::before,
			#yith-quick-view-close:hover,
			.process-valiable .valiable-total .process,
			.product-type-woosb .entry-summary .cart,
			.product-type-variable .entry-summary .woocommerce-variation-add-to-cart,
			.product-type-simple .entry-summary .cart,
			.product-type-external .entry-summary .cart .single_add_to_cart_button,
			.product-type-grouped .entry-summary .cart .single_add_to_cart_button,
			body.woocommerce-cart .return-to-shop a:hover,
			.wc-proceed-to-checkout .checkout-button,
			.checkout_coupon .button,
			#place_order,
			form.woocommerce-form-login .button,
			form.register .button,
			.woocommerce-MyAccount-content fieldset ~ p .woocommerce-Button,
			.woocommerce table.wishlist_table td.product-add-to-cart a,
			section.error-404 > a.button,
			.ecome-faqs .ecome-title,
			.loadmore-faqs a:hover,
			.page-title.blog-title::before,
			#widget-area .widget .select2-container--default .select2-selection--multiple .select2-selection__choice,
			.woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit,
			.widget_price_filter .button,
			.widget_price_filter .ui-slider-range,
			.loadmore-product:hover span:first-child,
			.ecome-tabs.default .tab-link li:hover a,
			.ecome-tabs.default .tab-link li.active a,
			.ecome-tabs.style1 .tab-link li.active,
			.ecome-tabs.style2 .ecome-title,
			.ecome-tabs.style2 .tab-link li:hover a,
			.ecome-tabs.style2 .tab-link li.active a,
			.ecome-iconbox.style4::before,
			.ecome-heading.style4 .ecome-title,
			.ecome-heading.style5 .ecome-title::before,
			.ecome-heading.style6 .ecome-title::before,
			.box-header-nav .ecome-custommenu .widgettitle::before,
			.block-nav-category .ecome-custommenu .widgettitle::before,
			.ecome-socials .content-socials .socials-list li a:hover span,
			.newsletter-form-wrap .submit-newsletter,
			.ecome-member .member-info .positions::before,
			.widget-ecome-socials .socials-list li a:hover,
			.ecome-category .button,
			#popup-newsletter .newsletter-form-wrap .submit-newsletter:hover,
			.background-main-gradient,
			.ecome-products.style-8 .product-inner::before {
				background: linear-gradient(90deg, ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');
			}
			.chosen-results > .scroll-element .scroll-bar:hover {
                background: linear-gradient(180deg, ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');
            }
			.ecome-tabs.style1 .tab-link li.active::before {
				border-color: transparent transparent transparent ' . $gradient_color_2 . ';
			}
			.total-price-html {
			    color: ' . $main_color . ';
			}

			div.famibt-wrap .famibt-item .famibt-price {
			    color: ' . $main_color . ';
			}

			.famibt-wrap ins {
			    color: ' . $main_color . ';
			}
			.famibt-messages-wrap a.button.wc-forward:hover {
			    background: ' . $main_color . ';
			}
			.sticky_info_single_product button.ecome-single-add-to-cart-btn.btn.button {
				background: ' . $main_color . ';
			}
		';
		/* GET CUSTOM 404 */
		if ( $ecome_page_404 )
			$css .= get_post_meta( $ecome_page_404, '_Ecome_Shortcode_custom_css', true );
		/* GET CUSTOM FAQS */
		if ( class_exists( 'WooCommerce' ) && $page_faqs_product && is_product() )
			$css .= get_post_meta( $page_faqs_product, '_Ecome_Shortcode_custom_css', true );
		/* Main color */
		$css .= '';

		return apply_filters( 'ecome_main_custom_css', $css );
	}
}
if ( !function_exists( 'ecome_vc_custom_css_footer' ) ) {
	function ecome_vc_custom_css_footer()
	{
		$data_meta           = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$footer_options      = Ecome_Functions::ecome_get_option( 'ecome_footer_options' );
		$enable_theme_option = Ecome_Functions::ecome_get_option( 'enable_theme_options' );
		$footer_options      = $enable_theme_option == 1 && isset( $data_meta['metabox_ecome_footer_options'] ) && $data_meta['metabox_ecome_footer_options'] != '' ? $data_meta['metabox_ecome_footer_options'] : $footer_options;
		if ( !$footer_options ) {
			$query = new WP_Query( array( 'p' => $footer_options, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
			while ( $query->have_posts() ): $query->the_post();
				$footer_options = get_the_ID();
			endwhile;
		}
		$shortcodes_custom_css = get_post_meta( $footer_options, '_Ecome_Shortcode_custom_css', true );

		return $shortcodes_custom_css;
	}
}
if ( !function_exists( 'ecome_write_custom_js ' ) ) {
	function ecome_write_custom_js()
	{
		$ecome_custom_js = Ecome_Functions::ecome_get_option( 'ecome_custom_js', '' );
		$content         = preg_replace( '/\s+/', ' ', $ecome_custom_js );
		wp_add_inline_script( 'ecome-script', $content );
	}
}
add_action( 'wp_enqueue_scripts', 'ecome_write_custom_js' );