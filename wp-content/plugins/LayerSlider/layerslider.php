<?php

/*
Plugin Name: The7 LayerSlider WP
Plugin URI: https://layerslider.kreaturamedia.com
Description: LayerSlider is a premium multi-purpose content creation and animation platform. Easily create sliders, image galleries, slideshows with mind-blowing effects, popups, landing pages, animated page blocks, or even a full website. LayerSlider empowers millions of active websites on a daily basis with stunning visuals and eye-catching effects.
Version: 6.10.2
Author: Kreatura Media
Author URI: https://kreaturamedia.com
Text Domain: LayerSlider
*/


// Prevent direct file access
defined( 'ABSPATH' ) || exit;


// Detect duplicate versions of LayerSlider
if( defined('LS_PLUGIN_VERSION') || isset( $GLOBALS['lsPluginPath'] ) ) {
	add_action( 'admin_notices', 'ls_duplicate_version_notice' );


// Check required PHP version
} elseif( version_compare( phpversion(), '5.3.0', '<' ) ) {
	add_action( 'admin_notices', 'ls_server_requirements_notice' );


// Initialize the plugin
} else {

	// Basic configuration
	define('LS_DB_TABLE', 'layerslider');
	define('LS_DB_VERSION', '6.9.0');
	define('LS_PLUGIN_VERSION', '6.10.2');

	// Path info
	// v6.2.0: LS_ROOT_URL is now set in the after_setup_theme action
	// hook to provide a way for theme authors to override its value
	define('LS_ROOT_FILE', __FILE__);
	define('LS_ROOT_PATH', dirname(__FILE__));

	// Other constants
	define('LS_WP_ADMIN', true);
	define('LS_PLUGIN_SLUG', basename(dirname(__FILE__)));
	define('LS_PLUGIN_BASE', plugin_basename(__FILE__));
	define('LS_MARKETPLACE_ID', '1362246');
	define('LS_TEXTDOMAIN', 'LayerSlider');
	define('LS_REPO_BASE_URL', 'https://repository.kreaturamedia.com/v4/');

	require LS_ROOT_PATH.'/init.php';
}

function layerslider_as_theme() {
	if(defined('LAYERSLIDER_THEME_INIT')) return;
	$theme_path = get_template_directory();
	$theme_core = "$theme_path/inc/extensions/core-functions.php";
	if ( file_exists( $theme_core ) ) {
		require_once( $theme_core );
		if  (function_exists("presscore_is_silence_enabled") && presscore_is_silence_enabled()){
			define('LAYERSLIDER_THEME_ACT', true);
			define('LAYERSLIDER_THEME_INIT', true);
			return;
		}
	}
	define('LAYERSLIDER_THEME_INIT', true);
}

add_action('after_setup_theme', 'layerslider_as_theme');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

if( ! function_exists('ls_duplicate_version_notice') ) {
	function ls_duplicate_version_notice() { ?>
		<div class="notice notice-error" style="text-align: justify;">
			<h3>Action Required: Multiple LayerSlider instances detected</h3>
			<p>It looks like you already had one copy of LayerSlider installed on your site. Having multiple copies installed simultaneously can cause serious issues, thus other copies are suppressed until this issue gets resolved. Here’s what you can do:</p>
			<ul class="ul-square">
				<li>Please check your <a href="<?php echo admin_url('plugins.php') ?>">Plugins screen</a> and disable the older copies of LayerSlider. <b>Remember, you should see at least two copies of LayerSlider and you should disable those beside the one you’ve just installed.</b> Look at their version number to easily identify them. You’ll likely want to disable the ones with a lower version number.</li>
				<li>If the other copies aren’t listed there, it’s almost certain that your active WordPress theme loads LayerSlider as a bundled plugin. In such a case, please check your theme’s settings and find a way to uninstall or disable loading the bundled version of LayerSlider. The process is different for each theme, thus we recommend contacting the appropriate theme author if you experience difficulties.</li>
			</ul>
			<p><small style="font-size: 13px; color: #666;">This message will automatically be dismissed once the issue has been resolved. You can also disable all copies of LayerSlider under the Plugins screen to hide this message. However, we strongly discourage choosing that since you might be stuck with an old and potentially outdated version of LayerSlider or no access to any version at all.</small></p>
		</div>

<?php } }


if( ! function_exists('ls_server_requirements_notice') ) {
	function ls_server_requirements_notice() { ?>
		<div class="notice notice-error" style="text-align: justify;">
			<h3>Action Required: LayerSlider cannot run on your server with its current settings</h3>
			<p><b>LayerSlider requires PHP 5.3.0 or greater. Please contact your web hosting provider and ask them to upgrade the PHP on your server. WordPress itself has much higher <a target="_blank" href="https://wordpress.org/about/requirements/">requirements</a> with its current releases. Upgrading is necessary to be compatible with the latest releases of WordPress and the overwhelming majority of its themes and plugins. It’s also crucial for security and performance, so be pushy if your host is hesitant.</b></p>

			<p><small style="font-size: 13px; color: #666;">This message will automatically be dismissed once the issue has been resolved. After that, look for the <b>LayerSlider WP</b> sidebar menu item to get started using the plugin. You can also disable LayerSlider under the Plugins screen to hide this message. However, we strongly discourage choosing to look away as your site will remain in a vulnerable state and you will experience more and more issues with themes and plugins if you don’t take the necessary steps.</small></p>
		</div>

<?php } }

