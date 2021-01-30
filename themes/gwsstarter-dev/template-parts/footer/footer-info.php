<?php
/**
 * Template part for displaying the footer info.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( ! wp_rig()->is_footer_info_active() ) {
	return;
}

?>
<div class="footer-info footer-widget-area">
	<img class="mb-8" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" loading="lazy">
	<?php wp_rig()->display_footer_info(); ?>
</div>
