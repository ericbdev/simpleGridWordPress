<?php
//update_option('siteurl','http://www.liveserver.com');
//update_option('home','http://www.liveserver.com');
/*********************/
require_once 'includes/disable-feeds.php';
require_once 'includes/base-theme.php';
require_once 'includes/meta-fields.php';
require_once 'includes/image.php';
require_once 'includes/post-types.php';
require_once 'includes/shortcodes.php';


/**Enqueing scripts**/
function is_login() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}
function wp_head_action(){
	echo "<script>window.jQuery || document.write('<script src=\"".get_template_directory_uri()."/scripts/vendor/jquery-1.11.0.min.js\"><\\/script>')</script>";
}
add_action('wp_head', 'wp_head_action');

function themeDomain(){
	return 'themeDomain';
}

function get_the_prefix() {
	return '_themePrefix_';
}

function load_my_scripts() {
	$cssPath = get_template_directory_uri().'/css/';
	$jsPath = get_template_directory_uri().'/scripts/';

	if (!is_admin()) {  //If the page is admin page, don't load//
		wp_enqueue_script( 'modernizr', $jsPath.'vendor/modernizr.js', false, '1.0', false);
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false, '1.11.0');
		wp_enqueue_script( 'jquery' );

		//wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );


		wp_enqueue_script( 'scrolltofixed', $jsPath.'vendor/jquery.scrolltofixed.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script( 'mobileMenu', $jsPath.'vendor/jquery.sidr.js',  array('jquery'), '1.0', false);
		wp_enqueue_script( 'customSelect', $jsPath.'vendor/jquery.customSelect.min.js',  array('jquery'), '1.0', false);
		wp_enqueue_script( 'placeHolders', $jsPath.'vendor/jquery.placeholders.js',  array('jquery'), '1.0', false);
		wp_enqueue_script( 'owl-js', $jsPath.'vendor/owl-carousel/owl.carousel.js',  array('jquery'), '1.0', true);

		wp_enqueue_script( 'siteJS', $jsPath.'site_js.js', array('jquery','mobileMenu','owl-js','scrolltofixed' ), '1.0', true);

		wp_enqueue_style( 'g-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic|Open+Sans:300,400italic,400,600,700,800',false, '1', 'all');
		wp_enqueue_style( 'owl-css-theme', $jsPath.'vendor/owl-carousel/owl.theme.css',array('g-fonts'), '1');
		wp_enqueue_style( 'owl-css-base', $jsPath.'vendor/owl-carousel/owl.carousel.css',array('g-fonts'), '1');
		wp_enqueue_style( 'style', $cssPath . 'style.css', array('g-fonts'), '1');



		/*Using wpml?*/
		define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGES_JS', true);


	}
	if(is_login()){
		//wp_register_style( 'wploginpage',get_template_directory_uri() . '/css/admin-style.css',false, '1', 'all');
		//wp_enqueue_style( 'wploginpage');


	}
	/*Code for additional scripts:
	wp_enqueue_script( $handle, $src, $deps, $ver, $in_foo ter );

	wp_register_style( $handle, $src, $deps, $ver, $media );
	wp_enqueue_style( $handle);*/

}
//add_action('init', 'load_my_scripts');  /*Loads up all enqueued scripts when loading the header*/
add_action( 'wp_enqueue_scripts', 'load_my_scripts' );




/*************************************************************************************/
function get_social_links($lang = false){
	if(!$lang) $lang = get_lang_active();
	$links = array(
		'twitter'  => array('title' => 'Twitter',       'link' => '#'),
		'linkedin'  => array('title' => 'LinkedIn',     'link' => '//www.linkedin.com/company/stack8-technologies'),
		'facebook'  => array('title' => 'Facebook',     'link' => '//www.facebook.com/Stack8'),
		'gplus'  => array('title' => 'Google Plus',     'link' => '//plus.google.com/100139591148710008953/about'),
		'youtube'  => array('title' => 'YouTube',     'link' => '#'),

	);

	return $links;
}



function get_id($name, $lang = false){
	if(!$lang) $lang = get_lang_active();
	switch($name){
		case 'home':
			return ($lang == 'en' ? 2 : 2);
			break;
	}
	return false;
}


function my_strftime ($format, $timestamp)
{
	$mapOrdinals = array(
		"st" => "<sup>er</sup>",
		"nd" => "<sup>e</sup>",
		"th" => "<sup>e</sup>"
	);

	$format = str_replace('%O', date('S', $timestamp), $format);

	return
		str_replace (array_keys($mapOrdinals), array_values($mapOrdinals), strftime($format, $timestamp) );

}


function number_of_posts_on_archive($query){
	if($query->is_post_type_archive('type-story') &&  $query->is_main_query()){
		$query->set('posts_per_page', 9);
	}
	if($query->is_post_type_archive('type-article') &&  $query->is_main_query()){
		$query->set('posts_per_page', 5);
	}
	return $query;
}
if(!is_admin()){
	add_filter('pre_get_posts', 'number_of_posts_on_archive');
}

