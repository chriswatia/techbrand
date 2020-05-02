<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

// Add new input type "border".
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
	wp_die( 'No direct script access allowed!' );
}
$cp_addon_list = Smile_Framework::$addon_list;?>
<div class="wrap about-wrap bsf-connect bsf-connect-new-list bend">
	<div class="wrap-container">
		<div class="bend-heading-section bsf-connect-header bsf-cnlist-header">
			<h1><?php esc_html_e( 'Create New Campaign', 'smile' ); ?></h1>
		</div>
		<!-- bend-heading section -->
		<div class="msg"></div>
		<input type="hidden" id="cp-connect-url" value="<?php echo esc_url( admin_url( 'admin.php?page=contact-manager&view=new-list' ) ); ?>">
		<div class="bend-content-wrap">
			<div class="smile-absolute-loader">
				<div class="smile-loader" style="transform: none !important;top: 120px !important;">
					<div class="smile-loading-bar"></div>
					<div class="smile-loading-bar"></div>
					<div class="smile-loading-bar"></div>
					<div class="smile-loading-bar"></div>
				</div>
			</div>
			<hr class="bsf-extensions-lists-separator" style="margin: -20px 0px 45px 0px;"></hr>
			<div class="container bsf-cnlist-content">
				<div class="bsf-cnlist-form col-sm-6 col-sm-offset-3">
					<div class="cp-wizard-progress">
						<div class="cp-wizard-progress-bar"></div>
					</div>
					<form id="bsf-cnlist-contact-form">
						<div class="container">
							<div class="col-sm-12">
								<div class="bsf-cnlist-form-row">
									<input type="hidden" name="action" value="smile_add_list" />
									<input type="hidden" name="date" value="<?php echo esc_attr( gmdate( 'j-n-Y' ) ); ?>" />
									<?php wp_nonce_field( 'cp-create-list-nonce' ); ?>
								</div>
								<?php
								$step1_class = '';
								$step2_class = '';

								if ( isset( $_GET['step'] ) && '1' == esc_attr( $_GET['step'] ) ) {
									$step1_class = 'active in';
								}

								if ( isset( $_GET['step'] ) && '2' == esc_attr( $_GET['step'] ) ) {
									$step2_class = 'active in';
								}
								?>
								<div class="step-1 bsf-cnlist-form-wizard <?php echo esc_attr( $step1_class ); ?>">
									<div class="steps-section">
										<div class="bsf-cnlist-form-row bsf-cnlist-list-name" >
											<label for="bsf-cnlist-list-name" >
												<?php esc_html_e( 'Campaign Name', 'smile' ); ?>
											</label>
											<?php $campaign_name = isset( $_GET['campaign'] ) ? sanitize_text_field( $_GET['campaign'] ) : ''; ?>
											<input type="text" id="bsf-cnlist-list-name" name="list-name" autofocus="autofocus" value="<?php echo esc_attr( $campaign_name ); ?>" />
											<span class="cp-validation-error"></span>
										</div>

										<?php
										if ( ! empty( $cp_addon_list ) ) {
											?>

											<div class="bsf-cnlist-form-row bsf-cnlist-list-provider" >
												<label for="bsf-cnlist-list-provider" >
													<?php esc_html_e( 'Do you want to sync connects with any third party software?', 'smile' ); ?>
												</label>
												<select id="bsf-cnlist-list-provider" class="bsf-cnlist-select" 

												name="list-provider">
													<option value="Convert Plug">No</option>
													<?php
													$list_provider = isset( $_GET['list-provider'] ) ? esc_attr( $_GET['list-provider'] ) : '';
													foreach ( $cp_addon_list as $slug => $setting ) {
														$selected = ( $slug == $list_provider ) ? 'selected' : '';
														echo '<option value="' . esc_attr( $slug ) . '" ' . esc_attr( $selected ) . ' >' . esc_html( $setting['name'] ) . '</option>';
													}
													?>
												</select>
												<div class="bsf-cnlist-list-provider-spinner"></div>
											</div>
										<?php } ?>
										<div class="bsf-cnlist-form-row short-description" >
											<p class="description">
												<?php
												echo wp_kses_post(
													'Your connects can be synced to CRM & Mailer softwares like HubSpot, MailChimp, etc.<br><br><strong>Important Note</strong> - If you need to integrate with third party CRM & Mailer software like MailChimp, Infusionsoft, etc. please install the respective addon from.',
													'smile'
												);
												echo sprintf(
													wp_kses_post( '<a href="' . bsf_exension_installer_url( '14058953' ) . '">here</a>', 'smile' )
												);
												?>
												</p>
										</div>
									</div><!-- .steps-section -->
								</div>
								<!-- .step-1    -->
								<div class="step-2 bsf-cnlist-form-wizard <?php echo esc_attr( $step2_class ); ?>" >
									<div class="steps-section">
										<div class="col-sm-12">
											<div class="bsf-cnlist-form-row bsf-cnlist-mailer-data" style="display:none;"></div>
											<div class="bsf-cnlist-mailer-help">
												<?php
												$docs_url = 'https://www.convertplug.com/plus/docs-category/mailer-integration/';
												?>
												<a href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener" ><?php esc_html_e( 'Where to find this?', 'smile' ); ?></a>
											</div><!-- .bsf-cnlist-mailer-help -->
										</div>
									</div><!-- .steps-section -->
								</div>
								<!-- .step-2    -->
							</div>
						</div>

						<div class="container bsf-new-list-wizard">
							<div class="col-sm-6">
								<button class="wizard-prev button button-primary disabled" type="button">
									<?php esc_html_e( 'Previous', 'smile' ); ?>
								</button>
							</div>
							<div class="col-sm-6">
								<div class="bsf-cnlist-save-btn" >
									<button id="save-btn" class="wizard-save button button-primary" data-provider="">
										<?php esc_html_e( 'Create Campaign', 'smile' ); ?>
									</button>
								</div>
								<div class="bsf-cnlist-next-btn" style="display:none;">
									<button class="wizard-next button button-primary" type="button" style="display: inline-block;">
										<?php esc_html_e( 'Next', 'smile' ); ?>
									</button>
								</div>
							</div>
						</div><!-- .bsf-new-list-wizard -->
					</form>
				</div>
				<!-- .bsf-cnlist-form -->
			</div>
			<!-- .container -->
		</div>
		<!-- .bend-content-wrap -->
	</div>
	<!-- .wrap-container -->
