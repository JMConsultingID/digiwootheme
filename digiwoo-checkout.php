<?php
/**
 * Template Name: DigiWoo Checkout
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
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
	            	<div class="btn-group btn-group-toggle w-100" data-toggle="buttons" role="group" aria-label="First group">

	            <?php
	            $uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
	            $product_categories = get_terms('product_cat', array('include' => array(16, 17), 'hide_empty' => 0));
	            foreach ($product_categories as $category) {
	            		echo '<label class="w-100 btn btn-outline py-4 my-3 rounded mx-1 fast-checkout-radio-select fast-checkout-radio-select-category fast-checkout-border-style-1 fast-checkout-title-category text-left">';
	            		echo '<i class="far fa-circle fa-lg mr-3"></i>';
	            		echo '<input type="radio" name="product-category" class="fast-checkout-radio-input" id="cat-' . $category->term_id . '" value="' . $category->term_id . '">' . $category->name;
	                    echo '</label>';
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

				    	<!-- Email Address -->
				    	<div class="col-md-12">
				        <p class="form-row form-row-wide" id="billing_email_field" data-priority="110">
				            <label for="billing_email" class=""><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="email" class="input-text " name="billing_email" id="billing_email" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_email')); ?>" autocomplete="email">
				        </p>
				    	</div>

				        <!-- First Name -->
				        <div class="col-md-6">
				        <p class="form-row form-row-first" id="billing_first_name_field" data-priority="10">
				            <label for="billing_first_name" class=""><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="text" class="input-text " name="billing_first_name" id="billing_first_name" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_first_name')); ?>" autocomplete="given-name" >
				        </p>
				        </div>

				        <!-- Last Name -->
				        <div class="col-md-6">
				        <p class="form-row form-row-last" id="billing_last_name_field" data-priority="20">
				            <label for="billing_last_name" class=""><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="text" class="input-text " name="billing_last_name" id="billing_last_name" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_last_name')); ?>" autocomplete="family-name">
				        </p>
				        </div>

				        <!-- Phone -->
				        <div class="col-md-12">
				        <p class="form-row form-row-wide" id="billing_phone_field" data-priority="100">
				            <label for="billing_phone" class=""><?php esc_html_e('Phone', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="tel" class="input-text " name="billing_phone" id="billing_phone" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>" autocomplete="tel">
				        </p>
				        </div>

				         <!-- Address Line 1 -->
				         <div class="col-md-12">
				        <p class="form-row form-row-wide" id="billing_address_1_field" data-priority="50">
				            <label for="billing_address_1" class=""><?php esc_html_e('Street address', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="text" class="input-text " name="billing_address_1" id="billing_address_1" placeholder="House number and street name" value="<?php echo esc_attr($checkout->get_value('billing_address_1')); ?>" autocomplete="address-line1">
				        </p>
				        </div>

				        <!-- Country -->
				        <div class="col-md-6">
				        <p class="form-row form-row-wide" id="billing_country_field" data-priority="40">
				            <label for="billing_country" class=""><?php esc_html_e('Country', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <?php
				            $countries = WC()->countries->get_countries();

							if ($countries) { ?>
							    <select name="billing_country" id="billing_country" class="country_to_state country_select" autocomplete="country">
							        <?php foreach ($countries as $code => $name) : ?>
							            <option value="<?php echo esc_attr($code); ?>" <?php selected($checkout->get_value('billing_country'), $code); ?>>
							                <?php echo esc_html($name); ?>
							            </option>
							        <?php endforeach; ?>
							    </select>
							<?php }


				           ?>
				        </p>
				        </div>

				        <!-- State/County -->
				        <div class="col-md-6">
				        <p class="form-row form-row-wide" id="billing_state_field" data-priority="80">
				            <label for="billing_state" class=""><?php esc_html_e('State / County', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="text" class="input-text " name="billing_state" id="billing_state" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_state')); ?>">
				        </p>
				        </div>

				        <!-- City -->
				        <div class="col-md-6">
				        <p class="form-row form-row-wide" id="billing_city_field" data-priority="70">
				            <label for="billing_city" class=""><?php esc_html_e('Town / City', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="text" class="input-text " name="billing_city" id="billing_city" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_city')); ?>" autocomplete="address-level2">
				        </p>
				        </div>

				        <!-- Zip/Postal Code -->
				        <div class="col-md-6">
				        <p class="form-row form-row-wide" id="billing_postcode_field" data-priority="90">
				            <label for="billing_postcode" class=""><?php esc_html_e('Postcode / ZIP', 'woocommerce'); ?>&nbsp;<abbr class="required" title="required">*</abbr></label>
				            <input type="text" class="input-text " name="billing_postcode" id="billing_postcode" placeholder="" value="<?php echo esc_attr($checkout->get_value('billing_postcode')); ?>" autocomplete="postal-code">
				        </p>
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


			    <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e('Place order', 'woocommerce'); ?>" data-value="<?php esc_attr_e('Complete Order', 'woocommerce'); ?>"><?php echo esc_html(apply_filters('woocommerce_order_button_text', __('Complete Order', 'woocommerce'))); ?></button>

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
