<?php
/**
 * PCAPredict Functions
 *
 * Functions
 *
 * @author 		PCAPredict
 * @package 	pcapredict
 * @version     1.0.2
 */
 
/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * function pcapredict_get_field()
 * gets the value of a meta box field for a pcapredict post
 * @param (string) $field is the name of the field to return
 * @param (int) $post_id is the id of the post for which to look for the field in - defaults to current loop post
 * @param (string) $prefix is the prefix to use for the custom field key. Defaults to _pcapredict_
 * return (string) $field the value of the field
 */
function pcapredict_get_field( $field, $post_id = '', $prefix = '_pcapredict_' ) {
	
	global $post;
	
	/* if no post id is provided use the current post id in the loop */
	if( empty( $post_id ) )
		$post_id = $post->ID;
	
	/* if we have no field name passed go no further */
	if( empty( $field ) )
		return false;
	
	/* build the meta key to return the value for */
	$key = $prefix . $field;
	
	/* gete the post meta value for this field name of meta key */
	$field = get_post_meta( $post_id, $key, true );
	
	return apply_filters( 'pcapredict_field_value', $field );
}

/**
 * function pcapredict_get_setting()
 *
 * gets a named plugin settings returning its value
 * @param	mixed	key name to retrieve - this is the key of the stored option
 * @return	mixed	the value of the key
 */
function pcapredict_get_setting( $name = '' ) {
	
	/* if no name is passed */
	if( empty( $name ) ) {
		return false;
	}
	
	/* get the option */
	$pcaOptions = get_option( '_pca_settings' );
	$setting = $pcaOptions[ $name ];
	
	/* check we have a value returned */
	if( empty( $setting ) ) {
		return false;
	}
	
	return apply_filters( 'pcapredict_get_setting', $setting );
}

/**
 * pcapredict_on_activation()
 * On plugin activation makes current user a wpbasis user and
 * sets an option to redirect the user to another page.
 */
function pcapredict_on_activation() {
	
	/* set option to initialise the redirect */
	add_option( 'pcapredict_activation_redirect', true );

}
register_activation_hook( __FILE__, 'pcapredict_on_activation' );

/**
 * pcapredict_activation_redirect()
 * Redirects user to the settings page for wp basis on plugin
 * activation.
 */
function pcapredict_activation_redirect() {
	
	/* check whether we should redirect the user or not based on the option set on activation */
	if( true == get_option( 'pcapredict_activation_redirect' ) ) {
		
		/* delete the redirect option */
		delete_option( 'pcapredict_activation_redirect' );
		
		/* redirect the user to the wp basis settings page */
		wp_redirect( admin_url( 'admin.php?page=pcapredict_settings' ) );
		exit;
		
	}
	
}
add_action( 'admin_init', 'pcapredict_activation_redirect' );

/**
 * Display an admin notice, if not on the integration screen and if the account isn't yet connected.
 * @since  1.0.0
 * @return void
 */
function pcapredict_maybe_display_admin_notices () {
	if ( isset( $_GET['page'] ) && 'pcapredict_settings' == $_GET['page'] ) return; // Don't show these notices on our admin screen.

	$accCode = pcapredict_get_setting( 'account_code' ) ;
	
	if ( false === $accCode ) {		
		$url = get_settings_url();
		echo '<div class="updated fade"><p>' . sprintf( __( '%sCreate%s an account or %slog in%s to %sconfigure PCAPredict%s.', 'pcapredict' ), '<strong>', '</strong>', '<strong>', '</strong>','<a href="' . esc_url( $url ) . '">', '</a>' ). '</p></div>' . "\n" ;
	}	
} 
add_action( 'admin_notices', 'pcapredict_maybe_display_admin_notices' );

/**
 * Generate a URL to our specific settings screen.
 * @since  1.0.0
 * @return string Generated URL.
 */
function get_settings_url () {
	$url = admin_url( 'admin.php' );
	$url = add_query_arg( 'page', 'pcapredict_settings', $url );
	return $url;
}

/**
 * pcapredict_hook_javascript()
 * 
 * Adds the PCA tag to the head of every page
 */
function pcapredict_hook_javascript() {
	
	$accCode = pcapredict_get_setting( 'account_code' );

	$pcaCustomJs = json_decode( pcapredict_get_setting( 'custom_javascript' ) );

	if ( $accCode ) { ?>
	
        <script>
	        (function (a, c, b, e) {
	        a[b] = a[b] || {}; a[b].initial = { accountCode: "<?php echo ( sanitize_text_field( $accCode ) ); ?>", host: "<?php echo ( sanitize_text_field( $accCode ) ); ?>.pcapredict.com" };
	        a[b].on = a[b].on || function () { (a[b].onq = a[b].onq || []).push(arguments) }; var d = c.createElement("script");
	        d.async = !0; d.src = e; c = c.getElementsByTagName("script")[0]; c.parentNode.insertBefore(d, c)
	        })(window, document, "pca", "//<?php echo ( sanitize_text_field( $accCode ) ); ?>.pcapredict.com/js/sensor.js");
			
			(function($) {

				pca.on('data', function(source, key, address, variations) {
					var provNameElId = "";
					
					if (pca.platform.productList.hasOwnProperty(key) && pca.platform.productList[key].hasOwnProperty("PLATFORM_CAPTUREPLUS")) {
						
						// Update the country field first in case it produes a selector for the county/state.$_COOKIE
						for (var b = 0; b < pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings.length; b++) {
							
							var ele = pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings[b].fields.filter(function(y) { return y.field === "{CountryName}"; });
							
							if (ele) {
								var el = document.getElementById(ele[0].element);

								if (el && el.options) {
									for (var j = 0; j < el.options.length; j++) {
										if (el.options[j].value === address.CountryIso2) {
											el.selectedIndex = j;
											if ($ && Select2) {
												$('select').trigger('change.select2');
											}
											break;
										}
									}
									pca.fire(el, 'change');
								}
							}
						}

						for (var b = 0; b < pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings.length; b++) {
							
							var ele = pca.platform.productList[key].PLATFORM_CAPTUREPLUS.bindings[b].fields.filter(function(y) { return y.field === "{ProvinceName}"; });

							if (ele) {
								var el = document.getElementById(ele[0].element);
								
								if (el && el.options) {
									for (var j = 0; j < el.options.length; j++) {
										if (el.options[j].text === address.ProvinceName) {
											el.selectedIndex = j;
											if ($ && Select2) {
												$('select').trigger('change.select2');
											}
											break;
										}
									}
									pca.fire(el, 'change');
								}
							}
						}
					}
				});

				<?php if ($pcaCustomJs) echo sanitize_text_field ( stripslashes( $pcaCustomJs ) ); ?>
			})(jQuery);
		
        </script>

        
	<?php }
}
add_action( 'wp_head', 'pcapredict_hook_javascript' );
add_action( 'admin_head', 'pcapredict_hook_javascript' );

function pcapredict_allow_setup() {
	
	$qval = isset( $_REQUEST[ 'pcasetup_ts' ] );
	if ( $qval ) {
		set_transient( 'allow_pca_setup', sanitize_text_field( $_REQUEST[ 'pcasetup_ts' ] ), 20 * MINUTE_IN_SECONDS );		
	}	
	if ( get_transient( 'allow_pca_setup' ) ) {
		remove_action( 'template_redirect', 'wc_send_frame_options_header' );
		remove_action( 'admin_init', 'send_frame_options_header' );
	}
}
add_action( 'init', 'pcapredict_allow_setup', 20, 0 );