</div>
<!-- .wrap -->
<script type="text/javascript">

var provider = jQuery("#bsf-cnlist-list-provider");
var connect_url = '';
jQuery(document).ready(function() {

	connect_url = jQuery("#cp-connect-url").val();
	var val = provider.length ? provider.val().toLowerCase() : 'convert plug';

	<?php if ( ! empty( $cp_addon_list ) ) { ?>
		jQuery("#save-btn").attr('data-provider',val);
		provider.change(function(e){
			if( jQuery(this).val() == 'Convert Plug' ) {
				jQuery(".bsf-cnlist-save-btn").show();
				jQuery(".bsf-cnlist-next-btn").hide();
				jQuery("#save-btn").removeAttr('disabled');
			} else {
				jQuery(".bsf-cnlist-save-btn").hide();
				jQuery("#save-btn").attr('disabled', 'disabled');
				jQuery(".bsf-cnlist-next-btn").show();
			}
		});
	<?php } ?>

	var is_campaign_exists = false;
	var campaignName = cpcpGetUrlVars()["campaign"];
	var step = cpcpGetUrlVars()["step"];

	if( step == '2' ) {

		jQuery(".smile-absolute-loader").css('visibility','visible');
		jQuery("#bsf-cnlist-list-name").removeClass('has-error');

		jQuery('.bsf-cnlist-provider-description').fadeOut(300);
		val = cpcpGetUrlVars()["list-provider"];

		jQuery("#save-btn").attr( 'data-provider',val );

		jQuery("#save-btn").attr('disabled','true');
		var action = 'get_'+val+'_data';
		var data = 'action='+action;

		jQuery.ajax({
			url: ajaxurl,
			data: data,
			method: "POST",
			dataType: "JSON",
			success: function(result){

				if( result.isconnected ) {
					jQuery(".bsf-cnlist-mailer-help").hide();
				} else if( typeof result.helplink !== 'undefined' && result.helplink !== '' ) {
					jQuery(".bsf-cnlist-mailer-help").show();
					jQuery(".bsf-cnlist-mailer-help a").attr('href',result.helplink);
				} else {
					jQuery(".bsf-cnlist-mailer-help").hide();
				}

				if( val == 'convertfox'){
					jQuery(".bsf-cnlist-mailer-help").show();
					jQuery(".bsf-cnlist-mailer-help").css('top','10px');
				}

				jQuery(".bsf-cnlist-mailer-help a").attr('href',result.helplink);
				jQuery('.bsf-cnlist-mailer-data').html(result.data);
				jQuery('.bsf-cnlist-mailer-data').slideDown(300);
				jQuery(".smile-absolute-loader").css('visibility','hidden');

				setTimeout(function(){
					jQuery('.bsf-cnlist-form-wizard.step-1').css('transform','translateX(-100px)');
				}, 800 );

				setTimeout(function(){
					jQuery('.bsf-cnlist-form-wizard.step-1').removeClass('active in');
					jQuery('.bsf-cnlist-form-wizard.step-2').addClass('in active').css( 'transform' ,'translateX(0px)');
				}, 1200 );

				if( jQuery("#"+val+"-list").length > 0 ) {
					jQuery("#save-btn").removeAttr('disabled');
				}
				jQuery(".select2-infusionsoft-list").cpselect2();
				jQuery( ".wizard-prev" ).removeClass('disabled');

			},
			error: function(err){
				console.log(err);
			}
		});

	}

});

