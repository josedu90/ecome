<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( !class_exists( 'Ecome_ThemeOption' ) ) {
	class Ecome_ThemeOption
	{
		public function __construct()
		{
			add_filter( 'cs_framework_settings', array( $this, 'framework_settings' ) );
			add_filter( 'cs_framework_options', array( $this, 'framework_options' ) );
			add_filter( 'cs_metabox_options', array( $this, 'metabox_options' ) );
		}

		public function get_header_options()
		{
			$layoutDir      = get_template_directory() . '/templates/header/';
			$header_options = array();
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                  = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                  = str_replace( 'header-', '', $fileInfo['filename'] );
								$header_options[$file_name] = array(
									'title'   => $file_data['Name'],
									'preview' => get_theme_file_uri( '/templates/header/header-' . $file_name . '.jpg' ),
								);
							}
						}
					}
				}
			}

			return $header_options;
		}

		public function get_sidebar_options()
		{
			$sidebars = array();
			global $wp_registered_sidebars;
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[$sidebar['id']] = $sidebar['name'];
			}

			return $sidebars;
		}

		public function get_social_options()
		{
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[$key] = $social['title_social'];
				}
			}

			return $socials;
		}

		public function get_footer_options()
		{
			$layoutDir      = get_template_directory() . '/templates/footer/';
			$footer_options = array();
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                  = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                  = str_replace( 'footer-', '', $fileInfo['filename'] );
								$footer_options[$file_name] = array(
									'title'   => $file_data['Name'],
									'preview' => get_theme_file_uri( '/templates/footer/footer-' . $file_name . '.jpg' ),
								);
							}
						}
					}
				}
			}

			return $footer_options;
		}

		public function get_footer_preview()
		{
			$footer_preview = array();
			$args           = array(
				'post_type'      => 'footer',
				'posts_per_page' => -1,
				'orderby'        => 'ASC',
			);
			$loop           = get_posts( $args );
			foreach ( $loop as $value ) {
				setup_postdata( $value );
				$data_meta                  = get_post_meta( $value->ID, '_custom_footer_options', true );
				$template_style             = isset( $data_meta['ecome_footer_style'] ) ? $data_meta['ecome_footer_style'] : 'default';
				$footer_preview[$value->ID] = array(
					'title'   => $value->post_title,
					'preview' => get_theme_file_uri( '/templates/footer/footer-' . $template_style . '.jpg' ),
				);
			}

			return $footer_preview;
		}

		public function ecome_attributes_options()
		{
			$attributes     = array();
			$attributes_tax = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attributes[$attribute->attribute_name] = $attribute->attribute_label;
				}
			}

			return $attributes;
		}

		function framework_settings( $settings )
		{
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK SETTINGS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$settings = array(
				'menu_title'      => esc_html__( 'Theme Options', 'ecome' ),
				'menu_type'       => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'       => 'ecome',
				'ajax_save'       => false,
				'menu_parent'     => 'ecome_menu',
				'show_reset_all'  => true,
				'menu_position'   => 5,
				'framework_title' => '<a href="' . esc_url( 'https://ecome.namcrafted.com/' ) . '" target="_blank"><img src="' . get_theme_file_uri( '/assets/images/logo-options.png' ) . '" alt="' . esc_attr( 'ecome' ) . '"></a> <i>' . esc_html__( 'By ', 'ecome' ) . '<a href="' . esc_url( 'https://themeforest.net/user/zankover-n/portfolio' ) . '" target="_blank">' . esc_html__( 'ZanThemes', 'ecome' ) . '</a></i>',
			);

			return $settings;
		}

		function framework_options( $options )
		{
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK OPTIONS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$options = array();
			// ----------------------------------------
			// a option section for options overview  -
			// ----------------------------------------
			$options[] = array(
				'name'     => 'general',
				'title'    => esc_html__( 'General', 'ecome' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'main_settings',
						'title'  => esc_html__( 'Main Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'    => 'ecome_logo',
								'type'  => 'image',
								'title' => esc_html__( 'Logo', 'ecome' ),
							),
							array(
								'id'      => 'ecome_main_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Main Color', 'ecome' ),
								'default' => '#e5534c',
								'rgba'    => true,
							),
							array(
								'id'      => 'ecome_gradient_color_1',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Gradient Color', 'ecome' ),
								'default' => '#c467f5',
							),
							array(
								'id'      => 'ecome_gradient_color_2',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Gradient Color', 'ecome' ),
								'default' => '#53f3ff',
							),
							array(
								'id'             => 'ecome_page_404',
								'type'           => 'select',
								'title'          => esc_html__( '404 Page Content', 'ecome' ),
								'options'        => 'pages',
								'default_option' => esc_html__( 'Select a page', 'ecome' ),
							),
							array(
								'id'    => 'gmap_api_key',
								'type'  => 'text',
								'title' => esc_html__( 'Google Map API Key', 'ecome' ),
								'desc'  => esc_html__( 'Enter your Google Map API key. ', 'ecome' ) . '<a href="' . esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key' ) . '" target="_blank">' . esc_html__( 'How to get?', 'ecome' ) . '</a>',
							),
							array(
								'id'    => 'enable_theme_options',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Meta Box Options', 'ecome' ),
								'desc'  => esc_html__( 'Enable for using Themes setting each single page.', 'ecome' ),
							),
							array(
								'id'    => 'ecome_theme_lazy_load',
								'type'  => 'switcher',
								'title' => esc_html__( 'Use image Lazy Load', 'ecome' ),
							),
						),
					),
					array(
						'name'   => 'popup_settings',
						'title'  => esc_html__( 'Newsletter Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'    => 'ecome_enable_popup',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Newsletter Popup', 'ecome' ),
							),
							array(
								'id'         => 'ecome_select_newsletter_page',
								'type'       => 'select',
								'title'      => esc_html__( 'Page Newsletter Popup', 'ecome' ),
								'options'    => 'pages',
								'query_args' => array(
									'sort_order'  => 'ASC',
									'sort_column' => 'post_title',
								),
								'attributes' => array(
									'multiple' => 'multiple',
								),
								'class'      => 'chosen',
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'ecome_popup_background',
								'type'       => 'image',
								'title'      => esc_html__( 'Popup Background', 'ecome' ),
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'ecome_popup_title',
								'type'       => 'text',
								'title'      => esc_html__( 'Title', 'ecome' ),
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
								'default'    => esc_html__( 'Sign up & connect to Ecome', 'ecome' ),
							),
							array(
								'id'         => 'ecome_popup_desc',
								'type'       => 'textarea',
								'title'      => esc_html__( 'Description', 'ecome' ),
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'ecome_popup_input_placeholder',
								'type'       => 'text',
								'title'      => esc_html__( 'Placeholder Input', 'ecome' ),
								'default'    => esc_html__( 'Email address here...', 'ecome' ),
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'ecome_popup_input_submit',
								'type'       => 'text',
								'title'      => esc_html__( 'Button', 'ecome' ),
								'default'    => esc_html__( 'SUBSCRIBE', 'ecome' ),
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'ecome_popup_delay_time',
								'type'       => 'number',
								'title'      => esc_html__( 'Delay Time', 'ecome' ),
								'default'    => '0',
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'ecome_enable_popup_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Poppup on Mobile', 'ecome' ),
								'default'    => false,
								'dependency' => array( 'ecome_enable_popup', '==', '1' ),
							),
						),
					),
					array(
						'name'   => 'widget_settings',
						'title'  => esc_html__( 'Widget Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'              => 'multi_widget',
								'type'            => 'group',
								'title'           => esc_html__( 'Multi Widget', 'ecome' ),
								'button_title'    => esc_html__( 'Add Widget', 'ecome' ),
								'accordion_title' => esc_html__( 'Add New Field', 'ecome' ),
								'fields'          => array(
									array(
										'id'    => 'add_widget',
										'type'  => 'text',
										'title' => esc_html__( 'Name Widget', 'ecome' ),
									),
								),
							),
						),
					),
					array(
						'name'   => 'theme_js_css',
						'title'  => esc_html__( 'Customs JS/CSS', 'ecome' ),
						'fields' => array(
							array(
								'id'         => 'ecome_custom_js',
								'type'       => 'ace_editor',
								'before'     => '<h1>' . esc_html__( 'Custom JS', 'ecome' ) . '</h1>',
								'attributes' => array(
									'data-theme' => 'twilight',  // the theme for ACE Editor
									'data-mode'  => 'javascript',     // the language for ACE Editor
								),
							),
						),
					),
					array(
						'name'   => 'live_search_settings',
						'title'  => esc_html__( 'Live Search Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'         => 'enable_live_search',
								'type'       => 'switcher',
								'attributes' => array(
									'data-depend-id' => 'enable_live_search',
								),
								'title'      => esc_html__( 'Enable Live Search', 'ecome' ),
								'default'    => false,
							),
							array(
								'id'         => 'show_suggestion',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Display Suggestion', 'ecome' ),
								'dependency' => array(
									'enable_live_search', '==', true,
								),
							),
							array(
								'id'         => 'min_characters',
								'type'       => 'number',
								'default'    => 3,
								'title'      => esc_html__( 'Min Search Characters', 'ecome' ),
								'dependency' => array(
									'enable_live_search', '==', true,
								),
							),
							array(
								'id'         => 'max_results',
								'type'       => 'number',
								'default'    => 3,
								'title'      => esc_html__( 'Max Search Characters', 'ecome' ),
								'dependency' => array(
									'enable_live_search', '==', true,
								),
							),
							array(
								'id'         => 'search_in',
								'type'       => 'checkbox',
								'title'      => esc_html__( 'Search In', 'ecome' ),
								'options'    => array(
									'title'       => esc_html__( 'Title', 'ecome' ),
									'description' => esc_html__( 'Description', 'ecome' ),
									'content'     => esc_html__( 'Content', 'ecome' ),
									'sku'         => esc_html__( 'SKU', 'ecome' ),
								),
								'dependency' => array(
									'enable_live_search', '==', true,
								),
							),
						),
					),
				),
			);
			$options[] = array(
				'name'     => 'header',
				'title'    => esc_html__( 'Header Settings', 'ecome' ),
				'icon'     => 'fa fa-folder-open-o',
				'sections' => array(
					array(
						'name'   => 'main_header',
						'title'  => esc_html__( 'Header Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'    => 'ecome_header_background',
								'type'  => 'image',
								'title' => esc_html__( 'Header Background', 'ecome' ),
							),
							array(
								'id'         => 'ecome_background_url',
								'type'       => 'text',
								'default'    => '#',
								'title'      => esc_html__( 'Header Background Url', 'ecome' ),
								'dependency' => array( 'ecome_header_background', '!=', '' ),
							),
							array(
								'id'    => 'ecome_enable_sticky_menu',
								'type'  => 'switcher',
								'title' => esc_html__( 'Main Menu Sticky', 'ecome' ),
							),
							array(
								'id'         => 'ecome_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'ecome' ),
								'desc'       => esc_html__( 'Select a header layout', 'ecome' ),
								'options'    => self::get_header_options(),
								'default'    => 'style-01',
								'attributes' => array(
									'data-depend-id' => 'ecome_used_header',
								),
							),
							array(
								'id'      => 'header_icon',
								'type'    => 'icon',
								'title'   => esc_html__( 'Header Icon', 'ecome' ),
								'default' => 'flaticon-people',
							),
							array(
								'id'    => 'header_text',
								'type'  => 'text',
								'title' => esc_html__( 'Phone Title', 'ecome' ),
							),
							array(
								'id'    => 'header_phone',
								'type'  => 'text',
								'title' => esc_html__( 'Header Phone Number', 'ecome' ),
							),
							array(
								'id'              => 'key_word',
								'title'           => esc_html__( 'Keyword', 'ecome' ),
								'type'            => 'group',
								'button_title'    => esc_html__( 'Add New Key', 'ecome' ),
								'accordion_title' => esc_html__( 'Key Item', 'ecome' ),
								'fields'          => array(
									array(
										'id'    => 'key_word_item',
										'type'  => 'text',
										'title' => esc_html__( 'Keyword', 'ecome' ),
									),
									array(
										'id'    => 'key_word_link',
										'type'  => 'text',
										'title' => esc_html__( 'Key Link', 'ecome' ),
									),
								),
							),
						),
					),
					array(
						'name'   => 'vertical',
						'title'  => esc_html__( 'Vertical Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'         => 'ecome_enable_vertical_menu',
								'type'       => 'switcher',
								'attributes' => array(
									'data-depend-id' => 'enable_vertical_menu',
								),
								'title'      => esc_html__( 'Enable Vertical Menu', 'ecome' ),
							),
							array(
								'id'         => 'ecome_block_vertical_menu',
								'type'       => 'select',
								'title'      => esc_html__( 'Vertical Menu Always Open', 'ecome' ),
								'options'    => 'page',
								'class'      => 'chosen',
								'attributes' => array(
									'placeholder' => 'Select a page',
									'multiple'    => 'multiple',
								),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
								'after'      => '<i class="ecome-text-desc">' . esc_html__( '-- Vertical menu will be always open --', 'ecome' ) . '</i>',
							),
							array(
								'id'         => 'ecome_vertical_menu_title',
								'type'       => 'text',
								'title'      => esc_html__( 'Vertical Menu Title', 'ecome' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
								'default'    => esc_html__( 'CATEGORIES', 'ecome' ),
							),
							array(
								'id'         => 'ecome_vertical_menu_button_all_text',
								'type'       => 'text',
								'title'      => esc_html__( 'Vertical Menu Button Show All Text', 'ecome' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
								'default'    => esc_html__( 'All Categories', 'ecome' ),
							),
							array(
								'id'         => 'ecome_vertical_menu_button_close_text',
								'type'       => 'text',
								'title'      => esc_html__( 'Vertical Menu Button Close Text', 'ecome' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
								'default'    => esc_html__( 'Close', 'ecome' ),
							),
							array(
								'id'         => 'ecome_vertical_item_visible',
								'type'       => 'number',
								'title'      => esc_html__( 'The Number of Visible Vertical Menu Items', 'ecome' ),
								'desc'       => esc_html__( 'The Number of Visible Vertical Menu Items', 'ecome' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
								'default'    => 10,
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'footer',
				'title'  => esc_html__( 'Footer Settings', 'ecome' ),
				'icon'   => 'fa fa-folder-open-o',
				'fields' => array(
					array(
						'id'      => 'ecome_footer_options',
						'type'    => 'select_preview',
						'title'   => esc_html__( 'Select Footer Builder', 'ecome' ),
						'options' => self::get_footer_preview(),
						'default' => 'default',
					),
				),
			);
			$options[] = array(
				'name'     => 'blog_main',
				'title'    => esc_html__( 'Blog', 'ecome' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'blog',
						'title'  => esc_html__( 'Blog', 'ecome' ),
						'fields' => array(
							'ecome_sidebar_blog_layout' => array(
								'id'      => 'ecome_sidebar_blog_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Blog Sidebar Layout', 'ecome' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'ecome' ),
								'options' => array(
									'left'  => esc_attr( ' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAANNJREFUeNrs2b0KwjAUhuG3NkUsYicHB117J16Pl9Rr00H8QaxItQjGwQilTo0QKXzfcshwDg8h00lkraVvMQC703kNTLo0xiYpyuN+Vd+rZRybAkgDeC95ni+MO8w9BkyBCBgDs0CXnAEM3KH0GHBz9QlUgdBlE+2TB2CB2tVg+QUdtWov0H+L0EILLbTQQgsttNBCCy200EILLbTQ37Gt2gt0wnslNiTwauyDzjx6R40ZaSBvBm6pDmzouFQHDu5pXIFtIPgFIOrj98ULAAD//wMA7UQkYA5MJngAAAAASUVORK5CYII=' ),
									'right' => esc_attr( ' data:image/png;base64,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' ),
									'full'  => esc_attr( ' data:image/png;base64,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' ),
								),
								'default' => 'left',
							),
							'ecome_blog_used_sidebar'   => array(
								'id'         => 'ecome_blog_used_sidebar',
								'type'       => 'select',
								'default'    => 'widget-area',
								'title'      => esc_html__( 'Blog Sidebar', 'ecome' ),
								'options'    => $this->get_sidebar_options(),
								'dependency' => array( 'ecome_sidebar_blog_layout_full', '==', false ),
							),
							'ecome_blog_list_style'     => array(
								'id'      => 'ecome_blog_list_style',
								'type'    => 'select',
								'default' => 'standard',
								'title'   => esc_html__( 'Blog List Style', 'ecome' ),
								'options' => array(
									'standard' => esc_html__( 'Standard', 'ecome' ),
									'grid'     => esc_html__( 'Grid', 'ecome' ),
								),
							),
							'ecome_blog_bg_items'       => array(
								'id'         => 'ecome_blog_bg_items',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'ecome' ),
									'6'  => esc_html__( '2 items', 'ecome' ),
									'4'  => esc_html__( '3 items', 'ecome' ),
									'3'  => esc_html__( '4 items', 'ecome' ),
									'15' => esc_html__( '5 items', 'ecome' ),
									'2'  => esc_html__( '6 items', 'ecome' ),
								),
								'default'    => '4',
								'dependency' => array( 'ecome_blog_list_style', '==', 'grid' ),
							),
							'ecome_blog_lg_items'       => array(
								'id'         => 'ecome_blog_lg_items',
								'default'    => '4',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'ecome' ),
									'6'  => esc_html__( '2 items', 'ecome' ),
									'4'  => esc_html__( '3 items', 'ecome' ),
									'3'  => esc_html__( '4 items', 'ecome' ),
									'15' => esc_html__( '5 items', 'ecome' ),
									'2'  => esc_html__( '6 items', 'ecome' ),
								),
								'dependency' => array( 'ecome_blog_list_style', '==', 'grid' ),
							),
							'ecome_blog_md_items'       => array(
								'id'         => 'ecome_blog_md_items',
								'default'    => '4',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
								'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'ecome' ),
									'6'  => esc_html__( '2 items', 'ecome' ),
									'4'  => esc_html__( '3 items', 'ecome' ),
									'3'  => esc_html__( '4 items', 'ecome' ),
									'15' => esc_html__( '5 items', 'ecome' ),
									'2'  => esc_html__( '6 items', 'ecome' ),
								),
								'dependency' => array( 'ecome_blog_list_style', '==', 'grid' ),
							),
							'ecome_blog_sm_items'       => array(
								'id'         => 'ecome_blog_sm_items',
								'default'    => '4',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
								'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'ecome' ),
									'6'  => esc_html__( '2 items', 'ecome' ),
									'4'  => esc_html__( '3 items', 'ecome' ),
									'3'  => esc_html__( '4 items', 'ecome' ),
									'15' => esc_html__( '5 items', 'ecome' ),
									'2'  => esc_html__( '6 items', 'ecome' ),
								),
								'dependency' => array( 'ecome_blog_list_style', '==', 'grid' ),
							),
							'ecome_blog_xs_items'       => array(
								'id'         => 'ecome_blog_xs_items',
								'default'    => '6',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
								'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'ecome' ),
									'6'  => esc_html__( '2 items', 'ecome' ),
									'4'  => esc_html__( '3 items', 'ecome' ),
									'3'  => esc_html__( '4 items', 'ecome' ),
									'15' => esc_html__( '5 items', 'ecome' ),
									'2'  => esc_html__( '6 items', 'ecome' ),
								),
								'dependency' => array( 'ecome_blog_list_style', '==', 'grid' ),
							),
							'ecome_blog_ts_items'       => array(
								'id'         => 'ecome_blog_ts_items',
								'default'    => '12',
								'type'       => 'select',
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
								'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
								'options'    => array(
									'12' => esc_html__( '1 item', 'ecome' ),
									'6'  => esc_html__( '2 items', 'ecome' ),
									'4'  => esc_html__( '3 items', 'ecome' ),
									'3'  => esc_html__( '4 items', 'ecome' ),
									'15' => esc_html__( '5 items', 'ecome' ),
									'2'  => esc_html__( '6 items', 'ecome' ),
								),
								'dependency' => array( 'ecome_blog_list_style', '==', 'grid' ),
							),
						),
					),
					array(
						'name'   => 'blog_single',
						'title'  => esc_html__( 'Blog Single', 'ecome' ),
						'fields' => array(
							'enable_share_post'           => array(
								'id'    => 'enable_share_post',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Share Button', 'ecome' ),
							),
							'enable_author_info'          => array(
								'id'    => 'enable_author_info',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable Author Info', 'ecome' ),
							),
							'ecome_sidebar_single_layout' => array(
								'id'      => 'ecome_sidebar_single_layout',
								'type'    => 'image_select',
								'default' => 'left',
								'title'   => esc_html__( 'Single Post Sidebar Layout', 'ecome' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'ecome' ),
								'options' => array(
									'left'  => esc_attr( ' data:image/png;base64,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' ),
									'right' => esc_attr( ' data:image/png;base64,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' ),
									'full'  => esc_attr( ' data:image/png;base64,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' ),
								),
							),
							'ecome_single_used_sidebar'   => array(
								'id'         => 'ecome_single_used_sidebar',
								'type'       => 'select',
								'default'    => 'widget-area',
								'title'      => esc_html__( 'Single Blog Sidebar', 'ecome' ),
								'options'    => $this->get_sidebar_options(),
								'dependency' => array( 'ecome_sidebar_single_layout_full', '==', false ),
							),
						),
					),
				),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$options[] = array(
					'name'     => 'woocommerce_main',
					'title'    => esc_html__( 'WooCommerce', 'ecome' ),
					'icon'     => 'fa fa-wordpress',
					'sections' => array(
						array(
							'name'   => 'categories',
							'title'  => esc_html__( 'Categories', 'ecome' ),
							'fields' => array(
								'ecome_woo_cat_enable'   => array(
									'id'    => 'ecome_woo_cat_enable',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Category Products', 'ecome' ),
								),
								array(
									'id'         => 'category_banner',
									'type'       => 'image',
									'title'      => esc_html__( 'Categories banner', 'ecome' ),
									'desc'       => esc_html__( 'Banner in category page WooCommerce.', 'ecome' ),
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
								array(
									'id'         => 'category_banner_url',
									'type'       => 'text',
									'default'    => '#',
									'title'      => esc_html__( 'Banner Url', 'ecome' ),
									'dependency' => array( 'category_banner', '!=', '' ),
								),
								'ecome_woo_cat_ls_items' => array(
									'id'         => 'ecome_woo_cat_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
								'ecome_woo_cat_lg_items' => array(
									'id'         => 'ecome_woo_cat_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
								'ecome_woo_cat_md_items' => array(
									'id'         => 'ecome_woo_cat_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on landscape tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
								'ecome_woo_cat_sm_items' => array(
									'id'         => 'ecome_woo_cat_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category product items per row on portrait tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '2',
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
								'ecome_woo_cat_xs_items' => array(
									'id'         => 'ecome_woo_cat_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
								'ecome_woo_cat_ts_items' => array(
									'id'         => 'ecome_woo_cat_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Category products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_cat_enable', '==', true ),
								),
							),
						),
						array(
							'name'   => 'woocommerce',
							'title'  => esc_html__( 'WooCommerce', 'ecome' ),
							'fields' => array(
								'enable_recent_product'     => array(
									'id'      => 'enable_recent_product',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Recent Product', 'ecome' ),
									'default' => false,
								),
								'ecome_product_newness'     => array(
									'id'      => 'ecome_product_newness',
									'default' => '10',
									'type'    => 'number',
									'title'   => esc_html__( 'Products Newness', 'ecome' ),
								),
								'ecome_sidebar_shop_layout' => array(
									'id'      => 'ecome_sidebar_shop_layout',
									'type'    => 'image_select',
									'default' => 'left',
									'title'   => esc_html__( 'Shop Page Sidebar Layout', 'ecome' ),
									'desc'    => esc_html__( 'Select sidebar position on Shop Page.', 'ecome' ),
									'options' => array(
										'left'  => esc_attr( ' data:image/png;base64,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' ),
										'right' => esc_attr( ' data:image/png;base64,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' ),
										'full'  => esc_attr( ' data:image/png;base64,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' ),
									),
								),
								'ecome_shop_used_sidebar'   => array(
									'id'         => 'ecome_shop_used_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Sidebar Used For Shop', 'ecome' ),
									'options'    => $this->get_sidebar_options(),
									'dependency' => array( 'ecome_sidebar_shop_layout_full', '==', false ),
								),
								'ecome_shop_list_style'     => array(
									'id'      => 'ecome_shop_list_style',
									'type'    => 'image_select',
									'default' => 'grid',
									'title'   => esc_html__( 'Shop Default Layout', 'ecome' ),
									'desc'    => esc_html__( 'Select default layout for shop, product category archive.', 'ecome' ),
									'options' => array(
										'grid'    => get_theme_file_uri( 'assets/images/grid-display.png' ),
										'grid-v2' => get_theme_file_uri( 'assets/images/grid-v2-display.png' ),
										'list'    => get_theme_file_uri( 'assets/images/list-display.png' ),
									),
								),
								'ecome_attribute_product'   => array(
									'id'      => 'ecome_attribute_product',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Attribute', 'ecome' ),
									'options' => $this->ecome_attributes_options(),
								),
								'ecome_product_per_page'    => array(
									'id'      => 'ecome_product_per_page',
									'type'    => 'number',
									'default' => '10',
									'title'   => esc_html__( 'Products perpage', 'ecome' ),
									'desc'    => esc_html__( 'Number of products on shop page.', 'ecome' ),
								),
								'product_carousel'          => array(
									'id'      => 'product_carousel',
									'type'    => 'heading',
									'content' => esc_html__( 'Grid Settings', 'ecome' ),
								),
								'ecome_woo_bg_items'        => array(
									'id'      => 'ecome_woo_bg_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'ecome' ),
										'6'  => esc_html__( '2 items', 'ecome' ),
										'4'  => esc_html__( '3 items', 'ecome' ),
										'3'  => esc_html__( '4 items', 'ecome' ),
										'15' => esc_html__( '5 items', 'ecome' ),
										'2'  => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '4',
								),
								'ecome_woo_lg_items'        => array(
									'id'      => 'ecome_woo_lg_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'ecome' ),
										'6'  => esc_html__( '2 items', 'ecome' ),
										'4'  => esc_html__( '3 items', 'ecome' ),
										'3'  => esc_html__( '4 items', 'ecome' ),
										'15' => esc_html__( '5 items', 'ecome' ),
										'2'  => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '4',
								),
								'ecome_woo_md_items'        => array(
									'id'      => 'ecome_woo_md_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'ecome' ),
										'6'  => esc_html__( '2 items', 'ecome' ),
										'4'  => esc_html__( '3 items', 'ecome' ),
										'3'  => esc_html__( '4 items', 'ecome' ),
										'15' => esc_html__( '5 items', 'ecome' ),
										'2'  => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '4',
								),
								'ecome_woo_sm_items'        => array(
									'id'      => 'ecome_woo_sm_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'ecome' ),
										'6'  => esc_html__( '2 items', 'ecome' ),
										'4'  => esc_html__( '3 items', 'ecome' ),
										'3'  => esc_html__( '4 items', 'ecome' ),
										'15' => esc_html__( '5 items', 'ecome' ),
										'2'  => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '4',
								),
								'ecome_woo_xs_items'        => array(
									'id'      => 'ecome_woo_xs_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'ecome' ),
										'6'  => esc_html__( '2 items', 'ecome' ),
										'4'  => esc_html__( '3 items', 'ecome' ),
										'3'  => esc_html__( '4 items', 'ecome' ),
										'15' => esc_html__( '5 items', 'ecome' ),
										'2'  => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '6',
								),
								'ecome_woo_ts_items'        => array(
									'id'      => 'ecome_woo_ts_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
									'options' => array(
										'12' => esc_html__( '1 item', 'ecome' ),
										'6'  => esc_html__( '2 items', 'ecome' ),
										'4'  => esc_html__( '3 items', 'ecome' ),
										'3'  => esc_html__( '4 items', 'ecome' ),
										'15' => esc_html__( '5 items', 'ecome' ),
										'2'  => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '12',
								),
							),
						),
						array(
							'name'   => 'single_product',
							'title'  => esc_html__( 'Single Products', 'ecome' ),
							'fields' => array(
								array(
									'id'      => 'enable_info_product_single',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Sticky Info Product Single', 'ecome' ),
									'default' => true,
									'desc'    => esc_html__( 'On or Off Sticky Info Product Single.', 'ecome' ), 
								), 
								array(
									'id'             => 'ecome_add_page_product',
									'type'           => 'select',
									'title'          => esc_html__( 'Page Content', 'ecome' ),
									'options'        => 'pages',
									'default_option' => esc_html__( 'Select a page', 'ecome' ),
								),
								'ecome_single_attribute'               => array(
									'id'      => 'ecome_single_attribute',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Attribute', 'ecome' ),
									'options' => $this->ecome_attributes_options(),
								),
								'ecome_sidebar_product_layout'         => array(
									'id'      => 'ecome_sidebar_product_layout',
									'type'    => 'image_select',
									'default' => 'left',
									'title'   => esc_html__( 'Product Page Sidebar Layout', 'ecome' ),
									'desc'    => esc_html__( 'Select sidebar position on Product Page.', 'ecome' ),
									'options' => array(
										'left'  => esc_attr( ' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAANNJREFUeNrs2b0KwjAUhuG3NkUsYicHB117J16Pl9Rr00H8QaxItQjGwQilTo0QKXzfcshwDg8h00lkraVvMQC703kNTLo0xiYpyuN+Vd+rZRybAkgDeC95ni+MO8w9BkyBCBgDs0CXnAEM3KH0GHBz9QlUgdBlE+2TB2CB2tVg+QUdtWov0H+L0EILLbTQQgsttNBCCy200EILLbTQ37Gt2gt0wnslNiTwauyDzjx6R40ZaSBvBm6pDmzouFQHDu5pXIFtIPgFIOrj98ULAAD//wMA7UQkYA5MJngAAAAASUVORK5CYII=' ),
										'right' => esc_attr( ' data:image/png;base64,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' ),
										'full'  => esc_attr( ' data:image/png;base64,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' ),
									),
								),
								'ecome_single_product_used_sidebar'    => array(
									'id'         => 'ecome_single_product_used_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Sidebar Used For Single Product', 'ecome' ),
									'options'    => $this->get_sidebar_options(),
									'dependency' => array( 'ecome_sidebar_product_layout_full', '==', false ),
								),
								'ecome_single_product_summary_sidebar' => array(
									'id'         => 'ecome_single_product_summary_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Sidebar Used For summary Single Product', 'ecome' ),
									'options'    => $this->get_sidebar_options(),
									'dependency' => array( 'ecome_sidebar_product_layout_full', '==', true ),
								),
								'ecome_product_thumbnail_ls_items'     => array(
									'id'      => 'ecome_product_thumbnail_ls_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Desktop', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '3',
								),
								'ecome_product_thumbnail_lg_items'     => array(
									'id'      => 'ecome_product_thumbnail_lg_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Desktop', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '3',
								),
								'ecome_product_thumbnail_md_items'     => array(
									'id'      => 'ecome_product_thumbnail_md_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on landscape tablet', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '3',
								),
								'ecome_product_thumbnail_sm_items'     => array(
									'id'      => 'ecome_product_thumbnail_sm_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on portrait tablet', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '2',
								),
								'ecome_product_thumbnail_xs_items'     => array(
									'id'      => 'ecome_product_thumbnail_xs_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Mobile', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '1',
								),
								'ecome_product_thumbnail_ts_items'     => array(
									'id'      => 'ecome_product_thumbnail_ts_items',
									'type'    => 'select',
									'title'   => esc_html__( 'Thumbnail items per row on Mobile', 'ecome' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
									'options' => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default' => '1',
								),
							),
						),
						array(
							'name'   => 'ecome_related_product',
							'title'  => esc_html__( 'Related Products', 'ecome' ),
							'fields' => array(
								'ecome_woo_related_enable'         => array(
									'id'      => 'ecome_woo_related_enable',
									'type'    => 'select',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'ecome' ),
										'disable' => esc_html__( 'Disable', 'ecome' ),
									),
									'title'   => esc_html__( 'Enable Related Products', 'ecome' ),
								),
								'ecome_woo_related_products_title' => array(
									'id'         => 'ecome_woo_related_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Related products title', 'ecome' ),
									'desc'       => esc_html__( 'Related products title', 'ecome' ),
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Related Products', 'ecome' ),
								),
								'ecome_woo_related_ls_items'       => array(
									'id'         => 'ecome_woo_related_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
								),
								'ecome_woo_related_lg_items'       => array(
									'id'         => 'ecome_woo_related_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
								),
								'ecome_woo_related_md_items'       => array(
									'id'         => 'ecome_woo_related_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on landscape tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
								),
								'ecome_woo_related_sm_items'       => array(
									'id'         => 'ecome_woo_related_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related product items per row on portrait tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '2',
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
								),
								'ecome_woo_related_xs_items'       => array(
									'id'         => 'ecome_woo_related_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
								),
								'ecome_woo_related_ts_items'       => array(
									'id'         => 'ecome_woo_related_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Related products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_related_enable', '==', 'enable' ),
								),
							),
						),
						array(
							'name'   => 'crosssell_product',
							'title'  => esc_html__( 'Cross Sell Products', 'ecome' ),
							'fields' => array(
								'ecome_woo_crosssell_enable'         => array(
									'id'      => 'ecome_woo_crosssell_enable',
									'type'    => 'select',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'ecome' ),
										'disable' => esc_html__( 'Disable', 'ecome' ),
									),
									'title'   => esc_html__( 'Enable Cross Sell Products', 'ecome' ),
								),
								'ecome_woo_crosssell_products_title' => array(
									'id'         => 'ecome_woo_crosssell_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Cross Sell products title', 'ecome' ),
									'desc'       => esc_html__( 'Cross Sell products title', 'ecome' ),
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Cross Sell Products', 'ecome' ),
								),
								'ecome_woo_crosssell_ls_items'       => array(
									'id'         => 'ecome_woo_crosssell_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
								),
								'ecome_woo_crosssell_lg_items'       => array(
									'id'         => 'ecome_woo_crosssell_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
								),
								'ecome_woo_crosssell_md_items'       => array(
									'id'         => 'ecome_woo_crosssell_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on landscape tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
								),
								'ecome_woo_crosssell_sm_items'       => array(
									'id'         => 'ecome_woo_crosssell_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell product items per row on portrait tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '2',
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
								),
								'ecome_woo_crosssell_xs_items'       => array(
									'id'         => 'ecome_woo_crosssell_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
								),
								'ecome_woo_crosssell_ts_items'       => array(
									'id'         => 'ecome_woo_crosssell_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Cross Sell products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_crosssell_enable', '==', 'enable' ),
								),
							),
						),
						array(
							'name'   => 'upsell_product',
							'title'  => esc_html__( 'Upsell Products', 'ecome' ),
							'fields' => array(
								'ecome_woo_upsell_enable'         => array(
									'id'      => 'ecome_woo_upsell_enable',
									'type'    => 'select',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'ecome' ),
										'disable' => esc_html__( 'Disable', 'ecome' ),
									),
									'title'   => esc_html__( 'Enable Upsell Products', 'ecome' ),
								),
								'ecome_woo_upsell_products_title' => array(
									'id'         => 'ecome_woo_upsell_products_title',
									'type'       => 'text',
									'title'      => esc_html__( 'Upsell products title', 'ecome' ),
									'desc'       => esc_html__( 'Upsell products title', 'ecome' ),
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
									'default'    => esc_html__( 'Upsell Products', 'ecome' ),
								),
								'ecome_woo_upsell_ls_items'       => array(
									'id'         => 'ecome_woo_upsell_ls_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
								),
								'ecome_woo_upsell_lg_items'       => array(
									'id'         => 'ecome_woo_upsell_lg_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Desktop', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
								),
								'ecome_woo_upsell_md_items'       => array(
									'id'         => 'ecome_woo_upsell_md_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on landscape tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '3',
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
								),
								'ecome_woo_upsell_sm_items'       => array(
									'id'         => 'ecome_woo_upsell_sm_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell product items per row on portrait tablet', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '2',
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
								),
								'ecome_woo_upsell_xs_items'       => array(
									'id'         => 'ecome_woo_upsell_xs_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
								),
								'ecome_woo_upsell_ts_items'       => array(
									'id'         => 'ecome_woo_upsell_ts_items',
									'type'       => 'select',
									'title'      => esc_html__( 'Upsell products items per row on Mobile', 'ecome' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ecome' ),
									'options'    => array(
										'1' => esc_html__( '1 item', 'ecome' ),
										'2' => esc_html__( '2 items', 'ecome' ),
										'3' => esc_html__( '3 items', 'ecome' ),
										'4' => esc_html__( '4 items', 'ecome' ),
										'5' => esc_html__( '5 items', 'ecome' ),
										'6' => esc_html__( '6 items', 'ecome' ),
									),
									'default'    => '1',
									'dependency' => array( 'ecome_woo_upsell_enable', '==', 'enable' ),
								),
							),
						),
					),
				);
			}
			$options[] = array(
				'name'   => 'social_settings',
				'title'  => esc_html__( 'Social Settings', 'ecome' ),
				'icon'   => 'fa fa-users',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Social User', 'ecome' ),
					),
					array(
						'id'              => 'user_all_social',
						'type'            => 'group',
						'title'           => esc_html__( 'Social', 'ecome' ),
						'button_title'    => esc_html__( 'Add New Social', 'ecome' ),
						'accordion_title' => esc_html__( 'Social Settings', 'ecome' ),
						'fields'          => array(
							array(
								'id'      => 'title_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Title Social', 'ecome' ),
								'default' => 'Facebook',
							),
							array(
								'id'      => 'link_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Link Social', 'ecome' ),
								'default' => 'https://facebook.com',
							),
							array(
								'id'      => 'icon_social',
								'type'    => 'icon',
								'title'   => esc_html__( 'Icon Social', 'ecome' ),
								'default' => 'fa fa-facebook',
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'typography',
				'title'  => esc_html__( 'Typography Options', 'ecome' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					'ecome_enable_typography' => array(
						'id'    => 'ecome_enable_typography',
						'type'  => 'switcher',
						'title' => esc_html__( 'Enable Typography', 'ecome' ),
					),
					array(
						'id'              => 'typography_group',
						'type'            => 'group',
						'title'           => esc_html__( 'Typography Options', 'ecome' ),
						'button_title'    => esc_html__( 'Add New Typography', 'ecome' ),
						'accordion_title' => esc_html__( 'Typography Item', 'ecome' ),
						'dependency'      => array(
							'ecome_enable_typography', '==', true,
						),
						'fields'          => array(
							'ecome_element_tag'            => array(
								'id'      => 'ecome_element_tag',
								'type'    => 'select',
								'options' => array(
									'body' => esc_html__( 'Body', 'ecome' ),
									'h1'   => esc_html__( 'H1', 'ecome' ),
									'h2'   => esc_html__( 'H2', 'ecome' ),
									'h3'   => esc_html__( 'H3', 'ecome' ),
									'h4'   => esc_html__( 'H4', 'ecome' ),
									'h5'   => esc_html__( 'H5', 'ecome' ),
									'h6'   => esc_html__( 'H6', 'ecome' ),
									'p'    => esc_html__( 'P', 'ecome' ),
								),
								'title'   => esc_html__( 'Element Tag', 'ecome' ),
								'desc'    => esc_html__( 'Select a Element Tag HTML', 'ecome' ),
							),
							'ecome_typography_font_family' => array(
								'id'     => 'ecome_typography_font_family',
								'type'   => 'typography',
								'title'  => esc_html__( 'Font Family', 'ecome' ),
								'desc'   => esc_html__( 'Select a Font Family', 'ecome' ),
								'chosen' => false,
							),
							'ecome_body_text_color'        => array(
								'id'    => 'ecome_body_text_color',
								'type'  => 'color_picker',
								'title' => esc_html__( 'Body Text Color', 'ecome' ),
							),
							'ecome_typography_font_size'   => array(
								'id'      => 'ecome_typography_font_size',
								'type'    => 'number',
								'default' => 16,
								'title'   => esc_html__( 'Font Size', 'ecome' ),
								'desc'    => esc_html__( 'Unit PX', 'ecome' ),
							),
							'ecome_typography_line_height' => array(
								'id'      => 'ecome_typography_line_height',
								'type'    => 'number',
								'default' => 24,
								'title'   => esc_html__( 'Line Height', 'ecome' ),
								'desc'    => esc_html__( 'Unit PX', 'ecome' ),
							),
						),
						'default'         => array(
							array(
								'ecome_element_tag'            => 'body',
								'ecome_typography_font_family' => 'Arial',
								'ecome_body_text_color'        => '#81d742',
								'ecome_typography_font_size'   => 16,
								'ecome_typography_line_height' => 24,
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'backup_option',
				'title'  => esc_html__( 'Backup Options', 'ecome' ),
				'icon'   => 'fa fa-bold',
				'fields' => array(
					array(
						'type'  => 'backup',
						'title' => esc_html__( 'Backup Field', 'ecome' ),
					),
				),
			);

			return $options;
		}

		function metabox_options( $options )
		{
			$options = array();
			// -----------------------------------------
			// Page Meta box Options                   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_metabox_theme_options',
				'title'     => esc_html__( 'Custom Theme Options', 'ecome' ),
				'post_type' => 'page',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					'banner' => array(
						'name'   => 'page_banner_settings',
						'title'  => esc_html__( 'Banner Settings', 'ecome' ),
						'icon'   => 'fa fa-picture-o',
						'fields' => array(
							array(
								'id'         => 'ecome_metabox_enable_banner',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Banner', 'ecome' ),
								'default'    => false,
								'attributes' => array(
									'data-depend-id' => 'ecome_metabox_enable_banner',
								),
							),
							array(
								'id'      => 'bg_banner_page',
								'type'    => 'background',
								'title'   => esc_html__( 'Background Banner', 'ecome' ),
								'default' => array(
									'image'      => '',
									'repeat'     => 'repeat-x',
									'position'   => 'center center',
									'attachment' => 'fixed',
									'size'       => 'cover',
									'color'      => '#d4bd52',
								),
							),
							array(
								'id'      => 'height_banner',
								'type'    => 'number',
								'title'   => esc_html__( 'Height Banner', 'ecome' ),
								'default' => '400',
							),
							array(
								'id'      => 'page_margin_top',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Top', 'ecome' ),
								'default' => 0,
							),
							array(
								'id'      => 'page_margin_bottom',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Bottom', 'ecome' ),
								'default' => 0,
							),
						),
					),
					'header' => array(
						'name'   => 'header',
						'title'  => esc_html__( 'Header Settings', 'ecome' ),
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'    => 'metabox_ecome_header_background',
								'type'  => 'image',
								'title' => esc_html__( 'Header Background', 'ecome' ),
							),
							array(
								'id'         => 'metabox_ecome_background_url',
								'type'       => 'text',
								'default'    => '#',
								'title'      => esc_html__( 'Header Background Url', 'ecome' ),
								'dependency' => array( 'metabox_ecome_header_background', '!=', '' ),
							),
							array(
								'id'      => 'metabox_ecome_used_header',
								'type'    => 'select_preview',
								'title'   => esc_html__( 'Header Layout', 'ecome' ),
								'desc'    => esc_html__( 'Select a header layout', 'ecome' ),
								'options' => self::get_header_options(),
								'default' => 'style-01',
							),
						),
					),
					'footer' => array(
						'name'   => 'footer',
						'title'  => esc_html__( 'Footer Settings', 'ecome' ),
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'      => 'metabox_ecome_footer_options',
								'type'    => 'select_preview',
								'title'   => esc_html__( 'Select Footer Builder', 'ecome' ),
								'options' => self::get_footer_preview(),
								'default' => 'default',
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Post Meta box Options                   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_metabox_post_options',
				'title'     => esc_html__( 'Custom Post Options', 'ecome' ),
				'post_type' => 'post',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'gallery_settings',
						'title'  => esc_html__( 'Gallery Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'    => 'gallery_post',
								'type'  => 'gallery',
								'title' => esc_html__( 'Gallery', 'ecome' ),
							),
						),
					),
					array(
						'name'   => 'video_settings',
						'title'  => esc_html__( 'Video Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'       => 'video_post',
								'type'     => 'upload',
								'title'    => esc_html__( 'Video Url', 'ecome' ),
								'settings' => array(
									'upload_type'  => 'video',
									'button_title' => esc_html__( 'Video', 'ecome' ),
									'frame_title'  => esc_html__( 'Select a video', 'ecome' ),
									'insert_title' => esc_html__( 'Use this video', 'ecome' ),
								),
								'desc'     => esc_html__( 'Supports video Url Youtube and upload.', 'ecome' ),
							),
						),
					),
					array(
						'name'   => 'quote_settings',
						'title'  => esc_html__( 'Quote Settings', 'ecome' ),
						'fields' => array(
							array(
								'id'    => 'quote_post',
								'type'  => 'wysiwyg',
								'title' => esc_html__( 'Quote Text', 'ecome' ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Footer Meta box Options            -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_footer_options',
				'title'     => esc_html__( 'Custom Footer Options', 'ecome' ),
				'post_type' => 'footer',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => esc_html__( 'FOOTER STYLE', 'ecome' ),
						'fields' => array(
							array(
								'id'       => 'ecome_footer_style',
								'type'     => 'select_preview',
								'title'    => esc_html__( 'Footer Style', 'ecome' ),
								'subtitle' => esc_html__( 'Select a Footer Style', 'ecome' ),
								'options'  => self::get_footer_options(),
								'default'  => 'style-01',
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Side Meta box Options              -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_page_side_options',
				'title'     => esc_html__( 'Custom Page Side Options', 'ecome' ),
				'post_type' => 'page',
				'context'   => 'side',
				'priority'  => 'default',
				'sections'  => array(
					array(
						'name'   => 'page_option',
						'fields' => array(
							array(
								'id'      => 'sidebar_page_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'ecome' ),
								'desc'    => esc_html__( 'Select sidebar position on Page.', 'ecome' ),
								'options' => array(
									'left'  => esc_attr( ' data:image/png;base64,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' ),
									'right' => esc_attr( ' data:image/png;base64,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' ),
									'full'  => esc_attr( ' data:image/png;base64,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' ),
								),
								'default' => 'left',
							),
							array(
								'id'         => 'page_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Page Sidebar', 'ecome' ),
								'options'    => self::get_sidebar_options(),
								'default'    => 'blue',
								'dependency' => array( 'sidebar_page_layout_full', '==', false ),
							),
							array(
								'id'    => 'page_extra_class',
								'type'  => 'text',
								'title' => esc_html__( 'Extra Class', 'ecome' ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Product Meta box Options      	   -
			// -----------------------------------------
			$options[] = array(
				'id'        => '_custom_product_woo_options',
				'title'     => esc_html__( 'Custom Product Options', 'ecome' ),
				'post_type' => 'product',
				'context'   => 'side',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'product_detail',
						'fields' => array(
							array(
								'id'      => 'product_options',
								'type'    => 'select',
								'title'   => esc_html__( 'Format Product', 'ecome' ),
								'options' => array(
									'video'  => esc_html__( 'Video', 'ecome' ),
									'360deg' => esc_html__( '360 Degree', 'ecome' ),
								),
							),
							array(
								'id'         => 'degree_product_gallery',
								'type'       => 'gallery',
								'title'      => esc_html__( '360 Degree Product', 'ecome' ),
								'dependency' => array( 'product_options', '==', '360deg' ),
							),
							array(
								'id'         => 'video_product_url',
								'type'       => 'upload',
								'title'      => esc_html__( 'Video Url', 'ecome' ),
								'dependency' => array( 'product_options', '==', 'video' ),
							),
						),
					),
				),
			);

			return $options;
		}

		function taxonomy_options( $options )
		{
			$options = array();
			// -----------------------------------------
			// Taxonomy Options                        -
			// -----------------------------------------
			$options[] = array(
				'id'       => '_custom_taxonomy_options',
				'taxonomy' => 'product_cat', // category, post_tag or your custom taxonomy name
				'fields'   => array(
					array(
						'id'      => 'icon_taxonomy',
						'type'    => 'icon',
						'title'   => esc_html__( 'Icon Taxonomy', 'ecome' ),
						'default' => '',
					),
				),
			);

			return $options;
		}
	}

	new Ecome_ThemeOption();
}