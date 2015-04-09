<?php

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
	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">';

	$form .= '<input type="text" class="search-field"';
	$form .= ' placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', theme_domain()) . '"';
	$form .= ' value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />';

	$form .= '<input type="submit" class="search-submit" value="'. esc_attr_x( 'Search', 'submit button' , theme_domain()) .'" />';

	$form .= '</form>';

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
			<h2><?php _ex('Categories:','Titles',theme_domain());?></h2>
			<ul>
				<?php
				$archiveLink = get_blog_archive_url();
				$archiveTitle = _x('View all','Links',theme_domain());
				echo "<li><a href='$archiveLink'>$archiveTitle</a>";
				echo $taxonomyUl;
				?>
			</ul>
		</div>
		<div class="recent-articles">
			<h2><?php _ex('Recent Articles:','Titles',theme_domain());?></h2>
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
		'term' => _x('Articles', 'Links', theme_domain())
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