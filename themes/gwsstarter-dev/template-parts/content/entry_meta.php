<?php
/**
 * Template part for displaying a post's metadata
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( is_singular( get_post_type() ) ) {
	?>
	<div class="entry-meta text-center">
		<p class=" fs7 text-gray text-up letter-spacing-3"><?php the_time( 'F j, Y' ); ?></p>
	</div><!-- .entry-meta -->
	<?php
} else {
	?>
	<div class="entry-meta">
		<p class="fs8 text-gray text-up letter-spacing-1"><?php the_time( 'F j, Y' ); ?></p>
	</div><!-- .entry-meta -->
	<?php
}
?>
<?php
