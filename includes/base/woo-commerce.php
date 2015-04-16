<?php
/**
 * Add WooCommerce Support
 */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/**
 * Allow WooCommerce to work with the default index.php or page.php template
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'woocommerce_wrapper_open', 10);
add_action('woocommerce_after_main_content', 'woocommerce_wrapper_close', 10);

function woocommerce_wrapper_open(){
	$return = '';
	$return .= "<section class='wrapper main-content default'>\n";
	$return .= "<div class='inner-wrapper small-padding'>\n";
	$return .= "<div class='row'>\n";
	$return .= "<div class='columns small-12'>\n";
	$return .= "\n";
	echo $return;
}
function woocommerce_wrapper_close(){
	$return = '';
	$return .= "\n";
	$return .= "</div>\n";
	$return .= "</div>\n";
	$return .= "</div>\n";
	$return .= "</section>\n";
	echo $return;
}
