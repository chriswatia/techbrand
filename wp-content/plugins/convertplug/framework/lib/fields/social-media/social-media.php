<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

// Add new input type "switch".
if ( function_exists( 'smile_add_input_type' ) ) {
	smile_add_input_type( 'social_media', 'social_media_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'social_media_box_scripts' );
/**
 * Function Name:social_media_box_scripts description.
 *
 * @param  array $hook ap page list.
 */
function social_media_box_scripts( $hook ) {
	if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
		$cp_page = strpos( $hook, CP_PLUS_SLUG );
		$data    = get_option( 'convert_plug_debug' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_style( 'jquery-ui' );

		if ( false !== $cp_page || isset( $_GET['view'] ) ) {
			if ( false !== $cp_page && isset( $data['cp-dev-mode'] ) && '1' === $data['cp-dev-mode'] && isset( $_GET['style-view'] ) && ( 'edit' === $_GET['style-view'] || 'variant' === $_GET['style-view'] ) ) {
				wp_enqueue_style( 'social-media', SMILE_FRAMEWORK_URI . '/lib/fields/social-media/social-media.css', array(), CP_VERSION );
				wp_enqueue_script( 'social-media', SMILE_FRAMEWORK_URI . '/lib/fields/social-media/social-media.js', array( 'jquery', 'cp-swal-js' ), '1.0.0', true );
				$nonce_object = array(
					'social_media_nonce' => wp_create_nonce( 'cp_social_media_nonce' ),
				);
				wp_localize_script( 'social-media', 'cp_social_media_nonce', $nonce_object['social_media_nonce'] );
			}
		}
	}
}

/**
 * Function Name:available_icon_types.
 *
 * @return array val.
 */
function available_icon_types() {
	$array = array(
		'Facebook',
		'Twitter',
		'Google',
		'Digg',
		'Pinterest',
		'reddit',
		'LinkedIn',
		'Myspace',
		'Blogger',
		'Tumblr',
		'StumbleUpon',
		'Instagram',
	);
	return $array;
}

/**
 * Function Name:available_action_types.
 *
 * @return array val.
 */
function available_action_types() {
	$action_array = array(
		'Social Sharing',
		'Profile Link',
	);
	return $action_array;
}

/**
 * Function Name:render_multi_social_media .
 *
 * @param  integer $uniq  number id.
 * @param  string  $value string val.
 * @return mixed        content.
 */
function render_multi_social_media( $uniq, $value ) {
	$output = '';

	$input_types = array();
	$input_types = available_icon_types();

	$action_types = array();
	$action_types = available_action_types();

	$uniq = uniqid( $uniq );

	$value_mix_array = explode( '|', $value );

	$_value_array = array();
	if ( ! empty( $value_mix_array ) ) {
		foreach ( $value_mix_array as $key => $value_mix_string ) {
			$_array_temp = explode( ':', $value_mix_string, 2 );
			if ( ! empty( $_array_temp ) ) {
				$_name  = ( isset( $_array_temp[0] ) ) ? $_array_temp[0] : '';
				$_value = ( isset( $_array_temp[1] ) ) ? $_array_temp[1] : '';
				if ( '' !== $_name ) {
					$_value_array[ $_name ] = $_value;
				}
			}
		}
	}
	$current_input_name_val  = ( isset( $_value_array['input_share'] ) ) ? urldecode( $_value_array['input_share'] ) : '';
	$current_input_label_val = ( isset( $_value_array['profile_link'] ) ) ? urldecode( $_value_array['profile_link'] ) : '';

	$current_input_type_val         = ( isset( $_value_array['input_type'] ) ) ? $_value_array['input_type'] : '';
	$current_network_name_label_val = ( isset( $_value_array['network_name'] ) ) ? $_value_array['network_name'] : '';

	$current_input_img_val = ( isset( $_value_array['input_img'] ) ) ? urldecode( $_value_array['input_img'] ) : '';

	$accordion_label = ( '' !== $current_input_type_val ) ? $current_input_type_val : '';

	$is_hidden        = false;
	$is_profile       = false;
	$is_share         = false;
	$need_placeholder = false;

	$order = ( isset( $_value_array['order'] ) ) ? $_value_array['order'] : '0';

	$bw_type           = isset( $_value_array['smile_adv_share_opt'] ) ? $_value_array['smile_adv_share_opt'] : 0;
	$bw_switch_checked = ( $bw_type ) ? 'checked="checked"' : '';

	$share_url_desc  = 'Select current page URL or any custom URL to share after click.';
	$custom_url_desc = 'Enter the custom URL that you would like to share.';

	$pintrest_img_url_css = 'style="display:none"';
	if ( 'Pinterest' === $current_input_type_val ) {
		$pintrest_img_url_css = 'style="display:block"';
	}

	$output .= '<div class="social-media">';
	$output .= '<div class="toggle-accordion-head">
	<span class="cp-mini-box accordion-head-label">' . $accordion_label . '</span>
	<span class="cp-mini-box social-media-delete"><i class="dashicons dashicons-no-alt"></i></span>
	<!--<span class="cp-mini-box social-media-handle"><i class="dashicons dashicons-sort"></i></span>
	<span class="cp-mini-box"><i class="dashicons dashicons-arrow-down"></i></span>-->
	</div>';
	$output .= '<div class="toggle-accordion-content">';
			// first selct box for network.
	$output           .= '<div class="social-media-field">';
	$output           .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="Select social network from given list." style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output           .= '<label>' . __( 'Select Social Network', 'smile' ) . '</label>';
	$output           .= '<select class="cp_sm_select" name="input_type" id="cp-input_type-' . $uniq . '">';
	$current_input_val = ( isset( $_value_array['input_type'] ) ) ? $_value_array['input_type'] : '';
	if ( ! empty( $input_types ) ) :
		foreach ( $input_types as $key => $type ) :
			$selected = ( $current_input_val === $type ) ? 'selected="selected"' : '';
			$output  .= '<option value="' . $type . '" ' . $selected . '>' . ucfirst( $type ) . '</option>';

		endforeach;
	endif;
	$output .= '</select>';
	$output .= '</div>';

			// add network name field.
	$output .= '<div class="social-media-field cp-net-name" >';
	$output .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="The network name will display along with icon. It\'s visibility depends on \'Layout\' selection & \'Display Network Names\' setting in \'Advance\' section." style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output .= '<label>' . __( 'Network Name', 'smile' ) . '</label>';
	$output .= '<input type="text" class="cp_sm_input" id="cp-network_name-' . $uniq . '" name="network_name" value="' . $current_network_name_label_val . '"/>';
	$output .= '</div>';

	$action_class = '';
	if ( 'Instagram' === $current_input_type_val ) {
		$action_class .= 'cp-hide-share';
	}
	// second selct box for action.
	$output                  .= '<div class="social-media-field">';
	$output                  .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="Select action for social icon click." style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output                  .= '<label>' . __( 'Select Action', 'smile' ) . '</label>';
	$output                  .= '<select class="cp_sm_select_action" name="input_action" id="cp-input_action-' . $uniq . '">';
	$current_input_action_val = ( isset( $_value_array['input_action'] ) ) ? $_value_array['input_action'] : '';
	if ( ! empty( $action_types ) ) :
		foreach ( $action_types as $key => $actiontype ) :
			$str_lower = strtolower( str_replace( ' ', '_', $actiontype ) );
			$selected  = ( $current_input_action_val === $str_lower ) ? 'selected="selected"' : '';
			if ( 'social_sharing' === $str_lower ) {
				$action_class = $action_class;
			} else {
				$action_class = '';
			}
			$output .= '<option value="' . $str_lower . '" ' . $selected . ' class="' . $action_class . '">' . ucfirst( $actiontype ) . '</option>';
			if ( $current_input_action_val === $actiontype && 'profile_link' === $actiontype ) {
				$is_profile = true;
			} elseif ( $current_input_action_val === $actiontype && 'social_sharing' === $actiontype ) {
				$is_share = true;
			}

		endforeach;
	endif;
	$output .= '</select>';
	$output .= '</div>';

	$profile_style_for_options = ( $is_profile ) ? 'style="display:block"' : 'style="display:none"';
			// profile_link.
	$output .= '<div class="social-media-field " ' . $profile_style_for_options . '>';
	$output .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="Enter the social profile URL where user redirect after click." style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output .= '<label>' . __( 'Profile Link', 'smile' ) . '</label>';
	$output .= '<input type="text" class="cp_sm_input" id="cp-profile_link-' . $uniq . '" name="profile_link" value="' . $current_input_label_val . '"/>';
	$output .= '</div>';

			// switch for share.
	$output .= '<div class="social-media-field " >';
	$output .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="' . $share_url_desc . '" style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output .= '<label>' . __( 'Share URL', 'smile' ) . '</label>';
	$output .= '<div class="param-advanced-switch">
	<div class="switch-wrapper param-switch">
	<input type="text" ' . $bw_switch_checked . ' id="smile_adv_share_opt-' . $order . '_' . $uniq . '" name="smile_adv_share_opt"  class="form-control smile-input smile-switch-input cp_sm_input"  value="' . $bw_type . '" />
	<input type="checkbox" ' . $bw_switch_checked . ' id="smile_adv_share_opt_btn_' . $order . '_' . $uniq . '" class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch-adv-options " value="' . $bw_type . '" >
	<label class="smile-switch-btn checkbox-label" data-on="CUSTOM URL"  data-off="CURENT PAGE URL" data-id="smile_adv_share_opt-' . $order . '_' . $uniq . '" for="smile_adv_share_opt_btn_' . $order . '_' . $uniq . '">
	</label>
	</div>
	</div>';
	$output .= '</div>';

			// share url.
	$output .= '<div class="social-media-field">';
	$output .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="' . $custom_url_desc . '" style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output .= '<label>' . __( 'Custom URL', 'smile' ) . '</label>';
	$output .= '<input type="text" class="cp_sm_input" id="cp-input_share-' . $uniq . '" name="input_share" value="' . $current_input_name_val . '"/>';
	$output .= '</div>';

	$shares     = '';
	$data_count = '';

	$output .= '<div class="social-media-field">';
	$output .= '<input type="hidden" class="cp_sm_input" id="cp-input_share_count-' . $uniq . '" name="input_share_count" value="' . $data_count . '"/>';
	$output .= '</div>';

	$output .= '<div class="social-media-field" ' . $pintrest_img_url_css . '>';
	$output .= '<span class="cp-tooltip-icon has-tip" data-position="right" title="Enter the image ULR to share" style="cursor: help;float: right;"><i class="dashicons dashicons-editor-help"></i></span>';
	$output .= '<label>' . __( 'Image URL', 'smile' ) . '</label>';
	$output .= '<input type="text" class="cp_sm_input" id="cp-input_img-' . $uniq . '" name="input_img" value="' . $current_input_img_val . '"/>';
	$output .= '</div>';

	$output .= '</div> <!-- toggle-accordion-content -->';
	$output .= '</div> <!-- social-media -->';
	return $output;
}

/**
 * Function Name:social_media_settings_field Function to handle new input type "social media".
 *
 * @param  string $name     settings provided when using the input type "social media".
 * @param  string $settings holds the default / updated value.
 * @param  string $value    html output generated by the function.
 * @return string           html output generated by the function.
 */
function social_media_settings_field( $name, $settings, $value ) {
	$input_share = $name;
	$type        = isset( $settings['type'] ) ? $settings['type'] : '';
	$class       = isset( $settings['class'] ) ? $settings['class'] : '';

	$uniq = uniqid();

	$output  = '<div class="social-media-wrapper" id="cp-wrapper-' . $uniq . '" data-id="' . $uniq . '">';
	$output .= '<textarea id="social-media-input-' . $uniq . '" class="content cp-hidden form-control smile-input smile-' . $type . ' ' . $input_share . ' ' . $type . ' ' . $class . '" name="' . $input_share . '" rows="6" cols="6" style="display:block !important">' . $value . '</textarea>';
	$output .= '<div class="social-media-inner">';
	$boxes   = explode( ';', $value );
	if ( ! empty( $boxes ) ) {
		foreach ( $boxes as $key => $box_value ) {
			$output .= render_multi_social_media( $uniq, $box_value );
		}
	}
	$output .= '</div> <!-- social-media-inner -->';
	$output .= '<div class="social-media-add-new">Add New Network<i class="dashicons dashicons-plus"></i></div>';
	$output .= '</div>';
	return $output;
}

add_action( 'wp_ajax_repeat_social_media', 'repeat_social_media_callback' );

/**
 * Function Name:repeat_social_media_callback.
 */
function repeat_social_media_callback() {
	check_ajax_referer( 'cp_social_media_nonce', 'security_nonce' );
	$uniq = $_POST['id'];

	if ( '' === $uniq ) {
		$response['type']    = 'error';
		$response['message'] = 'No wrapper ID found';
		wp_send_json( wp_json_encode( $response ) );
	}

	$value  = '';
	$output = render_multi_social_media( $uniq, $value );

	$response['type']    = 'success';
	$response['message'] = $output;
	wp_send_json( wp_json_encode( $response ) );
}

/**
 * [sc_dropdown_string_to_array function to convert dropdown string to array.
 *
 * @param  string $string string parameter.
 * @return array         value.
 */
function sc_dropdown_string_to_array( $string ) {
	$lines = explode( PHP_EOL, $string );
	$array = array();
	foreach ( $lines as $key => $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$temp          = array();
		$line_to_array = explode( '+', $line );
		$label         = ( isset( $line_to_array[0] ) ) ? ucfirst( $line_to_array[0] ) : ucfirst( $line );
		$value         = ( isset( $line_to_array[1] ) ) ? $line_to_array[1] : $line;
		$temp['label'] = trim( $label );
		$temp['value'] = trim( $value );
		array_push( $array, $temp );
	}
	return $array;
}
