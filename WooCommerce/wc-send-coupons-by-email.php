/**
 * Send an email each time an order with coupon(s) is completed
 * The email contains coupon(s) used during checkout process
 * Author: Yaser Alhosani
 */
function woo_email_order_coupons( $order_id ) {
    $order = new WC_Order( $order_id );
    if( $order->get_used_coupons() ) {
        $to = 'youremail@yourcompany.com';
        $subject = 'New Order Completed';
        $headers = 'From: My Name <youremail@yourcompany.com>' . "\r\n";
	    $message = 'A new order has been completed.\n';
	    $message .= 'Order ID: '.$order_id.'\n';
	    $message .= 'Coupons used:\n';
	    foreach( $order->get_used_coupons() as $coupon) {
	        $message .= $coupon.'\n';
	    }
	    @wp_mail( $to, $subject, $message, $headers );
    }
}
add_action( 'woocommerce_thankyou', 'woo_email_order_coupons' );
