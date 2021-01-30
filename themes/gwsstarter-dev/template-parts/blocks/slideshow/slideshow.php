<?php
/**
 * Block Name: Slideshow
 *
 * This is the template that displays the slideshow block.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

// Set up Block Identifiers.
$block_id   = wp_rig()->get_block_id( $block );
$block_slug = str_replace( 'acf/', '', $block['name'] );

// Set up Block classes fields.
$alignment = wp_rig()->get_block_alignment( $block );
$classes   = wp_rig()->get_block_classes( $block );

// Get Block data.
$data = wp_rig()->get_array_value( $block, 'data' );

// Get layout settings.
$layout         = get_field( 'layout' );
$text_align     = wp_rig()->get_text_align( $layout );
$content_align  = wp_rig()->get_content_align( $layout );
$content_valign = wp_rig()->get_content_valign( $layout );
$block_height   = wp_rig()->get_block_height( $layout );

// Variables for Current Block.
$enable_slideshow  = true;
$preview_slideshow = false;
$slideshow_options = get_field( 'slideshow_options' );
$style_presets     = get_field( 'style_presets' );

// Join block css classes.
$join_classes = $alignment . $text_align . $content_align . $content_valign . $classes . $block_height . ' ' . $style_presets;

// Set empty variables for custom styles from slides.
$slide_custom_styles    = '';

// Start a block <container> with possible block options.
wp_rig()->display_block_options(
	array(
		'block'     => $block,
		'container' => 'section', // Any HTML5 container: section, div, etc...
		'class'     => 'l-block block-slideshow ' . esc_attr( $join_classes ), // Block Container class.
		'height'    => $block_height,
	)
);

	wp_rig()->display_content_container_options();
?>
		<div class="slideshow-container grid grid-cols-1" id="<?php echo esc_attr( $block_id . '-slideshow' ); ?>">
			<?php
			$allowed_blocks = array( 'acf/slide' );
			$template = array(
				array(
					'acf/slide',
					array(
						'className'   => 'inner-slide-item relative',
					),
				),
			);
			?>
			<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
		</div>
	</div>
	<!-- / end content container -->

	<?php
	// Initiate Custom_styles.
	$custom_styles = '';

	// Force slideshow on frontend.
	$preview_slideshow = ! $is_preview ? true : $preview_slideshow;

	// Get all slideshow options.
	if ( $enable_slideshow && $preview_slideshow ) :
		// Get all slideshow options.
		$carousel_target = '.slideshow-container';

		$initial_slidestoshow = array(
			'slidestoshow' => 1,
			'md_slidestoshow' => 1,
			'sm_slidestoshow' => 1,
		);

		wp_rig()->initiate_carousel_script( $block_id, $block_slug, $carousel_target, $slideshow_options, '', $initial_slidestoshow );

		$carousel_styles = wp_rig()->get_carousel_custom_styles( $block_id, $slideshow_options );

		$custom_styles .= $carousel_styles;
	endif;

	wp_rig()->display_block_custom_styles( $block, $custom_styles );
	?>

</section>
<!-- / end block-slideshow container -->
