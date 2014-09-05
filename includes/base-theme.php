<?php
remove_action('wp_head', 'wp_generator');
foreach (array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
	remove_action($action, 'the_generator');
}


function remove_version() {
	return '';
}

add_filter('the_generator', 'remove_version');


function get_languages() {
	if(function_exists('icl_get_languages')){
		$languages = icl_get_languages('skip_missing=0');
		if (1 < count($languages)) {
			foreach ($languages as $l) {
				if (!$l['active']) echo '<a href="' . $l['url'] . '">' . $l['language_code'] . '</a>';
			}
		}
	}else{
		echo '';
	}
}

function get_languages_long() {
	if(function_exists('icl_get_languages')){
	$languages = icl_get_languages('skip_missing=0');

		if (1 < count($languages)) {
			foreach ($languages as $l) {
				if (!$l['active']) echo '<a href="' . $l['url'] . '">' . $l['native_name'] . '</a>';
			}
		}
	}else{
		echo '';
	}
}

function get_lang_active() {
	if(function_exists('icl_get_languages')){
		$languages = icl_get_languages('skip_missing=0');
		if (1 < count($languages)) {
			foreach ($languages as $l) {
				if ($l['active']) return $l['language_code'];
			}
		}
	}else{
		return 'en';
	}
}

function get_theme_path($withSlash = false, $extraPath = '') {
	$morePath = '';
	if ($withSlash == true) {
		$morePath .= '/';
	}
	if ($extraPath !== '') {
		$morePath .= $extraPath;
	}

	return get_template_directory_uri() . $morePath;
}


function youtube_id_from_url($url) {
	$pattern =
		'%^# Match any youtube URL
		(?:https?://)?  # Optional scheme. Either http or https
		(?:www\.)?      # Optional www subdomain
		(?:             # Group host alternatives
		  youtu\.be/    # Either youtu.be,
		| youtube\.com  # or youtube.com
		  (?:           # Group path alternatives
			/embed/     # Either /embed/
		  | /v/         # or /v/
		  | /watch\?v=  # or /watch\?v=
		  )             # End path alternatives.
		)               # End host alternatives.
		([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
		$%x';
	$result  = preg_match($pattern, $url, $matches);
	if (false !== $result) {
		return $matches[1];
	}
	return false;
}

function get_blog_archive_url(){
	if(function_exists('get_option')){
		$page_for_posts = get_option('page_for_posts');
		if($page_for_posts !== '' && $page_for_posts !== '0'){
			return get_permalink($page_for_posts);
		}
	}
	return false;

}



/*************************************************************************************/
//add_image_size( $name, $width, $height, $crop );
add_theme_support( 'html5', array( 'search-form' ) );

add_theme_support( 'post-thumbnails' );
add_image_size( 'page-banner', '1400', '377', false );
add_image_size( 'home-banner', '1600', '600', false );
add_image_size( 'sqr_450', '450', '450', true);
add_image_size( 'sqr_300', '300', '300', true);
add_image_size( 'sqr_210', '210', '210', true);
//add_image_size( 'preview', '300', '300', true);


/*add_image_size( 'sqr_750', '750', '750', true);
add_image_size( 'sqr_600', '600', '600', true);

add_image_size( 'sqr_450', '450', '450', true);
add_image_size( 'sqr_300', '300', '300', true);
add_image_size( 'sqr_200', '200', '200', true);*/


add_action( 'init', 'register_my_menus' );
function register_my_menus() {
	register_nav_menus(
		array(
			//'header-menu-desktop' => __( 'Desktop Header Menu' ),
			'footer-menu-desktop' => __( 'Desktop Footer Menu' ),
			'header-menu-mobile' => __( 'Mobile Header Menu' ),
			//'footer-menu-mobile' => __( 'Mobile Footer Menu' )
		)
	);
}

/*************************************************************************************/



class navWalker extends Walker_Nav_Menu
{
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>";
	}

	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//var_dump($item);
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) .  'post-id-'.esc_attr( $item->object_id ).'"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';

		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$strposlink = esc_attr( $item->url);

		if (strpos($strposlink, site_url()) === false) {

			$attributes .= ! empty( $item->url )        ? ' class="external_link"' : '';
			$attributes .= ' target="_blank"';
		}else{
			//	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>'; /* This is where I changed things. */
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
/*************************************************************************************/
add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';
	if($is_iphone) $classes[] = 'iphone';
	if(wp_is_mobile()){$classes[] = 'mobile';}

	return $classes;
}



add_filter( 'post_thumbnail_html', 'remove_width_attribute', 50 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 50 );

function remove_width_attribute( $html ) {
	$html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
	return $html;
}
function set_html_content_type() {
	return 'text/html';
}

function get_custom_except($charlength, $append = false, $pid = false) {
	$excerpt = '';
	if($pid){
		$excerpt = get_the_excerpt($pid);
	}else{
		$excerpt = get_the_excerpt();
	}

	$return = '';
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {

		$excerpt = strip_tags($excerpt);
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$return =  mb_substr( $subex, 0, $excut );
		} else {
			$return =  $subex;
		}
		if($append){
			return trim($return). '...';
		}else{
			return trim($return);
		}

	} else {
		return $excerpt;
	}
}

add_action('admin_menu','wphidenag');
function wphidenag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}

