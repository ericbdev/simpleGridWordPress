<?php

add_action( 'cmb2_init', 'ds_meta_fields' );
function ds_meta_fields( ) {
	$prefix = get_the_prefix();
	$backend_domain = get_the_prefix();
	$wysiwygOptions = array(
		'wpautop' => true, // use wpautop?
		'media_buttons' => true, // show insert/upload button(s)
		//'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
		'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		'tabindex' => '',
		'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
		'editor_class' => '', // add extra class(es) to the editor textarea
		'teeny' => false, // output the minimal editor config used in Press This
		'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
		'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
		'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
	);
	$wysiwygSmallNoMediaOptions = array(
		'wpautop' => true, // use wpautop?
		'media_buttons' => false, // show insert/upload button(s)
		//'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
		'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
		'tabindex' => '',
		'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
		'editor_class' => '', // add extra class(es) to the editor textarea
		'teeny' => false, // output the minimal editor config used in Press This
		'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
		'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
		'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
	);
	$homeWhitelist = array(2);

	$bannerColumnWidth = array(
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
		'7' => '7',
		'8' => '8',
		'9' => '9',
		'10' => '10',
		'11' => '11',
		'12' => '12',
	);


	/**
	 * Set options for the two page column
	 * @var  $two_cols
	 */
	$two_cols = new_cmb2_box( array(
		'id'            => $prefix . 'two_columns',
		'title'      => __( 'Columns',  be_domain() ),
		'object_types'  => array( 'page', ), // Post type
		'show_on' => array( 'key' => 'page-template', 'value' => array('tpl-two-col.php') ),
		// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );
	$two_cols->add_field(array(
		'name'       => __( 'Column One',  be_domain() ),
		'id'         => $prefix . 'col_one',
		'sanitization_cb' => false,
		'type'       => 'wysiwyg',
		'options' => $wysiwygOptions
	));
	$two_cols->add_field(array(
		'name'       => __( 'Column Two',  be_domain() ),
		'id'         => $prefix . 'col_two',
		'sanitization_cb' => false,
		'type'       => 'wysiwyg',
		'options' => $wysiwygOptions
	));
	$two_cols->add_field(array(
		'name'       => __( 'First Column Width',  be_domain() ),
		'id'         =>   $prefix.'col_width_one',
		'type'      => 'select',
		'options'   => $bannerColumnWidth,
		'default'=> '4'
	));
	$two_cols->add_field(array(
		'name'       => __( 'Second Column Width',  be_domain() ),
		'id'         =>   $prefix.'col_width_two',
		'type'      => 'select',
		'options'   => $bannerColumnWidth,
		'default'=> '8'
	));


	/**
	 * Add banners to the top of the page
	 * @var  $page_banners
	 */
	$page_banners = new_cmb2_box(array(
		'id'         => 'page_banner',
		'title'      => 'Banners',
		'object_types'  => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true
	));
	$banner_group = $page_banners->add_field(array(
		'title'   => 'Banner Images',
		'id'      => $prefix . 'banner_images',
		'type'    => 'group',
		'options' => array(
			'group_title'   => __('Image {#}', be_domain()), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __('Add Another Image', be_domain()),
			'remove_button' => __('Remove Image', be_domain()),
			'sortable'      => true, // beta
		)
	));
	$page_banners->add_group_field($banner_group, array(
		'name'       => __('Image',  be_domain()),
		'id'         => 'image',
		'show_names' => false,
		'type'       => 'file',
		'allow'      => array('attachment') // limit to just attachments with array( 'attachment' )
	));
	$page_banners->add_group_field($banner_group, array(
		'name' => __('Banner Text',  be_domain()),
		'id'   => 'text',
		'type' => 'wysiwyg',
		'options' => $wysiwygOptions,
	));

	/*array(
		'name' => __( 'Datasheet',  be_domain() ),
		'id'   => $prefix.'prod_datasheet',
		'type' => 'file',
		'allow' => array('attachment' ) // limit to just attachments with array( 'attachment' )
	),*/







}


/**
 * Excludes a PostID array
 * 	'show_on'    => array('key' => 'exclude_id', 'value' => array('id'),
 * @param $display
 * @param $meta_box
 * @return bool
 */
function be_metabox_exclude_for_id( $display, $meta_box ) {
    if ( 'exclude_id' !== $meta_box['show_on']['key'] )
        return $display;

    // If we're showing it based on ID, get the current ID
    if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
    elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
    if( !isset( $post_id ) )
        return $display;

    // If current page id is in the included array, do not display the metabox
    $meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];
    if ( in_array( $post_id, $meta_box['show_on']['value'] ) )
        return false;
    else
        return true;
}
add_filter( 'cmb_show_on', 'be_metabox_exclude_for_id', 10, 2 );

/**
 * Usage: 'show_on' => array( 'key' => 'page-template', 'value' => @array || @string ),
 * @param $display
 * @param $meta_box
 * @return bool
 */
function metabox_hide_on_template( $display, $meta_box ) {

	if( 'hide_on' !== $meta_box['show_on']['key'] )
		return $display;

	// Get the current ID
	if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
	elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
	if( !isset( $post_id ) ) return false;

	$template_name = get_page_template_slug( $post_id );

	$return = true;
	if(is_array($meta_box['show_on']['value'])):
		$return = (in_array($template_name, $meta_box['show_on']['value']) ? false : true);
	else:
		$return = ($template_name == $meta_box['show_on']['value'] ? false : true);
	endif;
	return $return;
}
add_filter( 'cmb_show_on', 'metabox_hide_on_template', 10, 2 );

function templateFilter() {
	if (isset($_GET['post'])) {
		$id = $_GET['post'];
		$template = get_post_meta($id, '_wp_page_template', true);

		$dontShowEditor = array(
			'tpl-two-col.php','tpl-placeholder.php'
		);


		if(in_array($template, $dontShowEditor) || in_array($id, $dontShowEditor)){
			remove_post_type_support( 'page', 'editor' );
		}

	}
}
add_action('init', 'templateFilter');

