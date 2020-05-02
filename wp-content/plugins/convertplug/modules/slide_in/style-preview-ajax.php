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
require_once CP_BASE_DIR_SLIDEIN . '/functions/functions.options.php';

$style           = isset( $_GET['style'] ) ? sanitize_text_field( $_GET['style'] ) : '';
$settings_method = isset( $_GET['method'] ) ? sanitize_text_field( $_GET['method'] ) : '';
$template_name   = isset( $_GET['temp_name'] ) ? sanitize_text_field( $_GET['temp_name'] ) : '';

$options       = Smile_Slide_Ins::$options;
$style_options = $options[ $style ]['options'];

$settings_encoded = cp_get_live_preview_settings( 'slide_in', $settings_method, $style_options, $template_name );

$smile_slide_in = '[smile_slide_in style="' . $style . '" settings_encoded="' . $settings_encoded . ' "][/smile_slide_in]';

$html_smile_slide_in = do_shortcode( $smile_slide_in );

echo ( htmlentities( $html_smile_slide_in, ENT_QUOTES, 'utf-8' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

?>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery(".slidein-overlay").addClass("si-open");
	jQuery("body").on("click",".slidein-overlay", function(){
		jQuery(this).removeClass("si-open");
		jQuery("#TB_ajaxContent").remove();
		jQuery("#TB_window").remove();
		jQuery("#TB_overlay").trigger("click");
		jQuery("body").removeClass("modal-open");
		jQuery("#TB_overlay").remove();
	});
	jQuery("body").on("click",".cp-slidein-content",function(e){
		e.preventDefault();
		e.stopPropagation();
	});
});
</script>
