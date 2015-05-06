<?php
function is_test(){
	if(isset($_REQUEST['reversetest'])):
		return false;
	elseif(isset($_REQUEST['prev'])):
		return true;
	elseif($_SERVER['REMOTE_ADDR'] == '24.37.85.130'):
		return true;
	else:
		return false;
	endif;
}


if(is_test()):
	@ini_set('display_errors',1 );
	@ini_set('ignore_repeated_errors',1);
	@ini_set( 'log_errors', 1 );
	@ini_set( 'error_log', get_template_directory() . '/debug.log' );
else:
	@ini_set('display_errors',0);
endif;

/**  Load dependent Files **/
require_once 'base/deprecated.php';
function cmb_initialize_cmb_meta_boxes() {
	if (!class_exists( 'cmb_Meta_Box' ) ){
		require_once 'vendor/lib-meta/init.php';
	}
}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
if (!class_exists( 'customPostType' ) ){
	require_once 'vendor/custom-post-types.php';
}
require_once 'base/archive-helpers.php';
require_once 'base/disable-feeds.php';
require_once 'base/image.php';
require_once 'base/post-types.php';
require_once 'base/shortcodes.php';
require_once 'base/form-builder.php';
require_once 'base/meta-fields.php';
require_once 'base/woo-commerce.php';




/*************************************************************************************/
/** Theme By Theme Customization **/
if(!function_exists('_wp_render_title_tag')):
	function add_document_title(){
		?><title><?php wp_title('|'); ?></title><?php
	}
	add_action('wp_head', 'add_document_title');
	add_theme_support( 'title-tag' );
endif;
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


function be_domain(){
	$front_domain = 'Theme';
	if (function_exists('theme_domain')):
		$front_domain = theme_domain();
	endif;
	return $front_domain.' Backend';
}



function load_admin_functions() {
	/* Serif fonts are for print. */
	if (is_admin()):
		add_editor_style('css/editor-style.css');
	endif;
	register_nav_menus(
		array(
			'header-menu-desktop' => __( 'Desktop Header Menu' ),
			'footer-menu-desktop' => __( 'Desktop Footer Menu' ),
			//'header-menu-mobile' => __( 'Mobile Header Menu' ),
			//'footer-menu-mobile' => __( 'Mobile Footer Menu' )
		)
	);
}
add_action( 'init', 'load_admin_functions' );

function get_theme_path($withSlash = false, $extraPath = '') {
	$morePath = '';

	if ($withSlash == true) $morePath .= '/';
	if ($extraPath !== '') $morePath .= $extraPath;

	return get_template_directory_uri() . $morePath;
}

function get_meta($id, $field){
	$prefix = get_the_prefix();
	return get_post_meta(intval($id), $prefix.$field, true);
}

if(!function_exists('get_term_name')):
	/**
	 * @param int    $term_id
	 * @param string $taxonomy_name
	 * @return string
	 */
	function get_term_name($term_id = 0, $taxonomy_name = '') {
		$term_id = (is_int($term_id) ? $term_id : intval($term_id));
		$return = '';
		if(!empty($taxonomy_name)):
			$term = get_term($term_id, $taxonomy_name);
			$return = $term->name;
		endif;
		return $return;
	}
endif;

function get_languages_short($includeActive = false) {
	if(function_exists('icl_get_languages')):
		$languages = icl_get_languages('skip_missing=0');
		$langReturn = array();
		if (1 < count($languages)):
			foreach ($languages as $l):
				if($includeActive):
					$return = "<a href='{$l['url']}'".($l['active'] ? " class='active'" : '').">";
					$return .= substr($l['language_code'],0,2);
					$return .= "</a>";
					$langReturn[] =  $return;
				else:
					if (!$l['active']) $langReturn[] =  '<a href="' . $l['url'] . '">' . $l['language_code'] . '</a>';
				endif;

			endforeach;
		endif;
		return $langReturn;
	endif;
	return '';
}

function get_languages_long($includeActive = false) {
	if(function_exists('icl_get_languages')):
		$languages = icl_get_languages('skip_missing=0');
		$langReturn = array();
		if (1 < count($languages)):
			foreach ($languages as $l):
				if($includeActive):
					$return = "<a href='{$l['url']}'".($l['active'] ? " class='active'" : '').">";
					$return .= $l['native_name'];
					$return .= "</a>";
					$langReturn[] =  $return;
				else:
					if (!$l['active']) $langReturn[] =  '<a href="' . $l['url'] . '">' . $l['native_name'] . '</a>';
				endif;
			endforeach;
		endif;
		return $langReturn;
	endif;
	return '';
}

function get_lang_active($validateAgainst = false) {
	$activeLang = 'en';
	if(class_exists('SitePress')):
		global $sitepress;
		$activeLang = substr($sitepress->get_current_language(),0,2);
	endif;

	if(!$validateAgainst):
		return $activeLang;
	else:
		return ($activeLang == $validateAgainst);
	endif;
}
function get_lang_code($activeLang = false) {
	if(class_exists('SitePress')):
		if(!$activeLang):
			$activeLang = get_lang_active();
		endif;
		$langs = icl_get_languages( 'skip_missing=0' );
		if( isset( $langs[$activeLang]['default_locale'] ) ):
			return $langs[$activeLang]['default_locale'];
		endif;
	endif;
	return false;
}

/**
 * @param        $id
 * @param string $type
 * @return int
 */
function get_translated_id($id, $type = 'page') {
	$returnID = intval($id);
	if(class_exists('SitePress')){
		if(function_exists('icl_object_id')) {
			$returnID = icl_object_id($id, $type, true);
		}
	}
	return intval($returnID);
}

