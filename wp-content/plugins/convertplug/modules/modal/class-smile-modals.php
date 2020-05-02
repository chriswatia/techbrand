<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! defined( 'CP_BASE_DIR_MODAL' ) ) {
	define( 'CP_BASE_DIR_MODAL', plugin_dir_path( __FILE__ ) );
}
require_once CP_BASE_DIR_MODAL . '/functions/functions.php';

if ( ! class_exists( 'Smile_Modals' ) ) {
	/**
	 * Class Smile_modals.
	 */
	class Smile_Modals extends Convert_Plug {

		/**
		 * $settings array for settings.
		 *
		 * @var array.
		 */
		public static $settings = array();

		/**
		 * $options array for settings.
		 *
		 * @var array.
		 */
		public static $options = array();

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ), 100 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ), 999 );
			add_action( 'admin_head', array( $this, 'load_customizer_scripts' ) );
			add_action( 'wp_footer', array( $this, 'load_modal_globally' ) );
			add_action( 'init', array( $this, 'register_theme_templates' ) );
			add_filter( 'admin_body_class', array( $this, 'cp_admin_body_class' ) );
			require_once CP_BASE_DIR_MODAL . 'modal-preset.php';
		}

		/**
		 * Function Name: cp_admin_body_class.
		 *
		 * @param  string $classes string parameter.
		 * @return string          string parameter.
		 */
		public function cp_admin_body_class( $classes ) {
			if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {

				if ( isset( $_GET['style-view'] ) && 'new' === $_GET['style-view'] ) {
					$classes .= 'cp-add-new-style';
				}
				return $classes;
			}
		}

		/**
		 * Function Name: register_theme_templates.
		 */
		public function register_theme_templates() {
			$dir    = plugin_dir_path( __FILE__ );
			$themes = glob( $dir . 'themes/*.php' );
			foreach ( $themes as $theme ) {
				require_once $theme;
			}
		}

		/**
		 * Function Name: add_admin_menu_page.
		 */
		public function add_admin_menu_page() {
			$page = add_submenu_page(
				CP_PLUS_SLUG,
				'Modal Popup Designer',
				'Modal Popup',
				'access_cp',
				'smile-modal-designer',
				array( $this, 'modal_dashboard' )
			);
			$obj  = new parent();
			add_action( 'admin_print_scripts-' . $page, array( $obj, 'convert_admin_scripts' ) );
			add_action( 'admin_print_scripts-' . $page, array( $this, 'modal_admin_scripts' ) );
			add_action( 'admin_footer-' . $page, array( $this, 'cp_admin_footer' ) );

		}

		/**
		 * Function Name: modal_admin_scripts.
		 */
		public function modal_admin_scripts() {
			if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
				if ( ( isset( $_GET['style-view'] ) && ( 'edit' === $_GET['style-view'] || 'variant' === $_GET['style-view'] ) ) || ! isset( $_GET['style-view'] ) ) {

					wp_enqueue_script( 'smile-modal-receiver', CP_PLUGIN_URL . 'modules/modal/assets/js/receiver.js', array(), CP_VERSION, false );
					wp_enqueue_media();
					wp_enqueue_script( 'smile-modal-importer', CP_PLUGIN_URL . 'modules/assets/js/admin-media.js', array( 'jquery' ), CP_VERSION, true );
				}

				if ( isset( $_GET['style-view'] ) && 'analytics' === $_GET['style-view'] ) {
					wp_enqueue_style( 'css-select2', CP_PLUGIN_URL . 'admin/assets/select2/select2.min.css', array(), CP_VERSION );
					wp_enqueue_script( 'convert-select2', CP_PLUGIN_URL . 'admin/assets/select2/select2.min.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'bsf-charts-js', CP_PLUGIN_URL . 'admin/assets/js/chart.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'bsf-charts-bar-js', CP_PLUGIN_URL . 'admin/assets/js/chart.bar.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'bsf-charts-donut-js', CP_PLUGIN_URL . 'admin/assets/js/chart.donuts.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'bsf-charts-line-js', CP_PLUGIN_URL . 'admin/assets/js/Chart.Line.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'bsf-charts-polararea-js', CP_PLUGIN_URL . 'admin/assets/js/Chart.PolarArea.js', array(), CP_VERSION, false );
					wp_enqueue_script( 'bsf-style-analytics-js', CP_PLUGIN_URL . 'modules/assets/js/style-analytics.js', array(), CP_VERSION, false );
				}
			}
		}

		/**
		 * Function Name: modal_dashboard.
		 */
		public function modal_dashboard() {
			if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
				$page = isset( $_GET['style-view'] ) ? $_GET['style-view'] : 'main';

				// Load default option set.
				if ( is_admin() ) {
					require_once CP_BASE_DIR_MODAL . '/functions/functions.options.php';
				}

				switch ( $page ) {
					case 'main':
						require_once CP_BASE_DIR_MODAL . '/views/main.php';
						break;
					case 'new':
						$default_google_fonts = array(
							'Lato',
							'Open Sans',
							'Libre Baskerville',
							'Montserrat',
							'Neuton',
							'Raleway',
							'Roboto',
							'Sacramento',
							'Varela Round',
							'Pacifico',
							'Bitter',
						);
						$gfonts               = implode( ',', $default_google_fonts );
						require_once CP_BASE_DIR_MODAL . '/functions/functions.php';
						require_once CP_BASE_DIR_MODAL . '/views/new-style.php';
						break;
					case 'edit':
						require_once CP_BASE_DIR_MODAL . '/views/edit.php';
						break;
					case 'variant':
						require_once CP_BASE_DIR_MODAL . '/views/variant.php';
						break;
					case 'analytics':
						require_once CP_BASE_DIR_MODAL . '/views/analytics.php';
						break;
				}
			}
		}

		/**
		 * Function Name: load_modal_globally.
		 */
		public function load_modal_globally() {
			?>
			<script type="text/javascript" id="modal">
				document.addEventListener("DOMContentLoaded", function(){
					startclock();
				});
				function stopclock (){
					if(timerRunning) clearTimeout(timerID);
					timerRunning = false;
						//document.cookie="time=0";
					}
					function showtime () {
						var now = new Date();
						var my = now.getTime() ;
						now = new Date(my-diffms) ;
						//document.cookie="time="+now.toLocaleString();
						timerID = setTimeout('showtime()',10000);
						timerRunning = true;
					}
					function startclock () {
						stopclock();
						showtime();
					}
					var timerID = null;
					var timerRunning = false;
					var x = new Date() ;
					var now = x.getTime() ;
					var gmt = <?php echo esc_attr( time() ); ?> * 1000 ;
					var diffms = (now - gmt) ;
				</script>
				<?php

				$modal_style         = '';
				$modal_style_delay   = '';
				$modal_cookie_delay  = '';
				$live_styles         = cp_get_live_styles( 'modal' );
				$prev_styles         = get_option( 'smile_modal_styles' );
				$smile_variant_tests = get_option( 'modal_variant_tests' );

				if ( is_array( $live_styles ) && ! empty( $live_styles ) ) {

					global $post;
					$modal_arrays = $live_styles;

					foreach ( $modal_arrays as $key => $modal_array ) {
						$display          = false;
						$settings_encoded = '';

						$style_settings     = array();
						$global_display     = '';
						$pages_to_exclude   = '';
						$cats_to_exclude    = '';
						$exclusive_pages    = '';
						$exclusive_cats     = '';
						$show_for_logged_in = '';
						$settings_array     = maybe_unserialize( $modal_array['style_settings'] );
						foreach ( $settings_array as $key => $setting ) {
							$style_settings[ $key ] = apply_filters( 'smile_render_setting', $setting );
						}

						$style_id    = $modal_array['style_id'];
						$modal_style = $style_settings['style'];

						if ( is_array( $style_settings ) && ! empty( $style_settings ) ) {

							$settings = maybe_unserialize( $modal_array['style_settings'] );
							$css      = isset( $settings['custom_css'] ) ? urldecode( $settings['custom_css'] ) : '';

							$display = cp_is_style_visible( $settings );

							// Remove back slashes from settings.
							$settings = stripslashes_deep( $settings );

							$settings         = wp_json_encode( $settings );
							$settings_encoded = base64_encode( $settings );
						}

						if ( $display ) {

							// Developer mode.
							if ( '1' === self::$cp_dev_mode ) {

								$script_handlers = array(
									'smile-modal-common',
									'smile-modal-script',
									'cp-ideal-timer-script',
									'smile-cp-common-script',
									'cp-modal-mailer-script',
									'cp-frosty-script',
									'cp-perfect-scroll-js',
								);

								$list = 'enqueued';

								foreach ( $script_handlers as $handler ) {
									if ( ! wp_script_is( $handler, $list ) ) {
										wp_enqueue_script( $handler );
									}
								}
							} else {

								if ( ! wp_script_is( 'cp-module-main-js', 'enqueued' ) ) {
									wp_enqueue_script( 'cp-module-main-js' );
								}

								if ( ! wp_style_is( 'cp-module-main-style', 'enqueued' ) ) {
									wp_enqueue_style( 'cp-module-main-style' );
								}

								if ( ! wp_script_is( 'smile-modal-script', 'enqueued' ) ) {
									wp_enqueue_script( 'smile-modal-script' );
								}
							}

							// Generate style ID.
							$id = $modal_style . '-' . $style_id;

							// Individual Style Path.
							$file_name = 'assets/demos/' . strtolower( $modal_style ) . '/' . strtolower( $modal_style ) . '.min.css';
							$url       = CP_PLUGIN_URL . 'modules/modal/' . $file_name;
							require_once CP_BASE_DIR_MODAL . '/functions/functions.options.php';
							$demo_html     = '';
							$customizer_js = '';
							$settings      = $this::$options;
							foreach ( $settings as $style => $options ) {
								if ( $style === $modal_style ) {
									$customizer_js = $options['customizer_js'];
									$url           = str_replace( 'customizer.js', strtolower( $modal_style ) . '.min.css', $customizer_js );
								}
							}

							if ( '' !== $customizer_js ) {
								// Check file exist or not - and append to the head.
								wp_enqueue_style( $id, $url, array(), CP_VERSION );
							}

							echo do_shortcode( '[smile_modal style_id = ' . $style_id . ' style="' . $modal_style . '" settings_encoded="' . $settings_encoded . ' "][/smile_modal]' );
							apply_filters( 'cp_custom_css', $style_id, $css );
						}
					}
				}
		}

		/**
		 * Function Name: load_customizer_scripts.
		 */
		public function load_customizer_scripts() {
			if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
				return;
			}
			if ( isset( $_GET['hidemenubar'] ) && isset( $_GET['module'] ) && 'modal' === $_GET['module'] ) {

				// countdown js.
				wp_enqueue_style( 'cp-countdown-style' );
				wp_enqueue_script( 'cp-counter-plugin-js' );
				wp_enqueue_script( 'cp-countdown-js' );

				// Enqueue - CKEditor script.
				wp_enqueue_style( 'cp-perfect-scroll-style', CP_PLUGIN_URL . 'admin/assets/css/perfect-scrollbar.min.css', array(), CP_VERSION );
				wp_register_script( 'cp-frosty-script', CP_PLUGIN_URL . 'admin/assets/js/frosty.js', array( 'jquery' ), CP_VERSION, true );
				wp_enqueue_script( 'cp-common-functions-js' );
				wp_enqueue_script( 'cp-admin-customizer-js', CP_PLUGIN_URL . 'modules/assets/js/admin.customizer.js', array(), CP_VERSION, false );
				if ( is_admin() ) {
					require_once CP_BASE_DIR_MODAL . '/functions/functions.options.php';
				}

				$demo_html     = '';
				$customizer_js = '';
				$settings      = $this::$options;
				foreach ( $settings as $style => $options ) {
					if ( $style === $_GET['theme'] ) {
						$customizer_js = $options['customizer_js'];
					}
				}
				if ( '' !== $customizer_js ) {
					wp_enqueue_script( 'cp-style-customizer-js', $customizer_js, array( 'jquery' ), CP_VERSION, true );
				}
			}
		}

		/**
		 * Function Name: enqueue_admin_scripts.
		 *
		 * @param  array $hook array contianing pagename of cp.
		 */
		public function enqueue_admin_scripts( $hook ) {

			if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
				return;
			}
			if ( ( isset( $_GET['hidemenubar'] ) && 'modal' === $_GET['module'] )
				|| ( isset( $_GET['style-view'] ) && 'new' === $_GET['style-view'] && CP_PLUS_SLUG . '_page_smile-modal-designer' === $hook ) ) {

				wp_enqueue_style( 'smile-modal', CP_PLUGIN_URL . 'modules/modal/assets/css/modal.min.css', array(), CP_VERSION );

				$handel = 'jquery';

				wp_register_script( 'cp-frosty-script', CP_PLUGIN_URL . 'admin/assets/js/frosty.js', array( 'jquery' ), CP_VERSION, true );

				if ( wp_script_is( 'cp-style-customizer-js', 'enqueued' ) ) {
					$handel = 'cp-style-customizer-js';
				}

				wp_register_script( 'smile-modal-common', CP_PLUGIN_URL . 'modules/modal/assets/js/modal.common.js', array( $handel ), CP_VERSION, true );
				wp_register_script( 'cp-common-functions-js', CP_PLUGIN_URL . 'modules/modal/assets/js/functions-common.js', array( 'smile-modal-common' ), CP_VERSION, true );

				wp_enqueue_script( 'smile-modal' );
				wp_enqueue_script( 'smile-modal-common' );
				wp_enqueue_script( 'cp-frosty-script' );
				wp_localize_script(
					'smile-modal-common',
					'cp',
					array(
						'demo_dir'       => CP_PLUGIN_URL . 'modules/modal/assets/demos',
						'module'         => 'modal',
						'module_img_dir' => CP_PLUGIN_URL . 'modules/assets/images',
					)
				);

				// Add 'Theme Name' as a class to <html> tag.
				// To provide theme compatibility.
				$theme_name = wp_get_theme();
				$theme_name = $theme_name->get( 'Name' );
				$theme_name = strtolower( preg_replace( '/[\s_]/', '-', $theme_name ) );

				wp_localize_script( 'jquery', 'cp_active_theme', array( 'slug' => $theme_name ) );
				wp_localize_script( 'cp-common-functions-js', 'smile_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );

			}
		}

		/**
		 * Function Name: enqueue_front_scripts.
		 */
		public function enqueue_front_scripts() {

			$live_styles = cp_get_live_styles( 'modal' );

			// If any style is live or modal is in live preview mode then only enqueue scripts and styles.
			if ( $live_styles && 0 < count( $live_styles ) ) {

				if ( '1' === self::$cp_dev_mode ) {

					// Register styles.
					wp_enqueue_style( 'cp-frosty-style', CP_PLUGIN_URL . 'admin/assets/css/frosty.css', array(), CP_VERSION );
					wp_enqueue_style( 'smile-modal-style', CP_PLUGIN_URL . 'modules/modal/assets/css/modal.css', array(), CP_VERSION );
					wp_enqueue_style( 'cp-animate-style', CP_PLUGIN_URL . 'modules/assets/css/animate.css', array(), CP_VERSION );
					wp_enqueue_style( 'cp-social-media-style', CP_PLUGIN_URL . 'modules/assets/css/cp-social-media-style.css', array(), CP_VERSION );
					wp_enqueue_style( 'cp-social-icon-style', CP_PLUGIN_URL . 'modules/assets/css/social-icon-css.css', array(), CP_VERSION );
					wp_enqueue_style( 'convertplug-style', CP_PLUGIN_URL . 'modules/assets/css/convertplug.css', array(), CP_VERSION );
					wp_enqueue_style( 'cp-social-icon-dev-style', CP_PLUGIN_URL . 'modules/assets/css/social-icon.css', array(), CP_VERSION );
					wp_enqueue_style( 'cp-perfect-scroll-style' );

					// Register scripts.
					wp_register_script( 'smile-modal-common', CP_PLUGIN_URL . 'modules/modal/assets/js/modal.common.js', array( 'jquery' ), CP_VERSION, true );
					wp_register_script( 'smile-cp-common-script', CP_PLUGIN_URL . 'modules/assets/js/convertplug-common.js', array( 'jquery' ), CP_VERSION, true );

					wp_register_script( 'smile-modal-script', CP_PLUGIN_URL . 'modules/modal/assets/js/modal.js', array( 'jquery', 'smile-cp-common-script' ), CP_VERSION, true );
					wp_register_script(
						'cp-modal-mailer-script',
						CP_PLUGIN_URL . 'modules/modal/assets/js/mailer.js',
						array( 'jquery' ),
						CP_VERSION,
						true
					);
					wp_register_script( 'cp-frosty-script', CP_PLUGIN_URL . 'admin/assets/js/frosty.js', array( 'jquery' ), CP_VERSION, true );

				} else {
					wp_register_script( 'smile-modal-script', CP_PLUGIN_URL . 'modules/modal/assets/js/modal.min.js', array( 'jquery' ), CP_VERSION, true );
					wp_enqueue_style( 'cp-module-main-style' );
					wp_enqueue_style( 'smile-modal-style', CP_PLUGIN_URL . 'modules/modal/assets/css/modal.min.css', array(), CP_VERSION );

				}

				wp_localize_script( 'smile-modal-script', 'smile_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
			}
		}
	}
	$smile_modals = new Smile_Modals();
}

