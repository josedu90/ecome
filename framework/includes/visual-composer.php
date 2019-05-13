<?php
/**
 * Ecome Visual composer setup
 *
 * @author   KHANH
 * @category API
 * @package  Ecome_Visual_composer
 * @since    1.0.0
 */
if ( !function_exists( 'ecome_custom_param_vc' ) ) {
	add_filter( 'ecome_add_param_visual_composer', 'ecome_custom_param_vc' );
	function ecome_custom_param_vc( $param )
	{
		$attributes_tax = array();
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attributes_tax = wc_get_attribute_taxonomies();
		}
		$attributes = array();
		if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
			foreach ( $attributes_tax as $attribute ) {
				$attributes[$attribute->attribute_label] = $attribute->attribute_name;
			}
		}
		// CUSTOM PRODUCT OPTIONS
		$layoutDir       = get_template_directory() . '/woocommerce/product-styles/';
		$product_options = array();
		if ( is_dir( $layoutDir ) ) {
			$files = scandir( $layoutDir );
			if ( $files && is_array( $files ) ) {
				foreach ( $files as $file ) {
					if ( $file != '.' && $file != '..' ) {
						$fileInfo = pathinfo( $file );
						if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' && $fileInfo['filename'] != 'content-product-list' ) {
							$file_data                   = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
							$file_name                   = str_replace( 'content-product-style-', '', $fileInfo['filename'] );
							$product_options[$file_name] = array(
								'title'   => $file_data['Name'],
								'preview' => get_theme_file_uri( 'woocommerce/product-styles/content-product-style-' . $file_name . '.jpg' ),
							);
						}
					}
				}
			}
		}
		// CUSTOM PRODUCT SIZE
		$product_size_width_list = array();
		$width                   = 300;
		$height                  = 300;
		$crop                    = 1;
		if ( function_exists( 'wc_get_image_size' ) ) {
			$size   = wc_get_image_size( 'shop_catalog' );
			$width  = isset( $size['width'] ) ? $size['width'] : $width;
			$height = isset( $size['height'] ) ? $size['height'] : $height;
			$crop   = isset( $size['crop'] ) ? $size['crop'] : $crop;
		}
		for ( $i = 100; $i < $width; $i = $i + 10 ) {
			array_push( $product_size_width_list, $i );
		}
		$product_size_list                         = array();
		$product_size_list[$width . 'x' . $height] = $width . 'x' . $height;
		foreach ( $product_size_width_list as $k => $w ) {
			$w = intval( $w );
			if ( isset( $width ) && $width > 0 ) {
				$h = round( $height * $w / $width );
			} else {
				$h = $w;
			}
			$product_size_list[$w . 'x' . $h] = $w . 'x' . $h;
		}
		$product_size_list['Custom'] = 'custom';
		/* Map New Custom menu */
		$all_menu = array();
		$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		if ( $menus && count( $menus ) > 0 ) {
			foreach ( $menus as $m ) {
				$all_menu[$m->name] = $m->slug;
			}
		}
		$param['ecome_category']   = array(
			'base'        => 'ecome_category',
			'name'        => esc_html__( 'Ecome: Category', 'ecome' ),
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/diagram.svg',
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Custom Category Product', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'Title shortcode.', 'ecome' ),
					'admin_label' => true,
				),
				array(
					'param_name'  => 'banner',
					'heading'     => esc_html__( 'Banner Category', 'ecome' ),
					'type'        => 'attach_image',
					'admin_label' => true,
				),
				array(
					'type'        => 'taxonomy',
					'heading'     => esc_html__( 'Product Category', 'ecome' ),
					'param_name'  => 'taxonomy',
					'options'     => array(
						'multiple'   => true,
						'hide_empty' => true,
						'taxonomy'   => 'product_cat',
					),
					'placeholder' => esc_html__( 'Choose category', 'ecome' ),
					'description' => esc_html__( 'Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'ecome' ),
				),
				array(
					'param_name' => 'link',
					'heading'    => esc_html__( 'Button', 'ecome' ),
					'type'       => 'vc_link',
				),
			),
		);
		$param['ecome_faqs']       = array(
			'base'        => 'ecome_faqs',
			'name'        => esc_html__( 'Ecome: FAQs', 'ecome' ),
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/question.svg',
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Custom FAQs', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'Title shortcode.', 'ecome' ),
					'std'         => esc_html__( 'Customer questions & answers', 'ecome' ),
					'admin_label' => true,
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button Load', 'ecome' ),
					'param_name' => 'button_load',
					'std'        => esc_html__( 'See more answered questions', 'ecome' ),
				),
				array(
					'type'        => 'number',
					'heading'     => esc_html__( 'FAQs items', 'ecome' ),
					'param_name'  => 'faqs_items',
					'description' => esc_html__( 'Title shortcode.', 'ecome' ),
					'std'         => 8,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( "Order by", 'ecome' ),
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__( 'None', 'ecome' )     => 'none',
						esc_html__( 'ID', 'ecome' )       => 'ID',
						esc_html__( 'Author', 'ecome' )   => 'author',
						esc_html__( 'Name', 'ecome' )     => 'name',
						esc_html__( 'Date', 'ecome' )     => 'date',
						esc_html__( 'Modified', 'ecome' ) => 'modified',
						esc_html__( 'Rand', 'ecome' )     => 'rand',
					),
					'std'         => 'date',
					'description' => esc_html__( "Select how to sort retrieved posts.", 'ecome' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( "Order", 'ecome' ),
					'param_name'  => 'order',
					'value'       => array(
						esc_html__( 'ASC', 'ecome' )  => 'ASC',
						esc_html__( 'DESC', 'ecome' ) => 'DESC',
					),
					'std'         => 'DESC',
					'description' => esc_html__( "Designates the ascending or descending order.", 'ecome' ),
				),
			),
		);
		$param['ecome_heading']    = array(
			'base'        => 'ecome_heading',
			'name'        => esc_html__( 'Ecome: Custom Heading', 'ecome' ),
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/text.svg',
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Custom Heading', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'ecome' ),
					'value'       => array(
						'default' => array(
							'title'   => esc_html__( 'Default', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/default.jpg' ),
						),
						'style1'  => array(
							'title'   => esc_html__( 'Style 01', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style1.jpg' ),
						),
						'style2'  => array(
							'title'   => esc_html__( 'Style 02', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style2.jpg' ),
						),
						'style3'  => array(
							'title'   => esc_html__( 'Style 03', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style3.jpg' ),
						),
						'style4'  => array(
							'title'   => esc_html__( 'Style 04', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style4.jpg' ),
						),
						'style5'  => array(
							'title'   => esc_html__( 'Style 05', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style5.jpg' ),
						),
						'style6'  => array(
							'title'   => esc_html__( 'Style 06', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/heading/style6.jpg' ),
						),
					),
					'default'     => 'default',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'Title shortcode.', 'ecome' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Descriptions', 'ecome' ),
					'param_name'  => 'desc',
					'description' => esc_html__( 'Descriptions of title.', 'ecome' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style3' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Icon library', 'ecome' ),
					'value'       => array(
						esc_html__( 'Font Awesome', 'ecome' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'ecome' )  => 'openiconic',
						esc_html__( 'Typicons', 'ecome' )     => 'typicons',
						esc_html__( 'Entypo', 'ecome' )       => 'entypo',
						esc_html__( 'Linecons', 'ecome' )     => 'linecons',
						esc_html__( 'Mono Social', 'ecome' )  => 'monosocial',
						esc_html__( 'Material', 'ecome' )     => 'material',
						esc_html__( 'Ecome Fonts', 'ecome' )  => 'ecomecustomfonts',
					),
					'admin_label' => true,
					'param_name'  => 'type',
					'description' => esc_html__( 'Select icon library.', 'ecome' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'default', 'style1', 'style2' ),
					),
				),
				array(
					'param_name'  => 'icon_ecomecustomfonts',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
					'type'        => 'iconpicker',
					'settings'    => array(
						'emptyIcon' => false,
						'type'      => 'ecomecustomfonts',
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'ecomecustomfonts',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_fontawesome',
					'value'       => 'fa fa-adjust',
					'settings'    => array(
						'emptyIcon'    => false,
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'fontawesome',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_openiconic',
					'value'       => 'vc-oi vc-oi-dial',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'openiconic',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'openiconic',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_typicons',
					'value'       => 'typcn typcn-adjust-brightness',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'typicons',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'typicons',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => esc_html__( 'Icon', 'ecome' ),
					'param_name' => 'icon_entypo',
					'value'      => 'entypo-icon entypo-icon-note',
					'settings'   => array(
						'emptyIcon'    => false,
						'type'         => 'entypo',
						'iconsPerPage' => 100,
					),
					'dependency' => array(
						'element' => 'type',
						'value'   => 'entypo',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_linecons',
					'value'       => 'vc_li vc_li-heart',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'linecons',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'linecons',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_monosocial',
					'value'       => 'vc-mono vc-mono-fivehundredpx',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'monosocial',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'monosocial',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_material',
					'value'       => 'vc-material vc-material-cake',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'material',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'material',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'Link', 'ecome' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'The Link to Icon', 'ecome' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style2', 'style3', 'style4' ),
					),
				),
			),
		);
		$param['ecome_custommenu'] = array(
			'base'        => 'ecome_custommenu',
			'name'        => esc_html__( 'Ecome: Custom Menu', 'ecome' ),
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/menu.svg',
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Custom Menu', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', 'ecome' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Menu', 'ecome' ),
					'value'       => $all_menu,
					'admin_label' => true,
					'param_name'  => 'nav_menu',
					'description' => esc_html__( 'Select menu to display.', 'ecome' ),
				),
			),
		);
		$param['ecome_iconbox']    = array(
			'base'        => 'ecome_iconbox',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/happiness.svg',
			'name'        => esc_html__( 'Ecome: Icon Box', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Icon Box', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'ecome' ),
					'value'       => array(
						'default' => array(
							'title'   => esc_html__( 'Default', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/default.jpg' ),
						),
						'style1'  => array(
							'title'   => esc_html__( 'Style 01', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style1.jpg' ),
						),
						'style2'  => array(
							'title'   => esc_html__( 'Style 02', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style2.jpg' ),
						),
						'style3'  => array(
							'title'   => esc_html__( 'Style 03', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style3.jpg' ),
						),
						'style4'  => array(
							'title'   => esc_html__( 'Style 04', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style4.jpg' ),
						),
						'style5'  => array(
							'title'   => esc_html__( 'Style 05', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style5.jpg' ),
						),
						'style6'  => array(
							'title'   => esc_html__( 'Style 06', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style6.jpg' ),
						),
						'style7'  => array(
							'title'   => esc_html__( 'Style 07', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/iconbox/style7.jpg' ),
						),
					),
					'default'     => 'default',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'param_name'  => 'label_image',
					'heading'     => esc_html__( 'Label Image', 'ecome' ),
					'type'        => 'attach_image',
					'admin_label' => true,
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style1' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'dependency'  => array(
						'element'            => 'style',
						'value_not_equal_to' => array(
							'style2',
						),
					),
				),
				array(
					'param_name' => 'text_content',
					'heading'    => esc_html__( 'Content', 'ecome' ),
					'type'       => 'textarea',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array(
							'style1',
						),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Icon library', 'ecome' ),
					'value'       => array(
						esc_html__( 'Font Awesome', 'ecome' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'ecome' )  => 'openiconic',
						esc_html__( 'Typicons', 'ecome' )     => 'typicons',
						esc_html__( 'Entypo', 'ecome' )       => 'entypo',
						esc_html__( 'Linecons', 'ecome' )     => 'linecons',
						esc_html__( 'Mono Social', 'ecome' )  => 'monosocial',
						esc_html__( 'Material', 'ecome' )     => 'material',
						esc_html__( 'Ecome Fonts', 'ecome' )  => 'ecomecustomfonts',
					),
					'admin_label' => true,
					'param_name'  => 'type',
					'description' => esc_html__( 'Select icon library.', 'ecome' ),
				),
				array(
					'param_name'  => 'icon_ecomecustomfonts',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
					'type'        => 'iconpicker',
					'settings'    => array(
						'emptyIcon' => false,
						'type'      => 'ecomecustomfonts',
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'ecomecustomfonts',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_fontawesome',
					'value'       => 'fa fa-adjust',
					'settings'    => array(
						'emptyIcon'    => false,
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'fontawesome',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_openiconic',
					'value'       => 'vc-oi vc-oi-dial',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'openiconic',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'openiconic',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_typicons',
					'value'       => 'typcn typcn-adjust-brightness',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'typicons',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'typicons',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => esc_html__( 'Icon', 'ecome' ),
					'param_name' => 'icon_entypo',
					'value'      => 'entypo-icon entypo-icon-note',
					'settings'   => array(
						'emptyIcon'    => false,
						'type'         => 'entypo',
						'iconsPerPage' => 100,
					),
					'dependency' => array(
						'element' => 'type',
						'value'   => 'entypo',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_linecons',
					'value'       => 'vc_li vc_li-heart',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'linecons',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'linecons',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_monosocial',
					'value'       => 'vc-mono vc-mono-fivehundredpx',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'monosocial',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'monosocial',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'ecome' ),
					'param_name'  => 'icon_material',
					'value'       => 'vc-material vc-material-cake',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'material',
						'iconsPerPage' => 100,
					),
					'dependency'  => array(
						'element' => 'type',
						'value'   => 'material',
					),
					'description' => esc_html__( 'Select icon from library.', 'ecome' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'Link', 'ecome' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'The Link to Icon', 'ecome' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'default', 'style1' ),
					),
				),
			),
		);
		/*Section Team*/
		$param['ecome_member']   = array(
			'base'        => 'ecome_member',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/happiness.svg',
			'name'        => esc_html__( 'Ecome: Member', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display member info', 'ecome' ),
			'params'      => array(
				array(
					'param_name'  => 'avatar_member',
					'heading'     => esc_html__( 'Avatar Member', 'ecome' ),
					'type'        => 'attach_image',
					'admin_label' => true,
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Member Name", "ecome" ),
					"param_name"  => "name",
					"description" => esc_html__( "Add name member.", "ecome" ),
				),
				array(
					"type"       => "textfield",
					"heading"    => esc_html__( "Member Postion", "ecome" ),
					"param_name" => "position",
				),
				array(
					"type"       => "textarea",
					"heading"    => esc_html__( "Member Descriptions", "ecome" ),
					"param_name" => "desc",
				),
			),
		);
		$param['ecome_map']      = array(
			'base'        => 'ecome_map',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/google.svg',
			'name'        => esc_html__( 'Ecome: Google Map', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Google Map', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'admin_label' => true,
					'description' => esc_html__( 'title.', 'ecome' ),
					'std'         => 'Ecome',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Phone', 'ecome' ),
					'param_name'  => 'phone',
					'description' => esc_html__( 'phone.', 'ecome' ),
					'std'         => '088-465 9965 02',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Email', 'ecome' ),
					'param_name'  => 'email',
					'description' => esc_html__( 'email.', 'ecome' ),
					'std'         => 'zankover@gmail.com',
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Map Height', 'ecome' ),
					'param_name' => 'map_height',
					'std'        => '400',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Maps type', 'ecome' ),
					'param_name' => 'map_type',
					'value'      => array(
						esc_html__( 'ROADMAP', 'ecome' )   => 'ROADMAP',
						esc_html__( 'SATELLITE', 'ecome' ) => 'SATELLITE',
						esc_html__( 'HYBRID', 'ecome' )    => 'HYBRID',
						esc_html__( 'TERRAIN', 'ecome' )   => 'TERRAIN',
					),
					'std'        => 'ROADMAP',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Address', 'ecome' ),
					'param_name'  => 'address',
					'admin_label' => true,
					'description' => esc_html__( 'address.', 'ecome' ),
					'std'         => 'Z115 TP. Thai Nguyen',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Longitude', 'ecome' ),
					'param_name'  => 'longitude',
					'admin_label' => true,
					'description' => esc_html__( 'longitude.', 'ecome' ),
					'std'         => '105.800286',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Latitude', 'ecome' ),
					'param_name'  => 'latitude',
					'admin_label' => true,
					'description' => esc_html__( 'latitude.', 'ecome' ),
					'std'         => '21.587001',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Zoom', 'ecome' ),
					'param_name'  => 'zoom',
					'admin_label' => true,
					'description' => esc_html__( 'zoom.', 'ecome' ),
					'std'         => '14',
				),
			),
		);
		$param['ecome_tabs']     = array(
			'base'                    => 'ecome_tabs',
			'icon'                    => ECOME_FRAMEWORK_URI . 'assets/images/tab.svg',
			'name'                    => esc_html__( 'Ecome: Tabs', 'ecome' ),
			'category'                => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description'             => esc_html__( 'Display Tabs', 'ecome' ),
			'is_container'            => true,
			'show_settings_on_create' => false,
			'as_parent'               => array(
				'only' => 'vc_tta_section',
			),
			'params'                  => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Select style', 'ecome' ),
					'value'       => array(
						'default' => array(
							'title'   => esc_html__( 'Tabs Horizontal', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/default.jpg' ),
						),
						'style1'  => array(
							'title'   => esc_html__( 'Tabs Vertical', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style1.jpg' ),
						),
						'style2'  => array(
							'title'   => esc_html__( 'Tabs Style 2', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style2.jpg' ),
						),
						'style3'  => array(
							'title'   => esc_html__( 'Tabs Style 3', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style3.jpg' ),
						),
						'style4'  => array(
							'title'   => esc_html__( 'Tabs Style 4', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/tabs/style4.jpg' ),
						),
					),
					'default'     => 'default',
					'admin_label' => true,
					'param_name'  => 'style',
				),
				array(
					'param_name' => 'tab_align',
					'heading'    => esc_html__( 'Tabs Align', 'ecome' ),
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Left', 'ecome' )   => 'left',
						esc_html__( 'Right', 'ecome' )  => 'right',
						esc_html__( 'Center', 'ecome' ) => 'center',
					),
					'std'        => 'right',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'default' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'tab_title',
					'description' => esc_html__( 'The title of shortcode', 'ecome' ),
					'admin_label' => true,
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style2' ),
					),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'Link', 'ecome' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'The Link to Icon', 'ecome' ),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'style3' ),
					),
				),
				vc_map_add_css_animation(),
				array(
					'param_name' => 'ajax_check',
					'heading'    => esc_html__( 'Using Ajax Tabs', 'ecome' ),
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'ecome' ) => '1',
						esc_html__( 'No', 'ecome' )  => '0',
					),
					'std'        => '0',
				),
				array(
					'param_name' => 'using_loop',
					'heading'    => esc_html__( 'Using Loop', 'ecome' ),
					'type'       => 'dropdown',
					'value'      => array(
						esc_html__( 'Yes', 'ecome' ) => '1',
						esc_html__( 'No', 'ecome' )  => '0',
					),
					'std'        => '1',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'style4' ),
					),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Active Section', 'ecome' ),
					'param_name' => 'active_section',
					'std'        => 0,
				),
			),
			'js_view'                 => 'VcBackendTtaTabsView',
			'custom_markup'           => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
				. '<ul class="vc_tta-tabs-list">'
				. '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
				. '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
			'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'ecome' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'ecome' ), 2 ) . '"][/vc_tta_section]
                    ',
			'admin_enqueue_js'        => array(
				vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
			),
		);
		$param['ecome_products'] = array(
			'base'        => 'ecome_products',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/shopping-bag.svg',
			'name'        => esc_html__( 'Ecome: Products', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Products', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Product Attribute', 'ecome' ),
					'param_name'  => 'product_attribute',
					'value'       => $attributes,
					'description' => esc_html__( 'Select a Attribute for product', 'ecome' ),
					'dependency'  => array( 'element' => 'product_style', 'value' => array( '2' ) ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Product List style', 'ecome' ),
					'param_name'  => 'productsliststyle',
					'value'       => array(
						esc_html__( 'Grid Bootstrap', 'ecome' ) => 'grid',
						esc_html__( 'Owl Carousel', 'ecome' )   => 'owl',
					),
					'description' => esc_html__( 'Select a style for list', 'ecome' ),
					'std'         => 'grid',
				),
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Product style', 'ecome' ),
					'value'       => $product_options,
					'default'     => '1',
					'admin_label' => true,
					'param_name'  => 'product_style',
					'description' => esc_html__( 'Select a style for product item', 'ecome' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Image size', 'ecome' ),
					'param_name'  => 'product_image_size',
					'value'       => $product_size_list,
					'description' => esc_html__( 'Select a size for product', 'ecome' ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Width', 'ecome' ),
					'param_name' => 'product_custom_thumb_width',
					'value'      => $width,
					'suffix'     => esc_html__( 'px', 'ecome' ),
					'dependency' => array( 'element' => 'product_image_size', 'value' => array( 'custom' ) ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Height', 'ecome' ),
					'param_name' => 'product_custom_thumb_height',
					'value'      => $height,
					'suffix'     => esc_html__( 'px', 'ecome' ),
					'dependency' => array( 'element' => 'product_image_size', 'value' => array( 'custom' ) ),
				),
				/* Products */
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Enable Load More', 'ecome' ),
					'param_name' => 'loadmore',
					'value'      => array(
						esc_html__( 'Enable', 'ecome' )  => 'enable',
						esc_html__( 'Disable', 'ecome' ) => 'disable',
					),
					'std'        => 'disable',
					'group'      => esc_html__( 'Products options', 'ecome' ),
				),
				array(
					'type'        => 'taxonomy',
					'heading'     => esc_html__( 'Product Category', 'ecome' ),
					'param_name'  => 'taxonomy',
					'options'     => array(
						'multiple'   => true,
						'hide_empty' => true,
						'taxonomy'   => 'product_cat',
					),
					'placeholder' => esc_html__( 'Choose category', 'ecome' ),
					'description' => esc_html__( 'Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'ecome' ),
					'group'       => esc_html__( 'Products options', 'ecome' ),
					'dependency'  => array( 'element' => 'target', 'value' => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'on_new' ) ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Target', 'ecome' ),
					'param_name'  => 'target',
					'value'       => array(
						esc_html__( 'Best Selling Products', 'ecome' ) => 'best-selling',
						esc_html__( 'Top Rated Products', 'ecome' )    => 'top-rated',
						esc_html__( 'Recent Products', 'ecome' )       => 'recent-product',
						esc_html__( 'Product Category', 'ecome' )      => 'product-category',
						esc_html__( 'Products', 'ecome' )              => 'products',
						esc_html__( 'Featured Products', 'ecome' )     => 'featured_products',
						esc_html__( 'On Sale', 'ecome' )               => 'on_sale',
						esc_html__( 'On New', 'ecome' )                => 'on_new',
					),
					'description' => esc_html__( 'Choose the target to filter products', 'ecome' ),
					'std'         => 'recent-product',
					'group'       => esc_html__( 'Products options', 'ecome' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Order by', 'ecome' ),
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__( 'Date', 'ecome' )          => 'date',
						esc_html__( 'ID', 'ecome' )            => 'ID',
						esc_html__( 'Author', 'ecome' )        => 'author',
						esc_html__( 'Title', 'ecome' )         => 'title',
						esc_html__( 'Modified', 'ecome' )      => 'modified',
						esc_html__( 'Random', 'ecome' )        => 'rand',
						esc_html__( 'Comment count', 'ecome' ) => 'comment_count',
						esc_html__( 'Menu order', 'ecome' )    => 'menu_order',
						esc_html__( 'Sale price', 'ecome' )    => '_sale_price',
					),
					'std'         => 'date',
					'description' => esc_html__( 'Select how to sort.', 'ecome' ),
					'dependency'  => array( 'element' => 'target', 'value' => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute' ) ),
					'group'       => esc_html__( 'Products options', 'ecome' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Order', 'ecome' ),
					'param_name'  => 'order',
					'value'       => array(
						esc_html__( 'ASC', 'ecome' )  => 'ASC',
						esc_html__( 'DESC', 'ecome' ) => 'DESC',
					),
					'std'         => 'DESC',
					'description' => esc_html__( 'Designates the ascending or descending order.', 'ecome' ),
					'dependency'  => array( 'element' => 'target', 'value' => array( 'top-rated', 'recent-product', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute' ) ),
					'group'       => esc_html__( 'Products options', 'ecome' ),
				),
				array(
					'type'       => 'number',
					'heading'    => esc_html__( 'Product per page', 'ecome' ),
					'param_name' => 'per_page',
					'value'      => 6,
					'dependency' => array( 'element' => 'target', 'value' => array( 'best-selling', 'top-rated', 'recent-product', 'product-category', 'featured_products', 'product_attribute', 'on_sale', 'on_new' ) ),
					'group'      => esc_html__( 'Products options', 'ecome' ),
				),
				array(
					'type'        => 'autocomplete',
					'heading'     => esc_html__( 'Products', 'ecome' ),
					'param_name'  => 'ids',
					'settings'    => array(
						'multiple'      => true,
						'sortable'      => true,
						'unique_values' => true,
					),
					'save_always' => true,
					'description' => esc_html__( 'Enter List of Products', 'ecome' ),
					'dependency'  => array( 'element' => 'target', 'value' => array( 'products' ) ),
					'group'       => esc_html__( 'Products options', 'ecome' ),
				),
			),
		);
		$param['ecome_slide']    = array(
			'base'                    => 'ecome_slide',
			'icon'                    => ECOME_FRAMEWORK_URI . 'assets/images/slider.svg',
			'name'                    => esc_html__( 'Ecome: Slide', 'ecome' ),
			'category'                => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description'             => esc_html__( 'Display Slide', 'ecome' ),
			'as_parent'               => array(
				'only' => 'vc_single_image, vc_custom_heading, ecome_person, vc_column_text, ecome_iconbox, ecome_category, ecome_socials, vc_row',
			),
			'content_element'         => true,
			'show_settings_on_create' => true,
			'js_view'                 => 'VcColumnView',
			'params'                  => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'slider_title',
					'admin_label' => true,
				),
			),
		);
		/* Map New blog */
		$categories_array = array(
			esc_html__( 'All', 'ecome' ) => '',
		);
		$args             = array();
		$categories       = get_categories( $args );
		foreach ( $categories as $category ) {
			$categories_array[$category->name] = $category->slug;
		}
		$param['ecome_blog'] = array(
			'base'        => 'ecome_blog',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/blogger.svg',
			'name'        => esc_html__( 'Ecome: Blog', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Blog', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'loop',
					'heading'     => esc_html__( 'Option Query', 'ecome' ),
					'param_name'  => 'loop',
					'save_always' => true,
					'value'       => 'size:10|order_by:date',
					'settings'    => array(
						'size'     => array(
							'hidden' => false,
							'value'  => 10,
						),
						'order_by' => array( 'value' => 'date' ),
					),
					'description' => esc_html__( 'Create WordPress loop, to populate content from your site.', 'ecome' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'blog_title',
					'description' => esc_html__( 'The title of shortcode', 'ecome' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Blog style', 'ecome' ),
					'value'       => array(
						'style-1' => array(
							'title'   => esc_html__( 'Style 01', 'ecome' ),
							'preview' => get_theme_file_uri( 'assets/images/shortcode-preview/blog/default.jpg' ),
						),
					),
					'default'     => 'style-1',
					'admin_label' => true,
					'param_name'  => 'blog_style',
					'description' => esc_html__( 'Select a style for blog item', 'ecome' ),
				),
			),
		);
		$socials             = array();
		$all_socials         = Ecome_Functions::ecome_get_option( 'user_all_social' );
		if ( !empty( $all_socials ) ) {
			foreach ( $all_socials as $key => $social ) {
				$socials[$social['title_social']] = $key;
			}
		}
		$param['ecome_socials']    = array(
			'base'        => 'ecome_socials',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/share.svg',
			'name'        => esc_html__( 'Ecome: Socials', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Socials', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'List Social', 'ecome' ),
					'param_name' => 'socials',
					'value'      => $socials,
				),
			),
		);
		$param['ecome_newsletter'] = array(
			'base'        => 'ecome_newsletter',
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/newsletter.svg',
			'name'        => esc_html__( 'Ecome: Newsletter', 'ecome' ),
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Newsletter', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'ecome' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'param_name' => 'desc',
					'heading'    => esc_html__( 'Descriptions', 'ecome' ),
					'type'       => 'textarea',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Placeholder text', 'ecome' ),
					'param_name' => 'placeholder_text',
					'std'        => esc_html__( 'Enter your email address', 'ecome' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button text', 'ecome' ),
					'std'        => esc_html__( 'Subscribe', 'ecome' ),
					'param_name' => 'button_text',
				),
			),
		);
		/* GET PINMAP */
		$args_pm        = array(
			'post_type'      => 'ecome_mapper',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);
		$pinmap_loop    = new wp_query( $args_pm );
		$pinmap_options = array();
		while ( $pinmap_loop->have_posts() ) {
			$pinmap_loop->the_post();
			$attachment_id                = get_post_meta( get_the_ID(), 'ecome_mapper_image', true );
			$pinmap_options[get_the_ID()] = array(
				'title'   => get_the_title(),
				'preview' => wp_get_attachment_image_url( $attachment_id, 'medium' ),
			);
		}
		$param['ecome_pinmapper'] = array(
			'base'        => 'ecome_pinmapper',
			'name'        => esc_html__( 'Ecome: Pin Map', 'ecome' ),
			'icon'        => ECOME_FRAMEWORK_URI . 'assets/images/push-pin.svg',
			'category'    => esc_html__( 'Ecome Shortcode', 'ecome' ),
			'description' => esc_html__( 'Display Pin Map', 'ecome' ),
			'params'      => array(
				array(
					'type'        => 'select_preview',
					'heading'     => esc_html__( 'Pinmaper style', 'ecome' ),
					'value'       => $pinmap_options,
					'admin_label' => true,
					'param_name'  => 'pinmaper_style',
					'description' => esc_html__( 'Select a style for pinmaper item', 'ecome' ),
				),
			),
		);

		return $param;
	}
}