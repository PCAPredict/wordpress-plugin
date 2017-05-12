<?php
/**
 * PCA Predict AJAX Functions
 *
 * Functions for ajaxy things.
 *
 * @author 		PCA Predict
 * @package 	pcapredict/admin
 * @version     1.0.2
 */

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * pcapredict_save_settings_callback()
 * callback for the save settigns ajax call
 * @return [type] [description]
 */
function pcapredict_save_settings_callback() {

	global $wpdb;
	$result = array (
		'success' => false
	);

	try {
		$accCode = $_POST["account_code"];
		$token = $_POST["account_token"];

		$settings = get_option( '_pca_settings' );
		$settings[ 'account_code' ] = sanitize_text_field ( $accCode );
		$settings[ 'account_token' ] = sanitize_text_field ( $token );

		if ( isset( $_POST['custom_javascript'] ) ) {
			$settings[ 'custom_javascript' ] = json_encode( sanitize_text_field( $_POST['custom_javascript'] ) );
		} else {
			$settings[ 'custom_javascript' ] = json_encode( '' );
		}

		update_option( '_pca_settings', $settings );
		$result['success'] = true;
	}
	catch(Exception $ex) {
		// we will only be passing back the default result
	}

	wp_send_json( $result );

}
add_action( 'wp_ajax_pcapredict_save_settings', 'pcapredict_save_settings_callback' );



/**
 * pcapredict_logout_callback()
 * callback for the logout ajax call
 * @return [type] [description]
 */
function pcapredict_logout_callback() {

	global $wpdb;
	$result = array ( 'success' => false );

	try {
		delete_option( '_pca_settings' );
		$result['success'] = true;
	}
	catch(Exception $ex) {
		// we will only be passing back the default result
	}
	wp_send_json( $result );
}
add_action( 'wp_ajax_pcapredict_logout', 'pcapredict_logout_callback' );


