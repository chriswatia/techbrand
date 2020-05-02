<?php
/**
 * Geolocation class
 *
 * Handles geolocation and updating the geolocation database.
 *
 * This product includes GeoLite data created by MaxMind, available from http://www.maxmind.com.
 *
 * @package Convert Pro/Classes
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * CP_Geolocation_Target Class.
 */
class CP_Geolocation_Target {

	/**
	 * GeoLite IPv4 DB.
	 *
	 * @deprecated 3.4.0
	 */
	const GEOLITE_DB = 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz';

	/**
	 * GeoLite IPv6 DB.
	 *
	 * @deprecated 3.4.0
	 */
	const GEOLITE_IPV6_DB = 'http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz';

	/**
	 * GeoLite2 DB.
	 *
	 * @since 3.4.0
	 */
	const GEOLITE2_DB = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.tar.gz';

	/**
	 * API endpoints for looking up user IP address.
	 *
	 * @var array
	 */
	private static $ip_lookup_apis = array(
		'icanhazip'         => 'http://icanhazip.com',
		'ipify'             => 'http://api.ipify.org/',
		'ipecho'            => 'http://ipecho.net/plain',
		'ident'             => 'http://ident.me',
		'whatismyipaddress' => 'http://bot.whatismyipaddress.com',
	);

	/**
	 * API endpoints for geolocating an IP address
	 *
	 * @var array
	 */
	private static $geoip_apis = array(
		'ipinfo.io'  => 'https://ipinfo.io/%s/json',
		'ip-api.com' => 'http://ip-api.com/json/%s',
	);

	/**
	 * Check if server supports MaxMind GeoLite2 Reader.
	 *
	 * @since 3.4.0
	 * @return bool
	 */
	private static function supports_geolite2() {
		return version_compare( PHP_VERSION, '5.4.0', '>=' );
	}

	/**
	 * Check if geolocation is enabled.
	 *
	 * @since 3.4.0
	 * @param string $current_settings Current geolocation settings.
	 * @return bool
	 */
	private static function is_geolocation_enabled( $current_settings ) {
		return in_array( $current_settings, array( 'geolocation', 'geolocation_ajax' ), true );
	}

	/**
	 * Hook in geolocation functionality.
	 */
	public static function init() {

		if ( self::supports_geolite2() ) {
			$database = self::get_local_database_path();
			if ( ! file_exists( $database ) ) {
				// Download the database from MaxMind.
				self::cp_update_database();
			}
		}
	}

