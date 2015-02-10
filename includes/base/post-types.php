<?php
$supports = array('title','custom-fields', 'revisions');
//$generic = new customPostType('generic', $supports);

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