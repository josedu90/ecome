<?php
/**
 * Name:  Header style 04
 **/
?>
<?php
$ecome_phone = Ecome_Functions::ecome_get_option( 'header_phone' );
$ecome_text  = Ecome_Functions::ecome_get_option( 'header_text' );
?>
<header id="header" class="header style3 style4">
	<?php ecome_header_background(); ?>
	<?php if ( has_nav_menu( 'top_left_menu' ) || has_nav_menu( 'top_right_menu' ) ): ?>
        <div class="header-top">
            <div class="container">
                <div class="header-top-inner">
					<?php
					if ( has_nav_menu( 'top_left_menu' ) ) {
						wp_nav_menu( array(
								'menu'            => 'top_left_menu',
								'theme_location'  => 'top_left_menu',
								'depth'           => 1,
								'container'       => '',
								'container_class' => '',
								'container_id'    => '',
								'menu_class'      => 'ecome-nav top-bar-menu',
								'fallback_cb'     => 'Ecome_navwalker::fallback',
								'walker'          => new Ecome_navwalker(),
							)
						);
					}
					if ( has_nav_menu( 'top_right_menu' ) ) {
						wp_nav_menu( array(
								'menu'            => 'top_right_menu',
								'theme_location'  => 'top_right_menu',
								'depth'           => 1,
								'container'       => '',
								'container_class' => '',
								'container_id'    => '',
								'menu_class'      => 'ecome-nav top-bar-menu right',
								'fallback_cb'     => 'Ecome_navwalker::fallback',
								'walker'          => new Ecome_navwalker(),
							)
						);
					}
					?>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="logo-mobile">
                    <?php ecome_get_logo(); ?>
                </div>
				<?php ecome_search_form(); ?>
                <div class="logo">
                    <?php ecome_get_logo(); ?>
                </div>
                <div class="header-control">
                    <div class="header-control-inner">
						<?php if ( $ecome_phone ) : ?>
                            <div class="phone-header">
                                <span class="flaticon-people"></span>
                                <div class="phone-number">
                                    <p><?php echo esc_html( $ecome_text ); ?></p>
                                    <p><?php echo esc_html( $ecome_phone ); ?></p>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="meta-woo">
                            <div class="block-menu-bar">
                                <a class="menu-bar menu-toggle" href="#">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </a>
                            </div>
							<?php
							ecome_user_link();
							do_action( 'ecome_header_wishlist' );
							do_action( 'ecome_header_mini_cart' );
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-inner">
				<?php ecome_header_vertical(); ?>
                <div class="box-header-nav ecome-menu-wapper">
					<?php
					wp_nav_menu( array(
							'menu'            => 'primary',
							'theme_location'  => 'primary',
							'depth'           => 3,
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'clone-main-menu ecome-clone-mobile-menu ecome-nav main-menu',
							'fallback_cb'     => 'Ecome_navwalker::fallback',
							'walker'          => new Ecome_navwalker(),
						)
					);
					?>
                </div>
            </div>
        </div>
    </div>
</header>