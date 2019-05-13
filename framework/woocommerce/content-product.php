<?php
global $product;
// Ensure visibility
if ( ! empty( $product ) || $product->is_visible() ) {
	// Custom columns
	$ecome_woo_bg_items = Ecome_Functions::ecome_get_option( 'ecome_woo_bg_items', 4 );
	$ecome_woo_lg_items = Ecome_Functions::ecome_get_option( 'ecome_woo_lg_items', 4 );
	$ecome_woo_md_items = Ecome_Functions::ecome_get_option( 'ecome_woo_md_items', 4 );
	$ecome_woo_sm_items = Ecome_Functions::ecome_get_option( 'ecome_woo_sm_items', 6 );
	$ecome_woo_xs_items = Ecome_Functions::ecome_get_option( 'ecome_woo_xs_items', 6 );
	$ecome_woo_ts_items = Ecome_Functions::ecome_get_option( 'ecome_woo_ts_items', 12 );
	// $shop_display_mode  = Ecome_Functions::ecome_get_option( 'ecome_shop_list_style', 'grid' );
	$shop_display_mode = Ecome_Functions::ecome_get_option( 'shop_display_mode', 'grid' );
	$classes[]         = 'product-item';
	if ( $shop_display_mode == 'list' ) {
		$classes[] = 'list col-sm-12';
	} else {
		$classes[] = 'col-bg-' . $ecome_woo_bg_items;
		$classes[] = 'col-lg-' . $ecome_woo_lg_items;
		$classes[] = 'col-md-' . $ecome_woo_md_items;
		$classes[] = 'col-sm-' . $ecome_woo_sm_items;
		$classes[] = 'col-xs-' . $ecome_woo_xs_items;
		$classes[] = 'col-ts-' . $ecome_woo_ts_items;
	}
	if ( $shop_display_mode == 'grid' ) {
		$classes[] = 'style-1';
	} elseif ( $shop_display_mode == 'grid-v2' ) {
		$classes[] = 'style-2';
	}
	?>
    <li <?php post_class( $classes ); ?>>
		<?php if ( $shop_display_mode == 'list' ):
			get_template_part( 'woocommerce/product-styles/content-product', 'list' );
        elseif ( $shop_display_mode == 'grid-v2' ):
			get_template_part( 'woocommerce/product-styles/content-product', 'style-2' );
		else:
			get_template_part( 'woocommerce/product-styles/content-product', 'style-1' );
		endif; ?>
    </li>
	<?php
}