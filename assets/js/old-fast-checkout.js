(function( $ ) {
	'use strict';
	window.addEventListener('scroll', function() {
	    const sidebar = document.getElementById('stickySidebar');
	    const rect = sidebar.getBoundingClientRect();

	    if (rect.top <= 100) {
	        sidebar.classList.add('sticky');
	    } else {
	        sidebar.classList.remove('sticky');
	    }
	});
	

	jQuery(document).ready(function($) {

		var categoryID = document.getElementById('fastCheckoutcategoryID').value;
		var productID = document.getElementById('fastCheckoutProductID').value;

		// Set default values if empty
		if (!categoryID) {
		    categoryID = 1376;
		}

		if (!productID) {
		    productID = 0;
		}

		console.log("Category ID:", categoryID);
		console.log("Product ID:", productID);

		$('input[name="product-category"][value="' + categoryID + '"]').prop('checked', true);
		$('input[name="product-category"][value="' + categoryID + '"]').closest('.fast-checkout-radio-select-category').addClass('active');
		if ($('input[name="product-category"][value="1376"]').prop('checked')) {
		    $('.no-time-limit').show();
		} else {
		    $('.no-time-limit').hide();
		}


	
        $.ajax({
            url: digiwoScriptVars.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_products_by_category',
                category_id: categoryID
            },
            success: function(response) {
            	$('.radio-category').hide();
                $('#products-radio-container').html(response);
                // Check the radio button for category id 1375
                $('input[name="product"]').prop('disabled', false);  

                $('input[name="product"][value="' + productID + '"]').prop('checked', true);
				$('input[name="product"][value="' + productID + '"]').closest('.fast-checkout-radio-select-product').addClass('active');
		
			    

				//$('input[name="product"][value="22"]').prop('checked', true);
				//$('input[name="product"][value="22"]').closest('.fast-checkout-radio-select-product').addClass('active');

				$('input[name="add-on-trading[]"]').prop('disabled', false);				
				$('.fast-checkout-radio-select-add-ons').removeClass('fast-checkout-btn-disable');

				 $.ajax({
			        type: 'POST',
			        url: digiwoScriptVars.ajax_url,
			        data: {
			            action: 'clear_and_add_to_cart',
			            product_id: productID
			        },
			        success: function(response) {
			            console.log(response);
			        }
			    });
				$.get(digiwoScriptVars.ajax_url, { action: 'digiwoo_get_order_review' }, function(data) {
					$('.woocommerce-checkout-review-order-table').replaceWith(data);
				});
			    // Call the updateTotalOrder function
			    updateTotalOrder();
            }
            
        });

	    // Function to update the total order
	    function formatCurrency(value) {
	        return new Intl.NumberFormat('en-US', {
	            style: 'currency',
	            currency: 'USD',
	            minimumFractionDigits: 0,
	            maximumFractionDigits: 0
	        }).format(value);
	    }

	    function updateTotalOrder(discountAmount = 0) {
		    var productPrice = parseFloat($('input[name="product"]:checked').data('price') || 0);
		    var addOnPrice = 0;
		    $('input[name="add-on-trading[]"]:checked').each(function() {
		        var addOnPercentage = parseFloat($(this).data('percentage') || 0);
		        if (addOnPercentage) {
		            addOnPrice += addOnPercentage * productPrice;
		        } else {
		            addOnPrice += parseFloat($(this).data('price') || 0);
		        }
		    });
		    var total = (productPrice + addOnPrice) - discountAmount; // Subtract the discount

		    console.log("Product Price:", productPrice);
			console.log("AddOn Price:", addOnPrice);
			console.log("Discount Amount:", discountAmount);

		    $('#total-order-value').text(formatCurrency(total));
		    $('.fast-checkout-total .woocommerce-Price-amount bdi').text(formatCurrency(total));
		}

	    $('button[name="apply_coupon"]').on('click', function(e) {
	        e.preventDefault();
	        
	        var coupon_code = $('input[name="coupon_code"]').val();
	        
	        $.ajax({
	            url: digiwoScriptVars.ajax_url, // This variable is automatically defined by WordPress if you've enqueued your script using wp_enqueue_script
	            method: 'POST',
	            data: {
	                action: 'apply_coupon_code',
	                coupon_code: coupon_code
	            },
	            success: function(response) {
	                if (response.success) {
				        $('#displayed-coupon-code').append('Coupon: <span data-couponcode="' + coupon_code + '">' + coupon_code + '</span> <button class="remove-coupon-btn">[Remove]</button><br>');
						// Clear the coupon code input field if you still want this functionality
				        $('#coupon_code').val('');

	                    jQuery(document.body).trigger('update_checkout');
                    	jQuery(document.body).trigger('wc_fragment_refresh');

                    	// If the response contains the discount amount
				        if(response.data && response.data.discountAmount) {
				            updateTotalOrder(response.data.discountAmount);
				        } else {
				            updateTotalOrder();
				        }

	                } else {
	                    alert(response.data.message);
	                }
	            }
	        });
	    });


	    $(document).on('click', '.remove-coupon-btn', function(e) {
	    e.preventDefault();
	    var couponToRemove = $(this).prev('span[data-couponcode]').data('couponcode');
	    
	    $.ajax({
	        url: digiwoScriptVars.ajax_url,
	        method: 'POST',
	        data: {
	            action: 'remove_coupon_code',
	            coupon_code: couponToRemove
	        },
	        success: function(response) {
	            if (response.success) {
	                // Remove the coupon display from the DOM.
	                $('span[data-couponcode="' + couponToRemove + '"]').parent().remove();

	                // Update the checkout/cart if needed.
	                jQuery(document.body).trigger('update_checkout');
	                jQuery(document.body).trigger('wc_fragment_refresh');
	                
	                // Optionally, you can update the total order if the coupon removal affects the total.
	                updateTotalOrder();
	            } else {
	                alert(response.data.message);
	            }
	        }
	    });
	});


	    function removeAppliedCoupon() {
		    if ($('#displayed-coupon-code span[data-couponcode]').length) {
		        // Extract the currently applied coupon code
		        var coupon_code = $('#displayed-coupon-code span[data-couponcode]').data('couponcode');
		        // Send an AJAX request to remove the coupon
		        $.ajax({
		            url: digiwoScriptVars.ajax_url,
		            method: 'POST',
		            data: {
		                action: 'remove_coupon_code',
		                coupon_code: coupon_code
		            },
		            success: function(response) {
		                if (response.success) {
		                    $('#displayed-coupon-code').empty();  // Remove displayed coupon code and the "Remove" button
		                    updateTotalOrder();
		                } else {
		                    alert(response.data.message);
		                }
		            }
		        });
		    }
		}

		// Use the function on product change
		$(document).on('change', 'input[name="product"]', function() {
		    removeAppliedCoupon();
		});


	    $('input[name="product-category"]').change(function() {	    	
	        var categoryID = $(this).val();	        
	        $('.fast-checkout-radio-select-category').removeClass('active');
	        $('.fast-checkout-radio-select-add-ons').removeClass('active');
	        $('input[name="add-on-trading[]"]').prop('checked', false);
	        $('input[name="add-on-trading[]"]').prop('disabled', true);
	        $('.fast-checkout-radio-select-add-ons').addClass('fast-checkout-btn-disable');	        
	        $('.product-category-section').addClass('loading');
	        $('.products-section').addClass('loading');
	    	$('.add-on-trading-section').addClass('loading');

		    updateTotalOrder();

	        if ($(this).val() == '1376') {
	            $('.no-time-limit').show(); // Show the div
	            $('input[name="product"]').prop('checked', false);
	        } else {
	            $('.no-time-limit').hide(); // Hide the div for other categories
	            $('input[name="add-on-trading[]"][value="no-time-limit"]').prop('checked', false);
	            $('input[name="product"]').prop('checked', false);
	        }

	        updateTotalOrder();

	        // Then, add .active class to the parent div of the checked radio
	        if ($(this).is(':checked')) {
	            $(this).closest('.fast-checkout-radio-select-category').addClass('active');
	        }
	        $.ajax({
	            url: digiwoScriptVars.ajax_url,
	            type: 'POST',
	            data: {
	                action: 'fetch_products_by_category',
	                category_id: categoryID
	            },
	            success: function(response) {
	            	$('.radio-category').hide();
	                $('#products-radio-container').html(response);
	                $('input[name="product"]').prop('disabled', false);
	            },
                complete:function(){
                	$('.product-category-section').removeClass('loading');
                	$('.products-section').removeClass('loading');
                	$('.add-on-trading-section').removeClass('loading');		                    	
                }
	            
	        });
	        
	    });

	    function setFastCheckoutProductID() {
	        var $checkedProduct = $('input[name="product"]:checked');
	        var $noTimeLimitAddon = $('input[name="add-on-trading[]"][value="no-time-limit"]');
	        
	        // If 'no-time-limit' checkbox is checked
	        if ($noTimeLimitAddon.is(':checked')) {
	            $('#fastCheckoutProgramID').val($checkedProduct.data('idaddon'));
	        } else {
	            // If it's unchecked, set the value to the checked product's data-id
	            $('#fastCheckoutProgramID').val($checkedProduct.data('id'));
	        }
	    }

	    $(document).on('change', 'input[name="product"]', function() {
	    	   

	    	var productId = $(this).val();
	    	$('.spinner-order-total').show();
	    	$('.checkout-order-total').hide(); 	
	    	$('.fast-checkout-radio-select-product').removeClass('active');
	    	$('.products-section').addClass('loading');
	    	$('.add-on-trading-section').addClass('loading');
	    	$('input[name="add-on-trading[]"]').prop('checked', false);
	    	$('.fast-checkout-radio-select-add-ons').removeClass('active');   

	    	setFastCheckoutProductID();

	        // Then, add .active class to the parent div of the checked radio
	        if ($(this).is(':checked')) {
	            $(this).closest('.fast-checkout-radio-select-product').addClass('active');
	        }
		    // First, clear the cart
		    $.ajax({
		        url: digiwoScriptVars.ajax_url,
		        type: 'POST',
		        data: {
		            action: 'clear_cart'
		        },
		        success: function(response) {
		            if (response.success) {
		                // Now, add the selected product to the cart
		                $.ajax({
		                    url: digiwoScriptVars.ajax_url,
		                    type: 'POST',
		                    data: {
		                        action: 'add_product_to_cart',
		                        product_id: productId
		                    },
		                    beforeSend:function(){
		                    	$('input[name="product"]').prop('disabled', true);
		                    },
		                    success: function(response) {
		                        if (response.success) {
		                           	jQuery(document.body).trigger('update_checkout');
		                           	$(document.body).trigger('wc_fragment_refresh');
		                           	$.get(digiwoScriptVars.ajax_url, { action: 'digiwoo_get_order_review' }, function(data) {
						                $('.woocommerce-checkout-review-order-table').replaceWith(data);
						            });
						            $('input[name="add-on-trading[]"]').prop('disabled', false);
						        	$('.fast-checkout-radio-select-add-ons').removeClass('fast-checkout-btn-disable');
						        } else {
		                            alert('There was an error adding the product to the cart.');
		                        }
		                        $('.spinner-order-total').hide();
	    						$('.checkout-order-total').show();	    						
		                    },
		                    complete:function(){
		                    	$('input[name="product"]').prop('disabled', false);
		                    	$('.products-section').removeClass('loading');
		                    	$('.add-on-trading-section').removeClass('loading');
		                    }
		                });

		            }
		        }
		    });
		    updateTotalOrder();
	    });


		var ajaxInProgress = false;
		$(document).on('change', 'input[name="add-on-trading[]"]', function(e) {
			e.stopPropagation();
			if (ajaxInProgress) {
	            return; // Exit if an AJAX request is already in progress
	        }
			$('.spinner-order-total').show();
	    	$('.checkout-order-total').hide();
	    	$('.add-on-trading-section').addClass('loading');

	    	if ($(this).is(':checked')) {
            $(this).closest('.fast-checkout-radio-select-add-ons').addClass('active');
	        } else {
	            // If checkbox is unchecked, remove .active class from its outermost parent div
	            $(this).closest('.fast-checkout-radio-select-add-ons').removeClass('active');
	        }

	        if ($(this).val() === 'no-time-limit') {
	            setFastCheckoutProductID();
	        }

	        ajaxInProgress = true; // Set the flag to true
			var addOnKey = $(this).val();
		    var isChecked = $(this).prop('checked');
		    var mainProductPrice = parseFloat($('input[name="product"]:checked').data('price') || 0);  		

		    setTimeout(function() {
		    $.ajax({
		        url: digiwoScriptVars.ajax_url,
		        type: 'POST',
		        data: {
		            action: 'handle_add_on_product',
		            add_on_key: addOnKey,
		            is_checked: isChecked,
		            main_product_price: mainProductPrice
		        },
		        success: function(response) {
		            if (response.success) {
		            	$.get(digiwoScriptVars.ajax_url, { action: 'digiwoo_get_order_review' }, function(data) {
						                $('.woocommerce-checkout-review-order-table').replaceWith(data);
						            });
		                updateTotalOrder();
		            } else {
		                alert('There was an error handling the add-on product.');
		            }
		            $('.spinner-order-total').hide();
	    			$('.checkout-order-total').show();
		        },
                complete:function(){
                	$('.add-on-trading-section').removeClass('loading');
                }
		    });
		    removeAppliedCoupon();
		    ajaxInProgress = false;
		    }, 100); // 100ms delay

		});

		 $('input[type="checkbox"][name="add-on-trading[]"]').on('change', function() {
        // If the checkbox is checked, add .active class to its parent div
        if ($(this).is(':checked')) {
            $(this).closest('div').addClass('active');
        } else {
            // If unchecked, remove the .active class
            $(this).closest('div').removeClass('active');
        }

		
    });

	});

	
})( jQuery );