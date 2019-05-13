<?php
if ( ! class_exists( 'Ecome_PluginLoad' ) ) {
	class Ecome_PluginLoad {
		public $plugins = array();
		public $config  = array();
		
		public function __construct() {
			$this->plugins();
			$this->config();
			if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
				return;
			}
			if ( function_exists( 'tgmpa' ) ) {
				tgmpa( $this->plugins, $this->config );
			}
		}
		
		public function plugins() {
			$this->plugins = array(
				array(
					'name'               => 'Ecome Toolkit',
					'slug'               => 'ecome-toolkit',
					'source'             => get_template_directory() . '/framework/plugins/ecome-toolkit.zip',
					'version'            => '1.0.3',
					'required'           => true,
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'Fami Sales Popup',
					'slug'               => 'fami-sales-popup',
					'source'             => get_template_directory() . '/framework/plugins/fami-sales-popup.zip',
					'version'            => '1.0.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'Fami Buy Together',
					'slug'               => 'fami-buy-together',
					'source'             => get_template_directory() . '/framework/plugins/fami-buy-together.zip',
					'version'            => '1.0.2',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'Revolution Slider',
					'slug'               => 'revslider',
					'source'             => get_template_directory() . '/framework/plugins/revslider.zip',
					'required'           => true,
					'version'            => '',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'WPBakery Visual Composer',
					'slug'               => 'js_composer',
					'source'             => get_template_directory() . '/framework/plugins/js_composer.zip',
					'required'           => true,
					'version'            => '',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'     => 'WooCommerce',
					'slug'     => 'woocommerce',
					'required' => true,
					'image'    => '',
				),
				array(
					'name'     => 'YITH WooCommerce Compare',
					'slug'     => 'yith-woocommerce-compare',
					'required' => false,
					'image'    => '',
				),
				array(
					'name'     => 'YITH WooCommerce Wishlist',
					'slug'     => 'yith-woocommerce-wishlist',
					'required' => false,
					'image'    => '',
				),
				array(
					'name'     => 'YITH WooCommerce Quick View',
					'slug'     => 'yith-woocommerce-quick-view',
					'required' => false,
					'image'    => '',
				),
				array(
					'name'     => 'Contact Form 7',
					'slug'     => 'contact-form-7',
					'required' => false,
					'image'    => '',
				),
			);
		}
		
		public function config() {
			$this->config = array(
				'id'           => 'ecome',
				'default_path' => '',
				'menu'         => 'ecome-install-plugins',
				'parent_slug'  => 'themes.php',
				'capability'   => 'edit_theme_options',
				'has_notices'  => true,
				'dismissable'  => true,
				'dismiss_msg'  => '',
				'is_automatic' => true,
				'message'      => '',
			);
		}
	}
}
if ( ! function_exists( 'Ecome_PluginLoad' ) ) {
	function Ecome_PluginLoad() {
		new  Ecome_PluginLoad();
	}
}
add_action( 'tgmpa_register', 'Ecome_PluginLoad' );