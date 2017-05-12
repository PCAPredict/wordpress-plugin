<?php
/**
 * PCA Predict WooCommerce Functions
 *
 * Functions for WooCommerce specific things.
 *
 * @author 		PCA Predict
 * @package 	pcapredict/admin
 * @version     1.0.2
 */
 
 
/* exit if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}



/**
 * pcapredict_hook_woocommerce_javascript()
 * 
 * Adds the PCA tag to the head of certain admin pages
 * if WooCommerce is installed
 */
function pcapredict_hook_woocommerce_javascript() {    
    /* Check if WooCommerce is active */
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        pcapredict_hook_javascript();
    }
}
add_action( 'show_user_profile', 'pcapredict_hook_woocommerce_javascript' );
add_action( 'edit_user_profile', 'pcapredict_hook_woocommerce_javascript' );
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'pcapredict_hook_woocommerce_javascript');


