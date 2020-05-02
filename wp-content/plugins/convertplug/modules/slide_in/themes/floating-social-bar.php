<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( ! function_exists( 'slide_in_theme_floating_social_bar' ) ) {
	/**
	 * Function name: slide_in_theme_floating_social_bar.
	 *
	 * @param  array  $atts    array attributes.
	 * @param  string $content string parameters.
	 * @return mixed(value)          html/array.
	 */
	function slide_in_theme_floating_social_bar( $atts, $content = null ) {
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

		/**
		 * Define Variables
		 */
		global $cp_social_vars;

		$settings       = base64_decode( $settings_encoded );
		$style_settings = json_decode( $settings, true );

		foreach ( $style_settings as $key => $setting ) {
			$style_settings[ $key ] = apply_filters( 'smile_render_setting', $setting );
		}

		unset( $style_settings['style_id'] );

		// Generate UID.
		$uid       = uniqid();
		$uid_class = 'content-' . $uid;

		// Individual style variables.
		$individual_vars = array(
			'uid'         => $uid,
			'uid_class'   => $uid_class,
			'style_class' => 'cp-floating-social-bar',
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
			$cp_social_vars,
			$style_settings,
			$atts
		);

		/**
		 *  Extract short-code variables.
		 *
		 *  @array      $all         All merged arrays.
		 *  @array      array()      Its required as per WP. Merged $style_settings in $all.
		 */
		$a = shortcode_atts( $all, $style_settings );

		// Before filter.
		apply_filters_ref_array( 'cp_slidein_global_before', array( $a ) );

		?>
		<!-- BEFORE CONTENTS -->
		<div class="cp_social_main cp-slide-in-float-on">
			<div class="cp_social_media_wrapper">
				<?php
						// Embed CP social media.
				apply_filters_ref_array( 'cp_get_social', array( $a ) );
				?>
			</div>
		</div>
		<!-- AFTER CONTENTS -->
		<?php
		// After filter.
		apply_filters_ref_array( 'cp_slidein_global_after', array( $a ) );

		return ob_get_clean();
	}
}
