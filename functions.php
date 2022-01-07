<?php
/**
 * WooCommerce - Remove "downloads" from My Account menu items
 * Author: Yaser Alhosani
 */
function custom_remove_downloads_my_account( $items ) {
    unset($items['downloads']);
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 10, 1 );
?>
