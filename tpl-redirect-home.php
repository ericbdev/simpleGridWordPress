<?php
/* Template Name: Redirect Home*/
$permaLink = (function_exists('icl_get_home_url') ? icl_get_home_url() :  get_permalink(get_id('home')));
wp_redirect($permaLink,  301);
die();


