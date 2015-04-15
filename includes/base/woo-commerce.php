<?php

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}


remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'woocommerce_wrappers', 10);
add_action('woocommerce_after_main_content', 'woocommerce_wrappers', 10);

function woocommerce_wrappers(){
	echo '';
}
