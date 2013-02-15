<?php
/**
 * The Template for displaying the cart for WC.
 *
 * Override this template by copying it to yourtheme/sensei/woocommerce/add-to-cart.php
 *
 * @author 		WooThemes
 * @package 	Sensei/Templates
 * @version     1.0.0
 */

global $post, $current_user, $woocommerce;

$wc_post_id = get_post_meta( $post->ID, '_course_woocommerce_product', true );
		
// Get User Meta
get_currentuserinfo();
// Check if customer purchased the product
if ( sensei_customer_bought_product( $current_user->user_email, $current_user->ID, $wc_post_id ) ) { ?>    
    <div class="woo-sc-box tick"><?php _e( 'You are currently taking this course.', 'woothemes-sensei' ); ?></div>
<?php } else {
    // based on simple.php in WC templates/single-product/add-to-cart/
    if ( 0 < $wc_post_id ) {
    	// Get the product
    	$product = new WC_Product( $wc_post_id );
    	if ( $product->is_purchasable() ) {
    		// Check Product Availability
    		$availability = $product->get_availability();
    		if ($availability['availability']) {
    			echo apply_filters( 'woocommerce_stock_html', '<p class="stock '.$availability['class'].'">'.$availability['availability'].'</p>', $availability['availability'] );
    		} // End If Statement
    		// Check for stock
    		if ( $product->is_in_stock() ) { ?>
    			<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype="multipart/form-data">
    				<?php if (! sensei_check_if_product_is_in_cart( $wc_post_id ) ) { ?>
    					<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->get_price_html(); ?> - <?php echo apply_filters('single_add_to_cart_text', __('Purchase this Course', 'woothemes-sensei'), $product->product_type); ?></button>
    		 		<?php } // End If Statement ?>
    		 	</form>
    		 <?php } // End If Statement
    	} // End If Statement
    } // End If Statement
} // End If Statement ?>