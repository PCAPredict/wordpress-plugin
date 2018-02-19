<?php
/**
 * PCA Predict Admin Functions
 *
 * Functions for admin specific things.
 *
 * @author 		PCA Predict
 * @package 	pcapredict/admin
 * @version     1.0.5
 */
 
/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * function pcapredict_admin_styles()
 * outputs css for the admin pages
 */
function pcapredict_admin_styles( $hook ) {

	if ( $hook == 'toplevel_page_pcapredict_settings' ) {
	} else {
		
		return;
	}

	wp_enqueue_style( 'pcapredict-admin-foundation-styles', PCAPREDICT_LOCATION_URL . '/css/foundation.min.css' );
	wp_enqueue_style( 'pcapredict-admin-styles', PCAPREDICT_LOCATION_URL . '/css/admin.min.css' );

}
add_action( 'admin_enqueue_scripts', 'pcapredict_admin_styles' );

/**
 * function pcapredict_admin_js()
 * outputs css for the admin pages
 */
function pcapredict_admin_js() {

	wp_enqueue_script( 'pcapredict-admin-foundation-js', PCAPREDICT_LOCATION_URL . '/js/foundation.min.js', array( 'jquery' ), false, true );

}
add_action( 'admin_enqueue_scripts', 'pcapredict_admin_js' );

/**
 * pcapredict_add_admin_sub_menus()
 * adds the plugins sub menus under the main admin menu item
 */
function pcapredict_add_admin_sub_menus() {

	/*** do we want the menu link to be a main menu item or a submenu item? ***/

	// main menu item
	add_menu_page(
		'PCA Predict',
		'PCA Predict',
		'manage_options',
		'pcapredict_settings',
		'pcapredict_settings_page_content',
		'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAARZJREFUeNqUVAERwyAMpDMwJFRCp2Ctg0lgDioBB5WAhG4KKoE56Bx0U8DCXdilLDD4u1zvmvAknwQhEnDO9WCz+8UCpkQpIFgmiDhiWUJoySED1hFfh/8CbJYUnJoEq0ycJ94wbsqVGoLGgkou5PKWC1DoXCv0tnECB+IPt9xEOe74PXOEAW9RD5kjPCaa0GUIX0UaerFx3ujs9YyGOtXlAB3NG7st2S4jqUkc7tEWxm9S2WkyhwEzvR0l4NbS7DYm2pA1kUkMi7Hfy7kuP8GGpmkG+F7BHowy/t8VYk4+Fs/sRsdnODIltESOoKHMSDRxu0lL2PCQ/KP1mn0fcRw44jEi2mofWsV0nc289vUOJRYRfQQYAFhTwY6JrFa5AAAAAElFTkSuQmCC',
		100
	);
}
add_action( 'admin_menu', 'pcapredict_add_admin_sub_menus' );

/**
 * pcapredict_settings_page_content()
 * Builds the content for the admin settings page.
 */