/**
 * @param        $id
 * @param string $type
 * @param string $lang
 * @return int
 */
function get_default_id($id, $type = 'page', $lang = 'en') {
	$returnID = intval($id);
	if(class_exists('SitePress')){
		if(function_exists('icl_object_id')) {
			$returnID = icl_object_id($id, $type, true, $lang);
		}
	}
	return intval($returnID);
}

/*************************************************************************************/

class navWalker extends Walker_Nav_Menu{
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>";
	}
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "<ul class=\"sub-menu\">";
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul>";
	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;
		//$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$indent = '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) .  ' post-id-'.esc_attr( $item->object_id ).'"';

		//$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		$output .= $indent . '<li' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';

		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$strposlink = esc_attr( $item->url);

		$item_output = '<a'. $attributes .'>';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= '</a>'; /* This is where I changed things. */


		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
	}
}
/*************************************************************************************/

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
	if (false !== $result) :
		return $matches[1];
	endif;
	return false;
}


/*************************************************************************************/

/** WordPress Extending // Fixes **/
add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );
add_filter( 'automatic_updater_disabled', '__return_true' );
add_filter( 'auto_update_core', '__return_false' );

function remove_info() {
	return false;
}
function remove_wp_non_essentials() {
	/**
	 * Disable generators
	 **/
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	add_filter('the_generator','remove_info');
	if(class_exists('SitePress')):
		remove_action('wp_head', array ( $GLOBALS['sitepress'], 'meta_generator_tag' ));
	endif;

	/**
	 * Disable Emojis
	 **/
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
//	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action('init', 'remove_wp_non_essentials');

function wphidenag() {
	if(!is_test()):
		remove_action( 'admin_notices', 'update_nag', 3 );
	endif;
}
add_action('admin_menu','wphidenag');

function remove_admin_bar() {
	if(isset($_REQUEST['hide_admin_bar'])):
		//show_admin_bar(false);
		add_filter('show_admin_bar', '__return_false');
	endif;
}
add_action('after_setup_theme', 'remove_admin_bar');

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
add_filter('body_class','browser_body_class');

add_filter( 'post_thumbnail_html', 'remove_width_attribute', 50 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 50 );

function remove_width_attribute( $html ) {
	$html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
	return $html;
}
function set_html_content_type() {
	return 'text/html';
}
function is_login() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

if (function_exists('add_filter') && !function_exists('alter_hosting_provider_filters')) {
	function alter_hosting_filters($methods) {
		if (isset($methods['pingback.ping'])) {
			unset($methods['pingback.ping']);
		}
		return $methods;
	}
	add_filter('xmlrpc_methods', 'alter_hosting_filters');
}

function remove_menus(){
	//remove_menu_page( 'edit.php' );                   //Posts
	//remove_menu_page( 'edit-comments.php' );          //Comments
	//remove_menu_page( 'themes.php' );                 //Appearance
}
function adjust_the_wp_menu() {
	//remove_submenu_page( 'themes.php', 'themes.php' );
	remove_submenu_page( 'themes.php', 'customize.php' );
	remove_submenu_page( 'themes.php', 'theme-editor.php' );
}
add_action( 'admin_menu', 'remove_menus' );
add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );

/** Add the template name as a column to the admin panel**/
/** TODO: Improve this to pull the file information, similar to wp_get_theme()->get_page_templates**/
add_filter( 'manage_edit-page_columns', 'page_columns_headers' ) ;
function page_columns_headers( $columns ) {
	$columns['template_name'] = _x( 'Template' ,'Title', theme_domain());
	return $columns;
}

add_action( 'manage_page_posts_custom_column', 'page_columns_content', 10, 2 );
function page_columns_content( $column, $post_id ) {
	if($column == 'template_name'):
		$search = array('tpl-', '-', '.php');
		$replace = array('',' ', '');
		echo ucwords(str_replace($search, $replace, basename(get_page_template_slug( $post_id))));
	endif;
}


if (!function_exists('array_replace')) {
	function array_replace(array &$array, array &$array1, $filterEmpty = false) {
		$args  = func_get_args();
		$count = func_num_args() - 1;
		for ($i = 0; $i < $count; ++$i) {
			if (is_array($args[$i])) :
				foreach ($args[$i] as $key => $val) {
					if ($filterEmpty && empty($val)) continue;
					$array[$key] = $val;
				}
			else:
				trigger_error(
					__FUNCTION__ . '(): Argument #' . ($i + 1) . ' is not an array',
					E_USER_WARNING
				);
				return NULL;
			endif;
		}

		return $array;
	}
}
if (!function_exists('recurse')) {
	function recurse($array, $array1) {
		foreach ($array1 as $key => $value) {
			// create new key in $array, if it is empty or not an array
			if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
				$array[$key] = array();
			}

			// overwrite the value in the base array
			if (is_array($value)) {
				$value = recurse($array[$key], $value);
			}
			$array[$key] = $value;
		}
		return $array;
	}
}
/**
 * Legacy support
 */
if (!function_exists('themeDomain')) {
	/**
	 * @deprecated use theme_domain();
	 * @return string
	 */
	function themeDomain(){
		return theme_domain();
	}
}
if (!function_exists('array_replace_recursive')) {
	function array_replace_recursive($array, $array1) {


		// handle the arguments, merge one by one
		$args  = func_get_args();
		$array = $args[0];
		if (!is_array($array)) {
			return $array;
		}
		for ($i = 1; $i < count($args); $i++) {
			if (is_array($args[$i])) {
				$array = recurse($array, $args[$i]);
			}
		}
		return $array;
	}
}