<?php
function cmb_initialize_cmb_meta_boxes() {
	if (!class_exists( 'cmb_Meta_Box' ) ){
		require_once 'vendor/lib-meta/init.php';
	}
}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

require_once 'base/disable-feeds.php';
require_once 'base/image.php';
require_once 'base/post-types.php';
require_once 'base/shortcodes.php';
require_once 'base/form-builder.php';

function is_test(){
	if(isset($_REQUEST['reversetest'])){
		return false;
	}elseif(isset($_REQUEST['prev'])){
		return true;
	}elseif($_SERVER['REMOTE_ADDR'] == '24.37.85.130'){
		return true;
	}else{
		return false;
	}
}

foreach (array('wp_head', 'rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
	remove_action($action, 'the_generator');
}
remove_action('wp_head', 'wp_generator');

function remove_version() {
	return '';
}
add_filter('the_generator', 'remove_version');

function wphidenag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}
add_action('admin_menu','wphidenag');


function get_languages_short() {
	if(function_exists('icl_get_languages')){
		$languages = icl_get_languages('skip_missing=0');
		if (1 < count($languages)) {
			$langReturn = array();
			foreach ($languages as $l) {
				if (!$l['active']) $langReturn[] =  '<a href="' . $l['url'] . '">' . $l['language_code'] . '</a>';
			}
			return $langReturn;
		}
	}else{
		echo '';
	}
}

function get_languages_long() {
	if(function_exists('icl_get_languages')){
		$languages = icl_get_languages('skip_missing=0');
		if (1 < count($languages)) {
			$langReturn = array();
			foreach ($languages as $l) {
				if (!$l['active']) $langReturn[] =  '<a href="' . $l['url'] . '">' . $l['native_name'] . '</a>';
			}
			return $langReturn;
		}
	}else{
		echo '';
	}
}

function get_lang_active($validateAgainst = false) {
	$activeLang = 'en';
	if(class_exists('SitePress')){
		global $sitepress;
		$activeLang = $sitepress->get_current_language();
	}

	if(!$validateAgainst){
		return $activeLang;
	}else{
		return ($activeLang == $validateAgainst);
	}
}

function get_translated_id($id, $type = 'page') {
	$returnID = $id;
	if(class_exists('SitePress')){
		if(function_exists('icl_object_id')) {
			$returnID = icl_object_id($id, $type, true);
		}
	}
	return $returnID;
}

function get_default_id($id, $type = 'page') {
	$returnID = $id;
	if(class_exists('SitePress')){
		if(function_exists('icl_object_id')) {
			$returnID = icl_object_id($id, $type, true, 'en');
		}
	}
	return $returnID;
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

function get_meta($id, $field){
	$prefix = get_the_prefix();
	return get_post_meta($id, $prefix.$field, true);
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
			'header-menu-desktop' => __( 'Desktop Header Menu' ),
			'footer-menu-desktop' => __( 'Desktop Footer Menu' ),
			//'header-menu-mobile' => __( 'Mobile Header Menu' ),
			//'footer-menu-mobile' => __( 'Mobile Footer Menu' )
		)
	);
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

	function start_el(&$output, $item, $depth, $args, $id = 0) {
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

		if (strpos($strposlink, site_url()) === false) {

			$attributes .= ! empty( $item->url )        ? ' class="external_link"' : '';
			$attributes .= ' target="_blank"';
		}else{
			//	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		}

		$item_output = '<a'. $attributes .'>';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= '</a>'; /* This is where I changed things. */


		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
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



/** Blogging / Archive **/

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



function get_blog_archive_url(){
	if(function_exists('get_option')){
		$page_for_posts = get_option('page_for_posts');
		if($page_for_posts !== '' && $page_for_posts !== '0'){
			return get_permalink($page_for_posts);
		}
	}
	return false;

}


function my_strftime ($format, $timestamp){
	$mapOrdinals = array(
		"st" => "<sup>er</sup>",
		"nd" => "<sup>e</sup>",
		"th" => "<sup>e</sup>"
	);

	$format = str_replace('%O', date('S', $timestamp), $format);

	return
		str_replace (array_keys($mapOrdinals), array_values($mapOrdinals), strftime($format, $timestamp) );

}

function my_search_form( $form ) {
	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				<input type="text" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />
				<input type="submit" class="search-submit" value="'. esc_attr_x( 'Search', 'submit button' ) .'" />
			</form>';

	return $form;
}
add_filter( 'get_search_form', 'my_search_form' );

function get_articles_side_bar($taxonomyUl){
	?>
	<div class="columns large-3 show-for-large-up article-sidebar">
		<div class="search">
			<?php get_search_form( true ); ?>
		</div>
		<div class="category-display">
			<h2><?php _ex('Categories:','Titles',themeDomain());?></h2>
			<ul>
				<?php
				$archiveLink = get_blog_archive_url();
				$archiveTitle = _x('View all','Links',themeDomain());
				echo "<li><a href='$archiveLink'>$archiveTitle</a>";
				echo $taxonomyUl;
				?>
			</ul>
		</div>
		<div class="recent-articles">
			<h2><?php _ex('Recent Articles:','Titles',themeDomain());?></h2>
			<ul>
				<?php
				$searchPostType = 'post';
				$searchTax = 'category';

				$args = array(
					'post_type' => $searchPostType, // the custom post type for logos
					'posts_per_page' => 5
				);
				$catQuery = new WP_Query($args);
				$postCount = $catQuery->found_posts;

				while ($catQuery->have_posts()) : $catQuery->the_post();
					global $args;
					global $post;

					$storyCat  = wp_get_post_terms($post->ID, 'category');


					echo "<li><a href='".get_permalink($post->ID)."'>".get_the_title($post->ID)."</a></li>";

				endwhile;
				?>
			</ul>
		</div>
	</div>
<?php
}


function number_of_posts_on_archive($query){
	if($query->is_post_type_archive('custom-post-type-1') &&  $query->is_main_query()){
		$query->set('posts_per_page', 9);
	}
	if($query->is_post_type_archive('custom-post-type-2') &&  $query->is_main_query()){
		$query->set('posts_per_page', 5);
	}
	return $query;
}
if(!is_admin()){
	add_filter('pre_get_posts', 'number_of_posts_on_archive');
}

function filter_search($query) {
	if(!is_admin()){
		if ($query->is_search) {
			$query->set('post_type', array('post'));
		};
	}
	return $query;
};
add_filter('pre_get_posts', 'filter_search');





function get_pagination($pageAmount, $args = array()){
	$defaults = array(
		'class' => '',
		'term' => _x('Articles', 'Links', themeDomain())
	);
	$args = array_replace( $defaults, $args );
	$rowClass = trim('row archive-navigation collapse '.$args['class']);
	if ( $pageAmount > 1 ) : ?>
		<nav class="<?php echo $rowClass?>" role="navigation">
			<?php
			$prevLinks = (ICL_LANGUAGE_CODE == 'fr' ? 'Suivants'.$args['term'] : 'Older '.$args['term']  );
			$nextLinks = (ICL_LANGUAGE_CODE == 'fr' ? 'Précédents'.$args['term']   :  'Newer '.$args['term']);


			?>
			<div class="nav-previous columns small-5"><?php previous_posts_link( "<span class='meta-nav'>$nextLinks</span>"); ?></div>
			<div class="nav-next columns small-5"><?php next_posts_link( "<span class='meta-nav'>$prevLinks</span>" ); ?></div>
		</nav>
	<?php endif;
}

