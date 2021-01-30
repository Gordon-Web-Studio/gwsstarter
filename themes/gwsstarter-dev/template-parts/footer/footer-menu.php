<?php
/**
 * Template part for displaying the footer Menu One.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( ! wp_rig()->is_footer_menu_one_active() || ! wp_rig()->is_footer_menu_two_active() ) {
	return;
}

?>
<div class="grid col-span-2 grid-cols-1 nav:grid-cols-2 gap-4 nav:px-20">
	<div class="footer-menu-one footer-widget-area">
		<?php wp_rig()->display_footer_menu_one(); ?>
	</div>
	<div class="footer-menu-two footer-widget-area">
		<?php wp_rig()->display_footer_menu_two(); ?>
	</div>
</div>
