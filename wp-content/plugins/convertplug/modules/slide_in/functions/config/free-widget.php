<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( function_exists( 'smile_framework_add_options' ) ) {
	$style_arr = array(
		'style_name'    => 'Free Widget',
		'demo_url'      => CP_PLUGIN_URL . 'modules/slide_in/assets/demos/free_widget/free_widget.html',
		'demo_dir'      => plugin_dir_path( __FILE__ ) . '../../assets/demos/free_widget/free_widget.html',
		'img_url'       => CP_PLUGIN_URL . 'modules/slide_in/assets/demos/free_widget/free_widget.png',
		'customizer_js' => CP_PLUGIN_URL . 'modules/slide_in/assets/demos/free_widget/customizer.js',
		'category'      => 'All,Optins,Updates',
		'tags'          => 'Hangout,Optin,Update,Training,Optin,Email,Subscribe',
		'options'       => array(
			// field to set ckeditor for middle description.
			array(
				'type'         => 'textarea',
				'class'        => '',
				'name'         => 'modal_middle_desc',
				'opts'         => array(
					'title' => __( 'Middle Description', 'smile' ),
					'value' => __( 'With John Doe', 'smile' ),
				),
				'panel'        => 'Name',
				'dependency'   => array(
					'name'     => 'hidden',
					'operator' => '==',
					'value'    => 'hide',
				),
				'section'      => 'Design',
				'section_icon' => 'connects-icon-disc',
			),
		),
	);
	smile_framework_add_options( 'Smile_slide_ins', 'free_widget', $style_arr );
}