jQuery(document).on( "click", ".update-mailer", function(){
	jQuery('.bsf-cnlist-mailer-data input[type="text"]').val('');
	jQuery(this).replaceWith('<button id="auth-'+jQuery(this).attr('data-mailer')+'" class="button button-secondary auth-button" disabled="true"><?php esc_html_e( "Authenticate ' + jQuery(this).attr('data-mailerslug') + '", 'smile' ); ?></button><span class="spinner" style="float: none;"></span>');
});

jQuery("#save-btn").click(function(e){

	e.preventDefault();

	if( jQuery("#bsf-cnlist-list-name").val() == "" ){
		jQuery('html, body').animate({ scrollTop: jQuery(".bsf-cnlist-list-name").offset().top - 100 }, 500);
		jQuery("#bsf-cnlist-list-name").focus();
		jQuery("#bsf-cnlist-list-name").addClass('connect-new-list-required');
		return false;
	}

	var is_campaign_exists = false;
	var campaignName = jQuery("#bsf-cnlist-list-name").val();

	jQuery.ajax({
		url: ajaxurl,
		data: {
			campaign: campaignName,
			action: 'is_campaign_exists',
		},
		async: false,
		method: "POST",
		dataType: "JSON",
		success: function(result){
			if( result.status == 'error' ) {

				jQuery(".cp-validation-error").show();
				jQuery(".cp-validation-error").html(result.message);
				is_campaign_exists = true;
			} else {
				jQuery(".cp-validation-error").html('');
			}
		},
		error: function(err){
			console.log(err);
		}
	});

	if( is_campaign_exists ) {
		return false;
	}

	<?php if ( ! empty( $cp_addon_list ) ) { ?>
		var data = jQuery("#bsf-cnlist-contact-form").serialize();
	<?php } else { ?>
		var data = jQuery("#bsf-cnlist-contact-form").serialize() + '&list-provider=Convert+Plug';
	<?php } ?>

	var provider = jQuery(this).data('provider');

	if( provider == "madmimi" ) {
		var mailer_list_name = 	jQuery("#"+provider+"-list option:selected").text();
		var mailer_list_id = jQuery("#"+provider+"-list option:selected").text();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	} else if( provider == "sendy" ){
		var mailer_list_name = 	jQuery( '#sendy_list_ids' ).val();
		var mailer_list_id = jQuery( '#sendy_list_ids' ).val();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	} else if( provider == "infusionsoft" ){
		var lists_arr = new Array();
		var mailer_list_id = '';
		var mailer_list_name = '';
		var selected_id = '';
		var name = '';
		if( jQuery( "#"+provider+"-list option:selected" ).text() != '' ) {
			jQuery( "#"+provider+"-list option:selected" ).each(function(){
				selected_id = jQuery(this).val();
				name = jQuery(this).text();
				lists_arr.push("{\""+selected_id+"\" : \""+name+"\"}");
			});

		} else {
			selected_id = -1;
			name = -1;
			lists_arr.push("{\""+selected_id+"\" : \""+name+"\"}");
		}
		mailer_list_id = JSON.stringify(lists_arr);
		mailer_list_name = 	JSON.stringify(lists_arr);

		var infusionsoft_action_id = jQuery('#infusionsoft_action_id').val();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name+"&infusionsoft_action_id="+infusionsoft_action_id;
		//console.log(data);exit();
	} else if( provider == "ontraport" ) {
		var mailer_list_id = jQuery("#"+provider+"-list option:selected").val();
		var mailer_list_name = 	jQuery("#"+provider+"-list option:selected").text();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	} else {
		var mailer_list_id = jQuery("#"+provider+"-list ").val();
		var mailer_list_name = 	jQuery("#"+provider+"-list option:selected").text();
		data += "&list="+mailer_list_id+"&provider_list="+mailer_list_name;
	}

	var loading = jQuery(this).next(".spinner");
	var msg = jQuery(".msg");
	loading.css('visibility','visible');
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		method: "POST",
		dataType: "JSON",
		success: function(result){

			if( result.status == 'error' ) {
				jQuery(".cp-validation-error").show();
				jQuery(".cp-validation-error").html(result.message);
				return false;
			} else {
				jQuery(".cp-validation-error").html('');
			}

			if( result.message == "added" ){
				swal({
					title: "<?php esc_html_e( 'Added!', 'smile' ); ?>",
					text: "<?php esc_html_e( 'The campaign you just created, is added to the list.', 'smile' ); ?>",
					type: "success",
					timer: 2000,
					showConfirmButton: false
				});
			} else {
				swal({
					title: "<?php esc_html_e( 'Error!', 'smile' ); ?>",
					text: "<?php esc_html_e( 'Error adding the campaign to the list. Please try again.', 'smile' ); ?>",
					type: "error",
					timer: 2000,
					showConfirmButton: false
				});
			}
			setTimeout( function(){
				document.location = 'admin.php?page=contact-manager';
			}, 600 );
		},
		error: function(err){
			swal({
				title: "<?php esc_html_e( 'Error!', 'smile' ); ?>",
				text: "<?php esc_html_e( 'Error adding the campaign to the list. Please try again.', 'smile' ); ?>",
				type: "error",
				timer: 2000,
				showConfirmButton: false
			});
		}
	});
});

