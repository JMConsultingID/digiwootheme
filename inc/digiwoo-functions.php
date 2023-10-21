<?php

// function enqueue_child_theme_styles() {
//     wp_register_style('fast-checkout-style', get_stylesheet_directory_uri() . '/main.css', array(), '1.0.1', 'all');
//     wp_enqueue_style('fast-checkout-style');
// }
// add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');

// sets the correct variables when we're on the checkout pages
add_action('wp', 'pk_custom_checkout_wp');
function pk_custom_checkout_wp() {
    if(in_array(basename(get_page_template()), array('digiwoo-checkout.php'))) {
        if(!defined('WOOCOMMERCE_CART')) { define('WOOCOMMERCE_CART', true); }
        add_filter('woocommerce_is_checkout', '__return_true');
    }
}

function enqueue_digiwoo_scripts() {
    if (is_page_template('digiwoo-checkout.php')) {
            // Enqueue Bootstrap CSS
            wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(), '4.6.2', false);
            wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '4.6.2', true);
            wp_enqueue_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2', false);         
            wp_enqueue_style('fast-checkout-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), '1.0.1', false);
            wp_enqueue_script('digiwoo_script', get_stylesheet_directory_uri() . '/assets/js/fast-checkout.js', array('jquery'), '1.0', true);
            wp_localize_script('digiwoo_script', 'digiwoScriptVars', array(
                'ajax_url' => admin_url('admin-ajax.php')
            ));
    }    
}
add_action('wp_enqueue_scripts', 'enqueue_digiwoo_scripts', 99);

function empty_cart_and_add_product_on_page_load() {
    if (is_page_template('digiwoo-checkout.php')) {
        // Empty the cart
        WC()->cart->empty_cart();
        
        // Get the ID of the current post or page
        $current_post_id = get_queried_object_id();

        // Get the product ID from the custom field for the current post or page
        $product_object = get_field('select_woocommerce_product', $current_post_id);
        if ($product_object) {
            $product_id = $product_object->ID;

            // Add the product to the cart
            WC()->cart->add_to_cart($product_id);
        } else {
            // Default product to add if custom field is empty
            WC()->cart->add_to_cart(19);
        }
    }
}
add_action('wp', 'empty_cart_and_add_product_on_page_load');


function clear_and_add_to_cart() {
    if (class_exists('WC_Cart')) {
        // Clear the cart
        WC()->cart->empty_cart();

        // Get the product ID from the AJAX request
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        // Add the product to the cart
        if ($product_id) {
            WC()->cart->add_to_cart($product_id);
            echo 'Product added to cart!';
        } else {
            echo 'Invalid product ID!';
        }
    }
    wp_die(); // Ensure AJAX request dies properly
}
add_action('wp_ajax_clear_and_add_to_cart', 'clear_and_add_to_cart'); // If user is logged in
add_action('wp_ajax_nopriv_clear_and_add_to_cart', 'clear_and_add_to_cart'); // If user is not logged in


function fetch_products_by_category() {
    $category_id = $_POST['category_id'];
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $category_id
            )
        )
    );
    $products = new WP_Query($args);
    $output = '';
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            $title = get_the_title();
            if (preg_match('/(\$[\d,]+)/', $title, $matches)) {
                $amount = $matches[1];
            }
            $product_price = get_post_meta($product_id, '_price', true);
            $program_id = get_post_meta($product_id, '_program_id', true);
            $program_id_comb = get_post_meta($product_id, '_program_id_combination', true);
            $en_program_id = wp_encrypt_string($program_id);
            $en_program_id_comb = wp_encrypt_string($program_id_comb);
            $formatted_price = wc_price($product_price);
            $output .= '<label class="col-sm-6 btn"><div class="w-100 btn btn-outline-success px-3 rounded fast-checkout-radio-select fast-checkout-radio-select-product fast-checkout-border-style-1 fast-checkout-title-product text-left"><div class="d-flex justify-content-between lh-condensed"><div><i class="far fa-circle fa-lg mr-2"></i><input type="radio" name="product" value="' . $product_id . '" data-price="' . $product_price . '" data-id="' . $program_id . '" data-idaddon="' . $program_id_comb . '" disabled>' . $amount. '</div><span class="fast-checkout-box-color-style-2 fast-checkout-text-color-style-1 px-2 py-1 fast-checkout-title-product-price">' .$formatted_price. '</span></div></div>  </label>';
        }

    }
    echo $output;
    die();
}
add_action('wp_ajax_fetch_products_by_category', 'fetch_products_by_category');
add_action('wp_ajax_nopriv_fetch_products_by_category', 'fetch_products_by_category');


