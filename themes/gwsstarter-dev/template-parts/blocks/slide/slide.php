<?php
/**
 * Block Name: Slide
 *
 * This is the template that displays the slide block.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

// Set up Block ID.
$block_id   = wp_rig()->get_block_id( $block );
$block_slug = str_replace( 'acf/', '', $block['name'] );

// Set up Block classes fields.
$classes   = wp_rig()->get_block_classes( $block );

// Get Block data.
$data = wp_rig()->get_array_value( $block, 'data' );

// Get layout settings.
$layout = get_field( 'layout' );

// Start a block <container> with possible block options.
wp_rig()->display_block_options(
	array(
		'block'     => $block,
		'container' => 'div', // Any HTML5 container: section, div, etc...
		'class'     => 'l-inner-block block-slide ' . esc_attr( $classes ), // Block Container class.
	)
);
	$allowed_blocks = array();
	$template = array(
		array( 'core/group', array() ),
	);
	?>
	<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
</div>
