<?php
/**
 * Template part for displaying the footer contact.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( ! wp_rig()->is_footer_contact_active() ) {
	return;
}

?>
<div class="footer-contact footer-widget-area">
	<?php wp_rig()->display_footer_contact(); ?>
</div>