function pcapredict_settings_page_content() {

	$pcaOptions = get_option( '_pca_settings' );
	$pcaAccCode = strtoupper( $pcaOptions[ 'account_code' ] );
	$pcaToken = $pcaOptions[ 'account_token' ];
	$customJavascript = json_decode( $pcaOptions[ 'custom_javascript' ] );

	?>
	
	<div ng-app = "PCAPredictWordPress" ng-controller = 'keyCtrl' class="pcapredict-container row">
		<!-- right space -->
		<div class="medium-2 large-3"></div>

		<div class="small-12 medium-8 large-6">
			<div class="row align-center">
				
				<div class="small-12">
					<div class="pcapredict-message"></div>
				</div>

				<div class="small-12 pcapredict-content">
					<?php if (!$pcaToken) : ?>

						<form id="formLogIn" onsubmit="return false;">
							
							<div class="logo">
								<img src="data:image/png;base64,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" />
							</div>

							<div class="separator"></div>

							<div class="fieldset">
								<div class="small-12 columns">												
									<p class="register-info-link">
										<?php esc_html_e( 'You will need your PCA Predict account code and password to login. If you don\'t have one you can register for free.', 'pcapredict' ); ?> 
									</p>
								</div>
							</div>

							<div class="fieldset">
								<div class="small-12 columns">
									<label><?php esc_html_e( 'Account Code', 'pcapredict' ); ?></label>
								</div>
								<div class="small-12 columns end">
									<input type="text" name="accountCode" id="accountCode" placeholder="<?php esc_html_e( 'AccountCode', 'pcapredict' ); ?>" data-cip-id="accountCode">
								</div>
							</div>

							<div class="fieldset">
								<div class="small-12 columns">
									<label><?php esc_html_e( 'Password', 'pcapredict' ); ?></label>
								</div>
								<div class="small-12 columns end">
									<input type="password" name="password" id="password" placeholder="<?php esc_html_e( 'Password', 'pcapredict' ); ?>" data-cip-id="password">
								</div>                 
							</div>

							<div class="fieldset">											
								<div class="small-12 columns">
									<button id="btnLogIn" type="submit" class="button-dark" form="formLogIn" value="Log in"><?php esc_html_e( 'Log in', 'pcapredict' ); ?></button>
								</div>                    
							</div> 
						</form>	

					<?php endif; ?>

					<?php if ($pcaToken) : ?>
						<form id="formSettings" onsubmit="return false;">
							<div class="logo">
								<img src="data:image/png;base64,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" />
							</div>
							
							<div class="separator"></div>

							<div class="fieldset">
								<div class="row">
									<div class="small-6 medium-3 columns">
										<label><?php esc_html_e( 'Account Code', 'pcapredict' ); ?></label>
									</div>
									<div class="small-6 medium-3 columns">
										<label><?php esc_html_e( $pcaAccCode, 'pcapredict' ); ?></label>
									</div>
								</div>
							</div>

							<!-- Debug purpose, hidden -->
							<div class="fieldset" style="display: none;">
								<div class="row">
									<div class="small-12 columns">
										<label><?php esc_html_e( $pcaToken, 'pcapredict' ); ?></label>
									</div>
								</div>
							</div>

							<div class="fieldset">
								<div class="row">
									<div class="small-12 columns">
										<label><?php esc_html_e( 'Custom Javascript', 'pcapredict' ); ?></label>
									</div>
								</div>
								<div class="row">
									<div class="small-12 columns">
										<textarea id="customjavascript_id" name="customjavascript_name"><?php echo ( sanitize_text_field( stripslashes ( $customJavascript ) ) ); ?></textarea>
									</div>
								</div>
							</div>

							<div class="fieldset">
								<div class="row">
									<div class="small-12 medium-6 columns">
										<button id="btnSave" type="submit" class="button-light" form="formSettings" value="Save changes" ><?php esc_html_e( 'Save Changes', 'pcapredict' ); ?></button>
									</div>
									<div class="small-12 medium-6 columns">
										<button id="btnAccount" type="button" class="button-dark" value="View my account" onclick="window.open('https://account.pcapredict.com', '_blank')" ><?php esc_html_e( 'View my account', 'pcapredict' ); ?></button>
									</div>
								</div>
							</div>
						</form>
						
						<form id="formLogOut" onsubmit="return false;">
							<div class="separator"></div>

							<div class="fieldset" style="margin-bottom: 0px;">
								<div class="row">
									<div class="small-12 medium-4 columns">
										<button id="btnLogOut" class="button-logout" type="submit" form="formLogOut" value="Log out" ><?php esc_html_e( 'Log out', 'pcapredict' ); ?></button>
									</div>
									<div class="small-12 medium-8 columns">
										<p><?php esc_html_e( 'Logging out will stop PCA Predict from working in your site. You can log back in at any time.', 'pcapredict' ); ?></p>
									</div>
								</div>
							</div>
						</form>

					<?php endif; ?>

					<div id="loader-container">
						<div id="loader"></div>
					</div>
				</div>

				<?php if (!$pcaToken) : ?>
					<div class="small-12 links" style="color: #fff; text-align: center;">
							<div class="link">
								<a href="https://account.pcapredict.com/security/forgot/" target="_blank">
									<?php esc_html_e( 'Forgotten password', 'pcapredict' ); ?>
								</a>
							</div>

							<div class="link-separator">
								|
							</div>

							<div class="link">
								<a href="https://www.pcapredict.com/register/" target="_blank">
									<?php esc_html_e( 'Register for free', 'pcapredict' ); ?>
								</a>
							</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- left space -->
		<div class="medium-2 large-3"></div>
	</div>

	<script type="text/javascript">

		(function($){

			<?php
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					
					// Get the woocommerce base location country code.

					$arr = wc_get_base_location();

					foreach ($arr as $key => $value) {
						if ($key === 'country') {
							$shopCountryCode = $value;
						}
					}
				}
				else {
					// Fallback to the site language countrycode.

					$pieces = explode("_", get_locale());

					if (sizeof($pieces) == 1) {
						$shopCountryCode = $pieces[0];
					} else {
						$shopCountryCode = $pieces[1];
					}
				}
			?>

			var initCode = null;
			
			$('#formSettings').on('submit', function(){

				if ($('#btnSave').prop('disabled')) {
					return;
				}

				$('#loader-container').show();  
				$('#formSettings').addClass('faded');
				$('#formLogOut').addClass('faded');

				$.ajax({
					type: 'POST',
					url: ajaxurl, 
					showLoader: true,                  
					data: { 
						"action": 'pcapredict_save_settings',
						"account_code": "<?php echo esc_html( $pcaAccCode ); ?>",
						"account_token": "<?php echo esc_html( $pcaToken ); ?>",
						"custom_javascript": $('#customjavascript_id').val(),
					}
				})
				.done(function(result){
					resetSaveButton();
					$('.pcapredict-message')
						.text('<?php esc_html_e( 'Your settings were saved.', 'pcapredict' ); ?>')
						.removeClass('pcapredict-message-error')
						.addClass('pcapredict-message-success')
						.slideDown(500, function(){
							hidePCAPredictMessage(5000, 500);
						});                     
				})
				.fail(function(result){
					$('.pcapredict-message')
						.text('<?php esc_html_e( 'Sorry, there was a problem saving the settings.', 'pcapredict' ); ?>')
						.removeClass('pcapredict-message-success')
						.addClass('pcapredict-message-error')
						.slideDown(500, function(){
							hidePCAPredictMessage(5000, 500);
						});
				})
				.always(function(){
					$('#loader-container').hide();
					$('#formSettings').removeClass('faded');
					$('#formLogOut').removeClass('faded');
				});
			});

			$('#formLogIn').on('submit', function(){ 

				$('#loader-container').show(); 
				$('#formLogIn').addClass('faded');

				$.ajax({
					showLoader: true,
					type: 'POST',
					url: 'https://app_api.pcapredict.com/api/primaryaccountauthorisation',
					processData: false,
					contentType: 'application/json',
					data: JSON.stringify({ 
						"accountcode": $('#accountCode').val(), 
						"password": $('#password').val(), 
						"deviceDescription": 'Wordpress | ' + window.location.hostname,
						"deviceType": 1
					})
				})
				.done(function(result){

					if(console && console.log) console.log(result);
				
					if(result.accounts && Object.keys(result.accounts).length > 0) {

						var token = result.token.token;

						var accountCode = $('#accountCode').val();

						var auth = btoa(accountCode + ':' + token);

						$.ajax({
							showLoader: true,
							type: 'POST',
							url: 'https://app_api.pcapredict.com/api/apps/wordpress/0.0.1/licences',
							processData: false,
							headers: {
								'Content-Type': 'application/json',
								'Authorization': 'Basic ' + auth
							},
							data : {
								"generateAddress" : true,
								"generatePhone" : true,
								"generateEmail" : true,
								"mobileCountryCodeDefaultValue" : "<?php esc_html_e( $shopCountryCode, 'pcapredict' ); ?>"
							}
						})
						.done(function(result){
							$.ajax({
								type: 'POST',
								url: ajaxurl,                 
								data: { 
									"action": 'pcapredict_save_settings',
									"account_code": accountCode,
									"account_token": token
								}
							})
							.done(function(result){
								if(result.success) {

								}
								else {
									$('.pcapredict-message')
										.text('<?php esc_html_e( 'Sorry, Wordpress reported a problem while saving the data.', 'pcapredict' ); ?>')
										.removeClass('pcapredict-message-success')
										.addClass('pcapredict-message-error')
										.slideDown(500, function(){
											hidePCAPredictMessage(5000, 500);
										});
								}                                                                
							})
							.fail(function(result){
								$('.pcapredict-message')
									.text('<?php esc_html_e( 'Sorry, there was a problem saving your login data to the wordpress store.', 'pcapredict' ); ?>')
									.removeClass('pcapredict-message-success')
									.addClass('pcapredict-message-error')
									.slideDown(500, function(){
										hidePCAPredictMessage(5000, 500);
									});
							})
							.always(function(){
								$('#loader-container').hide();   
								$('#formLogIn').removeClass('faded');  
								window.location.reload(true);
							});
						})
						.fail(function(result){
							$('.pcapredict-message')
								.text('<?php esc_html_e( 'Sorry, there was a problem creating your licence. Please email support@pcapredict.com', 'pcapredict' ); ?>')
								.removeClass('pcapredict-message-success')
								.addClass('pcapredict-message-error')
								.slideDown(500, function(){
									hidePCAPredictMessage(5000, 500);
								});
						});
					}  else {
						$('#loader-container').hide();
						$('#formLogIn').removeClass('faded');

						$('.pcapredict-message')
							.text('Sorry, there is an error with the response from authentication. Please email support@pcapredict.com')
							.removeClass('pcapredict-message-success')
							.addClass('pcapredict-message-error')
							.slideDown(500, function(){
								hidePCAPredictMessage(5000, 500);
						});
					}
				})
				.fail(function(result){
					
					$('#loader-container').hide();
					$('#formLogIn').removeClass('faded');  

					$('#accountCode').val("");
					$('#password').val("");
					$('.pcapredict-message')
						.text('<?php esc_html_e( 'Sorry, your email address or password was not recognized. Please try again.', 'pcapredict' ); ?>')
						.removeClass('pcapredict-message-success')
						.addClass('pcapredict-message-error')
						.slideDown(500, function(){
							hidePCAPredictMessage(5000, 500);
						});

				});
			});

			$('#formLogOut').on('submit', function() {
				$('#loader-container').show();
				$('#formLogOut').addClass('faded');
				$('#formSettings').addClass('faded');

				var accountCode = "<?php echo esc_html( $pcaAccCode ); ?>";
				var token = "<?php echo esc_html( $pcaToken ); ?>";
				var auth = btoa(accountCode + ':' + token);

				$.ajax({
					type: 'POST',
					url: ajaxurl,
					dataType: 'JSON',
					data: { 
						"action": 'pcapredict_logout'
					}
				})
				.done(function(result){

					$.ajax({
                            showLoader: true,
                            type: 'DELETE',
                            url: 'https://app_api.pcapredict.com/api/authtoken',
                            processData: false,
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Basic ' + auth
                            }
                        })
                        .done(function(result) {
                            // done, reload view.
                            if(console && console.log) console.log("Logged out successfully");

                            window.location.reload(true);
                        })
                        .fail(function(result){
                            // Not ideal but we have logged out now in magento.
                            if(console && console.log) console.log("Error logging out");

                            window.location.reload(true);
                        })
						.always(function() {
							$('#loader-container').hide();
						});
				})
				.fail(function(result){
					$('#loader-container').hide();

					$('.pcapredict-message')
						.text('<?php esc_html_e( 'Sorry, there was a problem logging out from your PCA Predict account within wordpress.', 'pcapredict' ); ?>')
						.removeClass('pcapredict-message-success')
						.addClass('pcapredict-message-error')
						.slideDown(500, function(){
							hidePCAPredictMessage(5000, 500);
						});
				})
				.always(function(){
					hidePCAPredictMessage(5000, 500);
				});
			});

			$('#customjavascript_id').bind('input propertychange', function() {
				if (initCode == $('#customjavascript_id')[0].value){
					resetSaveButton();
				} else {
					$('#btnSave').prop('disabled', false);
					$('#btnSave').removeClass('button-light');
					$('#btnSave').addClass('button-dark');
				}
			});

			var resetSaveButton = function() {
				$('#btnSave').prop('disabled', true);
				$('#btnSave').addClass('button-light');
				$('#btnSave').removeClass('button-dark');
				
				if ($('#customjavascript_id')[0] != undefined) {
					initCode = $('#customjavascript_id')[0].value;
				}
			}

			var hidePCAPredictMessage = function(delayMs, animateTime) {
				delayMs = delayMs || 0;
				animateTime = animateTime || 0;
				setTimeout(function(){
					$('.pcapredict-message').fadeOut(animateTime);
				}, delayMs);
			}

			resetSaveButton();

		})(jQuery);

	</script>
	
	<?php
	
	/* do after settings page action */
	do_action( 'pcapredict_after_settings_page' );
	
}

