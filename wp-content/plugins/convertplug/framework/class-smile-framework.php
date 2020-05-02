<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

/*
* Smile Theme Framework.
* @Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Framework Root.
 */
if ( ! defined( 'SMILE_FRAMEWORK_DIR' ) ) {
	define( 'SMILE_FRAMEWORK_DIR', dirname( __FILE__ ) );
}

/**
 * Framework URI.
 */
if ( ! defined( 'SMILE_FRAMEWORK_URI' ) ) {
	define( 'SMILE_FRAMEWORK_URI', plugins_url( '', __FILE__ ) );
}

/*
* Framework Starts from here.
*/
if ( ! class_exists( 'Smile_Framework' ) ) {
	/**
	 * Class Smile_Framework
	 */
	class Smile_Framework {
		/**
		 * $options array.
		 *
		 * @var array
		 */
		public static $options = array();

		/**
		 * $modules array.
		 *
		 * @var array
		 */
		public static $modules = array();

		/**
		 * $addon_list array.
		 *
		 * @var array
		 */
		public static $addon_list = array();

		/**
		 * $fields_dir string.
		 *
		 * @var string
		 */
		public $fields_dir;

		/**
		 * Constructor function that initializes required actions and hooks.
		 *
		 * @Since 1.0
		 */
		public function __construct() {
			$this->fields_dir = SMILE_FRAMEWORK_DIR . '/lib/fields/';
			// Load options.
			add_action( 'init', array( $this, 'load_framework_functions' ) );
			add_action( 'admin_head', array( $this, 'load_compatible_scripts' ) );
		}

		/**
		 * Function Name: load_compatible_scripts.
		 */
		public function load_compatible_scripts() {
			if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
				if ( isset( $_GET['hidemenubar'] ) ) {
					wp_register_script( 'cp-helper-js', SMILE_FRAMEWORK_URI . '/assets/js/cp-helper.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'cp-helper-js' );
					$nonce_object = array(
						'media_nonce' => wp_create_nonce( 'cp_media_nonce' ),
					);
					wp_localize_script( 'cp-helper-js', 'media_nonce', $nonce_object['media_nonce'] );
					wp_register_script( 'smile-customizer-js', SMILE_FRAMEWORK_URI . '/assets/js/customizer.js', array( 'cp-helper-js' ), CP_VERSION, false );
					wp_enqueue_script( 'smile-customizer-js' );
				}
			}
		}

		/**
		 * Function Name: load_framework_functions.
		 */
		public function load_framework_functions() {
			// load framework mapper class.
			require_once CP_BASE_DIR . '/framework/classes/class-smile-framework-mapper.php';

			// load style framework loader.
			require_once CP_BASE_DIR . '/framework/classes/class.style-framework.php';

			// load style framework loader.
			require_once CP_BASE_DIR . '/framework/classes/class-cpimport.php';

			if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
				// Add GEO target classes.
				require_once CP_BASE_DIR . '/framework/class-cp-geolocation-target.php';
				require_once CP_BASE_DIR . '/framework/class-cp-geolite-integration-target.php';

				// Include GeoIP2 lib.
				// if ( ! class_exists( 'MaxMind\\Db\\Reader', false ) ) {.
				require_once CP_BASE_DIR . '/framework/lib/geolite2/Reader.php';
				require_once CP_BASE_DIR . '/framework/lib/geolite2/Reader/Decoder.php';
				require_once CP_BASE_DIR . '/framework/lib/geolite2/Reader/InvalidDatabaseException.php';
				require_once CP_BASE_DIR . '/framework/lib/geolite2/Reader/Metadata.php';
				require_once CP_BASE_DIR . '/framework/lib/geolite2/Reader/Util.php';
				// }
			}

			// load required admin fuctions.
			require_once CP_BASE_DIR . '/framework/functions/functions.php';

			// load default input types from the directory "lib/fields".
			foreach ( glob( $this->fields_dir . '/*/*.php' ) as $module ) {
				require_once $module;
			}
		}

		/**
		 * Function Name: smile_store_data Retrieve and store data into the static variable $options.
		 *
		 * @param  string $class   string parameter.
		 * @param  string $name    string parameter.
		 * @param  string $settings string parameter.
		 * @return boolval(var)           true/false.
		 */
		public static function smile_store_data( $class, $name, $settings ) {
			$result = false;
			if ( '' !== $name && ! empty( $settings ) ) {
				$class::$options[ $name ] = $settings;
				$result                   = true;
			}
			return $result;
		}

		/**
		 * Function Name: smile_update_data Retrieve and update stored data into the static variable $options.
		 *
		 * @param  string $class   string parameter.
		 * @param  string $name    string parameter.
		 * @param  string $settings string parameter.
		 * @return boolval(var)           true/false.
		 */
		public static function smile_update_data( $class, $name, $settings ) {
			$result = false;
			if ( '' !== $name && ! empty( $settings ) ) {
				$prev_settings = $class::$options[ $name ]['options'];
				foreach ( $settings as $key => $setting ) {
					array_push( $prev_settings, $setting );
				}
				$class::$options[ $name ]['options'] = $prev_settings;
				$result                              = true;
			}
			return $result;
		}

		/**
		 * Function Name: smile_remove_setting Retrieve and update default value in stored data into the static variable $options.
		 *
		 * @param  string $class       string parameter.
		 * @param  string $style       string parameter.
		 * @param  string $name        string parameter.
		 * @param  string $value        string parameter.
		 * @return boolval(var)             treu/false.
		 */
		public static function smile_update_value( $class, $style, $name, $value ) {
			$result       = false;
			$new_settings = '';
			if ( '' !== $name ) {
				$settings = $class::$options[ $style ]['options'];
				foreach ( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if ( $opt_name == $name ) {
						$settings[ $key ]['opts']['value'] = $value;
					}
				}
				$class::$options[ $style ]['options'] = $settings;
				$result                               = true;
			}
			return $result;
		}

		/**
		 * Function Name: smile_remove_setting Retrieve settings array and remove option provided from settingsdata into the static variable $options.
		 *
		 * @param  string $class       string parameter.
		 * @param  string $style       string parameter.
		 * @param  string $name        string parameter.
		 * @return boolval(var)             treu/false.
		 */
		public static function smile_remove_setting( $class, $style, $name ) {
			$result = false;
			if ( ! empty( $name ) ) {
				$settings = $class::$options[ $style ]['options'];
				foreach ( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if ( in_array( $opt_name, $name ) ) {
						unset( $settings[ $key ] );
					}
				}
				$class::$options[ $style ]['options'] = $settings;
				$result                               = true;
			}
			return $result;
		}

		/**
		 * Function Name: smile_update_partial_refresh Retrieve and update default value in stored data into the static variable $options.
		 *
		 * @param  string $class       string parameter.
		 * @param  string $style       string parameter.
		 * @param  string $name        string parameter.
		 * @param  array  $parse_array array val.
		 * @return boolval(var)             treu/false.
		 */
		public static function smile_update_partial_refresh( $class, $style, $name, $parse_array ) {
			$result       = false;
			$new_settings = '';
			if ( '' !== $name ) {
				$settings = $class::$options[ $style ]['options'];
				foreach ( $settings as $key => $setting ) {
					$opt_name = $setting['name'];
					if ( $opt_name == $name && ! empty( $parse_array ) ) {
						if ( isset( $parse_array['css_selector'] ) ) {
							$settings[ $key ]['opts']['css_selector'] = $parse_array['css_selector'];
						}
						if ( isset( $parse_array['css_property'] ) ) {
							$settings[ $key ]['opts']['css_property'] = $parse_array['css_property'];
						}
					}
				}
				$class::$options[ $style ]['options'] = $settings;
				$result                               = true;
			}
			return $result;
		}

		/**
		 * Function Name: smile_add_mailer Add mailer addon to convertplug.
		 *
		 * @param  string $slug    string val.
		 * @param  array  $setting array val.
		 * @return boolval(var)         true/false.
		 */
		public static function smile_add_mailer( $slug, $setting ) {
			$result = false;
			if ( '' !== $slug ) {
				self::$addon_list[ $slug ] = $setting;
				$result                    = true;
			}
			return $result;
		}
	}
}
