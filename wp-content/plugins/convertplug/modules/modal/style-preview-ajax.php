<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
	return;
}

/*
* Preview Style
*/
require_once CP_BASE_DIR_MODAL . '/functions/functions.options.php';

$style           = isset( $_GET['style'] ) ? sanitize_text_field( $_GET['style'] ) : '';
$settings_method = isset( $_GET['method'] ) ? esc_attr( sanitize_text_field( $_GET['method'] ) ) : '';
$template_name   = isset( $_GET['temp_name'] ) ? esc_attr( sanitize_text_field( $_GET['temp_name'] ) ) : '';

$options       = Smile_Modals::$options;
$style_options = $options[ $style ]['options'];

$settings_encoded = cp_get_live_preview_settings( 'modal', $settings_method, $style_options, $template_name );

$smile_modal = '[smile_modal style="' . $style . '" settings_encoded="' . $settings_encoded . ' "][/smile_modal]';

$html_smile_modal = do_shortcode( $smile_modal );

echo ( htmlentities( $html_smile_modal, ENT_QUOTES, 'utf-8' ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

?>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		jQuery(".cp-overlay").addClass("cp-open");
		jQuery("#TB_ajaxContent").appendTo("body");
		jQuery("#TB_overlay").remove();
		jQuery("body").on("click",".cp-overlay", function(){
			jQuery(this).removeClass("cp-open");
			jQuery("#TB_ajaxContent").remove();
			jQuery("#TB_window").remove();
			jQuery("#TB_overlay").trigger("click");
			jQuery("body").removeClass("modal-open");
		});
		jQuery("body").on("click",".cp-modal-content",function(e){
			e.preventDefault();
			e.stopPropagation();
		});
	});

	jQuery(document).ready(function(){
		jQuery(document).bind('keydown', function(e) {
			if (e.which === 27) {
				var cp_overlay = jQuery(".cp-open");
				var modal = cp_overlay;
				modal.fadeOut('slow').remove();
				jQuery("#TB_ajaxContent").remove();
				jQuery("#TB_window").remove();
				jQuery("#TB_overlay").remove();
			}
		});
	});

</script>
