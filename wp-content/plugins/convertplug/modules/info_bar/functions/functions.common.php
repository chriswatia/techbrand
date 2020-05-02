<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( function_exists( 'smile_update_settings' ) ) {

	if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
		return;
	}

	// get style id.
	$style_id_for_ifbcustomcss = '';
	$cp_settings               = get_option( 'convert_plug_settings' );
	$user_inactivity           = isset( $cp_settings['user_inactivity'] ) ? $cp_settings['user_inactivity'] : '60';
	$style                     = isset( $_GET['style'] ) ? esc_attr( $_GET['style'] ) : '';

	if ( isset( $_GET['variant-style'] ) ) {
		$style_id_for_ifbcustomcss = esc_attr( $_GET['variant-style'] );
		$style                     = $_GET['variant-style'];
	} else {
		if ( isset( $_GET['style'] ) ) {
			$style_id_for_ifbcustomcss = esc_attr( $_GET['style'] );
		}
	}

	global $wp_roles;
	$roles    = $wp_roles->get_names();
	$user_arr = array();
	foreach ( $roles as $rkey => $rvalue ) {
		$user_arr [ $rvalue ] = $rvalue;
	}
	$first_item = array( 'None' );
	$new_arr    = $user_arr;
	unset( $new_arr['Administrator'] );
	$new_arr = $first_item + $new_arr;
	// Get Convert Plus Form Option Array .
	global $cp_form;
	global $cp_social;
	global $cp_count_down;

	/* translators:%s plugin name */
	$position_link = sprintf( __( 'If your theme has fixed header feature then there might be chances of Push Page will not work. In that case find ID or class of fixed header div & enter it <a target="_blank" href="%s" >here.</a>', 'smile' ), admin_url( 'admin.php?page=' . CP_PLUS_SLUG . '&view=debug&author=true' ) );

	/* translators:%s style_id */
	$custom_css_link = sprintf( __( "Add custom CSS to your style. Write custom css statement with prefixed the following unique class :<br><br/><span style='color:#444;font-size:18px;font-family: monospace;' ><b>%s </span> </b>", 'smile' ), $style_id_for_ifbcustomcss );

	/* translators:%s inactivity timer %s url for setting page */
	$inactive_link = sprintf( __( 'Info Bar will trigger after ` %1$s Seconds of user inactivity. If you would like, you can change the time <a rel="noopener" target="_blank" href="%2$s"> here</a>', 'smile' ), CP_PLUS_NAME, admin_url( 'admin.php?page=' . CP_PLUS_SLUG . '&view=settings#user_inactivity' ) );

	/* translators:%s plugin slug */
	$dev_link = sprintf( __( '%s can check user history and limit repeat occurrence of Modal when cookies are enabled. No more annoying Info Bars!', 'smile' ), CP_PLUS_NAME );

	/* translators:%s campaign link */
	$camp_link = sprintf( __( '"First" is the default and ready to use campaign. If you would like, you can create a new campaign <a href=" %s" target=\"_blank\" rel=\"noopener\">here</a>', 'smile' ), admin_url( 'admin.php?page=contact-manager&view=new-list&step=1' ) );

	/* translators:%s plugin name */
	$random_link = sprintf( __( '%s is all-in-one software to generate more leads & drive more sales with onsite targeting', 'smile' ), CP_PLUS_NAME );

	$name = array(
		array(
			'type'         => 'google_fonts',
			'name'         => 'cp_google_fonts',
			'opts'         => array(
				'title' => __( 'Google Fonts', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'panel'        => __( 'Name', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'infobar_title',
			'opts'         => array(
				'title'       => __( 'Info Bar Title', 'smile' ),
				'value'       => 'Subscribe to our newsleter',
				'description' => __( 'Enter the main heading title.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'panel'        => __( 'Name', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'infobar_description',
			'opts'         => array(
				'title'       => __( 'Info Bar Description', 'smile' ),
				'value'       => 'Loreal Epsum doller ',
				'description' => __( 'Enter the main heading title.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'panel'        => __( 'Name', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

	);

	/******* Background */
	$background = array(

		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'bg_gradient',
			'opts'         => array(
				'title'       => __( 'Enable Gradient Background', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Enhance your background with gradient effect.', 'smile' ),
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'bg_color',
			'opts'         => array(
				'title'        => __( 'Background Color', 'smile' ),
				'value'        => '#dddddd',
				'description'  => __( 'Select the background color for info bar.', 'smile' ),
				'css_property' => 'background-color',
				'css_selector' => '.cp-info-bar-body-overlay',
				'css_preview'  => true,
			),
			'dependency'   => array(
				'name'     => 'bg_gradient',
				'operator' => '==',
				'value'    => '0',
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'gradient-colorpicker',
			'class'        => '',
			'name'         => 'module_bg_gradient',
			'opts'         => array(
				'title'        => '',
				'value'        => '#ffffff|#1e73be|0|100|linear|bottom_center',
				'css_property' => 'background',
				'css_selector' => '.cp-info-bar-body-overlay',
			),
			'dependency'   => array(
				'name'     => 'bg_gradient',
				'operator' => '==',
				'value'    => true,
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),      // Hidden variable to store the (lighten border color).
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'bg_gradient_lighten',
			'opts'         => array(
				'title'       => __( 'Gradient Lighten', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter the short description of this optin.(HTML is Allowed)', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
		),
		// Hidden variable to store the (darken button color).
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'btn_darken',
			'opts'         => array(
				'title'       => __( 'Button Darken', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter the short description of this optin.(HTML is Allowed)', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => 'Design',
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// Hidden variable to store the (gradient button color).
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'btn_gradiant',
			'opts'         => array(
				'title'       => __( 'Button Darken', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter the short description of this optin.(HTML is Allowed)', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => 'Design',
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'info_bar_bg_image_src',
			'opts'         => array(
				'title'   => __( 'Background Image source', 'smile' ),
				'value'   => 'upload_img',
				'options' => array(
					__( 'Custom URL', 'smile' )   => 'custom_url',
					__( 'Upload Image', 'smile' ) => 'upload_img',
					__( 'None', 'smile' )         => 'none',
				),
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'info_bar_bg_image_custom_url',
			'opts'         => array(
				'title'       => __( 'Custom URL', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter custom URL for your image.', 'smile' ),
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'info_bar_bg_image_src',
				'operator' => '==',
				'value'    => 'custom_url',
			),
		),
		array(
			'type'         => 'media',
			'class'        => '',
			'name'         => 'info_bar_bg_image',
			'opts'         => array(
				'title'        => __( 'Background Image', 'smile' ),
				'value'        => '',
				'css_selector' => '.cp-info-bar-body',
				'css_property' => 'background-image',
				'css_preview'  => true,
				'description'  => __( "You can provide an image that would be appear behind the content in the Info Bar area. For this setting to work, the background color you've chosen must be transparent.", 'smile' ),
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'info_bar_bg_image_src',
				'operator' => '==',
				'value'    => 'upload_img',
			),
		),
		array(
			'type'         => 'background',
			'class'        => '',
			'name'         => 'opt_bg',
			'opts'         => array(
				'title' => '',
				'value' => 'no-repeat|center|cover',
			),
			'dependency'   => array(
				'name'     => 'info_bar_bg_image_src',
				'operator' => '!==',
				'value'    => 'none',
			),
			'panel'        => __( 'Background', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'infobar_size_section',
			'opts'         => array(
				'title' => __( 'Size', 'smile' ),
				'value' => '',
			),
			'section'      => 'Design',
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'infobar_height',
			'opts'         => array(
				'title'        => __( 'Height', 'smile' ),
				'value'        => 50,
				'min'          => 30,
				'max'          => 700,
				'step'         => 1,
				'suffix'       => 'px',
				'description'  => __( 'Set the height for info bar? (value in px).', 'smile' ),
				'css_property' => 'min-height',
				'css_selector' => '.cp-info-bar-body',
			),
			'section'      => 'Design',
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'infobar_width',
			'opts'         => array(
				'title'        => __( 'Width', 'smile' ),
				'value'        => 1000,
				'min'          => 320,
				'max'          => 1600,
				'step'         => 1,
				'suffix'       => 'px',
				'description'  => __( 'Set the width for info bar? (value in px).', 'smile' ),
				'css_property' => 'width',
				'css_selector' => '.cp-ib-container',
			),
			'section'      => 'Design',
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

	);

	// Advance Design Options .
	$advance_options = array(

		// Position.
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'infobar_position',
			'opts'         => array(
				'title'   => __( 'Info Bar Position', 'smile' ),
				'value'   => 'cp-pos-top',
				'options' => array(
					__( 'Top of the page', 'smile' )    => 'cp-pos-top',
					__( 'Bottom of the page', 'smile' ) => 'cp-pos-bottom',
				),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'fix_position',
			'opts'         => array(
				'title'       => __( 'Sticky', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Enable to stick the info bar at its position and follow the scroll.', 'smile' ),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'page_down',
			'opts'         => array(
				'title'       => __( 'Push Page', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Enable to push the page down and display the info bar above the page.', 'smile' ),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'infobar_position',
				'operator' => '==',
				'value'    => 'cp-pos-top',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'animate_push_page',
			'opts'         => array(
				'title'       => __( 'Animate Push Page', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Enable to animate page towards down while loading Info Bar.', 'smile' ),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'page_down',
				'operator' => '==',
				'value'    => true,
			),
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'position_link',
			'opts'         => array(
				'link'  => $position_link,
				'value' => '',
				'title' => '',
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'infobar_position',
				'operator' => '==',
				'value'    => 'cp-pos-top',
				'name'     => 'page_down',
				'operator' => '==',
				'value'    => 'true',
			),
		),

		// Border.
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'custom_code_sec_title',
			'opts'         => array(
				'title' => __( 'Border', 'smile' ),
				'value' => '',
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_border',
			'opts'         => array(
				'title'       => __( 'Border', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Enable Border', 'smile' ),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// Hidden variable to store the (darken border color).
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'border_darken',
			'opts'         => array(
				'title'        => __( 'Border Color', 'smile' ),
				'value'        => '#2c8dd7',
				'css_property' => 'border-color',
				'css_selector' => '.cp-info-bar',
			),
			'dependency'   => array(
				'name'     => 'enable_border',
				'operator' => '==',
				'value'    => true,
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'custom_code_sec_title',
			'opts'         => array(
				'title' => __( 'Shadow', 'smile' ),
				'value' => '',
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_shadow',
			'opts'         => array(
				'title'       => __( 'Shadow', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Enable Shadow', 'smile' ),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'custom_code_sec_title',
			'opts'         => array(
				'title' => __( 'Custom Code', 'smile' ),
				'value' => '',
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'custom_css',
			'opts'         => array(
				'title'       => __( 'Custom CSS', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter your custom css code for this Info Bar here.', 'smile' ),
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'custom_css_link',
			'opts'         => array(
				'link'  => $custom_css_link,
				'value' => '',
				'title' => '',
			),
			'section'      => 'Design',
			'panel'        => __( 'Advanced', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'custom_css_class',
			'opts'         => array(
				'title'       => __( 'Custom Class', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter your custom class for this Info Bar here.', 'smile' ),
			),
			'panel'        => __( 'Advanced', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
	);

	/****** Behaviour */
	$behavior = array(
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'ib_exit_intent',
			'opts'         => array(
				'title'       => __( 'Before User Leaves / Exit Intent', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, Info Bar will load right before user is about to leave your website.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'add_to_cart',
			'opts'         => array(
				'title'       => __( 'Launch when items are added to cart', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'This trigger will work only when Exit Intent is switched on and if there is an item added to the cart.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'dependency'   => array(
				'name'     => 'ib_exit_intent',
				'operator' => '==',
				'value'    => '1',
			),
			'section'      => 'Behavior',
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'autoload_on_duration',
			'opts'         => array(
				'title'       => __( 'After Few Seconds', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, Info Bar will load automatically after few seconds.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'load_on_duration',
			'opts'         => array(
				'title'       => __( 'Load After Seconds', 'smile' ),
				'value'       => 1,
				'min'         => 0.1,
				'max'         => 100,
				'step'        => 0.1,
				'suffix'      => 'Sec',
				'description' => __( 'How long the Info Bar should take to be displayed after the page is loaded? (value in seconds).', 'smile' ),
			),
			'panel'        => 'Smart Launch',
			'dependency'   => array(
				'name'     => 'autoload_on_duration',
				'operator' => '==',
				'value'    => '1',
			),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'autoload_on_scroll',
			'opts'         => array(
				'title'       => __( 'After User Scrolls', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, Info Bar will load as user scrolls down on the page.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'load_after_scroll',
			'opts'         => array(
				'title'       => __( 'Load After Scroll %', 'smile' ),
				'value'       => 75,
				'min'         => 1,
				'max'         => 100,
				'step'        => 1,
				'suffix'      => '%',
				'description' => __( 'How much should the user scroll the page to display the Info Bar? (value in %).', 'smile' ),
			),
			'panel'        => 'Smart Launch',
			'dependency'   => array(
				'name'     => 'autoload_on_scroll',
				'operator' => '==',
				'value'    => '1',
			),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'inactivity',
			'opts'         => array(
				'title'       => __( 'When User Is Inactive', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, a Info Bar will be displayed to visitor if he is idle on page for certain time.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'inactivity_link',
			'opts'         => array(
				'link'  => $inactive_link,
				'value' => '',
				'title' => '',
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
			'dependency'   => array(
				'name'     => 'inactivity',
				'operator' => '==',
				'value'    => 'true',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_after_post',
			'opts'         => array(
				'title'       => __( 'Launch after content', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Info Bar will be triggered when user scrolls to the end of post.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),

		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_display_inline',
			'opts'         => array(
				'title'       => __( 'Display Inline', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, module will display inline as a part of page / post content.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'inline_position',
			'opts'         => array(
				'title'       => __( 'Display Inline Position', 'smile' ),
				'value'       => 'none',
				'description' => __( 'Select the position, where you want to display module inline.', 'smile' ),
				'options'     => array(
					__( 'Before Post', 'smile' ) => 'before_post',
					__( 'After Post', 'smile' )  => 'after_post',
					__( 'Both', 'smile' )        => 'both',
				),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => 'Behavior',
			'section_icon' => 'connects-icon-toggle',
			'dependency'   => array(
				'name'     => 'enable_display_inline',
				'operator' => '==',
				'value'    => 'true',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_custom_scroll',
			'opts'         => array(
				'title'       => __( 'After Scroll To Certain ID / Class', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Inof Bar will be triggered when user scrolls to certain css class or id.', 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'enable_scroll_class',
			'opts'         => array(
				'title'       => __( 'Enter Class Name/Id', 'smile' ),
				'value'       => '',
				'description' => __( "Enter CSS Class / ID <br/>[ You can enter multiple values here by separating with comma. Class name should start with '.' & id name should start with '#', example => #id, .class ]", 'smile' ),
			),
			'panel'        => __( 'Smart Launch', 'smile' ),
			'section'      => 'Behavior',
			'section_icon' => 'connects-icon-toggle',
			'dependency'   => array(
				'name'     => 'enable_custom_scroll',
				'operator' => '==',
				'value'    => 'true',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_custom_class',
			'opts'         => array(
				'title'       => __( 'Launch With CSS Class', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Info Bar can be triggered on click of any UI element. Just provide the unique CSS class of that element here and Info Bar will be trigger when you click on that element.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'panel'        => __( 'Manual Display', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'custom_class',
			'opts'         => array(
				'title'       => __( 'Launch With CSS Class', 'smile' ),
				'value'       => '',
				'description' => __( '<br>Info Bar can be triggered on click of any UI element. Just provide the unique CSS class of that element here and Info Bar will be trigger when you click on that element.<br> If you have multiple classes, separate them with comma. Example - widget-title, site-description<br>', 'smile' ),
			),
			'panel'        => __( 'Manual Display', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'custom_shortcode',
			'opts'         => array(
				'link'        => '[cp_info_bar id="' . $style . '"]' . __( 'Your Content', 'smile' ) . '[/cp_info_bar]',
				'class'       => 'cp-shortcode',
				'value'       => '',
				'title'       => __( 'Launch With Shortcode', 'smile' ),
				'description' => __( 'Place your text, image or HTML in-between the provided shortcode to launch the Info Bar.', 'smile' ),
			),
			'panel'        => __( 'Manual Display', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'inline_shortcode',
			'opts'         => array(
				'link'        => '[cp_info_bar display="inline" id="' . $style . '"][/cp_info_bar]',
				'class'       => 'cp-shortcode',
				'value'       => '',
				'title'       => __( 'Display Inline', 'smile' ),
				'description' => __( 'Use this shortcode to display Info Bar popup inline as a part of page content / Widget.', 'smile' ),
			),
			'panel'        => __( 'Manual Display', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'tags',
			'class'        => '',
			'name'         => 'custom_selector',
			'opts'         => array(
				'title'       => __( 'Launch With Custom Selector', 'smile' ),
				'value'       => '',
				'description' => __( "Use this option to display Info Bar on click of custom selector.  <br/>Example - #myclass[reference='12345']<br>", 'smile' ),

			),
			'panel'        => __( 'Manual Display', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'developer_mode',
			'opts'         => array(
				'title'       => __( 'Enable Cookies', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => $dev_link,
			),
			'panel'        => __( 'Repeat Control', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'conversion_cookie',
			'opts'         => array(
				'title'       => __( 'Do Not Show After Conversion', 'smile' ),
				'value'       => 90,
				'min'         => 0,
				'max'         => 365,
				'step'        => 1,
				'suffix'      => 'days',
				'description' => __( 'How many days this Info Bar should not be displayed after user submits the form?', 'smile' ),
			),
			'panel'        => __( 'Repeat Control', 'smile' ),
			'dependency'   => array(
				'name'     => 'developer_mode',
				'operator' => '==',
				'value'    => '1',
			),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'closed_cookie',
			'opts'         => array(
				'title'       => __( 'Do Not Show After Closing', 'smile' ),
				'value'       => 15,
				'min'         => 0,
				'max'         => 365,
				'step'        => 1,
				'suffix'      => 'days',
				'description' => __( 'How many days this Info Bar should not be displayed after user closes the Info Bar?', 'smile' ),
			),
			'panel'        => __( 'Repeat Control', 'smile' ),
			'dependency'   => array(
				'name'     => 'developer_mode',
				'operator' => '==',
				'value'    => '1',
			),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),

		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'global',
			'opts'         => array(
				'title'       => __( 'Enable On Complete Site', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If set YES, code of this Info Bar will be added throughout the website so it can function anywhere. If set NO - select the specific areas where you want the Info Bar to function and code will be automatically embedded there.', 'smile' ),
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'group_filters',
			'class'        => '',
			'name'         => 'exclusive_on',
			'opts'         => array(
				'title'       => __( 'Enable Only On', 'smile' ),
				'description' => __( 'Enable Info Bar on selected pages, posts, custom posts, special pages.', 'smile' ),
				'value'       => '',
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-eye',
			'dependency'   => array(
				'name'     => 'global',
				'operator' => '==',
				'value'    => '0',
			),
		),
		array(
			'type'         => 'post-types',
			'class'        => '',
			'name'         => 'exclusive_post_type',
			'opts'         => array(
				'title'       => '',
				'description' => __( 'Enable Info Bar on all single posts of particular custom post types, taxonomies.', 'smile' ),
				'value'       => '',
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-eye',
			'dependency'   => array(
				'name'     => 'global',
				'operator' => '==',
				'value'    => '0',
			),
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'inactivity_link',
			'opts'         => array(
				'link'  => __( 'You can select the exceptional areas, where you want this Info Bar to function.', 'smile' ),
				'value' => '',
				'title' => '',
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
			'dependency'   => array(
				'name'     => 'global',
				'operator' => '==',
				'value'    => 'false',
			),
		),
		array(
			'type'         => 'group_filters',
			'class'        => '',
			'name'         => 'exclude_from',
			'opts'         => array(
				'title'       => __( 'Exceptionally, Disable On', 'smile' ),
				'description' => __( 'Exceptionally disable Info Bar on selected pages, posts, custom posts, special pages.', 'smile' ),
				'value'       => '',
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-eye',
			'dependency'   => array(
				'name'     => 'global',
				'operator' => '==',
				'value'    => '1',
			),
		),
		array(
			'type'         => 'post-types',
			'class'        => '',
			'name'         => 'exclude_post_type',
			'opts'         => array(
				'title'       => '',
				'description' => __( 'Exceptionally disable Info Bar on all single posts of particular custom post types, taxonomies.', 'smile' ),
				'value'       => '',
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-eye',
			'dependency'   => array(
				'name'     => 'global',
				'operator' => '==',
				'value'    => '1',
			),
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'inactivity_link',
			'opts'         => array(
				'link'  => __( 'You can select the areas, where you do not want this Info Bar to function.', 'smile' ),
				'value' => '',
				'title' => '',
			),
			'panel'        => __( 'Target Pages', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
			'dependency'   => array(
				'name'     => 'global',
				'operator' => '==',
				'value'    => 'true',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'show_for_logged_in',
			'opts'         => array(
				'title'       => __( 'Logged-in Users', 'smile' ),
				'value'       => true,
				'on'          => 'SHOW',
				'off'         => 'HIDE',
				'description' => __( 'If your website has login functionality, should the Info Bar be visible to logged users?', 'smile' ),
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'checkbox',
			'class'        => '',
			'name'         => 'visible_to_users',
			'opts'         => array(
				'title'   => __( 'Hide from Users', 'smile' ),
				'value'   => '',
				'options' => $user_arr,
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
			'dependency'   => array(
				'name'     => 'show_for_logged_in',
				'operator' => '==',
				'value'    => 'true',
			),
		),
		array(
			'type'         => 'checkbox',
			'class'        => '',
			'name'         => 'excl_visible_to_users',
			'opts'         => array(
				'title'   => __( 'visible to Users', 'smile' ),
				'value'   => '',
				'options' => $user_arr,
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
			'dependency'   => array(
				'name'     => 'show_for_logged_in',
				'operator' => '==',
				'value'    => 'false',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'display_on_first_load',
			'opts'         => array(
				'title'       => __( 'First Time Users', 'smile' ),
				'value'       => true,
				'on'          => 'SHOW',
				'off'         => 'HIDE',
				'description' => __( 'When user visits your site for the first time, should Info Bar be visible?', 'smile' ),
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'page_load_count',
			'opts'         => array(
				'title'  => __( 'Load After Number of Refreshes', 'smile' ),
				'value'  => 1,
				'min'    => 1,
				'max'    => 20,
				'step'   => 1,
				'suffix' => 'Time',
			),
			'dependency'   => array(
				'name'     => 'display_on_first_load',
				'operator' => '==',
				'value'    => '0',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'checkbox',
			'class'        => '',
			'name'         => 'hide_on_device',
			'opts'         => array(
				'title'   => __( 'Hide on Devices', 'smile' ),
				'value'   => '',
				'options' => array(
					__( 'Desktop', 'smile' ) => 'desktop',
					__( 'Tablet', 'smile' )  => 'tablet',
					__( 'Mobile', 'smile' )  => 'mobile',
				),
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'inactivity_link',
			'opts'         => array(
				'link'  => __( 'By default, this Info Bar will be effective for all. However using controls above, you can hide it for certain visitors.', 'smile' ),
				'value' => '',
				'title' => '',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_referrer',
			'opts'         => array(
				'title'       => __( 'Referrer Detection', 'smile' ),
				'value'       => false,
				'on'          => __( 'Display To', 'smile' ),
				'off'         => __( 'Hide From', 'smile' ),
				'description' => __( 'Info Bar can be displayed when the user is came from a website you would like to track. Eg. If you set to track google.com, all users coming from google will see this popup.', 'smile' ),
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'tags',
			'class'        => '',
			'name'         => 'display_to',
			'opts'         => array(
				'title' => __( 'Display only to -', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'enable_referrer',
				'operator' => '==',
				'value'    => 'true',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'tags',
			'class'        => '',
			'name'         => 'hide_from',
			'opts'         => array(
				'title' => __( 'Hide only from -', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'enable_referrer',
				'operator' => '==',
				'value'    => '0',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),      // Geo Location option.
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'geolocation_styling_section',
			'opts'         => array(
				'title' => 'Geo Location Setting',
				'link'  => '',
				'value' => '',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'enable_geotarget',
			'opts'         => array(
				'title'       => __( 'Enable Geo Location Tracker', 'smile' ),
				'value'       => false,
				'on'          => __( 'Yes', 'smile' ),
				'off'         => __( 'No', 'smile' ),
				'description' => __( 'Enable if you wish to select a country or countries in which you want to specifically show or hide this module', 'smile' ),
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'country_type',
			'opts'         => array(
				'title'   => __( 'Display Module in', 'smile' ),
				'value'   => 'all',
				'options' => array(
					__( 'All Countries', 'smile' )     => 'all',
					__( 'Only EU Countries', 'smile' ) => 'basic-eu',
					__( 'Non EU Countries', 'smile' )  => 'basic-non-eu',
					__( 'Target Specific Countries', 'smile' ) => 'specifics-geo',
				),
			),
			'dependency'   => array(
				'name'     => 'enable_geotarget',
				'operator' => '==',
				'value'    => '1',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'geo_target',
			'class'        => '',
			'name'         => 'specific_countries',
			'opts'         => array(
				'title' => __( 'Select Countries', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'country_type',
				'operator' => '==',
				'value'    => 'specifics-geo',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		array(
			'type'         => 'geo_target',
			'class'        => '',
			'name'         => 'hide_specific_countries',
			'opts'         => array(
				'title' => __( 'Hide Modules in', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'country_type',
				'operator' => '!=',
				'value'    => 'specifics-geo',
			),
			'panel'        => __( 'Target Visitors', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-toggle',
		),
		// End of Geo Location option.
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'live',
			'opts'         => array(
				'title'       => __( 'Enable Info Bar On Site', 'smile' ),
				'value'       => false,
				'on'          => __( 'LIVE', 'smile' ),
				'off'         => __( 'PAUSE', 'smile' ),
				'description' => __( "When Info Bar set as pause, it won't be effective on your website.", 'smile' ),
			),
			'panel'        => __( 'Info Bar Status', 'smile' ),
			'section'      => __( 'Behavior', 'smile' ),
			'section_icon' => 'connects-icon-cog',
		),
	);

	/****** Submission */
	$submission = array(
		array(
			'type'         => 'mailer',
			'class'        => '',
			'name'         => 'mailer',
			'opts'         => array(
				'title' => __( 'Collect Leads Using -', 'smile' ),
				'value' => '',
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'custom_html_form',
			'opts'         => array(
				'title'       => __( 'Paste HTML Code', 'smile' ),
				'value'       => '',
				'description' => __( 'Paste the HTML code of your form, that you can get in your CRM Software like MailChimp', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'mailer',
				'operator' => '==',
				'value'    => 'custom-form',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'txt-link',
			'class'        => '',
			'name'         => 'inactivity_link',
			'opts'         => array(
				'link'  => $camp_link,
				'value' => '',
				'title' => '',
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
			'dependency'   => array(
				'name'     => 'mailer',
				'operator' => '!=',
				'value'    => 'custom-form',
			),
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'on_success',
			'opts'         => array(
				'title'   => __( 'Successful Submission ', 'smile' ),
				'value'   => 'message',
				'options' => array(
					__( 'Display a message', 'smile' ) => 'message',
					__( 'Redirect user', 'smile' )     => 'redirect',
				),
			),
			'panel'        => 'Form Setup',
			'dependency'   => array(
				'name'     => 'mailer',
				'operator' => '!=',
				'value'    => 'custom-form',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'redirect_url',
			'opts'         => array(
				'title'       => __( 'Redirect URL', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter the URL where you would like to redirect user after successful submission.<br/><p>You can also add the link of the downloadable file/files. <br/></p>Separate multiple links with a comma.Please add complete URLs (with http / https)', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'on_success',
				'operator' => '==',
				'value'    => 'redirect',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'on_redirect',
			'opts'         => array(
				'title'   => __( 'Redirect User To', 'smile' ),
				'value'   => 'message',
				'options' => array(
					__( 'Same Tab', 'smile' )      => 'self',
					__( 'New Tab', 'smile' )       => 'blank',
					__( 'Download File', 'smile' ) => 'download',
				),
			),
			'panel'        => 'Form Setup',
			'dependency'   => array(
				'name'     => 'on_success',
				'operator' => '==',
				'value'    => 'redirect',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'redirect_data',
			'opts'         => array(
				'title'       => __( 'Pass Lead Data To Redirect URL', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'Passes the lead email (and name if enabled) as query arguments to redirect URL.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'on_success',
				'operator' => '==',
				'value'    => 'redirect',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'success_message',
			'opts'         => array(
				'title'       => __( 'Message After Success', 'smile' ),
				'value'       => __( 'Thank you.', 'smile' ),
				'description' => __( 'Enter the message you would like to display the user after successfully added to the list.<br/>This input field supports HTML too.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'on_success',
				'operator' => '==',
				'value'    => 'message',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'message_color',
			'opts'         => array(
				'title'       => __( 'Message Text Color', 'smile' ),
				'value'       => '#000000',
				'description' => __( 'Select the text color for success message.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'on_success',
				'operator' => '==',
				'value'    => 'message',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		// infobar close After form submission Options.
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'form_action_on_submit',
			'opts'         => array(
				'title'       => __( 'Action After Submission', 'smile' ),
				'value'       => 'do_nothing',
				'options'     => array(
					__( 'Reappear Form', 'smile' ) => 'reappear',
					__( 'Hide Form', 'smile' )     => 'disappears',
					__( 'Do Nothing', 'smile' )    => 'do_nothing',
				),
				'description' => __( 'Select how your Info Bar behaves after successful form submission.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'on_success',
				'operator' => '==',
				'value'    => 'message',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'form_reappear_time',
			'opts'         => array(
				'title'       => __( "Reappear Form After 'x' Seconds of Submission", 'smile' ),
				'value'       => 1,
				'min'         => 0,
				'max'         => 100,
				'step'        => 1,
				'suffix'      => 's',
				'description' => __( 'Reappear form after successful form submission.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'form_action_on_submit',
				'operator' => '==',
				'value'    => 'reappear',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'form_disappears_time',
			'opts'         => array(
				'title'       => __( "Hide Info Bar After 'x' Seconds of Submission", 'smile' ),
				'value'       => 1,
				'min'         => 0,
				'max'         => 100,
				'step'        => 1,
				'suffix'      => 's',
				'description' => __( 'Hide Info Bar after successful form submission.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'dependency'   => array(
				'name'     => 'form_action_on_submit',
				'operator' => '==',
				'value'    => 'disappears',
			),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'cp_new_user_role',
			'opts'         => array(
				'title'       => __( 'Set user role for subscriber', 'smile' ),
				'value'       => 'None',
				'options'     => $new_arr,
				'description' => __( 'Assign a WordPress user role after successful submission.', 'smile' ),
			),
			'panel'        => 'Form Setup',
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		// fail submission.
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'msg_on_fail_submission',
			'opts'         => array(
				'title' => __( 'Failed Submission', 'smile' ),
				'value' => '',
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
			'dependency'   => array(
				'name'     => 'mailer',
				'operator' => '!=',
				'value'    => 'custom-form',
			),
		),
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'msg_wrong_email',
			'opts'         => array(
				'title'       => __( 'Failed Submission', 'smile' ),
				'value'       => __( 'Please enter correct email address.', 'smile' ),
				'description' => __( 'Enter the message you would like to display the user for invalid email address.<br/>This input field supports HTML too.', 'smile' ),
			),
			'panel'        => __( 'Form Setup', 'smile' ),
			'section'      => __( 'Submission', 'smile' ),
			'section_icon' => 'connects-icon-disc',
			'dependency'   => array(
				'name'     => 'mailer',
				'operator' => '!=',
				'value'    => 'custom-form',
			),
		),
	);

	/*---------- Animation-----------*/
	$animation_array = array(
		__( 'No Effect', 'smile' )            => 'smile-none',
		__( '3D Slit', 'smile' )              => 'smile-3DSlit',
		__( '3D Sign', 'smile' )              => 'smile-3DSign',
		__( '3D Rotate Bottom', 'smile' )     => 'smile-3DRotateBottom',
		__( '3D Rotate In Left', 'smile' )    => 'smile-3DRotateInLeft',
		__( '3D Flip Vertical', 'smile' )     => 'smile-3DFlipVertical',
		__( '3D Flip Horizontal', 'smile' )   => 'smile-3DFlipHorizontal',
		__( 'Bounce', 'smile' )               => 'smile-bounce',
		__( 'Bounce In', 'smile' )            => 'smile-bounceIn',
		__( 'Bounce In Down', 'smile' )       => 'smile-bounceInDown',
		__( 'Bounce In Left', 'smile' )       => 'smile-bounceInLeft',
		__( 'Bounce In Right', 'smile' )      => 'smile-bounceInRight',
		__( 'Bounce In Up', 'smile' )         => 'smile-bounceInUp',
		__( 'Fade In', 'smile' )              => 'smile-fadeIn',
		__( 'Fade In & Scale', 'smile' )      => 'smile-fadeInScale',
		__( 'Fade In Down', 'smile' )         => 'smile-fadeInDown',
		__( 'Fade In Down Big', 'smile' )     => 'smile-fadeInDownBig',
		__( 'Fade In Left', 'smile' )         => 'smile-fadeInLeft',
		__( 'Fade In Left Big', 'smile' )     => 'smile-fadeInLeftBig',
		__( 'Fade In Right', 'smile' )        => 'smile-fadeInRight',
		__( 'Fade In Right Big', 'smile' )    => 'smile-fadeInRightBig',
		__( 'Fade In Up', 'smile' )           => 'smile-fadeInUp',
		__( 'Fade In Up Big', 'smile' )       => 'smile-fadeInUpBig',
		__( 'Fall', 'smile' )                 => 'smile-fall',
		__( 'Flash', 'smile' )                => 'smile-flash',
		__( 'Flip In X', 'smile' )            => 'smile-flipInX',
		__( 'Flip In Y', 'smile' )            => 'smile-flipInY',
		__( 'Jello', 'smile' )                => 'smile-jello',
		__( 'Light Speed In', 'smile' )       => 'smile-lightSpeedIn',
		__( 'Newspaper', 'smile' )            => 'smile-newsPaper',
		__( 'Pulse', 'smile' )                => 'smile-pulse',
		__( 'Roll In', 'smile' )              => 'smile-rollIn',
		__( 'Rotate In', 'smile' )            => 'smile-rotateIn',
		__( 'Rotate In Down Left', 'smile' )  => 'smile-rotateInDownLeft',
		__( 'Rotate In Down Right', 'smile' ) => 'smile-rotateInDownRight',
		__( 'Rotate In Up Left', 'smile' )    => 'smile-rotateInUpLeft',
		__( 'Rotate In Up Right', 'smile' )   => 'smile-rotateInUpRight',
		__( 'Rubber Band', 'smile' )          => 'smile-rubberBand',
		__( 'Shake', 'smile' )                => 'smile-shake',
		__( 'Side Fall', 'smile' )            => 'smile-sideFall',
		__( 'Slide In Bottom', 'smile' )      => 'smile-slideInBottom',
		__( 'Slide In Down', 'smile' )        => 'smile-slideInDown',
		__( 'Slide In Left', 'smile' )        => 'smile-slideInLeft',
		__( 'Slide In Right', 'smile' )       => 'smile-slideInRight',
		__( 'Slide In Up', 'smile' )          => 'smile-slideInUp',
		__( 'Super Scaled', 'smile' )         => 'smile-superScaled',
		__( 'Swing', 'smile' )                => 'smile-swing',
		__( 'Tada', 'smile' )                 => 'smile-tada',
		__( 'Wobble', 'smile' )               => 'smile-wobble',
		__( 'Zoom In', 'smile' )              => 'smile-zoomIn',
		__( 'Zoom In Down', 'smile' )         => 'smile-zoomInDown',
		__( 'Zoom In Left', 'smile' )         => 'smile-zoomInLeft',
		__( 'Zoom In Right', 'smile' )        => 'smile-zoomInRight',
		__( 'Zoom In Up', 'smile' )           => 'smile-zoomInUp',
	);

	$exit_animation = array(
		__( 'No Effect', 'smile' )             => 'cp-overlay-none',
		__( 'Bounce Out', 'smile' )            => 'smile-bounceOutDown',
		__( 'Bounce Out Down', 'smile' )       => 'smile-bounceOutDown',
		__( 'Bounce Out Left', 'smile' )       => 'smile-bounceOutLeft',
		__( 'Bounce Out Right', 'smile' )      => 'smile-bounceOutRight',
		__( 'Bounce Out Up', 'smile' )         => 'smile-bounceOutUp',
		__( 'Fade Out', 'smile' )              => 'smile-fadeOut',
		__( 'Fade Out Down', 'smile' )         => 'smile-fadeOutDown',
		__( 'Fade Out Down Big', 'smile' )     => 'smile-fadeOutDownBig',
		__( 'Fade Out Left', 'smile' )         => 'smile-fadeOutLeft',
		__( 'Fade Out Left Big', 'smile' )     => 'smile-fadeOutLeftBig',
		__( 'Fade Out Right', 'smile' )        => 'smile-fadeOutRight',
		__( 'Fade Out Right Big', 'smile' )    => 'smile-fadeOutRightBig',
		__( 'Fade Out Up', 'smile' )           => 'smile-fadeOutUp',
		__( 'Fade Out Up Big', 'smile' )       => 'smile-fadeOutUpBig',
		__( 'Flip Out X', 'smile' )            => 'smile-flipOutX',
		__( 'Flip Out Y', 'smile' )            => 'smile-flipOutY',
		__( 'Hinge', 'smile' )                 => 'smile-hinge',
		__( 'Light Speed Out', 'smile' )       => 'smile-lightSpeedOut',
		__( 'Rotate Out', 'smile' )            => 'smile-rotateOut',
		__( 'Rotate Out Down Left', 'smile' )  => 'smile-rotateOutDownLeft',
		__( 'Rotate Out Down Right', 'smile' ) => 'smile-rotateOutDownRight',
		__( 'Rotate Out Up Left', 'smile' )    => 'smile-rotateOutUpLeft',
		__( 'Rotate Out Up Right', 'smile' )   => 'smile-rotateOutUpRight',
		__( 'RollOut', 'smile' )               => 'smile-rollOut',
		__( 'Slide Out Down', 'smile' )        => 'smile-slideOutDown',
		__( 'Slide Out Left', 'smile' )        => 'smile-slideOutLeft',
		__( 'Slide Out Right', 'smile' )       => 'smile-slideOutRight',
		__( 'Slide Out Up', 'smile' )          => 'smile-slideOutUp',
		__( 'Zoom Out', 'smile' )              => 'smile-zoomOut',
		__( 'Zoom Out Down', 'smile' )         => 'smile-zoomOutDown',
		__( 'Zoom Out Left', 'smile' )         => 'smile-zoomOutLeft',
		__( 'Zoom Out Right', 'smile' )        => 'smile-zoomOutRight',
		__( 'Zoom Out Up', 'smile' )           => 'smile-zoomOutUp',
	);
	$animation      = array(

		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'entry_animation',
			'opts'         => array(
				'title'       => __( 'Entry Animation', 'smile' ),
				'description' => __( 'Select the entry level animation for info bar.', 'smile' ),
				'value'       => 'smile-slideInDown',
				'options'     => $animation_array,
			),
			'panel'        => __( 'Animation', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'exit_animation',
			'opts'         => array(
				'title'       => __( 'Exit Animation', 'smile' ),
				'description' => __( 'Select the exit level animation for info bar.', 'smile' ),
				'value'       => 'smile-slideOutUp',
				'options'     => $exit_animation,
			),
			'panel'        => __( 'Animation', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'button_animation',
			'opts'         => array(
				'title'       => __( 'Button Animation', 'smile' ),
				'description' => __( 'Select the exit level animation for info bar submit button .', 'smile' ),
				'value'       => 'smile-none',
				'options'     => $animation_array,
			),
			'panel'        => __( 'Animation', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'disable_overlay_effect',
			'opts'         => array(
				'title'       => __( 'Disable Animation on Small Screens', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'On smaller screens like mobile, disable animation with this setting.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'overlay_effect',
				'operator' => '!=',
				'value'    => 'cp-overlay-none',
			),
			'panel'        => __( 'Animation', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'hide_animation_width',
			'opts'         => array(
				'title'       => __( 'Disable When Browser Width Is Below -', 'smile' ),
				'value'       => 768,
				'min'         => 240,
				'max'         => 1200,
				'step'        => 1,
				'description' => __( 'When width of the browser is below provided value, the Info Bar animation will disable.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'disable_overlay_effect',
				'operator' => '==',
				'value'    => '1',
			),
			'panel'        => __( 'Animation', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
	);

	$close_link = array(
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'close_info_bar',
			'opts'         => array(
				'title'   => __( 'Type', 'smile' ),
				'value'   => 'close_img',
				'options' => array(
					__( 'Image', 'smile' )        => 'close_img',
					__( 'Text', 'smile' )         => 'close_txt',
					__( 'Do Not Close', 'smile' ) => 'do_not_close',
				),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'close_ib_image_src',
			'opts'         => array(
				'title'   => __( 'Image source', 'smile' ),
				'value'   => 'upload_img',
				'options' => array(
					__( 'Custom URL', 'smile' )       => 'custom_url',
					__( 'Upload Image', 'smile' )     => 'upload_img',
					__( 'Predefined Icons', 'smile' ) => 'pre_icons',
				),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '==',
				'value'    => 'close_img',
			),
		),
		array(
			'type'         => 'radio-image',
			'name'         => 'close_icon',
			'opts'         => array(
				'title'      => '',
				'value'      => 'default',
				'width'      => '80px',
				'options'    => array(
					'black'         => CP_BASE_URL . 'modules/assets/images/black.png',
					'blue_final'    => CP_BASE_URL . 'modules/assets/images/blue_final.png',
					'circle_final'  => CP_BASE_URL . 'modules/assets/images/circle_final.png',
					'default'       => CP_BASE_URL . 'modules/assets/images/default.png',
					'grey_close'    => CP_BASE_URL . 'modules/assets/images/grey_close.png',
					'red02'         => CP_BASE_URL . 'modules/assets/images/red02.png',
					'rounded_black' => CP_BASE_URL . 'modules/assets/images/rounded_black.png',
					'white20'       => CP_BASE_URL . 'modules/assets/images/white20_bb.png',
				),
				'imagetitle' => array(
					__( 'title-0', 'smile' ) => 'Black',
					__( 'title-1', 'smile' ) => 'Blue',
					__( 'title-2', 'smile' ) => 'Circle',
					__( 'title-3', 'smile' ) => 'Default',
					__( 'title-4', 'smile' ) => 'Grey',
					__( 'title-5', 'smile' ) => 'Red',
					__( 'title-6', 'smile' ) => 'Red',
					__( 'title-7', 'smile' ) => 'White',
				),
			),
			'panel'        => 'Close Link',
			'section'      => 'Design',
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'close_ib_image_src',
				'operator' => '==',
				'value'    => 'pre_icons',
			),
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'info_bar_close_img_custom_url',
			'opts'         => array(
				'title' => __( 'Custom URL', 'smile' ),
				'value' => '',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'close_ib_image_src',
				'operator' => '==',
				'value'    => 'custom_url',
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'close_info_bar_pos',
			'opts'         => array(
				'title'       => __( 'Position', 'smile' ),
				'description' => __( 'Inline position will look good for smaller width Info Bar.', 'smile' ),
				'value'       => true,
				'on'          => 'FIXED',
				'off'         => 'INLINE',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-disc',
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
		),

		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'close_txt',
			'opts'         => array(
				'title' => __( 'Close Text', 'smile' ),
				'value' => 'Close',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '==',
				'value'    => 'close_txt',
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'close_text_color',
			'opts'         => array(
				'title' => __( 'Close Text Color', 'smile' ),
				'value' => 'rgb(238, 238, 238)',
			),
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '==',
				'value'    => 'close_txt',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'google_fonts',
			'name'         => 'close_text_font',
			'opts'         => array(
				'title'        => __( 'Close Text Font Name', 'smile' ),
				'value'        => 'Montserrat',
				'use_in'       => 'panel',
				'css_property' => 'font-family',
				'css_selector' => '.ib-close.ib-text-close',
				'css_preview'  => true,
			),
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '==',
				'value'    => 'close_txt',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'media',
			'class'        => '',
			'name'         => 'close_img',
			'opts'         => array(
				'title' => __( 'Choose Image', 'smile' ),
				'value' => CP_PLUGIN_URL . 'modules/info_bar/functions/config/img/cross.png',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'close_ib_image_src',
				'operator' => '==',
				'value'    => 'upload_img',
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'close_img_width',
			'opts'         => array(
				'title'        => __( 'Close Image Width', 'smile' ),
				'value'        => 22,
				'min'          => 14,
				'max'          => 128,
				'step'         => 1,
				'suffix'       => 'px',
				'css_selector' => '.ib-img-close',
				'css_property' => 'width',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '==',
				'value'    => 'close_img',
				'name'     => 'close_ib_image_src',
				'operator' => '!=',
				'value'    => 'none',
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'adjacent_close_position',
			'opts'         => array(
				'title'       => __( 'Close Image Position', 'smile' ),
				'value'       => 'top_right',
				'options'     => array(
					__( 'Top Left', 'smile' )  => 'top_left',
					__( 'Top Right', 'smile' ) => 'top_right',
				),
				'description' => __( 'Choose position for close button.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '!=',
				'value'    => 'do_not_close',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'display_close_on_duration',
			'opts'         => array(
				'title'       => __( 'Display Close After Few Seconds', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, close image / text will display after few seconds.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '!=',
				'value'    => 'do_not_close',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'close_btn_duration',
			'opts'         => array(
				'title'       => __( 'Display After Seconds', 'smile' ),
				'value'       => 1,
				'min'         => 1,
				'max'         => 100,
				'step'        => 1,
				'suffix'      => 'Sec',
				'description' => __( 'How long the close image / text to be displayed after Info Bar is loaded? (value in seconds).', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'display_close_on_duration',
				'operator' => '==',
				'value'    => 'true',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

		// Toggle Button.
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'ifb_button_options_title',
			'opts'         => array(
				'title' => __( 'Toggle Button', 'smile' ),
				'value' => '',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '!=',
				'value'    => 'do_not_close',
				'name'     => 'close_ib_image_src',
				'operator' => '!=',
				'value'    => 'none',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'toggle_btn',
			'opts'         => array(
				'title'       => __( 'Enable Toggle Button', 'smile' ),
				'description' => __( 'If enabled, toggle button will display when user clicks on the close link.', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'close_info_bar',
				'operator' => '!=',
				'value'    => 'do_not_close',
				'name'     => 'close_ib_image_src',
				'operator' => '!=',
				'value'    => 'none',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'toggle_btn_visible',
			'opts'         => array(
				'title'       => __( 'Display Toggle Button Initially', 'smile' ),
				'description' => __( 'If enabled, toggle button will display by default from initial page load.', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'toggle_button_title',
			'opts'         => array(
				'title' => __( 'Enter the text for toggle button.', 'smile' ),
				'value' => 'Click Me',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
		),
		array(
			'type'         => 'google_fonts',
			'name'         => 'toggle_button_font',
			'opts'         => array(
				'title'  => __( 'Toggle Button Font', 'smile' ),
				'value'  => '',
				'use_in' => 'panel',
			),
			'description'  => __( 'Select font family for toggle button.', 'smile' ),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'toggle_btn_font_size',
			'opts'         => array(
				'title'        => __( 'Toggle Button Font Size', 'smile' ),
				'value'        => 12,
				'min'          => 10,
				'max'          => 40,
				'step'         => 1,
				'suffix'       => 'px',
				'css_selector' => '.cp-ifb-toggle-btn',
				'css_property' => 'font-size',
				'description'  => __( 'Controls the font size of toggle button.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'toggle_button_text_color',
			'opts'         => array(
				'title'        => __( 'Toggle Button Text Color', 'smile' ),
				'value'        => 'rgb(255, 255, 255)',
				'css_property' => 'color',
				'css_selector' => '.cp-ifb-toggle-btn',
				'description'  => __( 'Controls toggle button text color.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'toggle_button_bg_color',
			'opts'         => array(
				'title'        => __( 'Toggle Button Background Color', 'smile' ),
				'value'        => 'rgb(0, 0, 0)',
				'css_property' => 'background',
				'css_selector' => '.cp-ifb-toggle-btn',
				'description'  => __( 'Controls toggle button background color.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'toggle_btn_gradient',
			'opts'         => array(
				'value'       => false,
				'title'       => __( 'Enable Gradient Background', 'smile' ),
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'If enabled, flat color button will convert to gradient.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'toggle_button_border_color',
			'opts'         => array(
				'title'        => __( 'Toggle Button Border Color', 'smile' ),
				'value'        => 'rgb(0, 0, 0)',
				'css_property' => 'border-color',
				'css_selector' => '.cp-ifb-toggle-btn',
				'description'  => __( 'Controls the border color of toggle button.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'toggle_btn_border_size',
			'opts'         => array(
				'title'        => __( 'Toggle Button Border Size', 'smile' ),
				'value'        => 0,
				'min'          => 0,
				'max'          => 40,
				'step'         => 1,
				'suffix'       => 'px',
				'css_selector' => '.cp-ifb-toggle-btn',
				'css_property' => 'border-width',
				'description'  => __( 'Controls the border size of toggle button.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'toggle_btn_border_radius',
			'opts'         => array(
				'title'        => __( 'Toggle Button Border Radius', 'smile' ),
				'value'        => 0,
				'min'          => 0,
				'max'          => 50,
				'step'         => 1,
				'suffix'       => 'px',
				'css_selector' => '.cp-ifb-toggle-btn',
				'css_property' => 'border-radius',
				'description'  => __( 'Controls the border radius of toggle button.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// store button darken on hover.
		array(
			'type'         => 'textfield',
			'name'         => 'toggle_button_bg_hover_color',
			'opts'         => array(
				'title' => __( 'Button BG Hover Color', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'toggle_btn_padding_tb',
			'opts'         => array(
				'title'        => __( 'Toggle Button Vertical Padding', 'smile' ),
				'css_property' => 'padding-tb',
				'css_selector' => '.cp-ifb-toggle-btn',
				'value'        => 10,
				'min'          => 0,
				'max'          => 30,
				'step'         => 1,
				'suffix'       => 'px',
				'description'  => __( 'Controls top & bottom padding of toggle button.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'toggle_btn_padding_lrv',
			'opts'         => array(
				'title'        => __( 'Toggle Button Horizontal Padding', 'smile' ),
				'css_property' => 'padding-lr',
				'css_selector' => '.cp-ifb-toggle-btn',
				'value'        => 15,
				'min'          => 0,
				'max'          => 30,
				'step'         => 1,
				'suffix'       => 'px',
				'description'  => __( 'Controls left & right padding of toggle button.', 'smile' ),
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '==',
				'value'    => true,
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// store button lighten gradient.
		array(
			'type'         => 'textfield',
			'name'         => 'toggle_button_bg_gradient_color',
			'opts'         => array(
				'title' => __( 'Button Gradient Color', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// InfoBar Auto close Options.
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'infobar_auto_close_section',
			'opts'         => array(
				'title' => __( 'Auto Close Module', 'smile' ),
				'value' => '',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '!=',
				'value'    => true,
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'autoclose_on_duration',
			'opts'         => array(
				'title'       => __( 'Autoclose Module', 'smile' ),
				'value'       => false,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( "If enabled, Info Bar will close automatically after 'x' seconds when user is inactive on webpage.", 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'toggle_btn',
				'operator' => '!=',
				'value'    => true,
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'close_module_duration',
			'opts'         => array(
				'title'       => __( 'Autoclose Duration', 'smile' ),
				'value'       => 1,
				'min'         => 0.1,
				'max'         => 100,
				'step'        => 0.1,
				'suffix'      => 'Sec',
				'description' => __( 'How long the Info Bar should take to be close after its loaded? (value in seconds).', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'autoclose_on_duration',
				'operator' => '==',
				'value'    => '1',
			),
			'panel'        => __( 'Close Link', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

	);

	$ib_content = array(
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'info_bar_content',
			'opts'         => array(
				'title'       => __( 'Info Bar Content', 'smile' ),
				'value'       => __( 'BLANK style is purely built for customization. This style supports text, images, shortcodes, HTML etc. Use Source button from Rich Text Editor toolbar & customize your Info Bar effectively.', 'smile' ),
				'description' => __( 'Enter the short description of this optin.(HTML is Allowed)', 'smile' ),
			),
			'panel'        => __( 'Name', 'smile' ),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
	);

	// separator color for border.
	$seperator_color = array(
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'seperator_border_color',
			'opts'         => array(
				'title'       => __( 'Seperator Border Color', 'smile' ),
				'value'       => 'rgb(255, 255, 255)',
				'description' => __( 'Select the Seperator color for info bar.', 'smile' ),
			),
			'panel'        => __( 'Design', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-disc',
		),
	);

	/*** Array contains Info Bar image options */
	$ifb_img = array(

		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'info_bar_img_src',
			'opts'         => array(
				'title'   => __( 'Image source', 'smile' ),
				'value'   => 'upload_img',
				'options' => array(
					__( 'Custom URL', 'smile' )   => 'custom_url',
					__( 'Upload Image', 'smile' ) => 'upload_img',
					__( 'None', 'smile' )         => 'none',
				),
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'info_bar_img_custom_url',
			'opts'         => array(
				'title'       => __( 'Custom URL', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter custom URL for your image.', 'smile' ),
			),
			'panel'        => __( 'Image', 'smile' ),
			'dependency'   => array(
				'name'     => 'info_bar_img_src',
				'operator' => '==',
				'value'    => 'custom_url',
			),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'media',
			'class'        => '',
			'name'         => 'info_bar_image',
			'opts'         => array(
				'title'       => __( 'Upload Image', 'smile' ),
				'value'       => CP_PLUGIN_URL . 'modules/info_bar/functions/config/img/logo.png',
				'description' => __( 'Upload an image that will be displayed inside the content area.Image size will not bigger than its container.', 'smile' ),
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'info_bar_img_src',
				'operator' => '==',
				'value'    => 'upload_img',
			),
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'image_size',
			'opts'         => array(
				'title'        => __( 'Resize Image', 'smile' ),
				'value'        => 150,
				'min'          => 1,
				'max'          => 1000,
				'step'         => 1,
				'suffix'       => 'px',
				'css_property' => 'max-width',
				'css_selector' => '.cp-info-bar .cp-image-container img',
				'description'  => __( 'The maximum size of an image is limited to the size of its container.', 'smile' ),
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'info_bar_img_src',
				'operator' => '!=',
				'value'    => 'none',
			),
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'image_position',
			'opts'         => array(
				'title' => __( 'Image Position', 'smile' ),
				'value' => true,
				'on'    => 'RIGHT',
				'off'   => 'LEFT',
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'image_horizontal_position',
			'opts'         => array(
				'title' => __( 'Horizontal Position', 'smile' ),
				'value' => 0,
				'min'   => -250,
				'max'   => 250,
				'step'  => 1,
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'image_vertical_position',
			'opts'         => array(
				'title' => __( 'Vertical Position', 'smile' ),
				'value' => 0,
				'min'   => -250,
				'max'   => 250,
				'step'  => 1,
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'image_displayon_mobile',
			'opts'         => array(
				'title'       => __( 'Hide Image on Small Screens', 'smile' ),
				'value'       => true,
				'on'          => __( 'YES', 'smile' ),
				'off'         => __( 'NO', 'smile' ),
				'description' => __( 'On smaller screens like mobile, smaller Info Bars look more beautiful. To reduce the size of the Info Bar, you may hide the image with this setting.', 'smile' ),
			),
			'panel'        => __( 'Image', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
	);


	// ANOTHER SUBMIT BUTTON.
	$submit_btn = array(
		array(
			'type'         => 'section',
			'class'        => '',
			'name'         => 'ifb_button_options_title',
			'opts'         => array(
				'title' => __( 'CTA Button Options', 'smile' ),
				'value' => '',
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'dropdown',
			'class'        => '',
			'name'         => 'ifb_btn_style',
			'opts'         => array(
				'title'       => __( 'Button Style', 'smile' ),
				'value'       => 'cp-btn-flat',
				'description' => __( 'Style your button with nice effects.', 'smile' ),
				'options'     => array(
					__( 'Flat', 'smile' )     => 'cp-btn-flat',
					__( '3D', 'smile' )       => 'cp-btn-3d',
					__( 'Outline', 'smile' )  => 'cp-btn-outline',
					__( 'Gradient', 'smile' ) => 'cp-btn-gradient',
				),
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'ifb_button_bg_color',
			'opts'         => array(
				'title' => __( 'Submit Button Background Color', 'smile' ),
				'value' => 'rgb(255, 153, 0)',
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// This option is to store the default initial color of button text.
		array(
			'type'         => 'colorpicker',
			'class'        => '',
			'name'         => 'ifb_button_txt_hover_color',
			'opts'         => array(
				'title' => __( 'Submit Button Text Hover Color', 'smile' ),
				'value' => '#ffffff',
			),
			'dependency'   => array(
				'name'     => 'ifb_btn_style',
				'operator' => '==',
				'value'    => 'cp-btn-outline',
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// store button darken on hover.
		array(
			'type'         => 'textfield',
			'name'         => 'ifb_button_bg_hover_color',
			'opts'         => array(
				'title' => __( 'Button BG Hover Color', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// store button lighten gradient.
		array(
			'type'         => 'textfield',
			'name'         => 'ifb_button_bg_gradient_color',
			'opts'         => array(
				'title' => __( 'Button Gradient Color', 'smile' ),
				'value' => '',
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'textarea',
			'class'        => '',
			'name'         => 'ifb_button_title',
			'opts'         => array(
				'title'       => __( 'Submit Button Title', 'smile' ),
				'value'       => 'Subscribe',
				'description' => __( 'Enter the main heading title.', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'panel'        => __( 'Name', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'slider',
			'class'        => '',
			'name'         => 'ifb_btn_border_radius',
			'opts'         => array(
				'title' => __( 'Border Radius', 'smile' ),
				'value' => 3,
				'min'   => 0,
				'max'   => 20,
				'step'  => 1,
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		array(
			'type'         => 'switch',
			'class'        => '',
			'name'         => 'ifb_btn_shadow',
			'opts'         => array(
				'title' => __( 'Button Shadow', 'smile' ),
				'value' => false,
				'on'    => __( 'YES', 'smile' ),
				'off'   => __( 'NO', 'smile' ),
			),
			'panel'        => __( 'Call to Action', 'smile' ),
			'section'      => __( 'Design', 'smile' ),
			'section_icon' => 'connects-icon-image',
			'dependency'   => array(
				'name'     => 'ifb_btn_style',
				'operator' => '!=',
				'value'    => 'cp-btn-3d',
			),
		),
		// Hidden variable to store the (darken button color).
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'ifb_btn_darken',
			'opts'         => array(
				'title'       => __( 'Button Darken', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter the short description of this optin.(HTML is Allowed)', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),
		// Hidden variable to store the (gradient button color).
		array(
			'type'         => 'textfield',
			'class'        => '',
			'name'         => 'ifb_btn_gradiant',
			'opts'         => array(
				'title'       => __( 'Button Darken', 'smile' ),
				'value'       => '',
				'description' => __( 'Enter the short description of this optin.(HTML is Allowed)', 'smile' ),
			),
			'dependency'   => array(
				'name'     => 'hidden',
				'operator' => '==',
				'value'    => 'hide',
			),
			'section'      => __( 'Design', 'smile' ),
			'panel'        => __( 'Background', 'smile' ),
			'section_icon' => 'connects-icon-image',
		),

	);

	// Style - Blank info bar.
	smile_update_options(
		'Smile_Info_Bars',
		'blank',
		array_merge(
			$name,
			$ib_content,
			$background,
			$close_link,
			$animation,
			$advance_options,
			$behavior
		)
	);

	// Style - newsletter.
	smile_update_options(
		'Smile_Info_Bars',
		'newsletter',
		array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Add options for simple image_preview.
	smile_update_options(
		'Smile_Info_Bars',
		'image_preview',
		array_merge(
			$name,
			$background,
			$ifb_img,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - get_this_deal.
	smile_update_options(
		'Smile_Info_Bars',
		'get_this_deal',
		array_merge(
			$name,
			$background,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - free_trial.
	smile_update_options(
		'Smile_Info_Bars',
		'free_trial',
		array_merge(
			$name,
			$background,
			$ifb_img,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);
	// Style - free_trial.
	smile_update_options(
		'Smile_Info_Bars',
		'countdown',
		array_merge(
			$name,
			$background,
			$cp_count_down,
			$close_link,
			$animation,
			$behavior,
			$advance_options
		)
	);

	// Style - cp-weekly-article.
	smile_update_options(
		'Smile_Info_Bars',
		'weekly_article',
		array_merge(
			$name,
			$background,
			$ifb_img,
			$cp_form,
			$close_link,
			$animation,
			$behavior,
			$submission,
			$advance_options
		)
	);

	// Style - Social_media info bar.
	smile_update_options(
		'Smile_Info_Bars',
		'social_info_bar',
		array_merge(
			$name,
			$ib_content,
			$background,
			$cp_social,
			$close_link,
			$animation,
			$advance_options,
			$behavior
		)
	);
}

/**
 * Update Options.
 */
if ( function_exists( 'smile_update_default' ) ) {

	// Blank.
	$blank_default = array(
		'bg_color'       => '#dd433e',
		'infobar_height' => 30,
		'enable_shadow'  => false,
		'border_darken'  => '#d03631',
		'enable_border'  => true,
		'border'         => 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#d03631|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		'infobar_title'  => __( 'BLANK style is purely built for customization. This style supports text, images, shortcodes, HTML etc. Use Source button from Rich Text Editor toolbar & customize your Info Bar effectively.', 'smile' ),
	);

	foreach ( $blank_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'blank', $option, $value );
	}

	// NewsLetter.
	$newsletter_optin_default = array(
		'form_fields'              => 'order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true',
		'form_layout'              => 'cp-form-layout-3',
		'form_input_align'         => 'left',
		'form_submit_align'        => 'cp-submit-wrap-full',
		'form_grid_structure'      => 'cp-form-grid-structure-1',
		'form_input_font_size'     => 13,
		'form_input_padding_tb'    => 7,
		'form_input_padding_lr'    => 10,
		'submit_button_tb_padding' => 8,
		'infobar_title'            => 'Sign-up for exclusive content. Be the first to hear about ' . CP_PLUS_NAME . ' news.',
		'bg_color'                 => '#f26e27',
		'button_title'             => 'Subscribe',
		'button_bg_color'          => '#444444',
		'button_border_color'      => '#444444',
		'placeholder_font'         => 'Lato',
		'border'                   => 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#e5611a|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		'name_text'                => __( 'Enter Your Name', 'smile' ),
		'placeholder_text'         => __( 'Your Email', 'smile' ),
	);
	foreach ( $newsletter_optin_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'newsletter', $option, $value );
	}

	// get_this_deal.
	$get_this_deal_default = array(
		'form_fields'              => 'order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true',
		'form_layout'              => 'cp-form-layout-4',
		'form_input_align'         => 'left',
		'form_submit_align'        => 'cp-submit-wrap-full',
		'form_grid_structure'      => 'cp-form-grid-structure-1',
		'form_input_font_size'     => 13,
		'form_input_padding_tb'    => 7,
		'form_input_padding_lr'    => 12,
		'submit_button_tb_padding' => 8,
		'bg_color'                 => '#db6d2c',
		'infobar_height'           => 50,
		'btn_border_radius'        => 25,
		'enable_border'            => 1,
		'infobar_title'            => '<span style="font-weight: bold;font-size:20px;">' . __( '$25 Off ', 'smile' ) . '</span>' . __( ' when you complete your order today.', 'smile' ),
		'button_title'             => 'Apply Coupon',
		'button_bg_color'          => '#333332',
		'button_border_color'      => '#333332',
		'btn_border_radius'        => 8,
		'border'                   => 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#ffffff|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:8',
		'bg_gradient'              => false,
		'toggle_button_title'      => 'GET DEAL',
		'toggle_button_font'       => 'Bitter',
		'toggle_button_bg_color'   => '#db6d2c',
		'border_darken'            => '#ffffff',
	);
	foreach ( $get_this_deal_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'get_this_deal', $option, $value );
	}

	// Image Preview.
	$image_preview_default = array(
		'form_fields'            => 'order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true',
		'form_layout'            => 'cp-form-layout-4',
		'form_input_align'       => 'left',
		'form_submit_align'      => 'cp-submit-wrap-full',
		'form_grid_structure'    => 'cp-form-grid-structure-1',
		'form_input_font_size'   => 13,
		'form_input_padding_tb'  => 8,
		'form_input_padding_lr'  => 15,
		'bg_color'               => '#ffffff',
		'infobar_height'         => 80,
		'border'                 => 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#f2f2f2|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		'infobar_title'          => __( 'Merry Christmas! Enjoy all time low prices and discount this festival season.', 'smile' ),
		'image_size'             => 165,
		'button_title'           => 'Shop Now',
		'button_bg_color'        => '#db6d2c',
		'button_border_color'    => '#db6d2c',
		'bg_gradient'            => false,
		'image_displayon_mobile' => true,
	);
	foreach ( $image_preview_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'image_preview', $option, $value );
	}

	// Free Trial.
	$free_trial_default = array(
		'form_fields'              => 'order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true',
		'form_layout'              => 'cp-form-layout-3',
		'form_input_align'         => 'left',
		'form_submit_align'        => 'cp-submit-wrap-center',
		'form_grid_structure'      => 'cp-form-grid-structure-1',
		'form_input_font_size'     => 13,
		'form_input_padding_tb'    => 7,
		'form_input_padding_lr'    => 12,
		'submit_button_tb_padding' => 8,
		'infobar_height'           => 100,
		'infobar_title'            => __( 'GROW YOUR BUSINESS!', 'smile' ),
		'infobar_description'      => $random_link,
		'bg_color'                 => '#2c8dd7',
		'bg_gradient'              => true,
		'button_title'             => 'Book Free Trial',
		'button_bg_color'          => '#1a2730',
		'button_border_color'      => '#1a2730',
		'btn_border_radius'        => 4,
		'ifb_btn_shadow'           => true,
		'placeholder_font'         => 'Lato',
		'name_text'                => __( 'Your Name', 'smile' ),
		'placeholder_text'         => __( 'Your Email', 'smile' ),
		'image_size'               => 120,
		'image_displayon_mobile'   => true,
		'info_bar_image'           => CP_BASE_URL . 'modules/info_bar/assets/img/CP_Product_Box_Mockup.png',
		'infobar_position'         => 'cp-pos-bottom',
		'close_info_bar'           => 'do_not_close',
		'entry_animation'          => 'smile-slideInUp',
		'exit_animation'           => 'smile-slideOutDown',
		'border_darken'            => '#d1d1d1',
	);

	foreach ( $free_trial_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'free_trial', $option, $value );
	}

	// weekly_article.
	$weekly_article_optin_default = array(
		'form_fields'              => 'order->0|input_type->email|input_label->Email|input_name->email|input_placeholder->Enter Your Email Address|input_require->true',
		'form_layout'              => 'cp-form-layout-3',
		'form_input_align'         => 'left',
		'form_submit_align'        => 'cp-submit-wrap-full',
		'form_grid_structure'      => 'cp-form-grid-structure-1',
		'form_input_font_size'     => 15,
		'form_input_padding_tb'    => 9,
		'form_input_padding_lr'    => 10,
		'submit_button_tb_padding' => 10,
		'submit_button_lr_padding' => 20,
		'infobar_title'            => __( 'Get the week&rsquo;s best articles right in your inbox', 'smile' ),
		'bg_color'                 => '#324d5b',
		'button_title'             => 'Subscribe',
		'button_bg_color'          => '#f4b22e',
		'button_border_color'      => '#f4b22e',
		'placeholder_font'         => 'Lato',
		'name_text'                => 'Your Name',
		'placeholder_text'         => 'Your Email',
		'infobar_description'      => 'Join 15K subscribers',
		'infobar_width'            => '1600',
		'bg_gradient'              => false,
		'namefield'                => true,
		'info_bar_image'           => CP_BASE_URL . 'modules/info_bar/assets/img/hellobar.png',
		'image_size'               => '50',
	);
	foreach ( $weekly_article_optin_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'weekly_article', $option, $value );
	}

	// social media.
	$social_info_bar_default = array(
		'bg_color'              => '#ffffff',
		'infobar_height'        => 70,
		'enable_shadow'         => false,
		'border_darken'         => '#ededed',
		'infobar_width'         => '1600',
		'enable_border'         => true,
		'border'                => 'br_type:0|br_all:0|br_tl:0|br_tr:0|br_br:0|br_bl:0|style:solid|color:#d03631|bw_type:1|bw_all:5|bw_t:0|bw_l:0|bw_r:0|bw_b:2',
		'infobar_title'         => __( 'Share this Awesome Stuff with your Friends!', 'smile' ),
		'cp_display_nw_name'    => false,
		'cp_social_icon_effect' => 'flat',
		'cp_social_icon_align'  => 'left',
		'cp_social_icon'        => 'order:0|input_type:Facebook|network_name:|input_action:social_sharing|profile_link:|smile_adv_share_opt:0|input_share:|input_share_count:;order:1|input_type:StumbleUpon|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:2|input_type:Google|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:3|input_type:Blogger|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:;order:4|input_type:LinkedIn|network_name:|input_action:social_sharing|smile_adv_share_opt:0|input_share_count:',
	);

	foreach ( $social_info_bar_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'social_info_bar', $option, $value );
	}

	// countdown.
	$count_down_default = array(
		'infobar_height'               => 100,
		'infobar_title'                => __( "We're Coming Soon!", 'smile' ),
		'infobar_description'          => __( 'Stay tuned for something Amazingly breathtaking ', 'smile' ),
		'bg_color'                     => 'rgba(0,12,51,0.79)',
		'bg_gradient'                  => true,
		'info_bar_bg_image_src'        => 'custom_url',
		'info_bar_bg_image_custom_url' => CP_BASE_URL . 'modules/info_bar/assets/img/count_bg.jpg',
		'infobar_position'             => 'cp-pos-bottom',
		'close_info_bar'               => 'close_img',
		'entry_animation'              => 'smile-slideInUp',
		'exit_animation'               => 'smile-slideOutDown',
		'border_darken'                => '#d1d1d1',
		'counter_bg_color'             => '#ef0b5f',
		'counter_digit_border_color'   => 'rgba(0,8,73,0.01)',
		'datepicker_advance_option'    => 'style_2',
		'counter_digit_text_size'      => '29',
		'counter_timer_text_size'      => '11',
		'counter_font'                 => 'Quicksand',
		'counter_container_bg_color'   => '#084400',
		'counter_border_radius'        => '100',
	);

	foreach ( $count_down_default as $option => $value ) {
		smile_update_default( 'Smile_Info_Bars', 'countdown', $option, $value );
	}
}

/**
 * Remove option from style.
 */
if ( function_exists( 'smile_remove_option' ) ) {

	// Blank.
	smile_remove_option( 'Smile_Info_Bars', 'blank', array( 'button_animation', 'placeholder_font' ) );

	// get_this_deal.
	smile_remove_option( 'Smile_Info_Bars', 'get_this_deal', array( 'new_line_optin', 'namefield', 'name_text', 'placeholder_text', 'placeholder_color', 'input_bg_color', 'input_border_color', 'image_position', 'new_line_optin', 'inactivity_link_for_form', 'image_vertical_position', 'image_horizontal_position', 'placeholder_font' ) );

	// Image Preview.
	smile_remove_option( 'Smile_Info_Bars', 'image_preview', array( 'new_line_optin', 'namefield', 'name_text', 'placeholder_text', 'placeholder_color', 'input_bg_color', 'input_border_color', 'image_position', 'new_line_optin', 'inactivity_link_for_form', 'image_vertical_position', 'image_horizontal_position', 'placeholder_font' ) );

	// Big Image Bar.
	smile_remove_option( 'Smile_Info_Bars', 'free_trial', array( 'new_line_optin', 'image_position', 'image_vertical_position', 'image_horizontal_position' ) );

	// weekly_article.
	smile_remove_option( 'Smile_Info_Bars', 'weekly_article', array( 'image_position', 'new_line_optin', 'image_vertical_position', 'image_horizontal_position' ) );

		// social media.
	smile_remove_option( 'Smile_Info_Bars', 'social_info_bar', array( 'button_animation', 'placeholder_font' ) );

}
