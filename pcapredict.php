<?php
/*
Plugin Name: PCA Predict
Plugin URI: https://www.pcapredict.com
Description: Address verification made easy - a faster, smarter way to capture and verify addresses, phone and email
Version: 1.0.5
Author: PCA Predict
Author URI: https://www.pcapredict.com
License: GPLv2 or later
Text Domain: pcapredict
*/

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * initialises plugin
 * @return [type] [description]
 */
function pcapredict_init() {

	/* define variable for path to this plugin file. */
	define( 'PCAPREDICT_LOCATION', dirname( __FILE__ ) );
	define( 'PCAPREDICT_LOCATION_URL', plugin_dir_url( __FILE__ ) );

	/* load required files & functions */
	require_once( dirname( __FILE__ ) . '/functions/pcapredict-functions.php' );
    
    if ( is_admin() ) {
        /* load admin files & functions */
        require_once( dirname( __FILE__ ) . '/functions/admin/admin.php' );
        require_once( dirname( __FILE__ ) . '/functions/admin/ajax.php' );
        require_once( dirname( __FILE__ ) . '/functions/admin/woocommerce/woocommerce.php' );
    }
        
}
add_action( 'init', 'pcapredict_init' );


/**
 * Returns current plugin version. (https://code.garyjones.co.uk/get-wordpress-plugin-version)
 * 
 * @return string Plugin version
 */
function pcapredict_plugin_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}