function digiwoo_add_product_to_cart() {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $add_ons = isset($_POST['add_ons']) ? $_POST['add_ons'] : array();

    if ($product_id) {
        $cart_item_data = array(); // This can be used to add custom data to the cart item

        // Add the product to the cart
        $added = WC()->cart->add_to_cart($product_id, 1);
        wp_send_json_success();

    }

    wp_send_json_error();
}
add_action('wp_ajax_add_product_to_cart', 'digiwoo_add_product_to_cart');
add_action('wp_ajax_nopriv_add_product_to_cart', 'digiwoo_add_product_to_cart');

function display_cart_item_add_ons($item_data, $cart_item) {
    if (isset($cart_item['add_ons'])) {
        foreach ($cart_item['add_ons'] as $add_on) {
            $item_data[] = array(
                'key'     => 'Add-On',
                'value'   => $add_on,
                'display' => '',
            );
        }
    }
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'display_cart_item_add_ons', 10, 2);

function clear_cart_overall() {
    WC()->cart->empty_cart();
    wp_send_json_success();
    //WC()->cart->add_to_cart(19);
    WC()->cart->add_to_cart(22);
}
add_action('wp_ajax_clear_cart', 'clear_cart_overall');
add_action('wp_ajax_nopriv_clear_cart', 'clear_cart_overall');


function clear_cart() {
    WC()->cart->empty_cart();
    wp_send_json_success();
}
add_action('wp_ajax_clear_cart', 'clear_cart');
add_action('wp_ajax_nopriv_clear_cart', 'clear_cart');


function handle_add_on_product() {
    $addOnKey = $_POST['add_on_key'];
    $is_checked = isset($_POST['is_checked']) && $_POST['is_checked'] === 'true';
    $main_product_price = isset($_POST['main_product_price']) ? floatval($_POST['main_product_price']) : 0;

    // Define your add-ons and their product IDs and percentages here
    $add_ons = array(
        'increase-profit' => array('product_id' => 233, 'percentage' => 0.20),
        'increase-leverage' => array('product_id' => 234, 'percentage' => 0.25),
        'no-time-limit' => array('product_id' => 232, 'percentage' => 0.05),
        'bi-weekly-payouts' => array('product_id' => 235, 'percentage' => 0.05),
        'raw-spreads' => array('product_id' => 236, 'percentage' => 0.20),
    );

    if (isset($add_ons[$addOnKey])) {
        $product_id = $add_ons[$addOnKey]['product_id'];
        $percentage = $add_ons[$addOnKey]['percentage'];
        $add_on_price = number_format($percentage * $main_product_price, 2, '.', '');

        if ($is_checked) {
            // Add the product to the cart with a custom price
            $cart_item_data = array('custom_price' => $add_on_price);
            WC()->cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);
        } else {
            // Remove the product from the cart
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                if ($cart_item['product_id'] == $product_id) {
                    WC()->cart->remove_cart_item($cart_item_key);
                    break;
                }
            }
        }
    }

    wp_send_json_success();
}
add_action('wp_ajax_handle_add_on_product', 'handle_add_on_product');
add_action('wp_ajax_nopriv_handle_add_on_product', 'handle_add_on_product');

