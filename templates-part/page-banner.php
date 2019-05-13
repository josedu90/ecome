<?php
/* Data MetaBox */
$data_meta                    = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
$ecome_metabox_enable_banner  = isset( $data_meta['ecome_metabox_enable_banner'] ) ? $data_meta['ecome_metabox_enable_banner'] : 0;
$ecome_page_header_background = isset( $data_meta['bg_banner_page'] ) ? $data_meta['bg_banner_page'] : '';
$ecome_page_heading_height    = isset( $data_meta['height_banner'] ) ? $data_meta['height_banner'] : '';
$ecome_page_margin_top        = isset( $data_meta['page_margin_top'] ) ? $data_meta['page_margin_top'] : '';
$ecome_page_margin_bottom     = isset( $data_meta['page_margin_bottom'] ) ? $data_meta['page_margin_bottom'] : '';
$css                          = '';
if ( $ecome_metabox_enable_banner != 1 ) {
	return;
}
if ( $ecome_page_header_background != "" ) {
	$css .= 'background-image:  url("' . esc_url( $ecome_page_header_background['image'] ) . '");';
	$css .= 'background-repeat: ' . esc_attr( $ecome_page_header_background['repeat'] ) . ';';
	$css .= 'background-position:   ' . esc_attr( $ecome_page_header_background['position'] ) . ';';
	$css .= 'background-attachment: ' . esc_attr( $ecome_page_header_background['attachment'] ) . ';';
	$css .= 'background-size:   ' . esc_attr( $ecome_page_header_background['size'] ) . ';';
	$css .= 'background-color:  ' . esc_attr( $ecome_page_header_background['color'] ) . ';';
}
if ( $ecome_page_heading_height != "" ) {
	$css .= 'min-height:' . $ecome_page_heading_height . 'px;';
}
if ( $ecome_page_margin_top != "" ) {
	$css .= 'margin-top:' . $ecome_page_margin_top . 'px;';
}
if ( $ecome_page_margin_bottom != "" ) {
	$css .= 'margin-bottom:' . $ecome_page_margin_bottom . 'px;';
}
?>
<!-- Banner page -->
<div class="container">
    <div class="inner-page-banner" style='<?php echo esc_attr( $css ); ?>'></div>
	<?php
	if ( !is_front_page() ) {
		$args = array(
			'container'     => 'div',
			'before'        => '',
			'after'         => '',
			'show_on_front' => true,
			'network'       => false,
			'show_title'    => true,
			'show_browse'   => false,
			'post_taxonomy' => array(),
			'labels'        => array(),
			'echo'          => true,
		);
		do_action( 'ecome_breadcrumb', $args );
	}
	?>
    <h1 class="page-title"><?php single_post_title(); ?></h1>
</div>
<!-- /Banner page -->