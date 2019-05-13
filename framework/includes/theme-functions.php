<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 *
 * HOOK FOOTER
 */
add_action( 'ecome_footer', 'ecome_footer_content', 10 );
/**
 *
 * HOOK HEADER
 */
add_action( 'ecome_header', 'ecome_header_content', 10 );
/**
 *
 * HOOK BLOG META
 */
/* POST INFO */
add_action( 'ecome_post_info_content', 'ecome_post_title', 10 );
add_action( 'ecome_post_info_content', 'ecome_post_content', 20 );
add_action( 'ecome_post_info_content', 'ecome_post_author', 30 );
/**
 *
 * HOOK BLOG GRID
 */
add_action( 'ecome_after_blog_content', 'ecome_paging_nav', 10 );
add_action( 'ecome_post_content', 'ecome_post_thumbnail', 10 );
add_action( 'ecome_post_content', 'ecome_post_info', 20 );
/**
 *
 * HOOK BLOG SINGLE
 */
add_action( 'ecome_single_post_content', 'ecome_post_thumbnail', 10 );
add_action( 'ecome_single_post_content', 'ecome_post_info', 20 );
add_action( 'ecome_single_post_bottom_content', 'ecome_post_single_author', 30 );
/**
 *
 * HOOK TEMPLATE
 */
add_filter( 'wp_nav_menu_items', 'ecome_menu_detailing', 10, 2 );
add_filter( 'wp_nav_menu_items', 'ecome_top_right_menu', 10, 2 );
/**
 *
 * HOOK AJAX
 */
add_filter( 'wcml_multi_currency_ajax_actions', 'ecome_add_action_to_multi_currency_ajax', 10, 1 );
add_action( 'wp_ajax_ecome_ajax_tabs', 'ecome_ajax_tabs' );
add_action( 'wp_ajax_nopriv_ecome_ajax_tabs', 'ecome_ajax_tabs' );
/**
 *
 * HOOK AJAX
 */
add_action( 'wp_ajax_ecome_ajax_loadmore', 'ecome_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_ecome_ajax_loadmore', 'ecome_ajax_loadmore' );
add_action( 'wp_ajax_ecome_ajax_faqs_loadmore', 'ecome_ajax_faqs_loadmore' );
add_action( 'wp_ajax_nopriv_ecome_ajax_faqs_loadmore', 'ecome_ajax_faqs_loadmore' );
?>
<?php
/**
 *
 * HOOK TEMPLATE FUNCTIONS
 */
