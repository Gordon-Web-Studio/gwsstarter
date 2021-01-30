<?php
/**
 * Block Name: Wrapper
 *
 * This is the template that displays the block wrapper.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

// Set up Block ID.
$block_id   = wp_rig()->get_block_id( $block );
$block_slug = str_replace( 'acf/', '', $block['name'] );

// Set up Block classes fields.
$alignment  = wp_rig()->get_block_alignment( $block );
$classes        = wp_rig()->get_block_classes( $block );

// Variables for Current Block.
$layout         = get_field( 'layout' );
$text_align     = wp_rig()->get_text_align( $layout );
$content_align  = wp_rig()->get_content_align( $layout );
$content_valign = wp_rig()->get_content_valign( $layout );
$block_height   = wp_rig()->get_block_height( $layout );

// Join block css classes.
$join_classes = $alignment . $text_align . $content_align . $content_valign . $classes . $block_height;

// Start a block <container> with possible block options.
wp_rig()->display_block_options(
	array(
		'block'     => $block,
		'container' => 'section', // Any HTML5 container: section, div, etc...
		'class'     => 'l-block block-container' . esc_attr( $join_classes ), // Block Container class.
	)
);

wp_rig()->display_content_container_options();
?>
	<div class="block-content">
		<InnerBlocks />
	</div>
</div>
<!-- / end content container -->
<?php wp_rig()->display_block_custom_styles( $block ); ?>

</section>
<!-- / end block-container -->
