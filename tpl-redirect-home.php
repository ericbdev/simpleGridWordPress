<?php
/* Template Name: Redirect: Home*/
wp_redirect((function_exists('icl_get_home_url') ? icl_get_home_url() :  get_home_url()), 301);
exit;