/**
 * function pcapredict_settings_page_cta()
 * adds intro text on the settings page
 */
function pcapredict_settings_page_cta() {

}
add_action( 'pcapredict_before_settings_page', 'pcapredict_settings_page_cta', 10 );

/**
 *
 */
function pcapredict_settings_page_ctas() {
	
	/* get this plugins data - such as version, author etc. */
	$data = get_plugin_data(
		PCAPREDICT_LOCATION . '/pcapredict.php',
		false // no markup in return
	);
	?>
	
	<div class="pca-box-container">

		<h3><?php esc_html_e( 'Plugin Info', 'pcapredict' ); ?></h3>
		<p class="plugin-info">
			<?php esc_html_e( 'Version: ', 'pcapredict' ); echo esc_html( $data[ 'Version' ] ) ?><br />
			<?php esc_html_e( 'Written by:', 'pcapredict' ); ?> <a href="<?php echo esc_url( $data[ 'AuthorURI' ] ); ?>"><?php echo esc_html( $data[ 'AuthorName' ] ); ?></a><br />
			<?php esc_html_e( 'Website:', 'pcapredict' ); ?> <a href="https://www.pcapredict.com/integrations/wordpress-address-verification">PCA Predict</a>
		</p>
		<p>
			<?php esc_html_e( 'If you find this plugin useful then please', 'pcapredict' ); ?> <a href="https://wordpress.org/support/view/plugin-reviews/pcapredict-address-verification/"><?php esc_html_e( 'rate it on the plugin repository', 'pcapredict' ); ?></a>.
		</p>

	</div>
	
	<?php		
}
add_action( 'pcapredict_settings_page_right_column', 'pcapredict_settings_page_ctas' );