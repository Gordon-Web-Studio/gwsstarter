<?php
/**
 * Template part for displaying the custom header media
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( ! has_header_image() ) {
	return;
}

?>
<figure class="header-image absolute inset-0 ui-media-container media-container-expand w-full h-full -z-1">
	<?php the_header_image_tag(); ?>
</figure><!-- .header-image -->