function set_custom_price_for_add_on($cart_object) {
    foreach ($cart_object->cart_contents as $key => $value) {
        if (isset($value['custom_price'])) {
            $value['data']->set_price($value['custom_price']);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'set_custom_price_for_add_on', 10, 1);

function digiwoo_get_order_review() {
    woocommerce_order_review();
    die();
}
add_action('wp_ajax_digiwoo_get_order_review', 'digiwoo_get_order_review');
add_action('wp_ajax_nopriv_digiwoo_get_order_review', 'digiwoo_get_order_review');

function update_cart_via_ajax() {
    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();
    woocommerce_cart_totals();
    die();
}

add_action('wp_ajax_update_cart', 'update_cart_via_ajax');
add_action('wp_ajax_nopriv_update_cart', 'update_cart_via_ajax');

function get_states_for_country() {
    if(isset($_POST['country']) && !empty($_POST['country'])) {
        $states = WC()->countries->get_states($_POST['country']);

        if($states) {
            echo json_encode($states);
        } else {
            echo json_encode(array());
        }
    }
    die();
}
add_action('wp_ajax_get_states_for_country', 'get_states_for_country');         // If user is logged in
add_action('wp_ajax_nopriv_get_states_for_country', 'get_states_for_country');  // If user is not logged in

function wp_encrypt_string($plaintext) {
    $encryption_key = wp_salt('nonce');  // Use WordPress salt for added security
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($iv_length);

    $encrypted = openssl_encrypt($plaintext, 'aes-256-cbc', $encryption_key, 0, $iv);

    return base64_encode($encrypted . '::' . $iv);  // Combine encrypted data and IV for storage
}

function wp_decrypt_string($encrypted) {
    $encryption_key = wp_salt('nonce');
    list($encrypted_data, $iv) = explode('::', base64_decode($encrypted), 2);  // Split encrypted data and IV

    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

function apply_coupon_code() {
    if (!isset($_POST['coupon_code'])) {
        wp_send_json_error(array('message' => 'Coupon code not set!'));
        return;
    }    
    $coupon_code = sanitize_text_field($_POST['coupon_code']);
    if (WC()->cart->has_discount($coupon_code)) {
        wp_send_json_error(array('message' => 'Coupon has already been applied!'));
        return;
    }
    // Get the cart total before applying the coupon
    $total_before = WC()->cart->get_cart_contents_total();

    if (WC()->cart->apply_coupon($coupon_code)) {
        // Get the cart total after applying the coupon
        $total_after = WC()->cart->get_cart_discount_total();
        $subtotal = WC()->cart->get_cart_subtotal();
        $total = WC()->cart->get_total('edit');

        // Calculate the discount amount
        $discountAmount = $total_before - $total_after;

        // Send the discount amount in the success response
        wp_send_json_success(array('discountAmount' => $total_after, 'discountTemp' => $total));
    } else {
        wp_send_json_error(array('message' => 'Failed to apply coupon.'));
    }
}
// Attach the function to wp_ajax and wp_ajax_nopriv actions
add_action('wp_ajax_apply_coupon_code', 'apply_coupon_code');
add_action('wp_ajax_nopriv_apply_coupon_code', 'apply_coupon_code');

function remove_coupon_code() {
    if (!isset($_POST['coupon_code'])) {
        wp_send_json_error(array('message' => 'Coupon code not set!'));
        return;
    }
    
    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    if (WC()->cart->has_discount($coupon_code)) {
        WC()->cart->remove_coupon($coupon_code);
        wp_send_json_success();
    } else {
        wp_send_json_error(array('message' => 'Failed to remove coupon or coupon not applied.'));
    }
    wp_die(); // Ensure AJAX request dies properly
}

// Attach the function to wp_ajax and wp_ajax_nopriv actions
add_action('wp_ajax_remove_coupon_code', 'remove_coupon_code');
add_action('wp_ajax_nopriv_remove_coupon_code', 'remove_coupon_code');