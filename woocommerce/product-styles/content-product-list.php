<?php
/**
 * Name: Product list
 * Slug: content-product-list
 **/
?>
<div class="product-inner images equal-elem">
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @removed woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
    <div class="product-thumb">
		<?php
		/**
		 * woocommerce_before_shop_loop_item_title hook.
		 *
		 * @hooked ecome_woocommerce_group_flash - 10
		 * @hooked ecome_template_loop_product_thumbnail - 10
		 * @hooked ecome_action_attributes - 20
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );
		do_action( 'ecome_function_shop_loop_item_quickview' );
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
		/**
		 * woocommerce_after_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
        <div class="group-button">
            <div class="add-to-cart">
				<?php
				/**
				 * woocommerce_after_shop_loop_item hook.
				 *
				 * @removed woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
            </div>
			<?php
			do_action( 'ecome_function_shop_loop_item_wishlist' );
			do_action( 'ecome_function_shop_loop_item_compare' );
			?>
        </div>
    </div>
    <?php do_action( 'ecome_show_attributes' ); ?>
</div>