if ( !function_exists( 'ecome_get_logo' ) ) {
	function ecome_get_logo()
	{
		$logo_url = get_theme_file_uri( '/assets/images/logo.png' );
		$logo     = Ecome_Functions::ecome_get_option( 'ecome_logo' );
		if ( $logo != '' ) {
			$logo_url = wp_get_attachment_image_url( $logo, 'full' );
		}
		$html = '<a href="' . esc_url( home_url( '/' ) ) . '"><img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="_rw" /></a>';
		echo apply_filters( 'ecome_site_logo', $html );
	}
}
if ( !function_exists( 'ecome_set_post_views' ) ) {
	function ecome_set_post_views( $postID )
	{
		if ( get_post_type( $postID ) == 'post' ) {
			$count_key = 'ecome_post_views_count';
			$count     = get_post_meta( $postID, $count_key, true );
			if ( $count == '' ) {
				$count = 0;
				delete_post_meta( $postID, $count_key );
				add_post_meta( $postID, $count_key, '0' );
			} else {
				$count++;
				update_post_meta( $postID, $count_key, $count );
			}
		}
	}
}
if ( !function_exists( 'ecome_get_post_views' ) ) {
	function ecome_get_post_views( $postID )
	{
		$count_key = 'ecome_post_views_count';
		$count     = get_post_meta( $postID, $count_key, true );
		if ( $count == '' ) {
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
			echo 0;
		}
		echo esc_html( $count );
	}
}
if ( !function_exists( 'ecome_detected_shortcode' ) ) {
	function ecome_detected_shortcode( $id, $tab_id = null, $product_id = null )
	{
		$post              = get_post( $id );
		$content           = preg_replace( '/\s+/', ' ', $post->post_content );
		$shortcode_section = '';
		if ( $tab_id == null ) {
			$out = array();
			preg_match_all( '/\[ecome_products(.*?)\]/', $content, $matches );
			if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
				foreach ( $matches[0] as $key => $value ) {
					if ( shortcode_parse_atts( $matches[1][$key] )['products_custom_id'] == $product_id ) {
						$out['atts']    = shortcode_parse_atts( $matches[1][$key] );
						$out['content'] = $value;
					}
				}
			}
			$shortcode_section = $out;
		}
		if ( $product_id == null ) {
			preg_match_all( '/\[vc_tta_section(.*?)vc_tta_section\]/', $content, $matches );
			if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
				foreach ( $matches[0] as $key => $value ) {
					preg_match_all( '/tab_id="([^"]+)"/', $matches[0][$key], $matches_ids );
					foreach ( $matches_ids[1] as $matches_id ) {
						if ( $tab_id == $matches_id ) {
							$shortcode_section = $value;
						}
					}
				}
			}
		}

		return $shortcode_section;
	}
}
if ( !function_exists( 'ecome_add_action_to_multi_currency_ajax' ) ) {
	function ecome_add_action_to_multi_currency_ajax( $ajax_actions )
	{
		$ajax_actions[] = 'ecome_ajax_tabs'; // Add a AJAX action to the array

		return $ajax_actions;
	}
}
if ( !function_exists( 'ecome_ajax_tabs' ) ) {
	function ecome_ajax_tabs()
	{
		$response   = array(
			'html'    => '',
			'message' => '',
			'success' => 'no',
		);
		$section_id = isset( $_POST['section_id'] ) ? $_POST['section_id'] : '';
		$id         = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$shortcode  = ecome_detected_shortcode( $id, $section_id, null );
		WPBMap::addAllMappedShortcodes();
		$response['html']    = wpb_js_remove_wpautop( $shortcode );
		$response['success'] = 'ok';
		wp_send_json( $response );
		die();
	}
}
if ( !function_exists( 'ecome_menu_detailing' ) ) {
	function ecome_menu_detailing( $items, $args )
	{
		if ( $args->theme_location == 'primary' ) {
			$ecome_block_detailing = Ecome_Functions::ecome_get_option( 'ecome_block_detailing', '' );
			$content               = '';
			ob_start();
			$content .= $items;
			ob_start();
			if ( $ecome_block_detailing != '' ) : ?>
                <li class="menu-item block-detailing">
                    <p><?php echo wp_specialchars_decode( $ecome_block_detailing ); ?></p>
                </li>
			<?php endif;
			$content .= ob_get_clean();
			$items   = $content;
		}

		return $items;
	}
}
if ( !function_exists( 'ecome_header_language' ) ) {
	function ecome_header_language()
	{
		$current_language = '';
		$list_language    = '';
		$languages        = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
		if ( !empty( $languages ) ) {
			foreach ( $languages as $l ) {
				if ( !$l['active'] ) {
					$list_language .= '
						<li class="menu-item">
                            <a href="' . esc_url( $l['url'] ) . '">
                                <img src="' . esc_url( $l['country_flag_url'] ) . '" height="12"
                                     alt="' . esc_attr( $l['language_code'] ) . '" width="18"/>
								' . esc_html( $l['native_name'] ) . '
                            </a>
                        </li>';
				} else {
					$current_language = '
						<a href="' . esc_url( $l['url'] ) . '" data-ecome="ecome-dropdown">
                            <img src="' . esc_url( $l['country_flag_url'] ) . '" height="12"
                                 alt="' . esc_attr( $l['language_code'] ) . '" width="18"/>
							' . esc_html( $l['native_name'] ) . '
                        </a>
                        <span class="toggle-submenu"></span>';
				}
			}
			$menu_language = '
                 <li class="menu-item ecome-dropdown block-language">
                    ' . $current_language . '
                    <ul class="sub-menu">
                        ' . $list_language . '
                    </ul>
                </li>';
			echo wp_specialchars_decode( $menu_language );
			echo '<li class="menu-item">';
			do_action( 'wcml_currency_switcher', array( 'format' => '%code%', 'switcher_style' => 'wcml-dropdown' ) );
			echo '</li>';
		}
	}
}
if ( !function_exists( 'ecome_top_right_menu' ) ) {
	function ecome_top_right_menu( $items, $args )
	{
		if ( $args->theme_location == 'top_right_menu' ) {
			$content = '';
			$content .= $items;
			ob_start();
			ecome_header_language();
			$content .= ob_get_clean();
			$items   = $content;
		}

		return $items;
	}
}
/**
 *
 * TEMPLATE BLOG
 */