/************** JQuery change events *************/

jQuery(document).on('click', '.wizard-next', function(e){

	var cpDesc = jQuery('.bsf-cnlist-provider-description').html();
	if( jQuery("#bsf-cnlist-list-name").val() == '' ) {
		jQuery("#bsf-cnlist-list-name").addClass('connect-new-list-required');
		jQuery("#bsf-cnlist-list-name").focus();
		return false;
	} else {

		var is_campaign_exists = false;
		var campaignName = jQuery("#bsf-cnlist-list-name").val();
		jQuery.ajax({
			url: ajaxurl,
			data: {
				campaign: campaignName,
				action: 'is_campaign_exists',
				security_nonce: '<?php echo esc_attr( wp_create_nonce( 'is_campaign_exists_nonce' ) ); ?>'
			},
			async: false,
			method: "POST",
			dataType: "JSON",
			success: function(result){
				if( result.status == 'error' ) {
					jQuery(".cp-validation-error").show();
					jQuery(".cp-validation-error").html(result.message);
					is_campaign_exists = true;
				} else {
					jQuery(".cp-validation-error").html('');
				}
			},
			error: function(err){
				console.log(err);
			}
		});

		if(is_campaign_exists) {
			return false;
		}

		jQuery(".smile-absolute-loader").css('visibility','visible');
		jQuery("#bsf-cnlist-list-name").removeClass('has-error');
		jQuery(this).addClass('disabled');
		jQuery(".wizard-prev").removeClass('disabled');
		jQuery(".bsf-cnlist-save-btn").show();
		jQuery(".wizard-next").hide();

		jQuery('.bsf-cnlist-provider-description').fadeOut(300);
		val = jQuery("#bsf-cnlist-list-provider").val().toLowerCase();
		jQuery("#save-btn").attr('data-provider',val);

		jQuery("#save-btn").attr('disabled','true');
		var action = 'get_'+val+'_data';
		var data = 'action='+action;

		jQuery.ajax({
			url: ajaxurl,
			data: data,
			method: "POST",
			dataType: "JSON",
			success: function(result){
				if( result.isconnected ) {
					jQuery(".bsf-cnlist-mailer-help").hide();
				} else if( typeof result.helplink !== 'undefined' && result.helplink !== '' ) {
					jQuery(".bsf-cnlist-mailer-help").show();
					jQuery(".bsf-cnlist-mailer-help a").attr('href',result.helplink);
				} else {
					jQuery(".bsf-cnlist-mailer-help").hide();
				}

				if( val == 'convertfox'){
					jQuery(".bsf-cnlist-mailer-help").show();
					jQuery(".bsf-cnlist-mailer-help").css('top','10px');
				}

				jQuery('.bsf-cnlist-mailer-data').html(result.data);
				jQuery('.bsf-cnlist-mailer-data').slideDown(300);
				jQuery(".smile-absolute-loader").css('visibility','hidden');

				setTimeout(function(){
					jQuery('.bsf-cnlist-form-wizard.step-1').css('transform','translateX(-100px)');
				}, 800 );

				setTimeout(function(){
					jQuery('.bsf-cnlist-form-wizard.step-1').removeClass('active in');
					jQuery('.bsf-cnlist-form-wizard.step-2').addClass('in active').css( 'transform' ,'translateX(0px)');

					var params = '&step=2&list-provider='+val+'&campaign='+campaignName;
					var push_state_url = connect_url + params;

					window.history.pushState( 'connect_url', 'Connects', push_state_url );
				}, 1200 );

				if( jQuery("#"+val+"-list").length > 0 ) {
					jQuery("#save-btn").removeAttr('disabled');
				}
				jQuery(".select2-infusionsoft-list").cpselect2();
			},
			error: function(err){
				console.log(err);
			}
		});
	}
});

