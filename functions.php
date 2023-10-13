<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

function custom_redirect() {
    // Get the current request URI.
    $request_uri = $_SERVER['REQUEST_URI'];

    // If it's the homepage.
    if ('/' === $request_uri) {
        wp_redirect(home_url("/challenge/"));
        exit;
    }

    // Match the /ref/{string}/ structure (with or without query parameters).
    if (preg_match('|^/ref/([\w-]+)/?(\?.*)?$|', $request_uri, $matches)) {
        // Extract the string from the matches.
        $dynamic_string = $matches[1];

        // Check for query string and extract if it exists.
        $query_string = isset($matches[2]) ? $matches[2] : '';

        // Construct the new URL.
        $new_url = home_url("/challenge/ref/{$dynamic_string}/{$query_string}");
        
        // Perform the redirection.
        wp_redirect($new_url);
        exit;
    }
    
    // Use a regex pattern to match the /ref/{dynamic_number}/ structure.
    if ( preg_match('|^/ref/([\d\w]+)/?$|', $request_uri, $matches)) {
        // Extract the dynamic number from the matches.
        $dynamic_value = $matches[1];
        
        // Construct the new URL.
        $new_url = home_url( "/challenge/ref/{$dynamic_value}/" );
        
        // Perform the redirection.
        wp_redirect( $new_url );
        exit;
    }
}
add_action( 'template_redirect', 'custom_redirect',20 );



include get_stylesheet_directory() . '/inc/digiwoo-functions.php';

function digiwoo_woocommerce_checkout_terms_and_conditions() {
  remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
}
add_action( 'wp', 'digiwoo_woocommerce_checkout_terms_and_conditions' );