<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( ! function_exists( 'modal_theme_first_order' ) ) {
	/**
	 * Function Name: modal_theme_first_order.
	 *
	 * @param  array  $atts    array parameters.
	 * @param  string $content string parameter.
	 * @return mixed          string parameter.
	 */
	function modal_theme_first_order( $atts, $content = null ) {
		/**
		 * Define Variables.
		 */
		global $cp_form_vars;

		$style_id         = '';
		$settings_encoded = '';
		shortcode_atts(
			array(
				'style_id'         => '',
				'settings_encoded' => '',
			),
			$atts
		);
		$style_id         = isset( $atts['style_id'] ) ? $atts['style_id'] : '';
		$settings_encoded = $atts['settings_encoded'];

		$settings       = base64_decode( $settings_encoded );
		$style_settings = json_decode( $settings, true );

		foreach ( $style_settings as $key => $setting ) {
			$style_settings[ $key ] = apply_filters( 'smile_render_setting', $setting );
		}

		unset( $style_settings['style_id'] );

		// Generate UID.
		$uid       = uniqid();
		$uid_class = 'content-' . $uid;

		$individual_vars = array(
			'uid'         => $uid,
			'uid_class'   => $uid_class,
			'style_class' => 'cp-first-order',
		);

		/**
		 * Merge short code variables arrays.
		 *
		 * @array   $individual_vars        Individual style EXTRA short-code variables.
		 * @array   $cp_form_vars           CP Form global short-code variables.
		 * @array   $style_settings         Individual style short-code variables.
		 * @array   $atts                   short-code attributes.
		 */
		$all = array_merge(
			$individual_vars,
			$style_settings,
			$cp_form_vars,
			$atts
		);

		// Merge arrays - 'short code atts' & 'style options'.
		$a = shortcode_atts( $all, $style_settings );

		// Style - individual options.
		$on_success_action = ( 'redirect' === $a['on_success'] ) ? $a['redirect_url'] : $a['success_message'];
		$imagestyle        = cp_add_css( 'left', $a['image_horizontal_position'], 'px' );
		$imagestyle       .= cp_add_css( 'top', $a['image_vertical_position'], 'px' );
		$imagestyle       .= cp_add_css( 'max-width', $a['image_size'], 'px' );

		$cp_modal_img_custom_url = isset( $a['modal_img_custom_url'] ) ? $a['modal_img_custom_url'] : '';
		$cp_modal_img_src        = isset( $a['modal_img_src'] ) ? $a['modal_img_src'] : '';
		$cp_modal_image          = isset( $a['modal_image'] ) ? $a['modal_image'] : '';

		// Filters & Actions.
		$modal_image = cp_get_module_image_url_init( 'modal', $cp_modal_img_custom_url, $cp_modal_img_src, $cp_modal_image );

		// Filters & Actions for modal_image_alt.
		$modal_image_alt = cp_get_module_image_alt_init( 'modal', $cp_modal_img_src, $cp_modal_image );

		$convert_plug_settings = get_option( 'convert_plug_settings' );
		$images_on_load        = isset( $convert_plug_settings['cp-lazy-img'] ) ? $convert_plug_settings['cp-lazy-img'] : 1;

		// Before filter.
		apply_filters_ref_array( 'cp_modal_global_before', array( $a ) );

		?>
		<div class="cp-row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-text-container">
				<?php if ( isset( $a['modal_img_src'] ) && 'none' !== $a['modal_img_src'] ) { ?>
				<div class="">
					<div class="cp-image-container">
						<?php if ( $images_on_load ) { ?>
						<img style="<?php echo esc_attr( $imagestyle ); ?>" data-src="<?php echo esc_attr( $modal_image ); ?>" class="cp-image" <?php echo esc_attr( str_replace( "'", '', $modal_image_alt ) ); ?> >
						<?php } else { ?>
						<img style="<?php echo esc_attr( $imagestyle ); ?>" src="<?php echo esc_attr( $modal_image ); ?>" class="cp-image" <?php echo esc_attr( str_replace( "'", '', $modal_image_alt ) ); ?> >
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<div class="cp-title-container 
				<?php
				if ( '' === trim( $a['modal_title1'] ) ) {
					echo 'cp-empty'; }
				?>
					">
					<h2 class="cp-title cp_responsive" ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_title1'] ) ) ); ?></h2>
				</div>
				<div class="cp-short-desc-container cp-clear  
				<?php
				if ( '' === trim( $a['modal_content'] ) ) {
					echo 'cp-empty'; }
				?>
					">
					<div class="cp-short-description cp_responsive cp-clear " ><?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_content'] ) ) ); ?></div>
				</div>
				<div class="cp-form-container">
					<?php
						// Embed CP Form.
					apply_filters_ref_array( 'cp_get_form', array( $a ) );
					?>
				</div>
				<div class="cp-info-container cp_responsive 
				<?php
				if ( '' === trim( $a['modal_confidential'] ) ) {
					echo 'cp-empty'; }
				?>
					" >
					<?php echo do_shortcode( html_entity_decode( stripcslashes( $a['modal_confidential'] ) ) ); ?>
				</div>
			</div>
		</div>
		<!-- AFTER CONTENTS -->
		<?php
		// After filter.
		apply_filters_ref_array( 'cp_modal_global_after', array( $a ) );

		return ob_get_clean();
	}
}
