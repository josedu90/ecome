<?php
/**
 * Name: Product style 5
 * Slug: content-product-style-5
 **/
?>
<?php
remove_action( 'woocommerce_shop_loop_item_title', 'ecome_add_categories_product', 20 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'ecome_action_attributes', 20 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'ecome_woocommerce_group_flash', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 );
?>
    <div class="product-inner equal-elem">
        <div class="product-thumb">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked ecome_woocommerce_group_flash - 10
			 * @hooked ecome_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
        </div>
        <div class="product-info">
			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked ecome_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
        </div>
    </div>
<?php
add_action( 'woocommerce_shop_loop_item_title', 'ecome_add_categories_product', 20 );
add_action( 'woocommerce_before_shop_loop_item_title', 'ecome_action_attributes', 20 );
add_action( 'woocommerce_before_shop_loop_item_title', 'ecome_woocommerce_group_flash', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 );