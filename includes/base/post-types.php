<?php
function customPostTypes() {
	/*register_post_type('type-story', array(
		'labels'             => array(
			'name'          =>  _x( 'Success Stories', 'Titles', themeDomain() ),
			'singular_name' => _x( 'Success Story', 'Titles', themeDomain() ),
			'menu_name'     => _x( 'Success Stories', 'Titles', themeDomain() ),
		),
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'             => array('slug' => _x('success-stories', 'URL Slug', themeDomain()), 'with_front' => false, 'page' => false),
		'capability_type'    => 'page',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		//'menu_icon'          => 'dashicons-list-view',
		'supports'           => array('title','custom-fields', 'revisions')
	));*/

	$catArgs = 		array(
		'label'                 => __('Category', themeDomain()),

		'sort'                  => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'hierarchical'          => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'args'                  => array('orderby' => 'term_order'),
	);
	//register_taxonomy(	'stories', array('type-story'), $catArgs);
	//register_taxonomy(	'articles', array('type-article'), $catArgs);


}
//add_action('init', 'customPostTypes');


/** Use this to deregister bad post types **/
if (!function_exists('unregister_post_type')) :
	function unregister_post_type($post_type) {
		global $wp_post_types;
		if (isset($wp_post_types[$post_type])) {
			unset($wp_post_types[$post_type]);
			return true;
		}
		return false;
	}
endif;