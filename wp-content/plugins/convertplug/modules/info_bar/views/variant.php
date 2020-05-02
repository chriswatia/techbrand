<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
	return;
}

$test = isset( $_GET['variant-test'] ) ? esc_attr( $_GET['variant-test'] ) : 'main';
switch ( $test ) {
	case 'new':
		require_once CP_BASE_DIR_IFB . '/views/variant/new.php';
		break;
	case 'edit':
		require_once CP_BASE_DIR_IFB . '/views/variant/edit.php';
		break;
	case 'main':
	default:
		require_once CP_BASE_DIR_IFB . '/views/variant/variant.php';
		break;
}
