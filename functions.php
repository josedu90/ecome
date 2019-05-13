<?php
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}
if ( ! class_exists( 'Ecome_Functions' ) ) {
	class Ecome_Functions {
		/**
		 * @var Ecome_Functions The one true Ecome_Functions
		 * @since 1.0
		 */
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Ecome_Functions ) ) {
				self::$instance = new Ecome_Functions;
			}
			add_action( 'after_setup_theme', array( self::$instance, 'ecome_setup' ) );
			add_action( 'widgets_init', array( self::$instance, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'enqueue_scripts' ) );
			add_filter( 'get_default_comment_status', array(
				self::$instance,
				'open_default_comments_for_page'
			), 10, 3 );
			add_filter( 'comment_form_fields', array( self::$instance, 'ecome_move_comment_field_to_bottom' ), 10, 3 );
			
			self::includes();
			
			return self::$instance;
		}
		
		public function ecome_setup() {
			load_theme_textdomain( 'ecome', get_template_directory() . '/languages' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'custom-background' );
			add_theme_support( 'customize-selective-refresh-widgets' );
			/*This theme uses wp_nav_menu() in two locations.*/
			register_nav_menus( array(
				                    'primary'        => esc_html__( 'Primary Menu', 'ecome' ),
				                    'gradient_menu'  => esc_html__( 'Gradient Menu', 'ecome' ),
				                    'vertical_menu'  => esc_html__( 'Vertical Menu', 'ecome' ),
				                    'top_left_menu'  => esc_html__( 'Top Left Menu', 'ecome' ),
				                    'top_right_menu' => esc_html__( 'Top Right Menu', 'ecome' ),
			                    )
			);
			add_theme_support( 'html5', array(
				                          'search-form',
				                          'comment-form',
				                          'comment-list',
				                          'gallery',
				                          'caption',
			                          )
			);
			add_theme_support( 'post-formats',
			                   array(
				                   'image',
				                   'video',
				                   'quote',
				                   'link',
				                   'gallery',
				                   'audio',
			                   )
			);
			/*Support woocommerce*/
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		
		public function ecome_move_comment_field_to_bottom( $fields ) {
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;
			
			return $fields;
		}
		
		/**
		 * Register widget area.
		 *
		 * @since ecome 1.0
		 *
		 * @link  https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		function widgets_init() {
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Area', 'ecome' ),
				                  'id'            => 'widget-area',
				                  'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ecome' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Shop', 'ecome' ),
				                  'id'            => 'widget-shop',
				                  'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ecome' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Product', 'ecome' ),
				                  'id'            => 'widget-product',
				                  'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ecome' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Widget Summary Product', 'ecome' ),
				                  'id'            => 'widget-summary-product',
				                  'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ecome' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arrow"></span></h2>',
			                  )
			);
		}
		
		/**
		 * Register custom fonts.
		 */
		function ecome_fonts_url() {
			/**
			 * Translators: If there are characters in your language that are not
			 * supported by Montserrat, translate this to 'off'. Do not translate
			 * into your own language.
			 */
			$ecome_enable_typography = $this->ecome_get_option( 'ecome_enable_typography' );
			$ecome_typography_group  = $this->ecome_get_option( 'typography_group' );
			$settings                = get_option( 'wpb_js_google_fonts_subsets' );
			$font_families           = array();
			if ( $ecome_enable_typography == 1 && ! empty( $ecome_typography_group ) ) {
				foreach ( $ecome_typography_group as $item ) {
					$font_families[] = str_replace( ' ', '+', $item['ecome_typography_font_family']['family'] );
				}
			}
			$font_families[] = 'Rubik:300,300i,400,400i,500,500i,700,700i,900,900i';
			$font_families[] = 'Lato:300,300i,400,400i,700,700i,900,900i';
			$font_families[] = 'Roboto:300,300i,400,400i,500,500i,700,700i';
			$query_args      = array(
				'family' => urlencode( implode( '|', $font_families ) ),
			);
			if ( ! empty( $settings ) ) {
				$query_args['subset'] = implode( ',', $settings );
			}
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			
			return esc_url_raw( $fonts_url );
		}
		
		/**
		 * Enqueue scripts and styles.
		 *
		 * @since ecome 1.0
		 */
		function enqueue_scripts() {
			global $wp_query;
			$posts = $wp_query->posts;
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_style( 'yith-wcwl-font-awesome' );
			wp_dequeue_style( 'yith-quick-view' );
			foreach ( $posts as $post ) {
				if ( is_a( $post, 'WP_Post' ) && ! has_shortcode( $post->post_content, 'contact-form-7' ) ) {
					wp_dequeue_script( 'contact-form-7' );
				}
			}
			// Add custom fonts, used in the main stylesheet.
			wp_enqueue_style( 'ecome-fonts', self::ecome_fonts_url(), array(), null );
			/* Theme stylesheet. */
			wp_enqueue_style( 'animate-css' );
			wp_enqueue_style( 'flaticon', get_theme_file_uri( '/assets/fonts/flaticon/flaticon.css' ), array(), '1.0' );
			wp_enqueue_style( 'pe-icon-7-stroke', get_theme_file_uri( '/assets/css/pe-icon-7-stroke.css' ), array(), '1.0' );
			wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'growl', get_theme_file_uri( '/assets/css/jquery.growl.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.css' ), array(), '1.0' );
			wp_enqueue_style( 'slick', get_theme_file_uri( '/assets/css/slick.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'scrollbar', get_theme_file_uri( '/assets/css/jquery.scrollbar.css' ), array(), '1.0' );
			wp_enqueue_style( 'chosen', get_theme_file_uri( '/assets/css/chosen.min.css' ), array(), '1.0' );
			wp_enqueue_style( 'ecome-style', get_theme_file_uri( '/assets/css/style.css' ), array(), '1.0', 'all' );
			wp_enqueue_style( 'ecome-main-style', get_stylesheet_uri() );
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			/* SCRIPTS */
			$ecome_gmap_api_key = $this->ecome_get_option( 'gmap_api_key' );
			if ( $ecome_gmap_api_key ) {
				$ecome_gmap_api_key = '//maps.googleapis.com/maps/api/js?key=' . trim( $ecome_gmap_api_key );
			} else {
				$ecome_gmap_api_key = '//maps.google.com/maps/api/js?sensor=true';
			}
			if ( ! is_admin() ) {
				wp_dequeue_style( 'woocommerce_admin_styles' );
			}
			wp_enqueue_script( 'api-map', esc_url( $ecome_gmap_api_key ), array(), false );
			wp_enqueue_script( 'chosen', get_theme_file_uri( '/assets/js/libs/chosen.min.js' ), array(), '1.0', true );
			wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/libs/bootstrap.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'threesixty', get_theme_file_uri( '/assets/js/libs/threesixty.min.js' ), array(), '1.0.7', true );
			wp_enqueue_script( 'growl', get_theme_file_uri( '/assets/js/libs/jquery.growl.min.js' ), array(), '1.0.0', true );
			wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/libs/magnific-popup.min.js' ), array(), '1.1.0', true );
			wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/libs/slick.min.js' ), array(), '3.3.7', true );
			wp_enqueue_script( 'scrollbar', get_theme_file_uri( '/assets/js/libs/jquery.scrollbar.min.js' ), array(), '1.0.0', true );
			/* http://hilios.github.io/jQuery.countdown/documentation.html */
			wp_enqueue_script( 'countdown', get_theme_file_uri( '/assets/js/libs/countdown.min.js' ), array(), '1.0.0', true );
			/* http://jquery.eisbehr.de/lazy */
			wp_enqueue_script( 'lazy-load', get_theme_file_uri( '/assets/js/libs/lazyload.min.js' ), array(), '1.7.9', true );
			wp_enqueue_script( 'ecome-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0', true );
			wp_localize_script( 'ecome-script', 'ecome_ajax_frontend', array(
				                                  'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
				                                  'security'                        => wp_create_nonce( 'ecome_ajax_frontend' ),
				                                  'added_to_cart_notification_text' => apply_filters( 'ecome_added_to_cart_notification_text', esc_html__( 'has been added to cart!', 'ecome' ) ),
				                                  'view_cart_notification_text'     => apply_filters( 'ecome_view_cart_notification_text', esc_html__( 'View Cart', 'ecome' ) ),
				                                  'added_to_cart_text'              => apply_filters( 'ecome_adding_to_cart_text', esc_html__( 'Product has been added to cart!', 'ecome' ) ),
				                                  'wc_cart_url'                     => ( function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '' ),
				                                  'added_to_wishlist_text'          => get_option( 'yith_wcwl_product_added_text', esc_html__( 'Product has been added to wishlist!', 'ecome' ) ),
				                                  'wishlist_url'                    => ( function_exists( 'YITH_WCWL' ) ? esc_url( YITH_WCWL()->get_wishlist_url() ) : '' ),
				                                  'browse_wishlist_text'            => get_option( 'yith_wcwl_browse_wishlist_text', esc_html__( 'Browse Wishlist', 'ecome' ) ),
				                                  'growl_notice_text'               => esc_html__( 'Notice!', 'ecome' ),
				                                  'removed_cart_text'               => esc_html__( 'Product Removed', 'ecome' ),
				                                  'wp_nonce_url'                    => ( function_exists( 'wc_get_cart_url' ) ? wp_nonce_url( wc_get_cart_url() ) : '' ),
			                                  )
			);
			$ecome_enable_popup        = $this->ecome_get_option( 'ecome_enable_popup' );
			$ecome_enable_popup_mobile = $this->ecome_get_option( 'ecome_enable_popup_mobile' );
			$ecome_popup_delay_time    = $this->ecome_get_option( 'ecome_popup_delay_time' );
			$atts                      = array(
				'owl_vertical'            => true,
				'owl_responsive_vertical' => 1199,
				'owl_loop'                => false,
				'owl_slide_margin'        => 10,
				'owl_focus_select'        => true,
				'owl_ts_items'            => $this->ecome_get_option( 'ecome_product_thumbnail_ts_items', 2 ),
				'owl_xs_items'            => $this->ecome_get_option( 'ecome_product_thumbnail_xs_items', 2 ),
				'owl_sm_items'            => $this->ecome_get_option( 'ecome_product_thumbnail_sm_items', 3 ),
				'owl_md_items'            => $this->ecome_get_option( 'ecome_product_thumbnail_md_items', 3 ),
				'owl_lg_items'            => $this->ecome_get_option( 'ecome_product_thumbnail_lg_items', 3 ),
				'owl_ls_items'            => $this->ecome_get_option( 'ecome_product_thumbnail_ls_items', 3 ),
			);
			$atts                      = apply_filters( 'ecome_thumb_product_single_slide', $atts );
			$owl_settings              = explode( ' ', apply_filters( 'ecome_carousel_data_attributes', 'owl_', $atts ) );
			wp_localize_script( 'ecome-script', 'ecome_global_frontend',
			                    array(
				                    'ecome_enable_popup'        => $ecome_enable_popup,
				                    'ecome_popup_delay_time'    => $ecome_popup_delay_time,
				                    'ecome_enable_popup_mobile' => $ecome_enable_popup_mobile,
				                    'data_slick'                => urldecode( $owl_settings[3] ),
				                    'data_responsive'           => urldecode( $owl_settings[6] ),
				                    'countdown_day'             => esc_html__( 'Days', 'ecome' ),
				                    'countdown_hrs'             => esc_html__( 'Hours', 'ecome' ),
				                    'countdown_mins'            => esc_html__( 'Mins', 'ecome' ),
				                    'countdown_secs'            => esc_html__( 'Secs', 'ecome' ),
			                    )
			);
		}
		
		public static function ecome_get_option( $option_name, $default = '' ) {
			$get_value = isset( $_REQUEST[ $option_name ] ) ? $_REQUEST[ $option_name ] : '';
			$cs_option = null;
			if ( defined( 'CS_VERSION' ) ) {
				$cs_option = get_option( CS_OPTION );
			}
			if ( isset( $_REQUEST[ $option_name ] ) ) {
				$cs_option = $get_value;
				$default   = $get_value;
			}
			if ( $option_name == 'shop_display_mode' ) {
				$option_name = 'ecome_shop_list_style';
			}
			$options = apply_filters( 'cs_get_option', $cs_option, $option_name, $default );
			if ( ! empty( $option_name ) && ! empty( $options[ $option_name ] ) ) {
				$option = $options[ $option_name ];
				if ( is_array( $option ) && isset( $option['multilang'] ) && $option['multilang'] == true ) {
					if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
						if ( isset( $option[ ICL_LANGUAGE_CODE ] ) ) {
							return $option[ ICL_LANGUAGE_CODE ];
						}
					}
				}
				
				return $option;
			} else {
				return ( ! empty( $default ) ) ? $default : null;
			}
		}
		
		public static function theme_get_meta( $meta_key, $meta_value ) {
			$main_data            = '';
			$enable_theme_options = self::ecome_get_option( 'enable_theme_options' );
			$meta_data            = get_post_meta( get_the_ID(), $meta_key, true );
			if ( is_page() && isset( $meta_data[ $meta_value ] ) && $enable_theme_options == 1 ) {
				$main_data = $meta_data[ $meta_value ];
			}
			
			return $main_data;
		}
		
		/**
		 * Filter whether comments are open for a given post type.
		 *
		 * @param string $status       Default status for the given post type,
		 *                             either 'open' or 'closed'.
		 * @param string $post_type    Post type. Default is `post`.
		 * @param string $comment_type Type of comment. Default is `comment`.
		 *
		 * @return string (Maybe) filtered default status for the given post type.
		 */
		function open_default_comments_for_page( $status, $post_type, $comment_type ) {
			if ( 'page' == $post_type ) {
				return 'open';
			}
			
			return $status;
		}
		
		public static function includes() {
			include_once get_parent_theme_file_path( '/framework/framework.php' );
			define( 'CS_ACTIVE_FRAMEWORK', true );
			define( 'CS_ACTIVE_METABOX', true );
			define( 'CS_ACTIVE_TAXONOMY', false );
			define( 'CS_ACTIVE_SHORTCODE', false );
			define( 'CS_ACTIVE_CUSTOMIZE', false );
		}
	}
}
if ( ! function_exists( 'Ecome_Functions' ) ) {
	function Ecome_Functions() {
		return Ecome_Functions::instance();
	}
	
	Ecome_Functions();
}