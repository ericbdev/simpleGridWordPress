<?php
/* Template Name: Redirect */
$children = array();
$children = get_pages(array('child_of'=>$post->ID,'sort_order'=>'ASC','sort_column' => 'menu_order'));

if(!empty($children)):
	if(isset($children[0])):
		$permaLink = get_permalink($children[0]->ID);
		wp_redirect($permaLink, 301);
		exit;
	endif;
else:
	include 'index.php';
endif;

