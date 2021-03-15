<?php
/**
 * Block Name: Hero
 *
 * This is the template that displays the hero block.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

// Set up Block ID.
$block_id   = wp_rig()->get_block_id( $block );
$block_slug = str_replace( 'acf/', '', $block['name'] );

// Set up Block classes fields.
$alignment = wp_rig()->get_block_alignment( $block );
$classes   = wp_rig()->get_block_classes( $block );

// Get Block data.
$data = wp_rig()->get_array_value( $block, 'data' );

// Get layout settings.
$layout = get_field( 'layout' );

// gat values from data for initial loading.
if ( $is_preview ) :
	$layout['text_align']     = wp_rig()->get_array_value( $data, 'layout_text_align', $layout['text_align'] );
	$layout['content_valign'] = wp_rig()->get_array_value( $data, 'layout_content_valign', $layout['content_valign'] );
	$layout['height']         = wp_rig()->get_array_value( $data, 'layout_height', $layout['height'] );
endif;

// Variables for Current Block.
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
		'class'     => 'l-block block-hero' . esc_attr( $join_classes ), // Block Container class.
	)
);

	wp_rig()->display_content_container_options();
?>
		<div class="block-content">
			<?php
			$template = array(
				array(
					'core/paragraph',
					array(
						'content'   => __( 'Tagline', 'wp-rig' ),
						'className' => 'block-tagline',
					),
				),
				array(
					'core/heading',
					array(
						'content'   => __( 'Headline', 'wp-rig' ),
						'className' => 'block-headline is-style-default',
						'fontSize'  => 'h2',
					),
				),
				array(
					'core/paragraph',
					array(
						'content' => __( 'Caption Content', 'wp-rig' ),
					),
				),
			);
			?>
			<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
		</div>
	</div>
	<!-- / end content container -->
	<?php wp_rig()->display_block_custom_styles( $block ); ?>

</section>
<!-- / end block-hero container -->