if ( !function_exists( 'ecome_paging_nav' ) ) {
	function ecome_paging_nav()
	{
		global $wp_query;
		$max = $wp_query->max_num_pages;
		// Don't print empty markup if there's only one page.
		if ( $max >= 2 ) {
			echo get_the_posts_pagination( array(
					'screen_reader_text' => '&nbsp;',
					'before_page_number' => '',
					'prev_text'          => esc_html__( 'Prev', 'ecome' ),
					'next_text'          => esc_html__( 'Next', 'ecome' ),
				)
			);
		}
	}
}
if ( !function_exists( 'ecome_post_single_author' ) ) {
	function ecome_post_single_author()
	{
		$enable_author_info = Ecome_Functions::ecome_get_option( 'enable_author_info' );
		if ( $enable_author_info == 1 ):
			?>
            <div class="post-single-author">
                <figure class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?></figure>
                <div class="author-info">
                    <h4 class="name"><?php the_author(); ?></h4>
                    <p class="desc">
						<?php the_author_meta( 'description' ); ?>
                    </p>
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
						<?php echo esc_html__( 'All author posts', 'ecome' ); ?>
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
		<?php
		endif;
	}
}
if ( !function_exists( 'ecome_post_thumbnail' ) ) {
	function ecome_post_thumbnail()
	{
		$ecome_blog_style = Ecome_Functions::ecome_get_option( 'ecome_blog_list_style', 'standard' );
		$ecome_post_meta  = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
		$gallery_post     = isset( $ecome_post_meta['gallery_post'] ) ? $ecome_post_meta['gallery_post'] : '';
		$video_post       = isset( $ecome_post_meta['video_post'] ) ? $ecome_post_meta['video_post'] : '';
		$quote_post       = isset( $ecome_post_meta['quote_post'] ) ? $ecome_post_meta['quote_post'] : '';
		$post_format      = get_post_format();
		$class            = 'post-thumb';
		$check            = false;
		if ( $gallery_post != '' && $post_format == 'gallery' )
			$check = true;
		if ( $video_post != '' && $post_format == 'video' )
			$check = true;
		if ( $quote_post != '' && $post_format == 'quote' )
			$check = true;
		if ( $ecome_blog_style != 'grid' ) {
			$width  = false;
			$height = false;
		} else {
			$width  = 442;
			$height = 328;
		}
		if ( has_post_thumbnail() ) :
			?>
            <div class="<?php echo esc_attr( $class ); ?>">
				<?php
				if ( $check == true && $ecome_blog_style != 'grid' ) {
					if ( $post_format == 'gallery' ) :
						$gallery_post = explode( ',', $gallery_post );
						?>
                        <div class="owl-slick"
                             data-slick='{"arrows": false, "dots": true, "infinite": false, "slidesToShow": 1}'>
                            <figure>
								<?php
								$image_thumb = apply_filters( 'ecome_resize_image', get_post_thumbnail_id(), $width, $height, true, true );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </figure>
							<?php foreach ( $gallery_post as $item ) : ?>
                                <figure>
									<?php
									$image_gallery = apply_filters( 'ecome_resize_image', $item, $width, $height, true, true );
									echo wp_specialchars_decode( $image_gallery['img'] );
									?>
                                </figure>
							<?php endforeach; ?>
                        </div>
					<?php endif;
					if ( $post_format == 'quote' ) {
						echo '<p class="quote">' . wp_specialchars_decode( $quote_post ) . '</p>';
					}
					if ( $post_format == 'video' ) {
						the_widget( 'WP_Widget_Media_Video', 'url=' . $video_post . '' );
					}
				} else {
					if ( is_single() ) {
						the_post_thumbnail( 'full' );
					} else {
						$image_thumb = apply_filters( 'ecome_resize_image', get_post_thumbnail_id(), $width, $height, true, true );
						echo '<a href="' . get_permalink() . '">';
						echo wp_specialchars_decode( $image_thumb['img'] );
						echo '</a>';
					}
				}
				?>
                <div class="post-date">
                    <span class="date"><?php echo get_the_date( 'd' ); ?></span>
                    <span class="month"><?php echo get_the_date( 'M' ); ?></span>
                </div>
            </div>
		<?php
		endif;
	}
}
if ( !function_exists( 'ecome_callback_comment' ) ) {
	/**
	 * Ecome comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args the comment args.
	 * @param int $depth the comment depth.
	 * @since 1.0.0
	 */
	function ecome_callback_comment( $comment, $args, $depth )
	{
		if ( 'div' == $args['style'] ) {
			$tag       = 'div ';
			$add_below = 'comment';
		} else {
			$tag       = 'li ';
			$add_below = 'div-comment';
		}
		?>
        <<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php echo get_comment_ID(); ?>">
        <div class="comment_container">
            <div class="comment-avatar">
				<?php echo get_avatar( $comment, 60 ); ?>
            </div>
            <div class="comment-text commentmetadata">
                <div class="comment-author vcard">
					<?php printf( wp_kses_post( '%s', 'ecome' ), get_comment_author_link() ); ?>
                </div>
				<?php if ( '0' == $comment->comment_approved ) : ?>
                    <em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'ecome' ); ?></em>
                    <br/>
				<?php endif; ?>
                <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( get_comment_ID() ) ) ); ?>"
                   class="comment-date">
					<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
                </a>
				<?php edit_comment_link( __( 'Edit', 'ecome' ), '  ', '' ); ?>
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				<?php echo ( 'div' != $args['style'] ) ? '<div id="div-comment-' . get_comment_ID() . '" class="comment-content">' : '' ?>
				<?php comment_text(); ?>
				<?php echo 'div' != $args['style'] ? '</div>' : ''; ?>
            </div>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_post_title' ) ) {
	function ecome_post_title()
	{
		?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php
	}
}
if ( !function_exists( 'ecome_post_content' ) ) {
	function ecome_post_content()
	{
		$ecome_blog_style = Ecome_Functions::ecome_get_option( 'ecome_blog_list_style', 'standard' );
		if ( $ecome_blog_style == 'grid' && !is_single()):
			?>
            <div class="post-content">
				<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 15, esc_html__( '...', 'ecome' ) ); ?>
            </div>
		<?php else: ?>
            <div class="post-content">
				<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
						esc_html__( 'Continue reading %s', 'ecome' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					)
				);
				wp_link_pages( array(
						'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'ecome' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					)
				);
				?>
            </div>
		<?php
		endif;
	}
}
if ( !function_exists( 'ecome_post_single_content' ) ) {
	function ecome_post_single_content()
	{
		?>
        <div class="post-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
					esc_html__( 'Continue reading %s', 'ecome' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				)
			);
			wp_link_pages( array(
					'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'ecome' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				)
			);
			?>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_post_sticky' ) ) {
	function ecome_post_sticky()
	{
		if ( is_sticky() ) : ?>
            <li class="sticky-post"><i class="fa fa-flag"></i>
				<?php echo esc_html__( ' Sticky', 'ecome' ); ?>
            </li>
		<?php endif;
	}
}
if ( !function_exists( 'ecome_post_calendar' ) ) {
	function ecome_post_calendar()
	{
		ecome_post_tags();
		?>
        <div class="post-meta">
            <ul class="info-meta">
                <li class="date">
                    <span><?php echo esc_html__( 'Posted on ', 'ecome' ) ?></span>
                    <a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
                </li>
				<?php $get_term_cat = get_the_terms( get_the_ID(), 'category' );
				if ( !is_wp_error( $get_term_cat ) && !empty( $get_term_cat ) ) : ?>
                    <li class="category">
						<?php
						echo esc_html__( 'Categories: ', 'ecome' );
						the_category( ', ' );
						?>
                    </li>
				<?php endif; ?>
            </ul>
            <div class="comment">
                <span class="fa fa-commenting"></span>
				<?php
				comments_number(
					esc_html__( '0', 'ecome' ),
					esc_html__( '1', 'ecome' ),
					esc_html__( '%', 'ecome' )
				);
				?>
            </div>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_post_author' ) ) {
	function ecome_post_author()
	{
		?>
        <div class="post-meta clearfix">
            <div class="author">
                <span><?php echo esc_html__( 'Post by ', 'ecome' ) ?></span>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
					<?php the_author() ?>
                </a>
            </div>
            <div class="comment">
                <span class="fa fa-commenting"></span>
				<?php
				comments_number(
					esc_html__( '0', 'ecome' ),
					esc_html__( '1', 'ecome' ),
					esc_html__( '%', 'ecome' )
				);
				?>
            </div>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_share_button' ) ) {
	function ecome_share_button( $post_id )
	{
		$share_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
		$share_link_url        = get_permalink( $post_id );
		$share_link_title      = get_the_title();
		$share_twitter_summary = get_the_excerpt();
		?>
        <div class="ecome-share-socials">
            <a target="_blank" class="facebook"
               href="https://www.facebook.com/sharer.php?s=100&amp;p%5Btitle%5D=<?php echo esc_html( $share_link_title ); ?>&amp;p%5Burl%5D=<?php echo urlencode( $share_link_url ); ?>"
               title="<?php echo esc_attr( 'Facebook' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-facebook-f"></i>
            </a>
            <a target="_blank" class="twitter"
               href="https://twitter.com/share?url=<?php echo urlencode( $share_link_url ) ?>&amp;text=<?php echo esc_html( $share_twitter_summary ); ?>"
               title="<?php echo esc_attr( 'Twitter' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-twitter"></i>
            </a>
            <a target="_blank" class="pinterest"
               href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( $share_link_url ) ?>&amp;description=<?php echo esc_html( $share_twitter_summary ); ?>&amp;media=<?php echo urlencode( $share_image_url[0] ); ?>"
               title="<?php echo esc_attr( 'Pinterest' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-pinterest"></i>
            </a>
            <a target="_blank" class="googleplus"
               href="https://plus.google.com/share?url=<?php echo urlencode( $share_link_url ) ?>&amp;title=<?php echo esc_html( $share_link_title ); ?>"
               title="<?php echo esc_attr( 'Google+' ) ?>"
               onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                <i class="fa fa-google-plus"></i>
            </a>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_post_tags' ) ) {
	function ecome_post_tags()
	{
		$get_term_tag = get_the_terms( get_the_ID(), 'post_tag' );
		if ( !is_wp_error( $get_term_tag ) && !empty( $get_term_tag ) ) : ?>
            <div class="tags">
				<?php
				echo esc_html__( 'Tags: ', 'ecome' );
				the_tags( '' );
				?>
            </div>
		<?php endif;
	}
}
if ( !function_exists( 'ecome_post_category' ) ) {
	function ecome_post_category()
	{
		$get_term_cat = get_the_terms( get_the_ID(), 'category' );
		if ( !is_wp_error( $get_term_cat ) && !empty( $get_term_cat ) ) : ?>
			<?php
			echo esc_html__( 'Categories: ', 'ecome' );
			the_category( ', ' );
			?>
		<?php endif;
	}
}
if ( !function_exists( 'ecome_post_single_meta' ) ) {
	function ecome_post_single_meta()
	{
		$enable_share_post = Ecome_Functions::ecome_get_option( 'enable_share_post' );
		ecome_post_tags();
		?>
        <div class="single-meta-post">
            <div class="category">
				<?php
				ecome_post_category(); ?>
            </div>
			<?php if ( $enable_share_post == 1 )
				ecome_share_button( get_the_ID() ); ?>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_post_info' ) ) {
	function ecome_post_info()
	{ ?>
        <div class="post-info">
			<?php
			/**
			 * Functions hooked into ecome_post_info_content action
			 *
			 * @hooked ecome_post_title               - 10
			 * @hooked ecome_post_content             - 20
			 * @hooked ecome_post_author              - 30
			 */
			do_action( 'ecome_post_info_content' );
			?>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_search_form' ) ) {
	function ecome_search_form()
	{
		$key_words = Ecome_Functions::ecome_get_option( 'key_word' );
		$selected  = '';
		if ( isset( $_GET['product_cat'] ) && $_GET['product_cat'] ) {
			$selected = $_GET['product_cat'];
		}
		$args = array(
			'show_option_none'  => esc_html__( 'All Categories', 'ecome' ),
			'taxonomy'          => 'product_cat',
			'class'             => 'category-search-option',
			'hide_empty'        => 1,
			'orderby'           => 'name',
			'order'             => 'ASC',
			'tab_index'         => true,
			'hierarchical'      => true,
			'id'                => rand(),
			'name'              => 'product_cat',
			'value_field'       => 'slug',
			'selected'          => $selected,
			'option_none_value' => '0',
		);
		?>
        <div class="block-search">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>"
                  class="form-search block-search ecome-live-search-form">
                <div class="form-content search-box results-search">
                    <div class="inner">
                        <input autocomplete="off" type="text" class="searchfield txt-livesearch input" name="s"
                               value="<?php echo esc_attr( get_search_query() ); ?>"
                               placeholder="<?php echo esc_html__( 'I&#39;m searching for...', 'ecome' ); ?>">
                    </div>
                </div>
				<?php if ( class_exists( 'WooCommerce' ) ): ?>
                    <input type="hidden" name="post_type" value="product"/>
                    <input type="hidden" name="taxonomy" value="product_cat">
                    <div class="category">
						<?php wp_dropdown_categories( $args ); ?>
                    </div>
				<?php else: ?>
                    <input type="hidden" name="post_type" value="post"/>
				<?php endif; ?>
                <button type="submit" class="btn-submit">
                    <span class="fa fa-search" aria-hidden="true"></span>
                </button>
            </form><!-- block search -->
			<?php if ( !empty( $key_words ) ): ?>
                <div class="key-word-search">
                    <span class="title-key"><?php echo esc_html__( 'Most searched:', 'ecome' ); ?></span>
                    <div class="listkey-word">
						<?php foreach ( $key_words as $key_word ): ?>
                            <a class="key-item" href="<?php echo esc_url( $key_word['key_word_link'] ); ?>">
								<?php echo esc_html( $key_word['key_word_item'] ); ?>
                            </a>
						<?php endforeach; ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_header_vertical' ) ) {
	function ecome_header_vertical()
	{
		global $post;
		/* MAIN THEME OPTIONS */
		$ecome_enable_vertical = Ecome_Functions::ecome_get_option( 'ecome_enable_vertical_menu' );
		$ecome_block_vertical  = Ecome_Functions::ecome_get_option( 'ecome_block_vertical_menu' );
		$ecome_item_visible    = Ecome_Functions::ecome_get_option( 'ecome_vertical_item_visible', 10 );
		if ( $ecome_enable_vertical == 1 ) :
			$locations = get_nav_menu_locations();
			$menu_id           = $locations['vertical_menu'];
			$menu_items        = wp_get_nav_menu_items( $menu_id );
			$count             = 0;
			foreach ( $menu_items as $menu_item ) {
				if ( $menu_item->menu_item_parent == 0 )
					$count++;
			}
			/* MAIN THEME OPTIONS */
			$vertical_title        = Ecome_Functions::ecome_get_option( 'ecome_vertical_menu_title', esc_html__( 'CATEGORIES', 'ecome' ) );
			$vertical_button_all   = Ecome_Functions::ecome_get_option( 'ecome_vertical_menu_button_all_text', esc_html__( 'All Categories', 'ecome' ) );
			$vertical_button_close = Ecome_Functions::ecome_get_option( 'ecome_vertical_menu_button_close_text', esc_html__( 'Close', 'ecome' ) );
			$ecome_block_class     = array( 'vertical-wrapper block-nav-category' );
			$id                    = '';
			$post_type             = '';
			if ( $ecome_enable_vertical == 1 )
				$ecome_block_class[] = 'has-vertical-menu';
			if ( isset( $post->ID ) )
				$id = $post->ID;
			if ( isset( $post->post_type ) )
				$post_type = $post->post_type;
			if ( is_array( $ecome_block_vertical ) && in_array( $id, $ecome_block_vertical ) && $post_type == 'page' )
				$ecome_block_class[] = 'always-open';
			?>
            <!-- block category -->
            <div data-items="<?php echo esc_attr( $ecome_item_visible ); ?>"
                 class="<?php echo implode( ' ', $ecome_block_class ); ?>">
                <div class="block-title">
                    <span class="before">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="text-title"><?php echo esc_html( $vertical_title ); ?></span>
                </div>
                <div class="block-content verticalmenu-content">
					<?php
					wp_nav_menu( array(
							'menu'            => 'vertical_menu',
							'theme_location'  => 'vertical_menu',
							'depth'           => 3,
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'ecome-nav vertical-menu',
							'fallback_cb'     => 'Ecome_navwalker::fallback',
							'walker'          => new Ecome_navwalker(),
						)
					);
					if ( $count > $ecome_item_visible ) : ?>
                        <div class="view-all-category">
                            <a href="#" data-closetext="<?php echo esc_attr( $vertical_button_close ); ?>"
                               data-alltext="<?php echo esc_attr( $vertical_button_all ) ?>"
                               class="btn-view-all open-cate"><?php echo esc_html( $vertical_button_all ) ?></a>
                        </div>
					<?php endif; ?>
                </div>
            </div><!-- block category -->
		<?php endif;
	}
}
/**
 *
 * TEMPLATE FOOTER
 */
if ( !function_exists( 'ecome_footer_content' ) ) {
	function ecome_footer_content()
	{
		$data_meta             = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$footer_options        = Ecome_Functions::ecome_get_option( 'ecome_footer_options' );
		$enable_theme_option   = Ecome_Functions::ecome_get_option( 'enable_theme_options' );
		$footer_options        = $enable_theme_option == 1 && isset( $data_meta['metabox_ecome_footer_options'] ) && $data_meta['metabox_ecome_footer_options'] != '' ? $data_meta['metabox_ecome_footer_options'] : $footer_options;
		$meta_template_style   = get_post_meta( $footer_options, '_custom_footer_options', true );
		$footer_template_style = isset( $meta_template_style['ecome_footer_style'] ) ? $meta_template_style['ecome_footer_style'] : 'style-01';
		ob_start();
		$query = new WP_Query( array( 'p' => $footer_options, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post();
				get_template_part( 'templates/footer/footer', $footer_template_style );
			endwhile;
		endif;
		wp_reset_postdata();
		echo ob_get_clean();
	}
}
/**
 *
 * TEMPLATE HEADER
 */
if ( !function_exists( 'ecome_header_content' ) ) {
	function ecome_header_content()
	{
		$enable_theme_option = Ecome_Functions::ecome_get_option( 'enable_theme_options' );
		$data_meta           = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$header_options      = Ecome_Functions::ecome_get_option( 'ecome_used_header', 'style-01' );
		$header_options      = $enable_theme_option == 1 && isset( $data_meta['metabox_ecome_used_header'] ) && $data_meta['metabox_ecome_used_header'] != '' ? $data_meta['metabox_ecome_used_header'] : $header_options;
		get_template_part( 'templates/header/header', $header_options );
	}
}
if ( !function_exists( 'ecome_header_background' ) ) {
	function ecome_header_background()
	{
		$ecome_header_background = Ecome_Functions::ecome_get_option( 'ecome_header_background' );
		$ecome_background_url    = Ecome_Functions::ecome_get_option( 'ecome_background_url', '#' );
		$enable_theme_option     = Ecome_Functions::ecome_get_option( 'enable_theme_options' );
		$data_meta               = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		$ecome_header_background = $enable_theme_option == 1 && isset( $data_meta['metabox_ecome_header_background'] ) && $data_meta['metabox_ecome_header_background'] != '' ? $data_meta['metabox_ecome_header_background'] : $ecome_header_background;
		$ecome_background_url    = $enable_theme_option == 1 && isset( $data_meta['metabox_ecome_background_url'] ) && $data_meta['metabox_ecome_background_url'] != '' ? $data_meta['metabox_ecome_background_url'] : $ecome_background_url;
		if ( $ecome_header_background ):
			?>
            <a href="<?php echo esc_url( $ecome_background_url ); ?>">
				<?php
				$image_gallery = apply_filters( 'ecome_resize_image', $ecome_header_background, false, false, true, true );
				echo wp_specialchars_decode( $image_gallery['img'] );
				?>
            </a>
		<?php
		endif;
	}
}
if ( !function_exists( 'ecome_header_language' ) ) {
	function ecome_header_language()
	{
		$list_language = '';
		$menu_language = '';
		$languages     = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
		if ( !empty( $languages ) ) {
			foreach ( $languages as $l ) {
				if ( !$l['active'] ) {
					$list_language .= '
						<li>
                            <a href="' . esc_url( $l['url'] ) . '">
                                <img src="' . esc_url( $l['country_flag_url'] ) . '" height="12"
                                     alt="' . esc_attr( $l['language_code'] ) . '" width="18"/>
								' . esc_html( $l['native_name'] ) . '
                            </a>
                        </li>';
				}
			}
			$menu_language = '<ul>' . $list_language . '</ul>';
		}
		echo wp_specialchars_decode( $menu_language );
	}
}
if ( !function_exists( 'ecome_user_link' ) ) {
	function ecome_user_link()
	{
		$myaccount_link = wp_login_url();
		if ( class_exists( 'WooCommerce' ) ) {
			$myaccount_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		}
		?>
        <div class="menu-item block-user ecome-dropdown">
			<?php if ( is_user_logged_in() ): ?>
                <a data-ecome="ecome-dropdown" class="woo-wishlist-link"
                   href="<?php echo esc_url( $myaccount_link ); ?>">
                    <span class="flaticon-profile"></span>
                </a>
				<?php if ( function_exists( 'wc_get_account_menu_items' ) ): ?>
                    <ul class="sub-menu">
						<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                            <li class="menu-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                            </li>
						<?php endforeach; ?>
                    </ul>
				<?php else: ?>
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php esc_html_e( 'Logout', 'ecome' ); ?></a>
                        </li>
                    </ul>
				<?php endif;
			else: ?>
                <a class="woo-wishlist-link" href="<?php echo esc_url( $myaccount_link ); ?>">
                    <span class="flaticon-profile"></span>
                </a>
			<?php endif; ?>
        </div>
		<?php
	}
}
if ( !function_exists( 'ecome_header_sticky' ) ) {
	function ecome_header_sticky()
	{
		$enable_sticky_menu = Ecome_Functions::ecome_get_option( 'ecome_sticky_menu' );
		if ( $enable_sticky_menu == 1 ): ?>
            <div class="header-sticky">
                <div class="container">
                    <div class="header-nav-inner">
						<?php ecome_header_vertical(); ?>
                        <div class="box-header-nav main-menu-wapper">
							<?php
							wp_nav_menu( array(
									'menu'            => 'primary',
									'theme_location'  => 'Primary Menu',
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
		<?php endif;
	}
}
/**
 *
 * TEMPLATE LOAD MORE
 */
if ( !function_exists( 'ecome_ajax_loadmore' ) ) {
	function ecome_ajax_loadmore()
	{
		$response             = array(
			'html'     => '',
			'loop_id'  => array(),
			'out_post' => 'no',
			'message'  => '',
			'success'  => 'no',
		);
		$out_post             = 'no';
		$args                 = isset( $_POST['loop_query'] ) ? $_POST['loop_query'] : array();
		$class                = isset( $_POST['loop_class'] ) ? $_POST['loop_class'] : array();
		$loop_id              = isset( $_POST['loop_id'] ) ? $_POST['loop_id'] : array();
		$loop_style           = isset( $_POST['loop_style'] ) ? $_POST['loop_style'] : '';
		$loop_thumb           = isset( $_POST['loop_thumb'] ) ? explode( 'x', $_POST['loop_thumb'] ) : '';
		$args['post__not_in'] = $loop_id;
		add_filter( 'ecome_shop_pruduct_thumb_width', create_function( '', 'return ' . $loop_thumb[0] . ';' ) );
		add_filter( 'ecome_shop_pruduct_thumb_height', create_function( '', 'return ' . $loop_thumb[1] . ';' ) );
		$loop_posts = new WP_Query( $args );
		ob_start();
		if ( $loop_posts->have_posts() ) {
			while ( $loop_posts->have_posts() ) : $loop_posts->the_post(); ?>
				<?php $loop_id[] = get_the_ID(); ?>
                <div <?php post_class( $class ); ?>>
					<?php wc_get_template_part( 'product-styles/content-product', 'style-' . $loop_style ); ?>
                </div>
			<?php
			endwhile;
		} else {
			$out_post = 'yes';
		}
		$response['html']     = ob_get_clean();
		$response['loop_id']  = $loop_id;
		$response['out_post'] = $out_post;
		$response['success']  = 'yes';
		wp_send_json( $response );
		die();
	}
}
if ( !function_exists( 'ecome_ajax_faqs_loadmore' ) ) {
	function ecome_ajax_faqs_loadmore()
	{
		$response             = array(
			'html'     => '',
			'loop_id'  => array(),
			'out_post' => 'no',
			'message'  => '',
			'success'  => 'no',
		);
		$out_post             = 'no';
		$args                 = isset( $_POST['loop_query'] ) ? $_POST['loop_query'] : array();
		$class                = isset( $_POST['loop_class'] ) ? $_POST['loop_class'] : array();
		$loop_id              = isset( $_POST['loop_id'] ) ? $_POST['loop_id'] : array();
		$args['post__not_in'] = $loop_id;
		$loop_posts           = new WP_Query( $args );
		ob_start();
		if ( $loop_posts->have_posts() ) {
			while ( $loop_posts->have_posts() ) : $loop_posts->the_post(); ?>
				<?php $loop_id[] = get_the_ID(); ?>
                <article <?php post_class( $class ); ?>>
                    <div class="question">
                        <span class="icon"><?php echo esc_html__( 'Q', 'ecome' ); ?></span>
                        <p class="text"><?php the_title(); ?></p>
                    </div>
                    <div class="answer">
                        <span class="icon"><?php echo esc_html__( 'A', 'ecome' ); ?></span>
                        <p class="text"><?php the_content(); ?></p>
                    </div>
                </article>
			<?php
			endwhile;
		} else {
			$out_post = 'yes';
		}
		$response['html']     = ob_get_clean();
		$response['loop_id']  = $loop_id;
		$response['out_post'] = $out_post;
		$response['success']  = 'yes';
		wp_send_json( $response );
		die();
	}
}
if ( ! function_exists( 'ecome_change_buy_together_thumb_width' ) ) {
	function ecome_change_buy_together_thumb_width( $thumb_w ) {
		$thumb_w = 220;
		
		return $thumb_w;
	}
	
	add_filter( 'famibt_thumb_w', 'ecome_change_buy_together_thumb_width', 10, 1 );
}

if ( ! function_exists( 'ecome_change_buy_together_thumb_height' ) ) {
	function ecome_change_buy_together_thumb_height( $thumb_h ) {
		$thumb_h = 220;
		
		return $thumb_h;
	}
	
	add_filter( 'famibt_thumb_h', 'ecome_change_buy_together_thumb_height', 10, 1 );
}