jQuery(document).on('click', '.wizard-prev', function(e){

	if( !jQuery(this).hasClass('disabled') ) {

		setTimeout(function(){
			jQuery('.bsf-cnlist-form-wizard.step-2').css('transform','translateX(-100px)');
		}, 200 );

		setTimeout(function(){
			jQuery('.bsf-cnlist-form-wizard.step-2').removeClass('active in');
			jQuery('.bsf-cnlist-form-wizard.step-1').addClass('in active').css( 'transform' ,'translateX(0px)');
			jQuery(".wizard-next").removeClass('disabled');
			jQuery(".wizard-prev").addClass('disabled');
			jQuery(".bsf-cnlist-save-btn").hide();
			jQuery(".wizard-next").show();
			jQuery(".bsf-cnlist-next-btn").show();

			var list_provider = cpcpGetUrlVars()["list-provider"];
			if( typeof list_provider !== 'undefined' ) {
				jQuery("#bsf-cnlist-list-provider").val(list_provider);
			}

			var params = '&step=1';
			var push_state_url = connect_url + params;

			window.history.pushState( 'connect_url', 'Connects', push_state_url );

		}, 600 );
	}
});

jQuery(document).on('keyup change keydown', '#bsf-cnlist-list-name', function() {
	if(jQuery(this).val() !== '') {
		jQuery(this).removeClass('connect-new-list-required');
	}
});


function cpcpGetUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}

</script>
