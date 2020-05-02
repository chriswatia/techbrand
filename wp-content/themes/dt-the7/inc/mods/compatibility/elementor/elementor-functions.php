<?php
/**
 * @package The7
 */

defined( 'ABSPATH' ) || exit;

function the7_elementor_elements_widget_post_types() {
	$post_types = array_intersect_key(
		get_post_types( [], 'object' ),
		[
			'post'            => '',
			'dt_portfolio'    => '',
			'dt_team'         => '',
			'dt_testimonials' => '',
			'dt_gallery'      => '',
		]
	);

	$supported_post_types = [];
	foreach ( $post_types as $post_type ) {
		$supported_post_types[ $post_type->name ] = $post_type->label;
	}

	$supported_post_types['current_query'] = __( 'Archive (current query)', 'the7mk2' );

	return $supported_post_types;
}

/**
 * @return string
 */
function the7_elementor_get_message_about_disabled_post_type() {
	return '<p>' . esc_html__( 'The corresponding post type is disabled. Please make sure to 1) install The7 Elements plugin under The7 > Plugins and 2) enable desired post types under The7 > My The7, in the Settings section.', 'the7mk2' ) . '</p>';
}
