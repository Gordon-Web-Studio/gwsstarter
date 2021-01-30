<?php
/**
 * Block Name: Brands and Logos
 *
 * This is the template that displays the brands and logos block.
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

// Get layout settings.
$layout            = get_field( 'layout' );
$text_align        = wp_rig()->get_text_align( $layout );
$content_align     = wp_rig()->get_content_align( $layout );
$content_valign    = wp_rig()->get_content_valign( $layout );
$block_height      = wp_rig()->get_block_height( $layout );

// Variables for Current Block.
$items             = get_field( 'items' );
$grid_options      = get_field( 'grid_options' );
$enable_slideshow  = get_field( 'enable_slideshow' );
$preview_slideshow = get_field( 'preview_slideshow' );
$slideshow_options = get_field( 'slideshow_options' );

// Join block css classes.
$join_classes = $alignment . $text_align . $content_align . $content_valign . $classes . $block_height;

// Start a block <container> with possible block options.
wp_rig()->display_block_options(
	array(
		'block'     => $block,
		'container' => 'section', // Any HTML5 container: section, div, etc...
		'class'     => 'l-block block-brands-logos' . esc_attr( $join_classes ), // Block Container class.
		'height'    => $block_height,
	)
);
?>

<?php
wp_rig()->display_content_container_options();
?>
	<div class="block-content">

		<?php
		$template = array(
			array(
				'core/heading',
				array(
					'content' => __( 'Trusted by these brands', 'wp-rig' ),
					'className' => 'is-style-default mb-10 has-base-font-size',
					'fontSize'  => 'base',
					'level'     => '2',
					'textAlign' => 'center',
				),
			),
		);
		?>
		<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />

	</div> <!-- / end block-content container -->

	<?php
	if ( empty( $items ) ) :

		// Default items when no items is entered.
		$default_items = array(
			array(
				'acf_fc_layout' => 'item',
				'media_image' => array(
					'image' => '',
					'image_url' => get_template_directory_uri() . '/assets/images/logo-grayscale.svg',
					'image_size' => '',
					'media_alt' => '',
				),
				'media_link' => array(
					'title' => '',
					'url' => '',
					'target' => '',
				),
				'media_caption' => '',
				'media_container' => array(
					'media_aspect_ratio' => 'widescreen',
					'media_obj_fit' => 'contain',
					'media_obj_pos' => 'center center',
					'media_width_unit' => '%',
					'media_width' => '90',
					'media_aspect_ratio_sm' => '',
					'media_obj_fit_sm' => 'contain',
					'media_obj_pos_sm' => 'center center',
					'media_width_unit_sm' => '%',
					'media_width_sm' => '90',
				),
			),
		);

		// Preview items.
		$items = $default_items;

	endif;

	// Grid Options.
	$items_per_row    = wp_rig()->get_array_value( $grid_options, 'items_per_row', 'md:grid-cols-4' );
	$column_gap       = wp_rig()->get_array_value( $grid_options, 'column_gap', '' );
	$items_per_row_sm = wp_rig()->get_array_value( $grid_options, 'items_per_row_sm', 'grid-cols-2' );
	$column_gap_sm    = wp_rig()->get_array_value( $grid_options, 'column_gap_sm', '' );

	// Set Grid class.
	$grid_class       = $enable_slideshow && $preview_slideshow ? 'block' : 'grid';
	$grid_class       .= $items_per_row_sm ? ' ' . $items_per_row_sm : '';
	$grid_class       .= $items_per_row ? ' ' . $items_per_row : '';
	$grid_class       .= $column_gap_sm ? ' ' . $column_gap_sm : '';
	$grid_class       .= $column_gap ? ' ' . $column_gap : '';
	?>
	<div class="media-items <?php echo esc_attr( $grid_class ); ?>">
		<?php
		foreach ( $items as $key => $item ) :

			// Media image.
			$media_image   = wp_rig()->get_array_value( $item, 'media_image' );
			$image         = wp_rig()->get_array_value( $media_image, 'image' );
			$image_url     = wp_rig()->get_array_value( $media_image, 'image_url' );
			$image_size    = wp_rig()->get_array_value( $media_image, 'media_size', 'medium' );
			$media_alt     = wp_rig()->get_array_value( $media_image, 'media_alt' );
			$media_attr    = $media_alt ? array( 'alt' => $media_alt ) : array();
			$media_attr[]  = array( 'class' => 'mx-auto' );

			// Media Link.
			$media_link        = wp_rig()->get_array_value( $item, 'media_link' );
			$media_link_url    = wp_rig()->get_array_value( $media_link, 'url' );
			$media_link_title  = wp_rig()->get_array_value( $media_link, 'title' );
			$media_link_target = wp_rig()->get_array_value( $media_link, 'target' );

			// Media Caption.
			$media_caption = wp_rig()->get_array_value( $item, 'media_caption' );

			// Media Container.
			$media_container       = wp_rig()->get_array_value( $item, 'media_container' );
			$media_aspect_ratio    = wp_rig()->get_array_value( $media_container, 'media_aspect_ratio', 'square' );
			$media_aspect_ratio_sm = wp_rig()->get_array_value( $media_container, 'media_aspect_ratio_sm', 'square' );

			// set media container class.
			$media_aspect_ratio       = $media_aspect_ratio ? 'media-container-' . $media_aspect_ratio : '';
			$media_aspect_ratio_sm    = $media_aspect_ratio_sm ? ' md-max:media-container-' . $media_aspect_ratio_sm : '';
			$media_aspect_ratio_class = $media_aspect_ratio . $media_aspect_ratio_sm;

			// Item CSS Class(es).
			$item_class = wp_rig()->get_array_value( $item, 'item_class', '' );
			?>
			<div class="media-item flex flex-column items-center <?php echo esc_attr( 'media-item-' . $key ); ?> px-1">
				<div class="relative block w-full text-center <?php echo esc_attr( $item_class ); ?>">
					<?php
					if ( $media_link_url ) :
						echo sprintf(
							'<a class="ui-link link-clean" href="%1$s" title="Link for %2$s" target="%3$s">',
							esc_url( $media_link_url ),
							esc_attr( $media_link_title ),
							esc_attr( $media_link_target )
						);
					endif;

					if ( $image || $image_url ) :
						?>
						<figure>
							<div class="ui-media-container <?php echo esc_attr( $media_aspect_ratio_class ); ?>">
								<?php
								if ( $image ) :
									echo wp_get_attachment_image( $image, $image_size, false, $media_attr );
								elseif ( $image_url ) :
									?>
									<img class="mx-auto" src="<?php echo esc_url( $image_url ); ?>" loading="lazy">
									<?php
								endif;
								?>
							</div>
						</figure>
						<?php
					endif;

					if ( $media_caption ) :
						?>
						<small class="fs5 mt-0"><?php echo wp_kses_post( $media_caption ); ?></small>
						<?php
					endif;

					if ( $media_link_url ) :
						echo '</a>';
					endif;
					?>
				</div>
			</div>
			<?php
		endforeach;
		?>
	</div>

</div> <!-- / end content container -->

<?php
// Initiate Custom_styles.
$custom_styles = '';

// Getting items styles.
$items_styles = '';

foreach ( $items as $key => $item ) :

	// Media Container.
	$media_container       = wp_rig()->get_array_value( $item, 'media_container' );
	$media_obj_fit         = wp_rig()->get_array_value( $media_container, 'media_obj_fit', 'contain' );
	$media_obj_pos         = wp_rig()->get_array_value( $media_container, 'media_obj_pos' );
	$media_width_unit      = wp_rig()->get_array_value( $media_container, 'media_width_unit' );
	$media_width           = wp_rig()->get_array_value( $media_container, 'media_width' );
	$media_obj_fit_sm      = wp_rig()->get_array_value( $media_container, 'media_obj_fit_sm', 'contain' );
	$media_obj_pos_sm      = wp_rig()->get_array_value( $media_container, 'media_obj_pos_sm' );
	$media_width_unit_sm   = wp_rig()->get_array_value( $media_container, 'media_width_unit_sm' );
	$media_width_sm        = wp_rig()->get_array_value( $media_container, 'media_width_sm' );

	if ( $media_obj_fit || $media_obj_pos || $media_obj_fit_sm || $media_obj_pos_sm ) :

		// Output begins.
		ob_start();
		if ( $media_obj_fit || $media_obj_pos ) :
			?>
			#<?php echo esc_attr( $block_id . ' .media-item-' . $key ); ?> .ui-media-container,
			#<?php echo esc_attr( $block_id . ' .media-item-' . $key ); ?> .ui-media-container img {
				<?php
				if ( $media_obj_fit ) :
					echo 'object-fit: ' . esc_attr( $media_obj_fit ) . ';';
				endif;
				if ( $media_obj_pos ) :
					echo 'object-position: ' . esc_attr( $media_obj_pos ) . ';';
				endif;
				?>
			}
			<?php
		endif;

		if ( $media_width_unit && $media_width ) :
			?>
			#<?php echo esc_attr( $block_id . ' .media-item-' . $key ); ?> .ui-media-container img {
				width: <?php echo esc_attr( $media_width . $media_width_unit ); ?>;
				min-width: <?php echo esc_attr( $media_width . $media_width_unit ); ?>;
				max-width: initial;
				height: auto;
				min-height: auto;
				top: 50%;
				left: 50%;
				transform: translate( -50%, -50% );
			}
			<?php
		endif;

		if ( $media_obj_fit_sm || $media_obj_pos_sm ) :
			?>
			@media (max-width: 768px) {
				#<?php echo esc_attr( $block_id . ' .media-item-' . $key ); ?> .ui-media-container,
				#<?php echo esc_attr( $block_id . ' .media-item-' . $key ); ?> .ui-media-container img {
					<?php
					if ( $media_obj_fit_sm ) :
						echo 'object-fit: ' . esc_attr( $media_obj_fit_sm ) . ';';
					endif;
					if ( $media_obj_pos_sm ) :
						echo 'object-position: ' . esc_attr( $media_obj_pos_sm ) . ';';
					endif;
					?>
				}
			}
			<?php
		endif;

		if ( $media_width_unit_sm && $media_width_sm ) :
			?>
			@media (max-width: 768px) {
				#<?php echo esc_attr( $block_id . ' .media-item-' . $key ); ?> .ui-media-container img {
					width: <?php echo esc_attr( $media_width_sm . $media_width_unit_sm ); ?>;
					min-width: <?php echo esc_attr( $media_width_sm . $media_width_unit_sm ); ?>;
					max-width: initial;
					height: auto;
					min-height: auto;
					top: 50%;
					left: 50%;
					transform: translate( -50%, -50% );
				}
			}
			<?php
		endif;

		$items_styles .= ob_get_clean();
	endif;

	$custom_styles .= $items_styles;
endforeach;

// Force slideshow on frontend.
$preview_slideshow = ! $is_preview ? true : $preview_slideshow;

// Get all slideshow options.
if ( $enable_slideshow && $preview_slideshow ) :
	$carousel_target = '.media-items';

	wp_rig()->initiate_carousel_script( $block_id, $block_slug, $carousel_target, $slideshow_options );

	$carousel_styles = wp_rig()->get_carousel_custom_styles( $block_id, $slideshow_options );

	$custom_styles .= $carousel_styles;
endif;

wp_rig()->display_block_custom_styles( $block, $custom_styles );
?>

</section>
<!-- / end block-brand-logo container -->