if ( ! function_exists( 'smile_modal_popup' ) ) {
	/**
	 * Function Name: smile_modal_popup.
	 *
	 * @param  array  $atts    array parameter.
	 * @param  string $content string arameter.
	 */
	function smile_modal_popup( $atts, $content = null ) {
		shortcode_atts(
			array(
				'style'      => '',
				'style_name' => '',
			),
			$atts
		);

		$style      = isset( $atts['style'] ) ? $atts['style'] : '';
		$style_name = isset( $atts['style_name'] ) ? $atts['style_name'] : '';
		$output     = '';
		$func       = 'modal_theme_' . $style;

		$settings = base64_decode( $atts['settings_encoded'] );

		if ( isset( $atts['website'] ) && 'yes' === $atts['website'] ) {
			$settings       = maybe_unserialize( $settings );
			$style_settings = $settings;
		} else {
			$style_settings = json_decode( $settings, true );
		}

		// Remove back slashes from settings.
		$settings = stripslashes_deep( $style_settings );

		$settings                 = wp_json_encode( $settings );
		$settings_encoded         = base64_encode( $settings );
		$atts['settings_encoded'] = $settings_encoded;

		if ( function_exists( $func ) ) {
			$output = $func( $atts );
		}
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	add_shortcode( 'smile_modal', 'smile_modal_popup' );
}


if ( ! function_exists( 'cp_modal_custom' ) ) {
	/**
	 * Function Name: cp_modal_custom.
	 *
	 * @param  array  $atts    array parameter.
	 * @param  string $content string arameter.
	 */
	function cp_modal_custom( $atts, $content = null ) {
		ob_start();
		$id      = '';
		$display = '';
		shortcode_atts(
			array(
				'id'      => '',
				'display' => '',
			),
			$atts
		);

		$id          = isset( $atts['id'] ) ? $atts['id'] : '';
		$display     = isset( $atts['display'] ) ? $atts['display'] : '';
		$live_styles = cp_get_live_styles( 'modal' );
		$live_array  = '';
		$settings    = '';
		foreach ( $live_styles as $key => $modal_array ) {
			$style_id = $modal_array['style_id'];

			$settings = maybe_unserialize( $modal_array['style_settings'] );
			if ( isset( $settings['variant_style_id'] ) && $id === $settings['style_id'] ) {
				$id = $settings['variant_style_id'];
			}

			if ( $id === $style_id ) {
				$live_array     = $modal_array;
				$settings       = maybe_unserialize( $modal_array['style_settings'] );
				$settings_array = maybe_unserialize( $modal_array['style_settings'] );

				foreach ( $settings_array as $key => $setting ) {
					$style_settings[ $key ] = $setting;
				}
				$modal_style = $style_settings['style'];
				$global      = $style_settings['global'];

				$style_settings['display']       = $display;
				$style_settings['custom_class'] .= isset( $style_settings['custom_class'] ) ? $style_settings['custom_class'] . ',cp-trigger-' . $style_id : 'cp-trigger-' . $style_id;
				$display                         = cp_is_style_visible( $style_settings );

				// Remove back slashes from settings.
				$settings = stripslashes_deep( $settings );

				$encode_settings  = wp_json_encode( $style_settings );
				$settings_encoded = base64_encode( $encode_settings );

				echo '<span class="cp-trigger-shortcode cp-trigger-' . esc_attr( $style_id ) . ' cp-' . esc_attr( $style_id ) . '">' . do_shortcode( $content ) . '</span>';

				if ( $display ) {
					// Generate style ID.
					$id = $modal_style . '-' . $style_id;
					// Individual Style Path.
					$file_name = '/assets/demos/' . strtolower( $modal_style ) . '/' . strtolower( $modal_style ) . '.min.css';
					$url       = CP_PLUGIN_URL . 'modules/modal/' . $file_name;

					// Check file exist or not - and append to the head.
					wp_enqueue_style( $id, $url, array(), CP_VERSION );
					echo do_shortcode( '[smile_modal manual="true" style_id = ' . $style_id . ' style="' . $modal_style . '" settings_encoded="' . $settings_encoded . ' "][/smile_modal]' );
					$css = isset( $settings['custom_css'] ) ? urldecode( $settings['custom_css'] ) : '';
					apply_filters( 'cp_custom_css', $style_id, $css );
				}
				break;
			}
		}
		return ob_get_clean();
	}
	add_shortcode( 'cp_modal', 'cp_modal_custom' );
}
add_filter( 'widget_text', 'do_shortcode' );
