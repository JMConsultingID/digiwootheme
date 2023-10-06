<?php
/**
 * Template Name: DigiWoo Checkout
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

global $woocommerce;

get_header();
while ( have_posts() ) :
	the_post();
	?>

<main id="content" <?php post_class( 'site-main' ); ?>>
	<div class="page-content">
		<?php the_content(); ?>

		<!--Custom Checkout Start -->
		<form name="checkout" method="post" class="checkout woocommerce-checkout <?php echo esc_attr( $extra_class ); ?>" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<div class="bootstrap-wrapper">
		    <div class="container">
		        <div class="row">

		<div class="col-md-8">

			<!-- Cart Items -->
        <div class="fast-checkout-cart">
            <?php
            // echo do_shortcode('[woocommerce_cart]');
            ?>
        </div>

        	<!-- Products Radio -->
	        <h1 class="fast-checkout-title">Start a New Challenge</h1>
	        <p>Select a one phase or two phase assessment to start your trading journey today.</p>

	        <!-- Product Category Radio -->
	        <div class="product-category-section" style="margin-top:10px; margin-bottom:30px;">
	            <div class="radio-input" style="margin-top:10px; margin-bottom:10px;">
	            <div class="row">	
	            <?php
	            $uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
	            $product_categories = get_terms('product_cat', array('include' => array(1375, 1376), 'hide_empty' => 0));
	            foreach ($product_categories as $category) {
	            		echo '<div class="col-md-6" style="margin:10px 0px;">';
	            		echo '<div class="fast-checkout-radio-select fast-checkout-radio-select-category fast-checkout-border-style-1 fast-checkout-title-category">';
	                    echo '<input type="radio" name="product-category" class="fast-checkout-radio-input" value="' . $category->term_id . '">' . $category->name;
	                    echo '</div>';
	                    echo '</div>';
	                }
	            ?>
	            </div>
	        	</div>
	        </div>

	        <!-- Products Radio -->
	        <h3>Account Balance</h3>
	        <p>Select your initial starting capital</p>
	        <div class="products-section" style="margin-top:10px; margin-bottom:30px;">	        	
	            <div class="radio-input" style="margin-top:10px; margin-bottom:10px;">


	            <div id="products-radio-container" class="row ">            	
					<div class="radio-category">
						<div class="row" style="margin:0px;">

	                	</div>
	        		</div>
	            </div>
	            </div>
	        </div>


	        <h3>Addons</h3>
	        <p>Tailor your account to suit your trading style and preference.</p>
	        <div class="add-on-trading-section" style="margin-top:10px; margin-bottom:30px;">    
	        	<div class="add-on-trading">
	            <div class="radio-input add-on-trading-input" style="margin-top:10px; margin-bottom:10px;">

	            	<div class="row">

					<div class="col-md-8" style="margin:10px 0px;">
						<div class="fast-checkout-radio-select fast-checkout-border-style-1 d-flex justify-content-between lh-condensed fast-checkout-title-add-on fast-checkout-radio-select-add-ons"><div>
					    <input type="checkbox" name="add-on-trading[]" value="increase-profit" data-percentage="0.20" disabled>Increase profit split <span class="input-checkbox"> 90/10</span></div><span class="fast-checkout-box-color-style-2 fast-checkout-text-color-style-1 py-3 px-2 py-1 input-price">+20%</span>
						</div>
					</div>

					<div class="col-md-8" style="margin:10px 0px;">
						<div class="fast-checkout-radio-select fast-checkout-border-style-1 d-flex justify-content-between lh-condensed fast-checkout-title-add-on fast-checkout-radio-select-add-ons"><div>
					    <input type="checkbox" name="add-on-trading[]" value="increase-leverage" data-percentage="0.25" disabled>Increase leverage <span class="input-checkbox">1:100</span></div><span class="fast-checkout-box-color-style-2 fast-checkout-text-color-style-1 py-3 px-2 py-1 input-price">+25%</span>
						</div>
					</div>

					<div class="col-md-8 no-time-limit" style="margin:10px 0px;display: none;">
						<div class="fast-checkout-radio-select fast-checkout-border-style-1 d-flex justify-content-between lh-condensed fast-checkout-title-add-on fast-checkout-radio-select-add-ons"><div>
					    <input type="checkbox" name="add-on-trading[]" value="no-time-limit" data-percentage="0.05" disabled>No time limit<span class="input-checkbox">Unlimited</span></div><span class="fast-checkout-box-color-style-2 fast-checkout-text-color-style-1 py-3 px-2 py-1 input-price">+5%</span>
						</div>
					</div>

					<div class="col-md-8" style="margin:10px 0px;">
						<div class="fast-checkout-radio-select fast-checkout-border-style-1 d-flex justify-content-between lh-condensed fast-checkout-title-add-on fast-checkout-radio-select-add-ons"><div>
					    <input type="checkbox" name="add-on-trading[]" value="bi-weekly-payouts" data-percentage="0.05" disabled>Bi weekly payouts<span class="input-checkbox">Instead of Monthly</span></div><span class="fast-checkout-box-color-style-2 fast-checkout-text-color-style-1 py-3 px-2 py-1 input-price">+5%</span>
						</div>
					</div>

					<div class="col-md-8" style="margin:10px 0px;">
						<div class="fast-checkout-radio-select fast-checkout-border-style-1 d-flex justify-content-between lh-condensed fast-checkout-title-add-on fast-checkout-radio-select-add-ons"><div>
					    <input type="checkbox" name="add-on-trading[]" value="raw-spreads" data-percentage="0.20" disabled>Raw spreads<span class="input-checkbox"></span></div><span class="fast-checkout-box-color-style-2 fast-checkout-text-color-style-1 py-3 px-2 py-1 input-price">+20%</span>
						</div>
					</div>



	        	</div>

	            </div>
	        	</div>
	        </div>

	        <h3>Billing Address</h3>
	        <p>Please fill in your billing details below</p>

	        <!-- Billing Fields -->
		    <div class="fast-checkout-billing">

		    	<?php

		    	$checkout = WC()->checkout();
		            

				if ($checkout->get_checkout_fields()) { ?>
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				    <div class="woocommerce-billing-fields">

				    	<div class="row px-2">

				    	<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>


				    </div>

				    </div>
				    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				<?php }


		    	?>

		    </div>

		</div>

		<div class="col-md-4">

			<div class="is-sticky fast-checkout-border-style-2" id="stickySidebar">

			<!-- Payment Methods -->
			<!-- Total Order -->
			<div class="fast-checkout-total text-center">
				<div class="checkout-order-total text-center">				    
				    <h2 class="fast-checkout-title-price fast-checkout-text-color-style-1">
				    	<?php echo WC()->cart->get_total(); ?>				    		
				    </h2>
				</div>
				<div class="custom-loader spinner-order-total" role="status" style="display:none;">
					<span class="sr-only"></span>
				</div>
			</div>

		    <!-- Payment Methods -->
			<div class="fast-checkout-payment-wocoommerce">
				<?php
			    do_action('woocommerce_checkout_before_order_review');
			    do_action('woocommerce_checkout_order_review');  // Ini menampilkan metode pembayaran
			    do_action('woocommerce_checkout_after_order_review');
			    ?>
			</div>

			<div class="fast-checkout-place-order">
			<div class="place-order">
			    <noscript>
			        <?php _e('Your session has expired due to inactivity, please refresh your page to start again.', 'woocommerce'); ?>
			        <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Refresh', 'woocommerce'); ?>"><?php esc_html_e('Refresh', 'woocommerce'); ?></button>
			    </noscript>

			    <?php wc_get_template('checkout/terms.php'); ?>

			    <?php do_action('woocommerce_review_order_before_submit'); ?>

			    <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e('Place order', 'woocommerce'); ?>" data-value="<?php esc_attr_e('Complete Order', 'woocommerce'); ?>"><?php echo esc_html(apply_filters('woocommerce_order_button_text', __('Complete Order', 'woocommerce'))); ?></button>

			    <?php do_action('woocommerce_review_order_after_submit'); ?>

			    <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
			</div>
			</div>


	    </div>

		</div>

		</div>
	</div>

		    
		</div>

	</form>
	<!--Custom Checkout End -->
	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

	</div>
	<!--Page Content End -->
	<div class="fast-checkout-spacer"></div>
</main>
	<?php
endwhile;


get_footer();
