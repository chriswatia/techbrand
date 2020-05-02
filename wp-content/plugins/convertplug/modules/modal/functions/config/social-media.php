<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( function_exists( 'smile_framework_add_options' ) ) {
	$style_arr = array(
		'style_name'    => 'Social Media',
		'demo_url'      => CP_PLUGIN_URL . 'modules/modal/assets/demos/social_media/social_media.html',
		'demo_dir'      => plugin_dir_path( __FILE__ ) . '../../assets/demos/social_media/social_media.html',
		'img_url'       => CP_PLUGIN_URL . 'modules/modal/assets/demos/social_media/social_media.png',
		'customizer_js' => CP_PLUGIN_URL . 'modules/modal/assets/demos/social_media/customizer.js',
		'category'      => 'All,Social',
		'tags'          => 'Social,Share,Facebook,Twitter,Google,Digg,Reddit,Pinterest,LinkedIn,Myspace,Blogger,Tumblr,StumbleUpon',
		'options'       => array(),
	);

	smile_framework_add_options( 'Smile_Modals', 'social_media', $style_arr );
}
