<?php
/**
 * Block Name: Content Media
 *
 * This is the template that displays the content media block.
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

// Get media container settings.
$media_container = get_field( 'media_container' );

// gat values from data for initial loading.
if ( $is_preview ) :
	$layout['content_valign']              = wp_rig()->get_array_value( $data, 'layout_content_valign', $layout['content_valign'] );
	$media_container['media_aspect_ratio'] = wp_rig()->get_array_value( $data, 'media_container_media_aspect_ratio', $media_container['media_aspect_ratio'] );
	$media_container['media_obj_fit']      = wp_rig()->get_array_value( $data, 'media_container_media_obj_fit', $media_container['media_obj_fit'] );
	$media_container['media_obj_pos']      = wp_rig()->get_array_value( $data, 'media_container_media_obj_pos', $media_container['media_obj_pos'] );
endif;

// Variables for Current Block.
$media_aspect_ratio    = wp_rig()->get_array_value( $media_container, 'media_aspect_ratio', 'square' );
$media_obj_fit         = wp_rig()->get_array_value( $media_container, 'media_obj_fit' );
$media_obj_pos         = wp_rig()->get_array_value( $media_container, 'media_obj_pos' );
$media_width_unit      = wp_rig()->get_array_value( $media_container, 'media_width_unit' );
$media_width           = wp_rig()->get_array_value( $media_container, 'media_width' );
$media_aspect_ratio_sm = wp_rig()->get_array_value( $media_container, 'media_aspect_ratio_sm', 'square' );
$media_obj_fit_sm      = wp_rig()->get_array_value( $media_container, 'media_obj_fit_sm' );
$media_obj_pos_sm      = wp_rig()->get_array_value( $media_container, 'media_obj_pos_sm' );
$media_width_unit_sm   = wp_rig()->get_array_value( $media_container, 'media_width_unit_sm' );
$media_width_sm        = wp_rig()->get_array_value( $media_container, 'media_width_sm' );
$text_align            = wp_rig()->get_text_align( $layout );
$content_align         = wp_rig()->get_content_align( $layout );
$content_valign        = wp_rig()->get_content_valign( $layout );
$block_height          = wp_rig()->get_block_height( $layout );
$media_type            = get_field( 'media_type' );
$column_width          = get_field( 'column_width' );
$style_presets         = get_field( 'style_presets' );
$invert_order          = get_field( 'invert_order' );
$invert_order_sm       = get_field( 'invert_order_sm' );
$column_gap            = get_field( 'column_gap' );
$is_home_hero          = get_field( 'is_home_hero' );

// set media container class.
$media_aspect_ratio       = $media_aspect_ratio ? 'media-container-' . $media_aspect_ratio : '';
$media_aspect_ratio_sm    = $media_aspect_ratio_sm ? ' md-max:media-container-' . $media_aspect_ratio_sm : '';
$media_aspect_ratio_class = $media_aspect_ratio . $media_aspect_ratio_sm;
$home_hero_class          = $is_home_hero ? ' home-page-hero ' : '';

// Join block css classes.
$join_classes    = $alignment . $classes . $block_height . ' ' . $style_presets . $home_hero_class;
$content_classes = $text_align . $content_align . $content_valign;

// Add diagonal background for home hero.
if ( $is_home_hero ) {
	echo '<div class="diagonal-background"></div>';
}

// Start a block <container> with possible block options.
wp_rig()->display_block_options(
	array(
		'block'     => $block,
		'container' => 'section', // Any HTML5 container: section, div, etc...
		'class'     => 'l-block block-content-media ' . esc_attr( $join_classes ), // Block Container class.
		'height'    => $block_height,
	)
);

// Variable $invert_order.
$order_block_text     = $invert_order ? ' md:order-2' : ' md:order-1';
$order_block_media    = $invert_order ? ' md:order-1' : ' md:order-2';
$order_block_text_sm  = $invert_order_sm ? ' order-2' : ' order-1';
$order_block_media_sm = $invert_order_sm ? ' order-1' : ' order-2';

// Media Text column width set.
$grid_cols       = '';
$col_width_text  = '';
$col_width_media = '';

// Variable $column_width.
if ( 'half-half' === $column_width ) {
	$grid_cols       = ' md:grid-cols-2';
} else if ( 'two-fifths' === $column_width ) {
	$grid_cols       = ' md:grid-cols-5';
	$col_width_text  = ' md:col-span-2';
	$col_width_media = ' md:col-span-3';
} else if ( 'three-fifths' === $column_width ) {
	$grid_cols       = ' md:grid-cols-5';
	$col_width_text  = ' md:col-span-3';
	$col_width_media = ' md:col-span-2';
} else if ( 'one-thirds' === $column_width ) {
	$grid_cols       = ' md:grid-cols-3';
	$col_width_text  = ' md:col-span-1';
	$col_width_media = ' md:col-span-2';
} else if ( 'two-thirds' === $column_width ) {
	$grid_cols       = ' md:grid-cols-3';
	$col_width_text  = ' md:col-span-2';
	$col_width_media = ' md:col-span-1';
} else if ( 'three-quarters' === $column_width ) {
	$grid_cols       = ' md:grid-cols-4';
	$col_width_text  = ' md:col-span-3';
	$col_width_media = ' md:col-span-1';
} else if ( 'one-quarters' === $column_width ) {
	$grid_cols       = ' md:grid-cols-4';
	$col_width_text  = ' md:col-span-1';
	$col_width_media = ' md:col-span-3';
}

$col_text_class = $order_block_text . $order_block_text_sm . $col_width_text . $content_classes;
?>
	<div class="content-media grid grid-cols-1 <?php echo esc_attr( $grid_cols . ' ' . $column_gap ); ?>">
		<div class="col-text flex flex-col <?php echo esc_attr( $col_text_class ); ?>">
			<?php
			wp_rig()->display_content_container_options();
			?>
				<div class="content-text py-3">
					<?php
					$template = array(
						array(
							'core/paragraph',
							array(
								'content' => __( 'Tagline', 'wp-rig' ),
								'className'   => 'block-tagline',
							),
						),
						array(
							'core/heading',
							array(
								'content' => __( 'Headline', 'wp-rig' ),
								'className'   => 'block-headline is-style-default',
								'fontSize'    => 'h2',
							),
						),
						array(
							'core/paragraph',
							array(
								'content' => __( 'Some paragraph content', 'wp-rig' ),
							),
						),
					);
					?>
					<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
				</div>
			</div>
			<!-- / end content container -->
		</div>
		<div class="col-media relative flex flex-col justify-center <?php echo esc_attr( $order_block_media . $order_block_media_sm . $col_width_media . $block_height ); ?>">
			<?php
			if ( $is_home_hero ) :
				?>
				<div class="hero-grid" style="display: none">
					<!-- Row 1 -->
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div class="squircle squircle--red squircle--row-1"></div>
					<!-- Row 2 -->
					<div></div>
					<div></div>
					<div></div>
					<div class="squircle squircle--red squircle--icon squircle--row-2">
						<svg class="animated-group-icon" xmlns="http://www.w3.org/2000/svg" width="42" height="38" viewBox="0 0 42 38" fill="none">
							<path class="animated-group-icon__back" d="M39.9688 33.0989V29.9489C39.9678 28.5531 39.5032 27.1971 38.648 26.0939C37.7928 24.9907 36.5954 24.2027 35.2439 23.8537" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__back" d="M28.9435 4.9537C30.2986 5.30068 31.4997 6.0888 32.3575 7.19382C33.2152 8.29884 33.6808 9.65791 33.6808 11.0568C33.6808 12.4556 33.2152 13.8147 32.3575 14.9197C31.4997 16.0247 30.2986 16.8129 28.9435 17.1598" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__front" d="M30.5186 33.0986V29.9486C30.5186 28.2778 29.8549 26.6754 28.6734 25.4939C27.4919 24.3124 25.8895 23.6487 24.2187 23.6487H11.6188C9.94794 23.6487 8.34552 24.3124 7.16406 25.4939C5.98259 26.6754 5.31885 28.2778 5.31885 29.9486V33.0986" stroke="#D34747" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__front" d="M17.9192 17.3488C21.3986 17.3488 24.2191 14.5282 24.2191 11.0489C24.2191 7.56953 21.3986 4.74895 17.9192 4.74895C14.4398 4.74895 11.6193 7.56953 11.6193 11.0489C11.6193 14.5282 14.4398 17.3488 17.9192 17.3488Z" stroke="#D34747" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__front" d="M30.5186 33.0986V29.9486C30.5186 28.2778 29.8549 26.6754 28.6734 25.4939C27.4919 24.3124 25.8895 23.6487 24.2187 23.6487H11.6188C9.94794 23.6487 8.34552 24.3124 7.16406 25.4939C5.98259 26.6754 5.31885 28.2778 5.31885 29.9486V33.0986" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__front" d="M30.5186 33.0986V29.9486C30.5186 28.2778 29.8549 26.6754 28.6734 25.4939C27.4919 24.3124 25.8895 23.6487 24.2187 23.6487H11.6188C9.94794 23.6487 8.34552 24.3124 7.16406 25.4939C5.98259 26.6754 5.31885 28.2778 5.31885 29.9486V33.0986" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__front" d="M17.9192 17.3488C21.3986 17.3488 24.2191 14.5282 24.2191 11.0489C24.2191 7.56953 21.3986 4.74895 17.9192 4.74895C14.4398 4.74895 11.6193 7.56953 11.6193 11.0489C11.6193 14.5282 14.4398 17.3488 17.9192 17.3488Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-group-icon__front" d="M17.9192 17.3488C21.3986 17.3488 24.2191 14.5282 24.2191 11.0489C24.2191 7.56953 21.3986 4.74895 17.9192 4.74895C14.4398 4.74895 11.6193 7.56953 11.6193 11.0489C11.6193 14.5282 14.4398 17.3488 17.9192 17.3488Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<div></div>
					<div></div>
					<div class="squircle squircle--red squircle--row-2"></div>
					<div class="squircle squircle--red squircle--row-2"></div>
					<div class="squircle squircle--mustard squircle--row-2"></div>
					<!-- Row 3 -->
					<div></div>
					<div class="squircle squircle--video squircle--row-5">
						<video autoplay muted loop playsinline poster="<?php echo esc_url( get_template_directory_uri() . '/assets/images/pharmacy-video-cover.jpg' ); ?>">
							<source src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/pharmacy.mp4' ); ?>" type="video/mp4">
						</video>
					</div>
					<!-- squircle--video -->
					<div class="squircle squircle--mustard squircle--row-3"></div>
					<div class="squircle squircle--red squircle--row-3"></div>
					<div class="squircle squircle--red squircle--row-3"></div>
					<div class="squircle squircle--mustard squircle--row-3"></div>
					<div class="squircle squircle--mustard squircle--row-3"></div>
					<div class="squircle squircle--green squircle--row-3"></div>
					<!-- Row 4 -->
					<div></div>
					<!-- squircle--video -->
					<!-- squircle--video -->
					<div class="squircle squircle--green squircle--row-4"></div>
					<div class="squircle squircle--image squircle--row-4">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/sample-squircle-image-2.jpg' ); ?>" alt="">
					</div>
					<div class="squircle squircle--mustard squircle--row-4"></div>
					<div class="squircle squircle--green squircle--row-4"></div>
					<div class="squircle squircle--green squircle--row-4"></div>
					<div class="squircle squircle--green squircle--row-4"></div>
					<!-- Row 5 -->
					<div class="squircle squircle--red squircle--icon squircle--row-5">
						<svg class="animated-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="63" height="63" viewBox="0 0 63 63" fill="none">
							<path class="animated-arrow-icon__line" data-animate-path d="M 3.43274 47.0914 L 22.8077 27.7164 L 35.7244 40.633 L 60.2661 16.0914" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-arrow-icon__arrow" data-animate-path d="M 60.2661 16.0914 L 44.7661 16.0914" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path class="animated-arrow-icon__arrow" data-animate-path d="M60.2661 16.0914V31.5914" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<!-- squircle--video -->
					<!-- squircle--video -->
					<div class="squircle squircle--image squircle--row-5">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/sample-squircle-image-1.jpg' ); ?>" alt="">
					</div>
					<div class="squircle squircle--green squircle--row-5"></div>
					<div class="squircle squircle--green squircle--row-5"></div>
					<div class="squircle squircle--green squircle--row-5"></div>
					<div class="squircle squircle--green squircle--row-5"></div>
					<div class="squircle squircle--green squircle--row-5"></div>
				</div>
				<?php
			else :

				// Media Image.
				$media_image = get_field( 'media_image' );
				$image_url   = wp_rig()->get_array_value( $media_image, 'image_url' );

				// gat values from data for initial loading.
				if ( $is_preview ) :
					$image_url = wp_rig()->get_array_value( $data, 'media_image_image_url', $image_url );
				endif;

				$image           = wp_rig()->get_array_value( $media_image, 'image' );
				$image_size      = wp_rig()->get_array_value( $media_image, 'media_size', 'large' );
				$media_alt       = wp_rig()->get_array_value( $media_image, 'media_alt' );
				$media_alt_array = $media_alt ? array( 'alt' => $media_alt ) : array();
				$media_caption   = get_field( 'media_caption' );

				// video_glightbox_class.
				$video_glightbox_class = 'video-glightbox';

				if ( 'image' === $media_type ) :

					if ( $image || $image_url ) :
						?>
						<figure>
							<div class="ui-media-container rounded <?php echo esc_attr( $media_aspect_ratio_class ); ?>">
								<?php
								if ( $image ) :
									echo wp_get_attachment_image( $image, $image_size, false, $media_alt_array );
								elseif ( $image_url ) :
									?>
									<img src="<?php echo esc_url( $image_url ); ?>" loading="lazy">
									<?php
								endif;
								?>
							</div>
							<?php
							if ( $media_caption ) :
								?>
								<figcaption><?php echo wp_kses_post( $media_caption ); ?></figcaption>
								<?php
							endif;
							?>
						</figure>
						<?php

					endif;

				elseif ( 'video' === $media_type ) :

					// Media Video.
					$media_video           = get_field( 'media_video', false, false );
					$media_video           = $media_video ? $media_video : $image_url;

					if ( $media_video ) :
						?>
						<div class="video-container relative">
							<a href="<?php echo esc_url( $media_video ); ?>" class="group block <?php echo esc_attr( $video_glightbox_class ); ?>">
								<div class="ui-media-container rounded media-container-landscape img-landscape">
									<?php
									if ( $image ) :
										echo wp_get_attachment_image( $image, $image_size, false, $media_alt_array );
									elseif ( $image_url ) :
										?>
										<img src="<?php echo esc_url( $image_url ); ?>" loading="lazy">
										<?php
									endif;
									?>
								</div>
								<div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-24 h-24 text-fs4 flex justify-center items-center text-primary-light bg-primary rounded-full transform transition duration-200 group-hover:text-white group-hover:scale-110">
									<div class="animate-ping absolute inline-flex h-16 w-16 bg-primary opacity-75 rounded-full"></div>
									<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play" class="w-8 ml-2 z-10" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
								</div>
							</a>
						</div>
						<?php
					endif;
				endif;

				// Initiate Lightbox.
				wp_rig()->initiate_lightbox_script( $block_id, $block_slug, '.' . $video_glightbox_class );

			endif;
			?>
		</div>
	</div>

<?php
$additional_styles = '';

if ( $media_obj_fit || $media_obj_pos || $media_obj_fit_sm || $media_obj_pos_sm ) :

	// Output begins.
	ob_start();
	if ( $media_obj_fit || $media_obj_pos ) :
		?>
		#<?php echo esc_attr( $block_id ); ?> .ui-media-container,
		#<?php echo esc_attr( $block_id ); ?> .ui-media-container img {
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
		#<?php echo esc_attr( $block_id ); ?> .ui-media-container img {
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
			#<?php echo esc_attr( $block_id ); ?> .ui-media-container,
			#<?php echo esc_attr( $block_id ); ?> .ui-media-container img {
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
			#<?php echo esc_attr( $block_id ); ?> .ui-media-container img {
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

	$additional_styles = ob_get_clean();
endif;

wp_rig()->display_block_custom_styles( $block, $additional_styles );
?>

</section>
<!-- / end block-content-media container -->