	/**
	 * Get current user IP Address.
	 *
	 * @return string
	 */
	public static function get_ip_address() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) { // WPCS: input var ok, CSRF ok.
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );  // WPCS: input var ok, CSRF ok.
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // WPCS: input var ok, CSRF ok.
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/[,:]/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) ); // WPCS: input var ok, CSRF ok.
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) { // @codingStandardsIgnoreLine
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ); // @codingStandardsIgnoreLine
		}
		return '';
	}

	/**
	 * Get user IP Address using an external service.
	 * This is used mainly as a fallback for users on localhost where
	 * get_ip_address() will be a local IP and non-geolocatable.
	 *
	 * @return string
	 */
	public static function get_external_ip_address() {
		$external_ip_address = '0.0.0.0';

		if ( '' !== self::get_ip_address() ) {
			$transient_name      = 'external_ip_address_' . self::get_ip_address();
			$external_ip_address = get_transient( $transient_name );
		}

		if ( false === $external_ip_address ) {
			$external_ip_address     = '0.0.0.0';
			$ip_lookup_services      = apply_filters( 'convert_pro_geolocation_ip_lookup_apis', self::$ip_lookup_apis );
			$ip_lookup_services_keys = array_keys( $ip_lookup_services );
			shuffle( $ip_lookup_services_keys );

			foreach ( $ip_lookup_services_keys as $service_name ) {
				$service_endpoint = $ip_lookup_services[ $service_name ];
				$response         = wp_safe_remote_get( $service_endpoint, array( 'timeout' => 2 ) );

				if ( ! is_wp_error( $response ) && rest_is_ip_address( $response['body'] ) ) {
					$external_ip_address = apply_filters( 'convert_pro_geolocation_ip_lookup_api_response', sanitize_text_field( $response['body'] ), $service_name );
					break;
				}
			}

			set_transient( $transient_name, $external_ip_address, WEEK_IN_SECONDS );
		}

		return $external_ip_address;
	}

	/**
	 * Geolocate an IP address.
	 *
	 * @param  string $ip_address   IP Address.
	 * @param  bool   $fallback     If true, fallbacks to alternative IP detection (can be slower).
	 * @param  bool   $api_fallback If true, uses geolocation APIs if the database file doesn't exist (can be slower).
	 * @return array
	 */
	public static function geolocate_ip( $ip_address = '', $fallback = true, $api_fallback = true ) {
		// Filter to allow custom geolocation of the IP address.
		$country_code = apply_filters( 'convert_pro_geolocate_ip', false, $ip_address, $fallback, $api_fallback );
		if ( false === $country_code ) {
			// If GEOIP is enabled in CloudFlare, we can use that (Settings -> CloudFlare Settings -> Settings Overview).
			if ( ! empty( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) { // WPCS: input var ok, CSRF ok.
				$country_code = strtoupper( sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) ); // WPCS: input var ok, CSRF ok.
			} elseif ( ! empty( $_SERVER['GEOIP_COUNTRY_CODE'] ) ) { // WPCS: input var ok, CSRF ok.
				// WP.com VIP has a variable available.
				$country_code = strtoupper( sanitize_text_field( wp_unslash( $_SERVER['GEOIP_COUNTRY_CODE'] ) ) ); // WPCS: input var ok, CSRF ok.
			} elseif ( ! empty( $_SERVER['HTTP_X_COUNTRY_CODE'] ) ) { // WPCS: input var ok, CSRF ok.
				// VIP Go has a variable available also.
				$country_code = strtoupper( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_COUNTRY_CODE'] ) ) ); // WPCS: input var ok, CSRF ok.
			} else {

				$ip_address = $ip_address ? $ip_address : self::get_ip_address();
				$database   = self::get_local_database_path();

				if ( self::supports_geolite2() && file_exists( $database ) ) {
					$country_code = self::geolocate_via_db( $ip_address, $database );
				} elseif ( $api_fallback ) {
					$country_code = self::geolocate_via_api( $ip_address );
				} else {
					$country_code = '';
				}

				if ( ! $country_code && $fallback ) {
					// May be a local environment - find external IP.
					return self::geolocate_ip( self::get_external_ip_address(), false, $api_fallback );
				}
			}
		}

		return array(
			'country' => $country_code,
			'state'   => '',
		);
	}

	/**
	 * Path to our local db.
	 *
	 * @param  string $deprecated Deprecated since 3.4.0.
	 * @return string
	 */
	public static function get_local_database_path( $deprecated = '2' ) {
		$upload_dir = wp_upload_dir();
		$filename   = 'GeoLite2-Country.mmdb';
		global $wpdb;

		// @codingStandardsIgnoreStart
		$image_src = $upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $filename );
		$query     = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE guid='$image_src'";
		$count     = intval( $wpdb->get_var( $query ) );
		// @codingStandardsIgnoreStart
		return apply_filters( 'convert_pro_geolocation_local_database_path', $upload_dir['basedir'] . '/GeoLite2-Country.mmdb', $deprecated );
		// http://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.mmdb.gz
	}

	/**
	 * Use MAXMIND GeoLite database to geolocation the user.
	 *
	 * @param  string $ip_address IP address.
	 * @param  string $database   Database path.
	 * @return string
	 */
	private static function geolocate_via_db( $ip_address, $database ) {
		if ( ! class_exists( 'CP_Geolite_Integration', false ) ) {
			require_once SMILE_FRAMEWORK_DIR . '/class-cp-geolite-integration-target.php';
		}

		$geolite = new CP_Geolite_Integration_Target( $database );

		return $geolite->get_country_iso( $ip_address );
	}

	/**
	 * Update geoip database.
	 */
	public static function cp_update_database() {

		require_once( ABSPATH . 'wp-admin/includes/file.php' );			
		$tmp_database_path = download_url( self::GEOLITE2_DB, '300' );
		$upload_dir        = wp_upload_dir();	
		$error = false;

		

		if ( ! is_wp_error( $tmp_database_path ) ) {
			try {
				// GeoLite2 database name.
				$database  = 'GeoLite2-Country.mmdb';
				$dest_path = trailingslashit( $upload_dir['basedir'] ) . $database;

				// Extract files with PharData. Tool built into PHP since 5.3.
				
				if(class_exists('PharData')){					
				
					$file      = new PharData( $tmp_database_path ); // phpcs:ignore PHPCompatibility.PHP.NewClasses.phardataFound
					$file_path = trailingslashit( $file->current()->getFileName() ) . $database;

					// Extract under uploads directory.
					$file->extractTo( $upload_dir['basedir'], $file_path, true );

					// Remove old database.
					@unlink( $dest_path ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.VIP.FileSystemWritesDisallow.file_ops_unlink

					// Copy database and delete tmp directories.
					@rename( trailingslashit( $upload_dir['basedir'] ) . $file_path, $dest_path ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.VIP.FileSystemWritesDisallow.file_ops_rename
					@rmdir( trailingslashit( $upload_dir['basedir'] ) . $file->current()->getFileName() ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.VIP.FileSystemWritesDisallow.directory_rmdir

					// Set correct file permission.
					@chmod( $dest_path, 0644 ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.VIP.FileSystemWritesDisallow.chmod_chmod
				}
			} catch ( Exception $e ) {
				$error = true;
				//echo 'Message: ' . $e->getMessage();
			}

			@unlink( $tmp_database_path ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.VIP.FileSystemWritesDisallow.file_ops_unlink
		} else {
			//throw new Exception( 'Problem downloading the database.' );
			//error_log( 'Problem downloading the database.' );
			$error = true;
		}
	}

	/**
	 * Use APIs to Geolocate the user.
	 *
	 * Geolocation APIs can be added through the use of the convert_pro_geolocation_geoip_apis filter.
	 * Provide a name=>value pair for service-slug=>endpoint.
	 *
	 * If APIs are defined, one will be chosen at random to fulfil the request. After completing, the result
	 * will be cached in a transient.
	 *
	 * @param  string $ip_address IP address.
	 * @return string
	 */
	private static function geolocate_via_api( $ip_address ) {
		$country_code = get_transient( 'geoip_' . $ip_address );

		if ( false === $country_code ) {
			$geoip_services = apply_filters( 'convert_pro_geolocation_geoip_apis', self::$geoip_apis );

			if ( empty( $geoip_services ) ) {
				return '';
			}

			$geoip_services_keys = array_keys( $geoip_services );

			shuffle( $geoip_services_keys );

			foreach ( $geoip_services_keys as $service_name ) {
				$service_endpoint = $geoip_services[ $service_name ];
				$response         = wp_safe_remote_get( sprintf( $service_endpoint, $ip_address ), array( 'timeout' => 2 ) );

				if ( ! is_wp_error( $response ) && $response['body'] ) {
					switch ( $service_name ) {
						case 'ipinfo.io':
							$data         = json_decode( $response['body'] );
							$country_code = isset( $data->country ) ? $data->country : '';
							break;
						case 'ip-api.com':
							$data         = json_decode( $response['body'] );
							$country_code = isset( $data->countryCode ) ? $data->countryCode : ''; // @codingStandardsIgnoreLine
							break;
						default:
							$country_code = apply_filters( 'convert_pro_geolocation_geoip_response_' . $service_name, '', $response['body'] );
							break;
					}

					$country_code = sanitize_text_field( strtoupper( $country_code ) );

					if ( $country_code ) {
						break;
					}
				}
			}

			set_transient( 'geoip_' . $ip_address, $country_code, WEEK_IN_SECONDS );
		}

		return $country_code;
	}

}
CP_Geolocation_Target::init();

