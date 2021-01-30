<?php
/**
 * WP_Rig\WP_Rig\Carousel\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Carousel;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;


/**
 * Class for Carousel.
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'carousel';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_rig()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'display_slide_options'      => array( $this, 'display_slide_options' ),
			'get_slide_custom_styles'    => array( $this, 'get_slide_custom_styles' ),
			'initiate_carousel_script'   => array( $this, 'initiate_carousel_script' ),
			'get_carousel_custom_styles' => array( $this, 'get_carousel_custom_styles' ),
		);
	}

	/**
	 * Set the blocks options.
	 *
	 * @access public
	 * @param  array $slide_args Some arguments.
	 * @return void
	 */
	public function display_slide_options( $slide_args = array() ) {

		// Setup defaults.
		$slide_defaults = array(
			'class' => 'slide',
		);

		// Parse args.
		$slide_args = wp_parse_args( $slide_args, $slide_defaults );

		// Get the args variables.
		$slide_id    = wp_rig()->get_array_value( $slide_args, 'id' );
		$slide_class = wp_rig()->get_array_value( $slide_args, 'class' );
		$slide       = wp_rig()->get_array_value( $slide_args, 'slide' );

		// Initiate some html variables.
		$slide_bg_color_html    = '';
		$slide_bg_gradient_html = '';
		$slide_bg_video_html    = '';
		$slide_bg_image_html    = '';
		$slide_overlay_html     = '';

		// Get overlay type.
		$slide_overlay_type = isset( $slide['overlay_type'] ) && $slide['overlay_type'] ? $slide['overlay_type'] : false;

		// Only try to get the rest of the settings if the background type is set to anything.
		$slide_background_type = isset( $slide['background_type'] ) && $slide['background_type'] ? $slide['background_type'] : false;

		if ( $slide_background_type ) :
			$slide_background_color = isset( $slide['background_color'] ) && $slide['background_color'] ? $slide['background_color'] : false;
			$slide_background_image = isset( $slide['background_image'] ) && $slide['background_image'] ? $slide['background_image'] : false;
			$slide_background_video = isset( $slide['background_video'] ) && $slide['background_video'] ? $slide['background_video'] : false;
			$slide_has_show_overlay = $slide_overlay_type ? ' has-overlay' : ''; // Show overlay class, if it exists.

			// Color Background Set.
			if ( 'classic' === $slide_background_type && $slide_background_color && ! $slide_background_image ) :

				// Construct class.
				$slide_class .= ' has-background color-as-background';
				ob_start();
				?>
					<div class="slide-background color-background" aria-hidden="true"></div>
				<?php
				$slide_bg_color_html = ob_get_clean();
			endif;

			// Background Image Set.
			if ( 'classic' === $slide_background_type && $slide_background_image ) :

				// Construct class.
				$slide_class .= ' has-background image-as-background' . esc_attr( $slide_has_show_overlay );
				ob_start();
				?>
					<figure class="slide-background image-background" aria-hidden="true">
						<?php echo wp_get_attachment_image( $slide_background_image['id'], 'full' ); ?>
					</figure>
				<?php
				$slide_bg_image_html = ob_get_clean();
			endif;

			// Background Gradient Set.
			if ( 'gradient' === $slide_background_type ) {

				// Construct class.
				$slide_class .= ' has-background gradient-as-background';
				ob_start();
				?>
					<div class="slide-background gradient-background" aria-hidden="true"></div>
				<?php
				$slide_bg_gradient_html = ob_get_clean();
			}

			if ( 'video' === $slide_background_type && $slide_background_video ) :
				$slide_background_video_webm  = isset( $slide['background_video_webm'] ) && $slide['background_video_webm'] ? $slide['background_video_webm'] : false;
				$slide_background_video_title = isset( $slide['background_video_title'] ) && $slide['background_video_title'] ? $slide['background_video_title'] : false;
				$slide_video_placeholder      = isset( $slide['video_placeholder'] ) && $slide['video_placeholder'] ? $slide['video_placeholder'] : false;
				$slide_class         .= ' has-background video-as-background' . esc_attr( $slide_has_show_overlay );

				// Translators: get the title of the video.
				$slide_background_video_alt = $slide_background_video_title ? sprintf( 'Video Background of %s', 'wp-rig', esc_attr( $slide_background_video_title ) ) : __( 'Video Background', 'wp-rig' );

				ob_start();
				?>
					<video
						class="slide-background video-background" autoplay muted loop playsinline preload="auto" aria-hidden="true"
						<?php echo $slide_background_video_title ? ' title="' . esc_attr( $slide_background_video_alt ) . '"' : ''; ?>
						<?php echo $slide_video_placeholder ? ' poster="' . esc_url( $slide_video_placeholder['sizes']['full'] ) . '"' : ''; ?>
					>
							<?php if ( $slide_background_video_webm['url'] ) : ?>
								<source src="<?php echo esc_url( $slide_background_video_webm['url'] ); ?>" type="video/webm">
							<?php endif; ?>

							<?php if ( $slide_background_video['url'] ) : ?>
								<source src="<?php echo esc_url( $slide_background_video['url'] ); ?>" type="video/mp4">
							<?php endif; ?>
					</video>
				<?php
				$slide_bg_video_html = ob_get_clean();
			endif;

			if ( $slide_overlay_type && ( $slide_background_image || $slide_background_video ) ) :

				ob_start();
				?>
					<div class="slide-overlay"></div>
				<?php
				$slide_overlay_html = ob_get_clean();
			endif;

			if ( 'none' === $slide_background_type ) :
				$slide_class .= ' no-background';
			endif;
		endif;

		// Print our block container with options.
		printf( '<div class="%s" id="%s">', esc_attr( $slide_class ), esc_attr( $slide_id ) );

		// Print a background color markup inside the block container.
		if ( $slide_bg_color_html ) :
			echo $slide_bg_color_html; // phpcs:ignore
		endif;

		// Print a background gradient color markup inside the block container.
		if ( $slide_bg_gradient_html ) :
			echo $slide_bg_gradient_html; // phpcs:ignore
		endif;

		// Print a background image markup inside the block container.
		if ( $slide_bg_image_html ) :
			echo $slide_bg_image_html; // phpcs:ignore
		endif;

		// Print a background video markup inside the block container.
		if ( $slide_bg_video_html ) :
			echo $slide_bg_video_html; // phpcs:ignore
		endif;

		// Print a overlay markup inside the block container.
		if ( $slide_overlay_html ) :
			echo $slide_overlay_html; // phpcs:ignore
		endif;
	}

	/**
	 * Set slide custom styles.
	 *
	 * @access public
	 * @param  array $slide slide settings.
	 * @return string slide custom style.
	 */
	public function get_slide_custom_styles( $slide ) {

		// Variables for custom styles.
		$slide_selector                   = wp_rig()->get_array_value( $slide, 'slide_selector', '' );
		$slide_thumb_selector             = wp_rig()->get_array_value( $slide, 'slide_thumb_selector', '' );
		$slide_text_color                 = wp_rig()->get_array_value( $slide, 'text_color' );
		$slide_heading_color              = wp_rig()->get_array_value( $slide, 'heading_color' );
		$slide_tagline_color              = wp_rig()->get_array_value( $slide, 'tagline_color' );
		$slide_link_color                 = wp_rig()->get_array_value( $slide, 'link_color' );
		$slide_link_hover_color           = wp_rig()->get_array_value( $slide, 'link_hover_color' );
		$slide_background_type            = wp_rig()->get_array_value( $slide, 'background_type' );
		$slide_background_color           = wp_rig()->get_array_value( $slide, 'background_color' );
		$slide_background_image           = wp_rig()->get_array_value( $slide, 'background_image' );
		$slide_background_video           = wp_rig()->get_array_value( $slide, 'background_video' );
		$slide_first_gradient_color       = wp_rig()->get_array_value( $slide, 'first_gradient_color' );
		$slide_first_gradient_location    = wp_rig()->get_array_value( $slide, 'first_gradient_location' );
		$slide_second_gradient_color      = wp_rig()->get_array_value( $slide, 'second_gradient_color' );
		$slide_second_gradient_location   = wp_rig()->get_array_value( $slide, 'second_gradient_location' );
		$slide_gradient_type              = wp_rig()->get_array_value( $slide, 'gradient_type' );
		$slide_gradient_angle             = wp_rig()->get_array_value( $slide, 'gradient_angle' );
		$slide_gradient_position          = wp_rig()->arr2str(
			wp_rig()->get_array_value( $slide, 'gradient_position', array() )
		);
		$slide_background_object_fit      = wp_rig()->arr2str(
			wp_rig()->get_array_value( $slide, 'background_object_fit', array() )
		);
		$slide_background_object_position = wp_rig()->arr2str(
			wp_rig()->get_array_value( $slide, 'background_object_position', array() )
		);
		$slide_overlay_type               = wp_rig()->get_array_value( $slide, 'overlay_type' );
		$slide_overlay_color              = wp_rig()->get_array_value( $slide, 'overlay_color' );
		$slide_overlay_1st_color          = wp_rig()->get_array_value( $slide, 'overlay_1st_color' );
		$slide_overlay_1st_color_location = wp_rig()->get_array_value( $slide, 'overlay_1st_color_location' );
		$slide_overlay_1st_color_alpha    = wp_rig()->get_array_value( $slide, 'overlay_1st_color_alpha' );
		$slide_overlay_2nd_color          = wp_rig()->get_array_value( $slide, 'overlay_2nd_color' );
		$slide_overlay_2nd_color_location = wp_rig()->get_array_value( $slide, 'overlay_2nd_color_location' );
		$slide_overlay_2nd_color_alpha    = wp_rig()->get_array_value( $slide, 'overlay_2nd_color_alpha' );
		$slide_overlay_gradient_type      = wp_rig()->get_array_value( $slide, 'overlay_gradient_type' );
		$slide_overlay_gradient_angle     = wp_rig()->get_array_value( $slide, 'overlay_gradient_angle' );
		$slide_overlay_gradient_position  = wp_rig()->arr2str(
			wp_rig()->get_array_value( $slide, 'overlay_gradient_position', array() )
		);
		$slide_overlay_opacity            = wp_rig()->get_array_value( $slide, 'overlay_opacity' );
		$slide_overlay_blend_mode         = wp_rig()->arr2str(
			wp_rig()->get_array_value( $slide, 'overlay_blend_mode', array() )
		);
		$slide_custom_overlay_selector    = wp_rig()->get_array_value( $slide, 'custom_overlay_selector' );
		$slide_layout                     = wp_rig()->get_array_value( $slide, 'layout' );
		$slide_content_width              = wp_rig()->get_array_value( $slide_layout, 'content_width' );
		$slide_content_width_sm           = wp_rig()->get_array_value( $slide_layout, 'content_width_sm' );
		$slide_content_vertical_align     = wp_rig()->get_array_value( $slide_layout, 'content_vertical_align' );
		$slide_content_vertical_align_sm  = wp_rig()->get_array_value( $slide_layout, 'content_vertical_align_sm' );
		$slide_padding_top                = wp_rig()->get_array_value( $slide, 'padding_top' );
		$slide_padding_bottom             = wp_rig()->get_array_value( $slide, 'padding_bottom' );

		// Initiate slide_custom_styles blank.
		$slide_custom_styles = '';

		// Add custom styles only if there is any.
		if (
			$slide_background_color ||
			$slide_text_color ||
			( $slide_first_gradient_color && $slide_second_gradient_color ) ||
			$slide_overlay_type ||
			$slide_background_object_fit ||
			$slide_background_object_position ||
			$slide_content_width ||
			$slide_content_width_sm ||
			$slide_content_vertical_align ||
			$slide_content_vertical_align_sm ||
			$slide_padding_top ||
			$slide_padding_bottom
		) :

			// Output begins.
			ob_start();
			?>
				<?php
				if ( $slide_text_color || $slide_content_vertical_align || $slide_padding_top || $slide_padding_bottom ) :
					?>
					<?php echo esc_attr( '.block-slideshow ' . $slide_selector ); ?> {

						<?php
						if ( $slide_text_color ) :
							?>
							color: <?php echo esc_attr( $slide_text_color ); ?>;
							<?php
						endif;
						?>

						<?php
						if ( $slide_content_vertical_align ) :
							?>
							justify-content: <?php echo esc_attr( $slide_content_vertical_align ); ?>;
							<?php
						endif;
						?>

						<?php
						if ( $slide_padding_top ) :
							?>
							padding-top: <?php echo esc_attr( $slide_padding_top ); ?>;
							<?php
						endif;
						?>

						<?php
						if ( $slide_padding_bottom ) :
							?>
							padding-bottom: <?php echo esc_attr( $slide_padding_bottom ); ?>;
							<?php
						endif;
						?>
					}
					<?php
				endif;

				if ( $slide_content_vertical_align_sm ) :
					?>
					@media screen and (max-width: 768px) {
						<?php echo esc_attr( '.block-slideshow ' . $slide_selector ); ?> {
							justify-content: <?php echo esc_attr( $slide_content_vertical_align_sm ); ?>;
						}
					}
					<?php
				endif;

				if ( $slide_content_width ) :
					?>
					@media screen and (min-width: 768px) {
						<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content {
							width: <?php echo esc_attr( $slide_content_width ); ?>%;
							display: inline-block;
						}
					}
					<?php
				endif;

				if ( $slide_content_width_sm ) :
					?>
					@media screen and (max-width: 768px) {
						<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content {
							width: <?php echo esc_attr( $slide_content_width_sm ); ?>%;
							display: inline-block;
						}
					}
					<?php
				endif;

				if ( $slide_heading_color ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-headline {
						color: <?php echo esc_attr( $slide_heading_color ); ?>;
					}
					<?php
				endif;

				if ( $slide_tagline_color ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-tagline {
						color: <?php echo esc_attr( $slide_tagline_color ); ?>;
					}
					<?php
				endif;

				if ( $slide_link_color ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content a:not(.ui-btn),
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content a:not(.ui-btn):visited {
						color: <?php echo esc_attr( $slide_link_color ); ?>;
					}
					<?php
				endif;

				if ( $slide_link_hover_color ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content a:not(.ui-btn):focus,
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content a:not(.ui-btn):hover,
					<?php echo esc_attr( $slide_selector ); ?> .l-slide-content-container .slide-content a:not(.ui-btn):active {
						color: <?php echo esc_attr( $slide_link_hover_color ); ?>;
					}
					<?php
				endif;

				if ( $slide_overlay_type ) :
					$slide_custom_overlay_selector = $slide_custom_overlay_selector ? $slide_custom_overlay_selector : '.slide-overlay';
					?>
					<?php echo esc_attr( $slide_selector ); ?>.has-overlay > <?php echo esc_attr( $slide_custom_overlay_selector . ':after' ); ?> {
						<?php
						if ( 'color' === $slide_overlay_type && $slide_overlay_color ) :
							?>
							background-color: <?php echo esc_attr( $slide_overlay_color ); ?>;
							<?php
						endif;

						if ( 'gradient' === $slide_overlay_type && $slide_overlay_1st_color && $slide_overlay_2nd_color ) :
							if ( 'linear' === $slide_overlay_gradient_type ) :
								?>
								background-image: linear-gradient( <?php echo esc_attr( $slide_overlay_gradient_angle ) . 'deg, ' . esc_attr( $slide_overlay_1st_color ) . ' ' . esc_attr( $slide_overlay_1st_color_location ) . '%, ' . esc_attr( $slide_overlay_2nd_color ) . ' ' . esc_attr( $slide_overlay_2nd_color_location ) . '%'; ?>);
								<?php
							endif;
							if ( 'radial' === $slide_overlay_gradient_type ) :
								?>
								background-image: radial-gradient( at <?php echo esc_attr( $slide_overlay_gradient_position ) . ', ' . esc_attr( $slide_overlay_1st_color ) . ' ' . esc_attr( $slide_overlay_1st_color_location ) . '%, ' . esc_attr( $slide_overlay_2nd_color ) . ' ' . esc_attr( $slide_overlay_2nd_color_location ) . '%'; ?>);
								<?php
							endif;
						endif;

						if ( $slide_overlay_blend_mode ) :
							?>
							mix-blend-mode: <?php echo esc_attr( $slide_overlay_blend_mode ); ?>;
							<?php
						endif;

						if ( $slide_overlay_opacity ) :
							?>
							opacity: calc(<?php echo esc_attr( $slide_overlay_opacity ); ?>/100);
							<?php
						endif;
						?>
					}
					<?php
				endif;

				if ( $slide_background_color ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> > .slide-background,
					<?php echo esc_attr( $slide_thumb_selector ); ?> > .slide-background {
						background-color: <?php echo esc_attr( $slide_background_color ); ?>;
					}
					<?php
				endif;

				if ( 'gradient' === $slide_background_type && $slide_first_gradient_color && $slide_second_gradient_color ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> > .gradient-background,
					<?php echo esc_attr( $slide_thumb_selector ); ?> > .gradient-background {
						<?php
						if ( 'linear' === $slide_gradient_type ) :
							?>
							background-image: linear-gradient( <?php echo esc_attr( $slide_gradient_angle ) . 'deg, ' . esc_attr( $slide_first_gradient_color ) . ' ' . esc_attr( $slide_first_gradient_location ) . '%, ' . esc_attr( $slide_second_gradient_color ) . ' ' . esc_attr( $slide_second_gradient_location ) . '%'; ?>);
							<?php
						endif;
						if ( 'radial' === $slide_gradient_type ) :
							?>
							background-image: radial-gradient( at <?php echo esc_attr( $slide_gradient_position ) . ', ' . esc_attr( $slide_first_gradient_color ) . ' ' . esc_attr( $slide_first_gradient_location ) . '%, ' . esc_attr( $slide_second_gradient_color ) . ' ' . esc_attr( $slide_second_gradient_location ) . '%'; ?>);
							<?php
						endif;
						?>
					}
					<?php
				endif;

				if ( $slide_background_object_fit || $slide_background_object_position ) :
					?>
					<?php echo esc_attr( $slide_selector ); ?> > .video-background,
					<?php echo esc_attr( $slide_selector ); ?> > .image-background,
					<?php echo esc_attr( $slide_selector ); ?> > .image-background img {
						<?php
						if ( $slide_background_object_fit ) :
							echo 'object-fit: ' . esc_attr( $slide_background_object_fit ) . ';';
						endif;
						if ( $slide_background_object_position ) :
							echo 'object-position: ' . esc_attr( $slide_background_object_position ) . ';';
						endif;
						?>
					}
					<?php
				endif;
				?>
			<?php
			$slide_custom_styles = ob_get_clean();

		endif;

		if ( ! $slide_custom_styles ) {
			return;
		}

		return $slide_custom_styles; // phpcs:ignore
	}

	/**
	 * Initiate carousel script.
	 *
	 * @access public
	 * @param string $block_id The block ID.
	 * @param string $block_slug The block slug.
	 * @param string $carousel_target target container for carousel.
	 * @param array  $options slideshow settings.
	 * @param string $parent_slideshow element selector used on parent slideshow.
	 * @param array  $initial_slidestoshow array of initial values of slides to show ( slidestoshow, md_slidestoshow, sm_slidestoshow ).
	 * @return void
	 */
	public function initiate_carousel_script( $block_id, $block_slug, $carousel_target, $options = array(), $parent_slideshow = '', $initial_slidestoshow = array() ) {

		// get carousel options.
		$options = $this->get_carousel_options( $options );

		// Set initial slides to show values.
		$default_initial_slidestoshow = array(
			'slidestoshow'    => 3,
			'md_slidestoshow' => 2,
			'sm_slidestoshow' => 1,
		);

		$initial_slidestoshow = wp_parse_args( $initial_slidestoshow, $default_initial_slidestoshow );

		if ( isset( $options['slidestoshow'] ) && 0 == $options['slidestoshow'] ) {
			$options['slidestoshow'] = $initial_slidestoshow['slidestoshow'];
		}

		if ( isset( $options['md_slidestoshow'] ) && 0 == $options['md_slidestoshow'] ) {
			$options['md_slidestoshow'] = $initial_slidestoshow['md_slidestoshow'];
		}

		if ( isset( $options['sm_slidestoshow'] ) && 0 == $options['sm_slidestoshow'] ) {
			$options['sm_slidestoshow'] = $initial_slidestoshow['sm_slidestoshow'];
		}

		// Responsive Carousel options settings.
		$options_md_responsive = isset( $options['md_responsive'] ) ? $options['md_responsive'] : false;
		$options_sm_responsive = isset( $options['sm_responsive'] ) ? $options['sm_responsive'] : false;

		// Tablet Responsive.
		$md_responsive = '';

		if ( $options_md_responsive ) {
			$join                      = $options_sm_responsive ? ',' : '';
			$options_md_breakpoint     = isset( $options['md_breakpoint'] ) ? $options['md_breakpoint'] : '768';
			$options_md_slidestoshow   = isset( $options['md_slidestoshow'] ) ? $options['md_slidestoshow'] : '2';
			$options_md_slidestoscroll = isset( $options['md_slidestoscroll'] ) ? $options['md_slidestoscroll'] : '1';
			$options_md_autoplay       = isset( $options['md_autoplay'] ) ? $options['md_autoplay'] : false;
			$options_md_infinite       = isset( $options['md_infinite'] ) ? $options['md_infinite'] : false;
			$options_md_dots           = isset( $options['md_dots'] ) ? $options['md_dots'] : false;
			$options_md_arrows         = isset( $options['md_arrows'] ) ? $options['md_arrows'] : false;
			$options_md_centermode     = isset( $options['md_centermode'] ) ? $options['md_centermode'] : false;
			$options_md_centerpadding  = isset( $options['md_centerpadding'] ) ? $options['md_centerpadding'] : false;
			$md_responsive             = '
			{
				breakpoint: ' . $options_md_breakpoint . ',
				settings: {
					slidesToShow: ' . $options_md_slidestoshow . ',
					slidesToScroll: ' . $options_md_slidestoscroll . ',
					autoplay: ' . wp_rig()->bool( $options_md_autoplay ) . ',
					infinite: ' . wp_rig()->bool( $options_md_infinite ) . ',
					dots: ' . wp_rig()->bool( $options_md_dots ) . ',
					arrows: ' . wp_rig()->bool( $options_md_arrows ) . ',
					centerMode: ' . wp_rig()->bool( $options_md_centermode ) . ',
					centerPadding: "' . $options_md_centerpadding . 'px",
				}
			} ' . $join;
		}

		// Mobile Responsive.
		$sm_responsive = '';

		if ( $options_sm_responsive ) {
			$options_sm_breakpoint     = isset( $options['sm_breakpoint'] ) ? $options['sm_breakpoint'] : '580';
			$options_sm_slidestoshow   = isset( $options['sm_slidestoshow'] ) ? $options['sm_slidestoshow'] : '1';
			$options_sm_slidestoscroll = isset( $options['sm_slidestoscroll'] ) ? $options['sm_slidestoscroll'] : '1';
			$options_sm_autoplay       = isset( $options['sm_autoplay'] ) ? $options['sm_autoplay'] : false;
			$options_sm_infinite       = isset( $options['sm_infinite'] ) ? $options['sm_infinite'] : false;
			$options_sm_dots           = isset( $options['sm_dots'] ) ? $options['sm_dots'] : false;
			$options_sm_arrows         = isset( $options['sm_arrows'] ) ? $options['sm_arrows'] : false;
			$options_sm_centermode     = isset( $options['sm_centermode'] ) ? $options['sm_centermode'] : false;
			$options_sm_centerpadding  = isset( $options['sm_centerpadding'] ) ? $options['sm_centerpadding'] : false;
			$sm_responsive             = '
			{
				breakpoint: ' . $options['sm_breakpoint'] . ',
				settings: {
					slidesToShow: ' . $options_sm_slidestoshow . ',
					slidesToScroll: ' . $options_sm_slidestoscroll . ',
					autoplay: ' . wp_rig()->bool( $options_sm_autoplay ) . ',
					infinite: ' . wp_rig()->bool( $options_sm_infinite ) . ',
					dots: ' . wp_rig()->bool( $options_sm_dots ) . ',
					arrows: ' . wp_rig()->bool( $options_sm_arrows ) . ',
					centerMode: ' . wp_rig()->bool( $options_sm_centermode ) . ',
					centerPadding: "' . $options_sm_centerpadding . 'px",
				}
			}
			';
		}

		// Concatenating Responsive settings.
		$responsive = '';
		if ( $options_md_responsive || $options_sm_responsive ) {
			$responsive = '
			responsive: [
				' . $md_responsive . '
				' . $sm_responsive . '
			]
			';
		}

		$options_rows         = isset( $options['rows'] ) ? $options['rows'] : '0';
		$options_slidesperrow = isset( $options['slidesperrow'] ) ? $options['slidesperrow'] : '';
		$slides_per_row       = 2 < $options_rows ? 'slidesPerRow: ' . $options_slidesperrow . ',' : '';
		$initialslide         = isset( $options['initialslide'] ) && 0 < $options['initialslide'] ? $options['initialslide'] : 0;
		$thumbnail_sync       = $parent_slideshow ? 'asNavFor: "' . $parent_slideshow . '",' : '';

		// Main Slick settings.
		$options_slidestoshow   = isset( $options['slidestoshow'] ) ? $options['slidestoshow'] : false;
		$options_slidestoscroll = isset( $options['slidestoscroll'] ) ? $options['slidestoscroll'] : false;
		$options_autoplay       = isset( $options['autoplay'] ) ? $options['autoplay'] : false;
		$options_infinite       = isset( $options['infinite'] ) ? $options['infinite'] : false;
		$options_dots           = isset( $options['dots'] ) ? $options['dots'] : false;
		$options_arrows         = isset( $options['arrows'] ) ? $options['arrows'] : false;
		$options_prevarrow      = isset( $options['prevarrow'] ) ? $options['prevarrow'] : '';
		$options_nextarrow      = isset( $options['nextarrow'] ) ? $options['nextarrow'] : '';
		$options_speed          = isset( $options['speed'] ) ? $options['speed'] : false;
		$options_autoplayspeed  = isset( $options['autoplayspeed'] ) ? $options['autoplayspeed'] : false;
		$options_adaptiveheight = isset( $options['adaptiveheight'] ) ? $options['adaptiveheight'] : false;
		$options_swipe          = isset( $options['swipe'] ) ? $options['swipe'] : false;
		$options_draggable      = isset( $options['draggable'] ) ? $options['draggable'] : false;
		$options_centermode     = isset( $options['centermode'] ) ? $options['centermode'] : false;
		$options_centerpadding  = isset( $options['centerpadding'] ) ? $options['centerpadding'] : false;
		$options_fade           = isset( $options['fade'] ) ? $options['fade'] : false;
		$options_lazyload       = isset( $options['lazyload'] ) ? $options['lazyload'] : false;
		$options_pauseonfocus   = isset( $options['pauseonfocus'] ) ? $options['pauseonfocus'] : false;
		$options_pauseonhover   = isset( $options['pauseonhover'] ) ? $options['pauseonhover'] : false;

		$slick_id       = str_replace( '-', '_', $block_id );
		$slick_initiate = '
		jQuery( function( $ ) {

			/**
			 * initializeCarousel
			 *
			 * Adds slick script initializer to the block HTML.
			 *
			 * @param   object $block The block jQuery element.
			 * @param   object attributes The block attributes (only available when editing).
			 * @return  void
			 */
			var initialize_slick_' . $slick_id . ' = function( $block ) {

				function getSliderSettings() {
					return {
						slidesToShow: ' . $options_slidestoshow . ',
						slidesToScroll: ' . $options_slidestoscroll . ',
						autoplay: ' . wp_rig()->bool( $options_autoplay ) . ',
						infinite: ' . wp_rig()->bool( $options_infinite ) . ',
						dots: ' . wp_rig()->bool( $options_dots ) . ',
						arrows: ' . wp_rig()->bool( $options_arrows ) . ',
						prevArrow: ' . $options_prevarrow . ',
						nextArrow: ' . $options_nextarrow . ',
						speed: ' . $options_speed . ',
						autoplaySpeed: ' . $options_autoplayspeed . ',
						adaptiveHeight: ' . wp_rig()->bool( $options_adaptiveheight ) . ',
						swipe: ' . wp_rig()->bool( $options_swipe ) . ',
						draggable: ' . wp_rig()->bool( $options_draggable ) . ',
						centerMode: ' . wp_rig()->bool( $options_centermode ) . ',
						centerPadding: "' . $options_centerpadding . 'px",
						fade: ' . wp_rig()->bool( $options_fade ) . ',
						initialSlide: ' . $initialslide . ',
						lazyLoad: "' . $options_lazyload . '",
						pauseOnFocus: ' . wp_rig()->bool( $options_pauseonfocus ) . ',
						pauseOnHover: ' . wp_rig()->bool( $options_pauseonhover ) . ',
						focusOnSelect: true,
						rows: ' . $options_rows . ',
						' . $slides_per_row . '
						' . $thumbnail_sync . '
						' . $responsive . '
					}
				}

				$block.find( "' . $carousel_target . '" ).slick( getSliderSettings() );

				// If using tabby, refresh on event.
				document.addEventListener("tabby", function (event) {
					$block.find( "' . $carousel_target . '" ).slick( "unslick" );
					$block.find( "' . $carousel_target . '" ).not( ".slick-initialized" ).slick( getSliderSettings() );
				}, false);
			}

			// Initialize each block on page load (front end).
			$(document).ready(function(){
				if ( $( "#' . $block_id . '" ) ) {
					initialize_slick_' . $slick_id . '( $( "#' . $block_id . '" ) );
				}
			});

			// Initialize dynamic block preview (editor).
			if ( window.acf ) {
				window.acf.addAction( "render_block_preview/type=' . $block_slug . '", initialize_slick_' . $slick_id . ' );
			}
		} );
		';

		wp_add_inline_script( 'wp-slick', $slick_initiate );
	}

	/**
	 * Get carousel options.
	 *
	 * @access public
	 * @param array $options slideshow settings.
	 * @return array $options Array of carousel settings ready to initiate
	 */
	public function get_carousel_options( $options = array() ) {

		// Setup defaults options.
		$defaults_options = array(
			'slidestoshow'   => 1,
			'slidestoscroll' => 1,
			'autoplay'       => false,
			'infinite'       => true,
			'dots'           => true,
			'arrows'         => true,
			'prevarrow'      => '\'<div class="arrows slick-prev"><svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"far\" data-icon=\"chevron-left\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\"><path fill=\"currentColor\" d=\"M231.293 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L70.393 256 251.092 74.87c4.686-4.686 4.686-12.284 0-16.971L231.293 38.1c-4.686-4.686-12.284-4.686-16.971 0L4.908 247.515c-4.686 4.686-4.686 12.284 0 16.971L214.322 473.9c4.687 4.686 12.285 4.686 16.971-.001z\"></path></svg></div>\'',
			'nextarrow'      => '\'<div class="arrows slick-next"><svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"far\" data-icon=\"chevron-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\"><path fill=\"currentColor\" d=\"M24.707 38.101L4.908 57.899c-4.686 4.686-4.686 12.284 0 16.971L185.607 256 4.908 437.13c-4.686 4.686-4.686 12.284 0 16.971L24.707 473.9c4.686 4.686 12.284 4.686 16.971 0l209.414-209.414c4.686-4.686 4.686-12.284 0-16.971L41.678 38.101c-4.687-4.687-12.285-4.687-16.971 0z\"></path></svg></div>\'',
			'speed'          => 300,
			'autoplayspeed'  => 3000,
			'adaptiveheight' => false,
			'swipe'          => true,
			'draggable'      => true,
			'centermode'     => false,
			'centerpadding'  => 50,
			'fade'           => false,
			'initialslide'   => 0,
			'lazyload'       => 'ondemand',
			'pauseonfocus'   => true,
			'pauseonhover'   => true,
			'rows'           => true,
			'slidesperrow'   => true,
			'color_overall'  => '',
			'color_arrows'   => '',
			'color_dots'     => '',
			'md_responsive'  => false,
			'sm_responsive'  => false,
		);

		// Parse options.
		$options = wp_parse_args( $options, $defaults_options );

		return $options;
	}

	/**
	 * Get carousel custom styles.
	 *
	 * @access public
	 * @param string $block_id The block ID.
	 * @param array  $options slideshow settings.
	 * @param bool   $add_thumbs slideshow has thumbs.
	 * @param string $slideshow_suffix suffix for slideshow container.
	 * @param string $slideshow_thumbs_suffix suffix for slideshow thumbs container.
	 * @return string carousel custom styles.
	 */
	public function get_carousel_custom_styles( $block_id, $options, $add_thumbs = false, $slideshow_suffix = '', $slideshow_thumbs_suffix = '' ) {
		$color_overall = '';
		$color_arrows  = '';
		$color_dots    = '';

		if ( isset( $options['color_overall'] ) && $options['color_overall'] ) {
			$color_overall = '
				#' . $block_id . $slideshow_suffix . ' .slick-arrow,
				#' . $block_id . $slideshow_suffix . ' .slick-dots li button {
					color: ' . $options['color_overall'] . ';
				}
			';
		}

		if ( isset( $options['color_arrows'] ) && $options['color_arrows'] ) {
			$color_arrows = '
				#' . $block_id . $slideshow_suffix . ' .slick-arrow  {
					color: ' . $options['color_arrows'] . ';
				}
			';
		}

		if ( isset( $options['color_dots'] ) && $options['color_dots'] ) {
			$color_dots = '
				#' . $block_id . $slideshow_suffix . ' .slick-dots li button {
					color: ' . $options['color_dots'] . ';
				}
			';
		}

		$slideshow_thumbs = '';

		if ( ! empty( $slides ) && isset( $options['add_thumbnails'] ) && $options['add_thumbnails'] ) {
			$thumb_background_color = '';

			if ( isset( $options['thumb_text_color'] ) && $options['thumb_text_color'] ) {
				$thumb_text_color = 'color: ' . esc_attr( $options['thumb_text_color'] ) . ';';
			}

			if ( isset( $options['thumb_background_color'] ) && $options['thumb_background_color'] ) {
				$thumb_background_color = 'background-color: ' . wp_rig()->hex2rgba( esc_attr( $options['thumb_background_color'] ), '0.25' ) . ';';
			}

			if ( isset( $options['thumb_text_color'] ) && $options['thumb_text_color'] || isset( $options['thumb_background_color'] ) && $options['thumb_background_color'] ) {
				$slideshow_thumbs = '
					#' . esc_attr( $block_id ) . $slideshow_thumbs_suffix . ' {
						' . $thumb_text_color . '
						' . $thumb_background_color . '
					}
				';
			}
		}

		$carousel_custom_styles = '
			' . $color_overall . '
			' . $color_arrows . '
			' . $color_dots . '
			' . $slideshow_thumbs . '
		';

		return $carousel_custom_styles;
	}
}
