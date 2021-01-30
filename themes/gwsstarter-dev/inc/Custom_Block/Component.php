<?php
/**
 * WP_Rig\WP_Rig\Custom_Block\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Block;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function add_action;
use function add_filter;
use function wp_enqueue_style;
use function wp_enqueue_script;
use function get_template_directory_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function get_theme_support;

/**
 * Class for Custom_Blocks.
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_block';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

		add_action( 'acf/input/admin_head', array( $this, 'acf_admin_style' ) );

		if ( function_exists( 'acf_register_block_type' ) ) {
			add_action( 'acf/init', array( $this, 'register_acf_block_types' ) );
		}

		add_action( 'acf/input/admin_footer', array( $this, 'register_acf_color_palette' ) );

		if ( ! function_exists( 'fa_custom_setup_kit' ) ) {
			foreach (
				array(
					// 'wp_enqueue_scripts', // Add/Remove Fontawesome on Frontend.
					'admin_enqueue_scripts', // Add/Remove Fontawesome on WP Admin Editor.
					'login_enqueue_scripts', // Add/Remove Fontawesome on Login Screen.
				)
			as $action ) {
				add_action( $action, array( $this, 'fa_custom_setup_kit' ) );
			}
		}

		/**
		 * Remove gutenberg css.
		 * if ( ! function_exists( 'remove_gutenberg_css' ) ) {
		 * add_action( 'wp_enqueue_scripts', array( $this, 'remove_gutenberg_css' ), 100 );
		 * }
		 */

		add_filter( 'acf/load_field/name=top_divider_style', array( $this, 'acf_load_shape_dividers_choices' ) );
		add_filter( 'acf/load_field/name=bottom_divider_style', array( $this, 'acf_load_shape_dividers_choices' ) );
	}

	/**
	 * Register Custom Blocks
	 *
	 * @access public
	 * @return void
	 */
	public function register_acf_block_types() {

		$supports = array(
			'align'     => array( 'wide', 'full' ),
			'anchor'    => true,
			'className' => false,
			'html'      => false,
		);

		// block wrapper.
		acf_register_block_type(
			array(
				'name'            => 'hero',
				'title'           => __( 'Hero', 'wp-rig' ),
				'description'     => __( 'Custom hero block.', 'wp-rig' ),
				'render_callback' => array( $this, 'acf_block_callback' ),
				'category'        => 'custom-blocks',
				'icon'            => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="tv-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="#22222e" d="M520 448H120a24 24 0 0 0-24 24v16a24 24 0 0 0 24 24h400a24 24 0 0 0 24-24v-16a24 24 0 0 0-24-24zM592 0H48A48 48 0 0 0 0 48v320a48 48 0 0 0 48 48h544a48 48 0 0 0 48-48V48a48 48 0 0 0-48-48zm-16 352H64V64h512z" opacity="1"></path><path class="fa-primary" fill="#38d6c7" d="M576 352H64V64h512z"></path></g></svg>',
				'mode'            => 'preview', // auto, preview, edit.
				'enqueue_assets'  => array( $this, 'acf_block_assets' ),
				'align'           => 'full',
				'keywords'        => array( 'hero', 'cover', 'header' ),
				'supports'        => array(
					'align'               => array( 'wide', 'full' ), // left, center, right, wide, full.
					'anchor'              => true,
					'html'                => false,
					'jsx'                 => true,
					'color'               => array(
						'text'       => true,
						'background' => true,
						'gradients'  => true,
					),
				),
				'textColor'       => 'theme-white',
				'backgroundColor' => 'theme-gray',
				'data'            => array(
					'background_type'            => 'image',
					'background_image_url'       => get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg',
					'background_object_fit'      => 'cover',
					'background_object_position' => 'center center',
					'overlay_blend_mode'         => 'normal',
					'overlay_type'               => 'color',
					'overlay_color'              => '#151520',
					'overlay_opacity'            => '40',
					'content_container'          => 'container',
					'layout'                     => array(
						'text_align'     => 'center',
						'content_valign' => 'center',
						'height'         => 'half-height',
					),
					'layout_text_align'          => 'center',
					'layout_content_valign'      => 'center',
					'layout_height'              => 'half-height',
				),
				'example'         => array(
					'attributes'  => array(
						'mode'            => 'preview',
						'align'           => 'full',
						'textColor'       => 'theme-white',
						'backgroundColor' => 'theme-gray',
						'data'            => array(
							'is_preview'                 => true,
							'background_type'            => 'image',
							'background_image_url'       => get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg',
							'background_object_fit'      => 'cover',
							'background_object_position' => 'center center',
							'overlay_blend_mode'         => 'normal',
							'overlay_type'               => 'color',
							'overlay_color'              => '#151520',
							'overlay_opacity'            => '40',
							'content_container'          => 'container',
							'layout_text_align'          => 'center',
							'layout_content_valign'      => 'center',
							'layout_height'              => 'half-height',
						),
					),
					'innerBlocks' => array(
						array(
							'name' => 'core/paragraph',
							'attributes' => array(
								'align'     => 'center',
								'content'   => __( 'Tagline', 'wp-rig' ),
								'className' => 'block-tagline',
							),
						),
						array(
							'name' => 'core/heading',
							'attributes' => array(
								'align'     => 'center',
								'content'   => __( 'Headline', 'wp-rig' ),
								'className' => 'block-headline is-style-default',
								'fontSize'  => 'h2',
							),
						),
						array(
							'name' => 'core/paragraph',
							'attributes' => array(
								'align'   => 'center',
								'content' => __( 'Some content here', 'wp-rig' ),
							),
						),
					),
				),
			)
		);

		// block container.
		acf_register_block_type(
			array(
				'name'            => 'container',
				'title'           => __( 'Container Wrapper', 'wp-rig' ),
				'description'     => __( 'Custom block container.', 'wp-rig' ),
				'render_callback' => array( $this, 'acf_block_callback' ),
				'category'        => 'custom-blocks',
				'icon'            => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="warehouse" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="#22222e" d="M504 448H136.1a8 8 0 0 0-8 8l-.1 48a8 8 0 0 0 8 8h368a8 8 0 0 0 8-8v-48a8 8 0 0 0-8-8zm0-192H136.6a8 8 0 0 0-8 8l-.1 48a8 8 0 0 0 8 8H504a8 8 0 0 0 8-8v-48a8 8 0 0 0-8-8zm0 96H136.4a8 8 0 0 0-8 8l-.1 48a8 8 0 0 0 8 8H504a8 8 0 0 0 8-8v-48a8 8 0 0 0-8-8z" opacity="1"></path><path class="fa-primary" fill="#38d6c7" d="M640 161.28V504a8 8 0 0 1-8 8h-80a8 8 0 0 1-8-8V256c0-17.6-14.6-32-32.6-32H128.6c-18 0-32.6 14.4-32.6 32v248a8 8 0 0 1-8 8H8a8 8 0 0 1-8-8V161.28A48.11 48.11 0 0 1 29.5 117l272-113.3a48.06 48.06 0 0 1 36.9 0L610.5 117a48.11 48.11 0 0 1 29.5 44.28z"></path></g></svg>',
				'mode'            => 'preview', // auto, preview, edit.
				'enqueue_assets'  => array( $this, 'acf_block_assets' ),
				'align'           => 'full', // left, center, right, wide, full.
				'keywords'        => array( 'wrapper', 'container', 'parent' ),
				'supports'        => array(
					'align'               => array( 'wide', 'full' ),
					'anchor'              => true,
					'html'                => false,
					'jsx'                 => true,
					'color'               => array(
						'text'       => true,
						'background' => true,
						'gradients'  => true,
					),
				),
				'data'            => array(
					'content_container'     => 'full-container',
					'layout'                     => array(
						'text_align'     => 'center',
						'content_valign' => 'center',
					),
					'layout_text_align'     => 'center',
					'layout_content_valign' => 'center',
				),
				'example'         => array(
					'attributes'  => array(
						'mode'            => 'preview',
						'align'           => 'full',
						'className'       => 'border-1 border-primary text-white',
						'textColor'       => 'theme-white',
						'backgroundColor' => 'theme-primary',
						'data'            => array(
							'is_preview'            => true,
							'background_type'       => 'image',
							'background_image_url'  => get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg',
							'content_container'     => 'container',
							'layout_text_align'     => 'center',
							'layout_content_valign' => 'center',
							'layout_height'         => 'half-height',
						),
					),
					'innerBlocks' => array(
						array(
							'name'       => 'core/group',
							'attributes' => array(
								'align'           => 'full',
								'className'       => 'border-1 border-white px-3 py-6',
							),
							'innerBlocks' => array(
								array(
									'name'       => 'core/heading',
									'attributes' => array(
										'align'     => 'center',
										'content'   => __( 'Container', 'wp-rig' ),
										'className' => 'block-headline is-style-default',
										'fontSize'  => 'h2',
									),
								),
								array(
									'name'       => 'core/paragraph',
									'attributes' => array(
										'align'    => 'center',
										'content'  => __( '[ Add Blocks Here ]', 'wp-rig' ),
										'fontSize' => 'h4',
									),
								),
							),
						),
					),
				),
			)
		);

		// content media block.
		acf_register_block_type(
			array(
				'name'            => 'content-media',
				'title'           => __( 'Content Media', 'wp-rig' ),
				'description'     => __( 'Content Media Block.', 'wp-rig' ),
				'render_callback' => array( $this, 'acf_block_callback' ),
				'category'        => 'custom-blocks',
				'icon'            => '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 448"><title>content-media-card-duotone</title><path d="M528,0H48A48,48,0,0,0,0,48V400a48,48,0,0,0,48,48H528a48,48,0,0,0,48-48V48A48,48,0,0,0,528,0Z" style="fill:#22222e"/><path d="M81,140.92V284.68a8.13,8.13,0,0,0,8.12,8.12H272.88a8.13,8.13,0,0,0,8.12-8.12V140.92a8.13,8.13,0,0,0-8.12-8.12H89.12A8.13,8.13,0,0,0,81,140.92Z" style="fill:#38d6c7"/><rect x="328" y="260.8" width="160" height="32" rx="8" style="fill:#38d6c7"/><rect x="328" y="196.8" width="160" height="32" rx="8" style="fill:#38d6c7"/><rect x="328" y="132.8" width="160" height="32" rx="8" style="fill:#38d6c7"/></svg>',
				'mode'            => 'preview', // auto, preview, edit.
				'enqueue_assets'  => array( $this, 'acf_block_assets' ),
				'align'           => 'full', // left, center, right, wide, full.
				'keywords'        => array( 'content', 'media', 'photo', 'picture', 'callout' ),
				'supports'        => array(
					'align'               => array( 'wide', 'full' ), // left, center, right, wide, full.
					'anchor'              => true,
					'html'                => false,
					'jsx'                 => true,
					'color'               => array(
						'text'       => true,
						'background' => true,
						'gradients'  => true,
					),
				),
				'data'            => array(
					'column_width'                       => 'half-half',
					'media_type'                         => 'image',
					'style_presets'                      => 'preset-1',
					'content_container'                  => 'fluid-container',
					'media_image'                        => array(
						'image_url' => get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg',
					),
					'media_container'                    => array(
						'media_aspect_ratio' => 'widescreen',
						'media_obj_fit'      => 'cover',
						'media_obj_pos'      => 'center center',
					),
					'layout'                             => array(
						'content_valign'     => 'center',
					),
					'media_image_image_url'              => get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg',
					'media_container_media_aspect_ratio' => 'widescreen',
					'media_container_media_obj_fit'      => 'cover',
					'media_container_media_obj_pos'      => 'center center',
					'layout_content_valign'              => 'center',
				),
			)
		);

		// brand and logos block.
		acf_register_block_type(
			array(
				'name'              => 'brands-logos',
				'title'             => __( 'Brands and Logos', 'wp-rig' ),
				'description'       => __( 'Brands and Logos.', 'wp-rig' ),
				'render_callback'   => array( $this, 'acf_block_callback' ),
				'category'          => 'custom-blocks',
				'icon'              => '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 128"><title>credit-card-front-duotone</title><path d="M96,178H32A32,32,0,0,0,0,210v64a32,32,0,0,0,32,32H96a32,32,0,0,0,32-32V210A32,32,0,0,0,96,178Zm160,0H192a32,32,0,0,0-32,32v64a32,32,0,0,0,32,32h64a32,32,0,0,0,32-32V210A32,32,0,0,0,256,178Zm160,0H352a32,32,0,0,0-32,32v64a32,32,0,0,0,32,32h64a32,32,0,0,0,32-32V210A32,32,0,0,0,416,178Z" transform="translate(0 -178)" style="fill:#22222e"/></svg>',
				'mode'              => 'preview', // auto, preview, edit.
				'enqueue_assets'    => array( $this, 'acf_block_assets' ),
				'align'             => 'full', // left, center, right, wide, full.
				'keywords'          => array( 'brands', 'logos', 'callout' ),
				'supports'        => array(
					'align'               => array( 'wide', 'full' ), // left, center, right, wide, full.
					'anchor'              => true,
					'html'                => false,
					'jsx'                 => true,
					'color'               => array(
						'text'       => true,
						'background' => true,
						'gradients'  => true,
					),
				),
				'data' => array(
					'content_container' => 'container',
				),
			)
		);

		// slideshow block.
		acf_register_block_type(
			array(
				'name'            => 'slideshow',
				'title'           => __( 'Slideshow', 'wp-rig' ),
				'description'     => __( 'Custom Slideshow block.', 'wp-rig' ),
				'render_callback' => array( $this, 'acf_block_callback' ),
				'category'        => 'custom-blocks',
				'icon'            => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="projector" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="#22222e" d="M592 192h-95.41C543.47 215.77 576 263.93 576 320c0 61.88-39.44 114.31-94.34 134.64L493 499.88A16 16 0 0 0 508.49 512h39A16 16 0 0 0 563 499.88L576 448h16a48 48 0 0 0 48-48V240a48 48 0 0 0-48-48zm-224.59 0H48a48 48 0 0 0-48 48v160a48 48 0 0 0 48 48h16l13 51.88A16 16 0 0 0 92.49 512h39A16 16 0 0 0 147 499.88L160 448h207.41C320.53 424.23 288 376.07 288 320s32.53-104.23 79.41-128zM96 352a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm96 0a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm325.66-218.35a16 16 0 0 0 22.62 0l67.88-67.88a16 16 0 0 0 0-22.63l-11.32-11.31a16 16 0 0 0-22.62 0l-67.88 67.89a16 16 0 0 0 0 22.62zM440 0h-16a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16h16a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16zM323.72 133.65a16 16 0 0 0 22.62 0l11.32-11.31a16 16 0 0 0 0-22.62l-67.88-67.89a16 16 0 0 0-22.62 0l-11.32 11.31a16 16 0 0 0 0 22.63z" opacity="1"></path><path class="fa-primary" fill="#38d6c7" d="M96 288a32 32 0 1 0 32 32 32 32 0 0 0-32-32zm336-112a144 144 0 1 0 144 144 144 144 0 0 0-144-144zm0 240a96 96 0 1 1 96-96 96.14 96.14 0 0 1-96 96z"></path></g></svg>',
				'mode'            => 'preview', // auto, preview, edit.
				'enqueue_assets'  => array( $this, 'acf_block_assets' ),
				'align'           => 'full', // left, center, right, wide, full.
				'keywords'        => array( 'slideshow', 'hero', 'cover', 'header' ),
				'supports'        => array(
					'align'  => array( 'wide', 'full' ), // left, center, right, wide, full.
					'anchor' => true,
					'html'   => false,
					'jsx'    => true,
					'color'  => array(
						'text'       => true,
						'background' => true,
						'gradients'  => true,
					),
				),
				'data'           => array(
					'content_container' => 'full-container',
				),
			)
		);

		// slideshow block.
		acf_register_block_type(
			array(
				'name'            => 'slide',
				'title'           => __( 'Slide', 'wp-rig' ),
				'description'     => __( 'Custom Slide block.', 'wp-rig' ),
				'render_callback' => array( $this, 'acf_block_callback' ),
				'category'        => 'custom-blocks',
				'icon'            => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="presentation" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><g class="fa-group"><path class="fa-secondary" fill="#22222e" d="M480 64h64v256a32 32 0 0 1-32 32H64a32 32 0 0 1-32-32V64h64v224h384z" opacity="1"></path><path class="fa-primary" fill="#38d6c7" d="M560 0H16A16 16 0 0 0 0 16v32a16 16 0 0 0 16 16h544a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16zM320 386.75V352h-64v34.75l-75.31 75.31a16 16 0 0 0 0 22.63l22.62 22.62a16 16 0 0 0 22.63 0L288 445.25l62.06 62.06a16 16 0 0 0 22.63 0l22.62-22.62a16 16 0 0 0 0-22.63z"></path></g></svg>',
				'mode'            => 'preview', // auto, preview, edit.
				'enqueue_assets'  => array( $this, 'acf_block_assets' ),
				'keywords'        => array( 'slide', 'slideshow', 'hero', 'cover', 'header' ),
				'supports'        => array(
					'align'  => false, // left, center, right, wide, full.
					'anchor' => false,
					'html'   => false,
					'jsx'    => true,
				),
			)
		);
	}

	/**
	 * Render callback function
	 *
	 * @access public
	 *
	 * @param array      $block The block settings and attributes.
	 * @param string     $content The block inner HTML (empty).
	 * @param bool       $is_preview True during AJAX preview.
	 * @param int|string $post_id The post ID this block is saved to.
	 * @return void Bail if the block has expired.
	 */
	public function acf_block_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

		// Convert the block name.
		$block_slug = str_replace( 'acf/', '', $block['name'] );

		// Include block template part.
		if ( file_exists( get_theme_file_path( '/template-parts/blocks/' . $block_slug . '/' . $block_slug . '.php' ) ) ) {
			include get_theme_file_path( '/template-parts/blocks/' . $block_slug . '/' . $block_slug . '.php' );
		}
	}

	/**
	 * Enqueue block assets callback function
	 *
	 * @access public
	 * @param array $block The block details.
	 * @return void Bail if the block has expired.
	 */
	public function acf_block_assets( $block ) {

		// Convert the block name.
		$block_slug            = str_replace( 'acf/', '', $block['name'] );
		$block_css_path        = get_theme_file_path( '/assets/css/blocks/' . $block_slug . '.min.css' );
		$block_css_uri         = get_template_directory_uri() . '/assets/css/blocks/' . $block_slug . '.min.css';
		$block_editor_css_path = get_theme_file_path( '/assets/css/blocks/' . $block_slug . '-editor.min.css' );
		$block_editor_css_uri  = get_template_directory_uri() . '/assets/css/blocks/' . $block_slug . '-editor.min.css';
		$block_js_path         = get_theme_file_path( '/assets/js/blocks/' . $block_slug . '.min.js' );
		$block_js_uri          = get_template_directory_uri() . '/assets/js/blocks/' . $block_slug . '.min.js';

		if ( file_exists( $block_css_path ) ) {
			wp_enqueue_style(
				'block-' . $block_slug,
				$block_css_uri,
				array(),
				filemtime( $block_css_path )
			);
		}

		if ( file_exists( $block_js_path ) ) {
			wp_enqueue_script(
				'block-' . $block_slug,
				$block_js_uri,
				array( 'jquery' ),
				filemtime( $block_js_path ),
				true
			);
			wp_script_add_data( 'block-' . $block_slug, 'defer', true );
		}

		if (
			has_block( 'acf/hero' )
			&& array_key_exists( 'data', $block )
		) {
			if (
				array_key_exists(
					'add_foreground_video',
					$block['data']
				)
				&& $block['data']['add_foreground_video']
			) {
				$this->enqueue_foreground_video_scripts();
			}
		}

		if (
			( has_block( 'acf/brands-logos' )
			|| has_block( 'acf/slideshow' ) )
			&& array_key_exists( 'data', $block )
		) {
			$this->enqueue_carousel_scripts();
		}

		if (
			( has_block( 'acf/video-with-painpoint-content' )
			|| has_block( 'acf/content-media' ) )
			&& array_key_exists( 'data', $block )
		) {
			$this->enqueue_lightbox_scripts();
		}
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_rig()`.
	 *
	 * @access public
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'get_block_alignment'               => array( $this, 'get_block_alignment' ),
			'get_text_align'                    => array( $this, 'get_text_align' ),
			'get_content_align'                 => array( $this, 'get_content_align' ),
			'get_content_valign'                => array( $this, 'get_content_valign' ),
			'get_block_height'                  => array( $this, 'get_block_height' ),
			'get_block_classes'                 => array( $this, 'get_block_classes' ),
			'get_block_id'                      => array( $this, 'get_block_id' ),
			'display_block_options'             => array( $this, 'display_block_options' ),
			'display_content_container_options' => array( $this, 'display_content_container_options' ),
			'display_block_custom_styles'       => array( $this, 'display_block_custom_styles' ),
			'get_array_value'                   => array( $this, 'get_array_value' ),
			'get_shape_divider_svg'             => array( $this, 'get_shape_divider_svg' ),
			'get_the_colors'                    => array( $this, 'get_the_colors' ),
			'get_headline'                      => array( $this, 'get_headline' ),
			'get_tagline'                       => array( $this, 'get_tagline' ),
			'get_caption'                       => array( $this, 'get_caption' ),
			'get_button'                        => array( $this, 'get_button' ),
			'get_link'                          => array( $this, 'get_link' ),
			'bool'                              => array( $this, 'bool' ),
			'arr2str'                           => array( $this, 'arr2str' ),
			'hex2rgba'                          => array( $this, 'hex2rgba' ),
			'does_url_exists'                   => array( $this, 'does_url_exists' ),
		);
	}

	/**
	 * Returns the alignment set for a content block.
	 *
	 * @access public
	 * @param array $block The block settings.
	 * @return string The block alignment set.
	 */
	public function get_block_alignment( $block ) {

		if ( ! $block ) {
			return;
		}

		$align = wp_rig()->get_array_value( $block, 'align' );

		return $align ? ' align' . esc_attr( $align ) : 'alignwide';
	}

	/**
	 * Returns the text alignment set for a content block.
	 *
	 * @access public
	 * @param string $layout The layout settings.
	 * @return string The text alignment set.
	 */
	public function get_text_align( $layout ) {

		if ( empty( $layout ) ) {
			return;
		}

		$text_align          = wp_rig()->get_array_value( $layout, 'text_align' );
		$text_align_sm       = wp_rig()->get_array_value( $layout, 'text_align_sm' );
		$text_align_class    = $text_align ? esc_attr( ' has-text-align-' . $text_align ) : '';
		$text_align_class_sm = $text_align_sm ? esc_attr( ' md-max:has-text-align-' . $text_align_sm ) : '';

		return $text_align_class . $text_align_class_sm;
	}

	/**
	 * Returns the content alignment set for a content block.
	 *
	 * @access public
	 * @param string $layout The layout settings.
	 * @return string The content alignment set.
	 */
	public function get_content_align( $layout ) {

		if ( empty( $layout ) ) {
			return;
		}

		$content_align          = wp_rig()->get_array_value( $layout, 'content_align' );
		$content_align_sm       = wp_rig()->get_array_value( $layout, 'content_align_sm' );
		$content_align_class    = $content_align ? esc_attr( ' has-content-align-' . $content_align ) : '';
		$content_align_class_sm = $content_align_sm ? esc_attr( ' md-max\:has-content-align-' . $content_align_sm ) : '';

		return $content_align_class . $content_align_class_sm;
	}

	/**
	 * Returns the content vertical alignment set for a content block.
	 *
	 * @access public
	 * @param string $layout The layout settings.
	 * @return string The block vertical alignment set.
	 */
	public function get_content_valign( $layout ) {

		if ( empty( $layout ) ) {
			return;
		}

		$content_valign          = wp_rig()->get_array_value( $layout, 'content_valign' );
		$content_valign_sm       = wp_rig()->get_array_value( $layout, 'content_valign_sm' );
		$content_valign_class    = $content_valign ? esc_attr( ' has-content-valign-' . $content_valign ) : '';
		$content_valign_class_sm = $content_valign_sm ? esc_attr( ' md-max\:has-content-valign-' . $content_valign_sm ) : '';

		return $content_valign_class . $content_valign_class_sm;
	}

	/**
	 * Returns the block height css classes.
	 *
	 * @access public
	 * @param string $layout The layout settings.
	 * @return string The block height set.
	 */
	public function get_block_height( $layout ) {

		if ( empty( $layout ) ) {
			return;
		}

		$height          = wp_rig()->get_array_value( $layout, 'height' );
		$height_sm       = wp_rig()->get_array_value( $layout, 'height_sm' );

		return ' ' . $height . ' ' . $height_sm;
	}

	/**
	 * Returns the class names set for a content block.
	 *
	 * @access public
	 * @param array $block The block settings.
	 * @param bool  $is_preview True during AJAX preview.
	 *
	 * @return string The block class set.
	 */
	public function get_block_classes( $block, $is_preview = false ) {

		if ( ! $block ) {
			return;
		}

		$classes  = '';

		if ( $is_preview ) {
			$classes .= ' is-admin';
		}

		$classes .= ! empty( $block['className'] ) ? ' ' . esc_attr( $block['className'] ) : '';

		return $classes;
	}

	/**
	 * Returns the ID or anchor link field set for a content block.
	 *
	 * @access public
	 * @param  array $block The block settings.
	 * @return string The Block ID set.
	 */
	public function get_block_id( $block ) {

		if ( empty( $block ) ) {
			return;
		}

		return empty( $block['anchor'] ) ? str_replace( '_', '-', $block['id'] ) : esc_attr( $block['anchor'] );
	}

	/**
	 * Set the blocks options.
	 *
	 * @access public
	 * @param  array $args Some arguments.
	 * @return void
	 */
	public function display_block_options( $args = array() ) {

		// Get block array.
		$block = wp_rig()->get_array_value( $args, 'block' );

		// Get the block ID.
		$block_id = wp_rig()->get_block_id( $block );

		// Get Block Height.
		$layout = get_field( 'layout' );
		$height = wp_rig()->get_array_value( $layout, 'height', '' );

		// Setup defaults.
		$defaults = array(
			'background_type'         => get_field( 'background_type' ),
			'container'               => 'section',
			'class'                   => 'l-block',
			'id'                      => $block_id,
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		$text_color_html     = '';
		$bg_color_html       = '';
		$bg_gradient_html    = '';
		$bg_video_html       = '';
		$bg_image_html       = '';
		$overlay_html        = '';
		$top_divider_html    = '';
		$bottom_divider_html = '';

		// Mixing gutenberg native color settings with advanced background settings.
		$text_color                = wp_rig()->get_array_value( $block, 'textColor' );
		$background_color          = wp_rig()->get_array_value( $block, 'backgroundColor' );
		$background_gradient       = wp_rig()->get_array_value( $block, 'gradient' );
		$background_type           = wp_rig()->get_array_value( $args, 'background_type' );
		$style                     = wp_rig()->get_array_value( $block, 'style' );
		$style_color               = wp_rig()->get_array_value( $style, 'color' );
		$style_text_color          = wp_rig()->get_array_value( $style_color, 'text' );
		$style_background_color    = wp_rig()->get_array_value( $style_color, 'background' );
		$style_background_gradient = wp_rig()->get_array_value( $style_color, 'gradient' );

		// Set text color from gutenberg text color setting.
		if ( $text_color || $style_text_color ) :
			$args['class'] .= ' has-text-color has-' . $text_color . '-color';
		endif;

		// Set background class if any background type is set.
		if ( $background_color || $style_background_color || $background_gradient || $style_background_gradient || $background_type ) :
			$args['class'] .= ' has-background';
		endif;

		// Set background color from gutenberg background color setting.
		if ( $background_color || $style_background_color ) :
			$args['class'] .= ' color-as-background';
			$background_color_class = $background_color ? 'has-' . $background_color . '-background-color' : '';
			ob_start();
			?>
				<div class="block-background color-background <?php echo esc_attr( $background_color_class ); ?>" aria-hidden="true"></div>
			<?php
			$bg_color_html = ob_get_clean();
		endif;

		// Set background gradient color from gutenberg background gradient color setting.
		if ( $background_gradient || $style_background_gradient ) :
			$args['class'] .= ' gradient-as-background';
			$background_gradient_class = $background_gradient ? 'has-' . $background_gradient . '-gradient-background' : '';
			ob_start();
			?>
				<div class="block-background gradient-background <?php echo esc_attr( $background_gradient_class ); ?>" aria-hidden="true"></div>
			<?php
			$bg_color_html = ob_get_clean();
		endif;

		// Set advanced background image or video from acf blocks.
		if ( $background_type ) :
			$background_image     = get_field( 'background_image' );
			$background_image_url = get_field( 'background_image_url' );
			$background_video     = get_field( 'background_video' );
			$background_video_url = wp_rig()->get_array_value( $background_video, 'url' );

			// Background Image Set.
			if ( ( 'image' === $background_type && $background_image ) || ( 'image' === $background_type && $background_image_url ) ) :

				// Construct class.
				$args['class'] .= ' image-as-background';
				ob_start();
				?>
					<figure class="block-background image-background" aria-hidden="true">
						<?php
						if ( $background_image ) :
							echo wp_get_attachment_image( $background_image['id'], 'full' );
						elseif ( $background_image_url ) :
							?>
							<img class="attachment-full size-full" src="<?php echo esc_url( $background_image_url ); ?>" loading="lazy">
							<?php
						endif;
						?>
					</figure>
				<?php
				$bg_image_html = ob_get_clean();
			endif;

			if ( 'video' === $background_type && $background_video ) :
				$background_video_webm     = get_field( 'background_video_webm' );
				$background_video_webm_url = wp_rig()->get_array_value( $background_video_webm, 'url' );
				$background_video_title    = get_field( 'background_video_title' );
				$video_placeholder         = get_field( 'video_placeholder' );
				$video_placeholder_sizes   = wp_rig()->get_array_value( $video_placeholder, 'sizes' );
				$video_placeholder_img     = wp_rig()->get_array_value( $video_placeholder_sizes, 'full' );
				$args['class']             .= ' video-as-background';

				// Translators: get the title of the video.
				$background_video_alt = $background_video_title ? sprintf( 'Video Background of %s', 'wp-rig', esc_attr( $background_video_title ) ) : __( 'Video Background', 'wp-rig' );

				ob_start();
				?>
					<video
						class="block-background video-background" autoplay muted loop playsinline preload="auto" aria-hidden="true"
						<?php
						echo $background_video_title ? ' title="' . esc_attr( $background_video_alt ) . '"' : '';
						echo $video_placeholder_img ? ' poster="' . esc_url( $video_placeholder_img ) . '"' : '';
						?>
					>
						<?php
						if ( $background_video_webm_url ) :
							?>
							<source src="<?php echo esc_url( $background_video_webm_url ); ?>" type="video/webm">
							<?php
						endif;

						if ( $background_video_url ) :
							?>
							<source src="<?php echo esc_url( $background_video_url ); ?>" type="video/mp4">
							<?php
						endif;
						?>
					</video>
				<?php
				$bg_video_html = ob_get_clean();
			endif;
		endif;

		// Get overlay type.
		$overlay_type            = get_field( 'overlay_type' );
		$custom_overlay_selector = get_field( 'custom_overlay_selector' );

		// Overlay settings are independent from background settings cause some blocks uses others elements as background.
		if ( $overlay_type ) :
			$has_show_overlay = $overlay_type ? ' has-overlay' : ''; // Show overlay class, if it exists.

			// Set Overlay class.
			if ( $has_show_overlay ) :
				$args['class'] .= esc_attr( $has_show_overlay );
			endif;

			if ( $overlay_type && empty( $custom_overlay_selector ) ) :
				ob_start();
				?>
					<div class="block-overlay" aria-hidden="true"></div>
				<?php
				$overlay_html = ob_get_clean();
			endif;
		endif;

		// Set the top or bottom shape divider css class.
		$top_divider          = wp_rig()->get_array_value( $args, 'top_divider' );
		$top_divider_style    = wp_rig()->get_array_value( $top_divider, 'top_divider_style', 'none' );
		$bottom_divider       = wp_rig()->get_array_value( $args, 'bottom_divider' );
		$bottom_divider_style = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_style', 'none' );

		if ( 'none' !== $top_divider_style || 'none' !== $bottom_divider_style ) :
			$args['class'] .= ' has-divider';
		endif;

		// Set the top shape divider markup.
		if ( 'none' !== $top_divider_style ) :
			$top_divider_svg = wp_rig()->get_shape_divider_svg( $top_divider_style );
			$top_divider_svg = strtr( $top_divider_svg, array( '{$block_id}' => $block_id . '-top' ) );

			ob_start();
			if ( $top_divider_svg ) :
				?>
				<div class="block-divider top-divider" aria-hidden="true">
					<?php echo $top_divider_svg; // phpcs:ignore ?>
				</div>
				<?php
			endif;
			$top_divider_html = ob_get_clean();
		endif;

		// Set the bottom shape divider markup.
		if ( 'none' !== $bottom_divider_style ) :
			$bottom_divider_svg = wp_rig()->get_shape_divider_svg( $bottom_divider_style );
			$bottom_divider_svg = strtr( $bottom_divider_svg, array( '{$block_id}' => $block_id . '-top' ) );

			ob_start();
			if ( $bottom_divider_svg ) :
				?>
				<div class="block-divider bottom-divider" aria-hidden="true">
					<?php echo $bottom_divider_svg; // phpcs:ignore ?>
				</div>
				<?php
			endif;
			$bottom_divider_html = ob_get_clean();
		endif;

		// Print our block container with options.
		printf(
			'<%s id="%s" class="%s">',
			esc_attr( $args['container'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['class'] )
		);

		// Print a background color markup inside the block container.
		if ( $bg_color_html ) :
			echo $bg_color_html; // phpcs:ignore
		endif;

		// Print a background gradient color markup inside the block container.
		if ( $bg_gradient_html ) :
			echo $bg_gradient_html; // phpcs:ignore
		endif;

		// Print a background image markup inside the block container.
		if ( $bg_image_html ) :
			echo $bg_image_html; // phpcs:ignore
		endif;

		// Print a background video markup inside the block container.
		if ( $bg_video_html ) :
			echo $bg_video_html; // phpcs:ignore
		endif;

		// Print a overlay markup inside the block container.
		if ( $overlay_html ) :
			echo $overlay_html; // phpcs:ignore
		endif;

		// Print a top divider markup inside the block container.
		if ( $top_divider_html ) :
			echo $top_divider_html; // phpcs:ignore
		endif;

		// Print a top divider markup inside the block container.
		if ( $bottom_divider_html ) :
			echo $bottom_divider_html; // phpcs:ignore
		endif;
	}

	/**
	 * Set the inner content container options.
	 *
	 * @access public
	 * @param array $args Some arguments.
	 * @return void
	 */
	public function display_content_container_options( $args = array() ) {

		// Setup defaults.
		$defaults = array(
			'container'         => 'div',
			'class'             => 'l-content-container',
			'content_container' => get_field( 'content_container' ),
			'layout'            => get_field( 'layout' ),
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Get some values from args.
		$container         = wp_rig()->get_array_value( $args, 'container' );
		$content_container = wp_rig()->get_array_value( $args, 'content_container' );
		$layout            = wp_rig()->get_array_value( $args, 'layout' );
		$content_class     = wp_rig()->get_array_value( $args, 'class' );
		$extra_classes     = wp_rig()->get_array_value( $args, 'extra_classes' );

		// Set the inner content container width css class.
		if ( $content_container ) {
			$content_class .= ' ' . $content_container;
		}

		// Add extra css classes.
		if ( $extra_classes ) {
			$content_class .= ' ' . $extra_classes;
		}

		// Print our block container with options.
		printf(
			'<%s class="%s">',
			esc_attr( $container ),
			esc_attr( $content_class )
		);
	}

	/**
	 * Set block custom styles.
	 *
	 * @access public
	 * @param  array  $block Some arguments.
	 * @param  string $additional_styles Push additional styles.
	 * @param  array  $args Array of values to set styles.
	 * @return void
	 */
	public function display_block_custom_styles( $block, $additional_styles = '', $args = array() ) {

		$defaults = array(
			'background_type'            => get_field( 'background_type' ),
			'background_image'           => get_field( 'background_image' ),
			'background_video'           => get_field( 'background_video' ),
			'background_object_fit'      => get_field( 'background_object_fit' ),
			'background_object_position' => get_field( 'background_object_position' ),
			'overlay_type'               => get_field( 'overlay_type' ),
			'overlay_color'              => get_field( 'overlay_color' ),
			'overlay_1st_color'          => get_field( 'overlay_1st_color' ),
			'overlay_1st_color_location' => get_field( 'overlay_1st_color_location' ),
			'overlay_1st_color_alpha'    => get_field( 'overlay_1st_color_alpha' ),
			'overlay_2nd_color'          => get_field( 'overlay_2nd_color' ),
			'overlay_2nd_color_location' => get_field( 'overlay_2nd_color_location' ),
			'overlay_2nd_color_alpha'    => get_field( 'overlay_2nd_color_alpha' ),
			'overlay_gradient_type'      => get_field( 'overlay_gradient_type' ),
			'overlay_gradient_angle'     => get_field( 'overlay_gradient_angle' ),
			'overlay_gradient_position'  => get_field( 'overlay_gradient_position' ),
			'overlay_opacity'            => get_field( 'overlay_opacity' ),
			'overlay_blend_mode'         => get_field( 'overlay_blend_mode' ),
			'custom_overlay_selector'    => get_field( 'custom_overlay_selector' ),
			'layout'                     => get_field( 'layout' ),
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Setting variables for custom styles.
		$the_block_id               = wp_rig()->get_block_id( $block );
		$background_type            = wp_rig()->get_array_value( $args, 'background_type' );
		$background_image           = wp_rig()->get_array_value( $args, 'background_image' );
		$background_video           = wp_rig()->get_array_value( $args, 'background_video' );
		$background_object_fit      = wp_rig()->get_array_value( $args, 'background_object_fit' );
		$background_object_position = wp_rig()->get_array_value( $args, 'background_object_position' );
		$overlay_type               = wp_rig()->get_array_value( $args, 'overlay_type' );
		$overlay_color              = wp_rig()->get_array_value( $args, 'overlay_color' );
		$overlay_1st_color          = wp_rig()->get_array_value( $args, 'overlay_1st_color' );
		$overlay_1st_color_location = wp_rig()->get_array_value( $args, 'overlay_1st_color_location' );
		$overlay_1st_color_alpha    = wp_rig()->get_array_value( $args, 'overlay_1st_color_alpha' );
		$overlay_2nd_color          = wp_rig()->get_array_value( $args, 'overlay_2nd_color' );
		$overlay_2nd_color_location = wp_rig()->get_array_value( $args, 'overlay_2nd_color_location' );
		$overlay_2nd_color_alpha    = wp_rig()->get_array_value( $args, 'overlay_2nd_color_alpha' );
		$overlay_gradient_type      = wp_rig()->get_array_value( $args, 'overlay_gradient_type' );
		$overlay_gradient_angle     = wp_rig()->get_array_value( $args, 'overlay_gradient_angle' );
		$overlay_gradient_position  = wp_rig()->get_array_value( $args, 'overlay_gradient_position' );
		$overlay_opacity            = wp_rig()->get_array_value( $args, 'overlay_opacity' );
		$overlay_blend_mode         = wp_rig()->get_array_value( $args, 'overlay_blend_mode' );
		$custom_overlay_selector    = wp_rig()->get_array_value( $args, 'custom_overlay_selector' );
		$layout                     = wp_rig()->get_array_value( $args, 'layout' );
		$content_width              = wp_rig()->get_array_value( $layout, 'content_width' );
		$content_width_sm           = wp_rig()->get_array_value( $layout, 'content_width_sm' );
		$height                     = wp_rig()->get_array_value( $layout, 'height', '' );
		$height_sm                  = wp_rig()->get_array_value( $layout, 'height_sm', '' );
		$height_unit                = wp_rig()->get_array_value( $layout, 'height_unit' );
		$height_unit_sm             = wp_rig()->get_array_value( $layout, 'height_unit_sm' );
		$min_height                 = wp_rig()->get_array_value( $layout, 'min_height' );
		$min_height_sm              = wp_rig()->get_array_value( $layout, 'min_height_sm' );
		$padding_top                = wp_rig()->get_array_value( $layout, 'padding_top' );
		$padding_top_sm             = wp_rig()->get_array_value( $layout, 'padding_top_sm' );
		$padding_bottom             = wp_rig()->get_array_value( $layout, 'padding_bottom' );
		$padding_bottom_sm          = wp_rig()->get_array_value( $layout, 'padding_bottom_sm' );
		$top_divider                = wp_rig()->get_array_value( $layout, 'top_divider' );
		$bottom_divider             = wp_rig()->get_array_value( $layout, 'bottom_divider' );

		// Mixing gutenberg native color settings.
		$style                      = wp_rig()->get_array_value( $block, 'style' );
		$style_color                = wp_rig()->get_array_value( $style, 'color' );
		$style_text_color           = wp_rig()->get_array_value( $style_color, 'text' );
		$style_background_color     = wp_rig()->get_array_value( $style_color, 'background' );
		$style_background_gradient  = wp_rig()->get_array_value( $style_color, 'gradient' );

		// Initiate block_custom_styles blank.
		$block_custom_styles = '';

		// Add custom styles only if there is any.
		if (
			$style_text_color
			|| $style_background_color
			|| $style_background_gradient
			|| $overlay_type
			|| $background_object_fit
			|| $background_object_position
			|| ( 'min-height' === $height && $min_height )
			|| $content_width
			|| $content_width_sm
			|| $padding_top
			|| $padding_top_sm
			|| $padding_bottom
			|| $padding_bottom_sm
			|| 'none' !== wp_rig()->get_array_value( $top_divider, 'top_divider_style', 'none' )
			|| 'none' !== wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_style', 'none' )
			|| $additional_styles
		) :

			// Output begins.
			ob_start();
			?>
			<style type="text/css">
				<?php
				if ( $additional_styles ) {
					echo $additional_styles; // phpcs:ignore
				}
				?>
				<?php
				if ( $style_text_color || ( 'min-height' === $height && $min_height ) || $padding_top || $padding_bottom ) :
					?>
					#<?php echo esc_attr( $the_block_id ); ?> {

						<?php
						if ( $style_text_color ) :
							?>
							color: <?php echo esc_attr( $style_text_color ); ?>;
							<?php
						endif;
						?>

						<?php
						if ( 'min-height' === $height && $min_height ) :
							?>
							min-height: <?php echo esc_attr( $min_height . $height_unit ); ?>;
							<?php
						endif;
						?>

						<?php
						if ( $padding_top ) :
							?>
							padding-top: <?php echo esc_attr( $padding_top ); ?>;
							<?php
						endif;
						?>

						<?php
						if ( $padding_bottom ) :
							?>
							padding-bottom: <?php echo esc_attr( $padding_bottom ); ?>;
							<?php
						endif;
						?>
					}
					<?php
				endif;

				if ( 'md-max:min-height' === $height_sm && $min_height_sm ) :
					?>
					@media screen and (max-width: 768px) {
						#<?php echo esc_attr( $the_block_id ); ?> {
							min-height: <?php echo esc_attr( $min_height_sm . $height_unit_sm ); ?>;
						}
					}
					<?php
				endif;

				if ( $padding_top_sm ) :
					?>
					@media screen and (max-width: 768px) {
						#<?php echo esc_attr( $the_block_id ); ?> {
							padding-top: <?php echo esc_attr( $padding_top_sm ); ?>;
						}
					}
					<?php
				endif;

				if ( $padding_bottom_sm ) :
					?>
					@media screen and (max-width: 768px) {
						#<?php echo esc_attr( $the_block_id ); ?> {
							padding-bottom: <?php echo esc_attr( $padding_bottom_sm ); ?>;
						}
					}
					<?php
				endif;

				if ( $content_width ) :
					?>
					@media screen and (min-width: 768px) {
						#<?php echo esc_attr( $the_block_id ); ?> > .l-content-container,
						#<?php echo esc_attr( $the_block_id ); ?> .col > .l-content-container {
							width: <?php echo esc_attr( $content_width ); ?>%;
							display: inline-block;
						}
					}
					<?php
				endif;

				if ( $content_width_sm ) :
					?>
					@media screen and (max-width: 768px) {
						#<?php echo esc_attr( $the_block_id ); ?> > .l-content-container,
						#<?php echo esc_attr( $the_block_id ); ?> .col > .l-content-container {
							width: <?php echo esc_attr( $content_width_sm ); ?>%;
							display: inline-block;
						}
					}
					<?php
				endif;

				if ( $overlay_type ) :
					$custom_overlay_selector = $custom_overlay_selector ? $custom_overlay_selector : '.block-overlay';
					?>
					#<?php echo esc_attr( $the_block_id ); ?>.has-overlay > <?php echo esc_attr( $custom_overlay_selector . ':after' ); ?> {
						content: '';
						position: absolute;
						top: 0;
						right: 0;
						display: block;
						height: 100%;
						width: 100%;
						z-index: -1;

						<?php
						if ( 'color' === $overlay_type && $overlay_color ) :
							?>
							background-color: <?php echo esc_attr( $overlay_color ); ?>;
							<?php
						endif;

						if ( 'gradient' === $overlay_type && $overlay_1st_color && $overlay_2nd_color ) :
							if ( 'linear' === $overlay_gradient_type ) :
								?>
								background-image: linear-gradient( <?php echo esc_attr( $overlay_gradient_angle ) . 'deg, ' . esc_attr( $this->hex2rgba( $overlay_1st_color, $overlay_1st_color_alpha / 100 ) ) . ' ' . esc_attr( $overlay_1st_color_location ) . '%, ' . esc_attr( $this->hex2rgba( $overlay_2nd_color, $overlay_2nd_color_alpha / 100 ) ) . ' ' . esc_attr( $overlay_2nd_color_location ) . '%'; ?>);
								<?php
							endif;
							if ( 'radial' === $overlay_gradient_type ) :
								?>
								background-image: radial-gradient( at <?php echo esc_attr( $overlay_gradient_position ) . ', ' . esc_attr( $this->hex2rgba( $overlay_1st_color, $overlay_1st_color_alpha / 100 ) ) . ' ' . esc_attr( $overlay_1st_color_location ) . '%, ' . esc_attr( $this->hex2rgba( $overlay_2nd_color, $overlay_2nd_color_alpha / 100 ) ) . ' ' . esc_attr( $overlay_2nd_color_location ) . '%'; ?>);
								<?php
							endif;
						endif;

						if ( $overlay_blend_mode ) :
							?>
							mix-blend-mode: <?php echo esc_attr( $overlay_blend_mode ); ?>;
							<?php
						endif;

						if ( $overlay_opacity ) :
							?>
							opacity: calc(<?php echo esc_attr( $overlay_opacity ); ?>/100);
							<?php
						endif;
						?>
					}
					<?php
				endif;

				if ( $style_background_color ) :
					?>
					#<?php echo esc_attr( $the_block_id ); ?> > .block-background {
						background-color: <?php echo esc_attr( $style_background_color ); ?>;
					}
					<?php
				endif;

				if ( $style_background_gradient ) :
					?>
					#<?php echo esc_attr( $the_block_id ); ?> > .gradient-background {
						background-image: <?php echo esc_attr( $style_background_gradient ); ?>
					}
					<?php
				endif;

				if ( $background_object_fit || $background_object_position ) :
					?>
					#<?php echo esc_attr( $the_block_id ); ?> > .video-background,
					#<?php echo esc_attr( $the_block_id ); ?> > .image-background,
					#<?php echo esc_attr( $the_block_id ); ?> > .image-background img {
						<?php
						if ( $background_object_fit ) :
							echo 'object-fit: ' . esc_attr( $background_object_fit ) . ';';
						endif;
						if ( $background_object_position ) :
							echo 'object-position: ' . esc_attr( $background_object_position ) . ';';
						endif;
						?>
					}
					<?php
				endif;

				$top_divider_style  = wp_rig()->get_array_value( $top_divider, 'top_divider_style', 'none' );
				$top_divider_front  = wp_rig()->get_array_value( $top_divider, 'top_divider_front' );
				$top_divider_border = wp_rig()->get_array_value( $top_divider, 'top_divider_border' );
				$top_divider_color  = wp_rig()->get_array_value( $top_divider, 'top_divider_color' );
				$top_divider_width  = wp_rig()->get_array_value( $top_divider, 'top_divider_width' );
				$top_divider_height = wp_rig()->get_array_value( $top_divider, 'top_divider_height' );
				$top_divider_flip_y = wp_rig()->get_array_value( $top_divider, 'top_divider_flip_y' );
				$top_divider_flip_x = wp_rig()->get_array_value( $top_divider, 'top_divider_flip_x' );

				if ( 'none' !== $top_divider_style ) :
					if ( $top_divider_front || $top_divider_border || $top_divider_color ) :
						?>
						#<?php echo esc_attr( $the_block_id ); ?> > .top-divider {
							<?php
							if ( $top_divider_front ) :
								?>
							z-index: 2;
								<?php
							endif;

							if ( $top_divider_border ) :
								?>
							border-top-style: solid;
							border-top-width: <?php echo esc_attr( $top_divider_border . 'vh' ); ?>;
								<?php
							endif;

							if ( $top_divider_color ) :
								?>
							border-top-color: <?php echo esc_attr( $top_divider_color ); ?>;
								<?php
							endif;
							?>
						}
						<?php
					endif;

					if ( $top_divider_color ) :
						?>
						#<?php echo esc_attr( $the_block_id ); ?> > .top-divider .shape-divider {
							fill: <?php echo esc_attr( $top_divider_color ); ?>;
							stop-color: <?php echo esc_attr( $top_divider_color ); ?>;
						}
						<?php
					endif;

					if ( $top_divider_width ) :
						?>
						@media screen and (min-width: 768px) {
							#<?php echo esc_attr( $the_block_id ); ?> > .top-divider svg {
								width: calc(<?php echo esc_attr( $top_divider_width . '%' ); ?> + 100px);
							}
						}
						<?php
					endif;

					if (
						$top_divider_height ||
						$top_divider_flip_y ||
						$top_divider_flip_x
					) :
						?>

						#<?php echo esc_attr( $the_block_id ); ?> > .top-divider svg {
							<?php
							if ( $top_divider_height ) :
								?>
								height: <?php echo esc_attr( $top_divider_height . 'px' ); ?>;
								<?php
							endif;

							if ( $top_divider_flip_y || $top_divider_flip_x ) :
								$top_divider_flip_y = $top_divider_flip_y ? ' rotateX(180deg)' : '';
								$top_divider_flip_x = $top_divider_flip_x ? ' rotateY(180deg)' : '';
								?>
								-webkit-transform: <?php echo esc_attr( 'translateX(-50%)' . $top_divider_flip_y . $top_divider_flip_x ); ?>;
								transform: <?php echo esc_attr( 'translateX(-50%)' . $top_divider_flip_y . $top_divider_flip_x ); ?>;
								<?php
							endif;
							?>
						}
						<?php
					endif;
				endif;

				$bottom_divider_style  = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_style', 'none' );
				$bottom_divider_front  = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_front' );
				$bottom_divider_border = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_border' );
				$bottom_divider_color  = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_color' );
				$bottom_divider_width  = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_width' );
				$bottom_divider_height = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_height' );
				$bottom_divider_flip_y = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_flip_y' );
				$bottom_divider_flip_x = wp_rig()->get_array_value( $bottom_divider, 'bottom_divider_flip_x' );

				if ( 'none' !== $bottom_divider_style ) :
					if ( $bottom_divider_front || $bottom_divider_border || $bottom_divider_color ) :
						?>
						#<?php echo esc_attr( $the_block_id ); ?> > .bottom-divider {
							<?php
							if ( $bottom_divider_front ) :
								?>
							z-index: 2;
								<?php
							endif;

							if ( $bottom_divider_border ) :
								?>
							border-bottom-style: solid;
							border-bottom-width: <?php echo esc_attr( $bottom_divider_border . 'vh' ); ?>;
								<?php
							endif;

							if ( $bottom_divider_color ) :
								?>
							border-bottom-color: <?php echo esc_attr( $bottom_divider_color ); ?>;
								<?php
							endif;
							?>
						}
						<?php
					endif;

					if ( $bottom_divider_color ) :
						?>
						#<?php echo esc_attr( $the_block_id ); ?> > .bottom-divider .shape-divider {
							fill: <?php echo esc_attr( $bottom_divider_color ); ?>;
							stop-color: <?php echo esc_attr( $bottom_divider_color ); ?>;
						}
						<?php
					endif;

					if ( $bottom_divider_width ) :
						?>
						@media screen and (min-width: 768px) {
							#<?php echo esc_attr( $the_block_id ); ?> > .bottom-divider svg {
								width: calc(<?php echo esc_attr( $bottom_divider_width . '%' ); ?> + 100px);
							}
						}
						<?php
					endif;

					if (
						$bottom_divider_height ||
						$bottom_divider_flip_y ||
						$bottom_divider_flip_x
					) :
						?>
						#<?php echo esc_attr( $the_block_id ); ?> > .bottom-divider svg {
							<?php
							if ( $bottom_divider_height ) :
								?>
								height: <?php echo esc_attr( $bottom_divider_height . 'px' ); ?>;
								<?php
							endif;

							if ( $bottom_divider_flip_y || $bottom_divider_flip_x ) :
								$bottom_divider_flip_y = $bottom_divider_flip_y ? ' rotateX(180deg)' : '';
								$bottom_divider_flip_x = $bottom_divider_flip_x ? ' rotateY(180deg)' : '';
								?>
								-webkit-transform: <?php echo esc_attr( 'translateX(-50%)' . $bottom_divider_flip_y . $bottom_divider_flip_x ); ?>;
								transform: <?php echo esc_attr( 'translateX(-50%)' . $bottom_divider_flip_y . $bottom_divider_flip_x ); ?>;
								<?php
							endif;
							?>
						}
						<?php
					endif;
				endif;
				?>
			</style>
			<?php
			$block_custom_styles = ob_get_clean();

		endif;

		if ( $block_custom_styles ) {
			echo $block_custom_styles; // phpcs:ignore
		}
	}

	/**
	 * Get available shape sividers.
	 *
	 * @access public
	 * @return array List of shape dividers.
	 */
	public function get_shape_dividers() {

		$shape_dividers = array(
			'tilt' => array(
				'name'    => 'Tilt',
				'svgcode' => '
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
						<path class="shape-divider" d="M0,6V0h1000v100L0,6z"></path>
					</svg>
				',
			),
			'tilt-opacity' => array(
				'name'    => 'Tilt Opacity',
				'svgcode' => '
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2600 131.1" preserveAspectRatio="none">
						<path class="shape-divider" d="M0 0L2600 0 2600 69.1 0 0z"></path>
						<path class="shape-divider" style="opacity:0.5" d="M0 0L2600 0 2600 69.1 0 69.1z"></path>
						<path class="shape-divider" style="opacity:0.25" d="M2600 0L0 0 0 130.1 2600 69.1z"></path>
					</svg>
				',
			),
			'triangle' => array(
				'name'    => 'Triangle',
				'svgcode' => '
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
						<path class="shape-divider" d="M500,98.9L0,6.1V0h1000v6.1L500,98.9z"></path>
					</svg>
				',
			),
			'triangle-scalene' => array(
				'name'    => 'Triangle Scalene',
				'svgcode' => '
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
						<path class="shape-divider" d="M738,99l262-93V0H0v5.6L738,99z"></path>
					</svg>
				',
			),
			'mountains' => array(
				'name'    => 'Mountains',
				'svgcode' => '
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
						<path class="shape-divider" opacity="0.33" d="M473,67.3c-203.9,88.3-263.1-34-320.3,0C66,119.1,0,59.7,0,59.7V0h1000v59.7 c0,0-62.1,26.1-94.9,29.3c-32.8,3.3-62.8-12.3-75.8-22.1C806,49.6,745.3,8.7,694.9,4.7S492.4,59,473,67.3z"></path>
						<path class="shape-divider" opacity="0.66" d="M734,67.3c-45.5,0-77.2-23.2-129.1-39.1c-28.6-8.7-150.3-10.1-254,39.1 s-91.7-34.4-149.2,0C115.7,118.3,0,39.8,0,39.8V0h1000v36.5c0,0-28.2-18.5-92.1-18.5C810.2,18.1,775.7,67.3,734,67.3z"></path>
						<path class="shape-divider" d="M766.1,28.9c-200-57.5-266,65.5-395.1,19.5C242,1.8,242,5.4,184.8,20.6C128,35.8,132.3,44.9,89.9,52.5C28.6,63.7,0,0,0,0 h1000c0,0-9.9,40.9-83.6,48.1S829.6,47,766.1,28.9z"></path>
					</svg>
				',
			),
			'zig-zag' => array(
				'name'    => 'Zig Zag',
				'svgcode' => '
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1800 5.8" preserveAspectRatio="none">
						<path class="shape-divider" d="M5.4.4l5.4 5.3L16.5.4l5.4 5.3L27.5.4 33 5.7 38.6.4l5.5 5.4h.1L49.9.4l5.4 5.3L60.9.4l5.5 5.3L72 .4l5.5 5.3L83.1.4l5.4 5.3L94.1.4l5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3L161 .4l5.4 5.3L172 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3L261 .4l5.4 5.3L272 .4l5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3L361 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.6-5.4 5.5 5.3L461 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1L550 .4l5.4 5.3L561 .4l5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2L650 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2L750 .4l5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2L850 .4l5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.4h.2l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.7-5.4 5.4 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.5 5.4h.1l5.6-5.4 5.5 5.3 5.6-5.3 5.5 5.3 5.6-5.3 5.4 5.3 5.7-5.3 5.4 5.3 5.6-5.3 5.5 5.4V0H-.2v5.8z"></path>
					</svg>
				',
			),
		);

		return $shape_dividers;
	}

	/**
	 * Populate Shape Dividers choices.
	 *
	 * @access public
	 * @param array $field The field data.
	 * @return array The field with new choices.
	 */
	public function acf_load_shape_dividers_choices( $field ) {

		// Reset choices.
		$field['choices'] = array(
			'none' => 'None',
		);

		// Get the array value from registered shape dividers.
		$choices = $this->get_shape_dividers();

		// Loop through array and add to field 'choices'.
		if ( is_array( $choices ) ) {

			foreach ( $choices as $key => $choice ) {
				$field['choices'][ $key ] = $choice['name'];
			}
		}

		// Return the field.
		return $field;
	}

	/**
	 *
	 * Get the shape divider svg code.
	 *
	 * @access public
	 * @param string $divider_style key value from array.
	 * @return string value of SVG code.
	 */
	public function get_shape_divider_svg( $divider_style ) {

		if ( empty( $divider_style ) || 'none' == $divider_style ) {
			return;
		}

		// Get the array value from registered shape dividers.
		$shape_dividers = $this->get_shape_dividers();

		$shape_divider_svg = '';

		if ( ! empty( $shape_dividers ) && array_key_exists( $divider_style, $shape_dividers ) ) {
			$shape_divider_svg = $shape_dividers[ $divider_style ]['svgcode'];
		}

		return $shape_divider_svg;
	}

	/**
	 *
	 * Get the value from array.
	 *
	 * @access public
	 * @param array  $array array value.
	 * @param string $key key value from array.
	 * @param string $default value when is false.
	 * @return string value from array.
	 */
	public function get_array_value( $array = array(), $key = '', $default = false ) {

		return isset( $array[ $key ] ) ? $array[ $key ] : $default;
	}

	/**
	 *
	 * Get the gutenberg colors formatted for use with Iris, Automattic's color picker.
	 *
	 * @access public
	 * @return array Gutenberg color array.
	 */
	public function get_the_colors() {

		// Get the colors.
		$color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );

		// Bail if there aren't any colors found.
		if ( ! $color_palette ) {
			return;
		}

		// Output begins.
		ob_start();

		// Output the names in a string.
		echo '[';
		foreach ( $color_palette as $color ) {
			echo "'" . esc_attr( $color['color'] ) . "', ";
		}
		echo ']';

		return ob_get_clean();
	}

	/**
	 *
	 * Add gutenberg colors into Iris.
	 *
	 * @access public
	 * @return void
	 */
	public function register_acf_color_palette() {

		$color_palette = wp_rig()->get_the_colors();

		if ( ! $color_palette ) {
			return;
		}
		?>
		<script type="text/javascript">
			(function( $ ) {
				acf.add_filter( 'color_picker_args', function( args, $field ){
					// add the hexadecimal codes here for the colors you want to appear as swatches
					args.palettes = <?php echo $color_palette; // phpcs:ignore ?>
					// return colors
					return args;
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 *
	 * Convert #HEX color value too RGBA.
	 *
	 * @access public
	 * @param string $hex #HEX color value.
	 * @param string $opacity percentage of opacity in decimal.
	 * @return string rgba color value.
	 */
	public function hex2rgba( $hex, $opacity = '1' ) {

		if ( empty( $hex ) ) {
			return;
		}

		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) === 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$rgba = 'rgba( ' . $r . ', ' . $g . ', ' . $b . ', ' . $opacity . ')';

		return $rgba;
	}

	/**
	 * Get headline with heading tag level .
	 *
	 * @access public
	 * @param string $headline acf value.
	 * @param string $class headline css class.
	 * @param string $level acf value.
	 * @param string $size acf value.
	 * @return void
	 */
	public function get_headline( $headline, $class, $level = 'h2', $size = null ) {

		// Bail if headline is empty.
		if ( empty( $headline ) ) {
			return;
		}

		if ( ! empty( $size ) ) {
			$class .= ' ' . $size;
		}

		$level = ! empty( $level ) ? $level : 'h2';

		echo sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>',
			esc_attr( $level ),
			esc_attr( $class ),
			wp_kses_post( $headline )
		);
	}

	/**
	 * Get tagline.
	 *
	 * @access public
	 * @param string $tagline acf value.
	 * @param string $class tagline css class.
	 * @return void
	 */
	public function get_tagline( $tagline, $class ) {

		// Bail if tagline is empty.
		if ( empty( $tagline ) ) {
			return;
		}

		echo sprintf( '<small class="%2$s">%1$s</small>', wp_kses_post( $tagline ), esc_attr( $class ) );
	}

	/**
	 * Get caption.
	 *
	 * @access public
	 * @param string $caption acf value.
	 * @param bool   $rich_content Whether caption enable rich content.
	 * @param string $caption_wysiwyg acf value.
	 * @param string $class caption css class.
	 * @param string $size caption css class.
	 * @return void
	 */
	public function get_caption( $caption, $rich_content, $caption_wysiwyg, $class, $size = null ) {

		// Bail if caption is empty.
		if ( empty( $caption ) && empty( $caption_wysiwyg ) ) {
			return;
		}

		if ( $size ) {
			$class .= ' ' . $size;
		}

		$caption = $rich_content ? $caption_wysiwyg : $caption;

		echo sprintf( '<div class="%2$s">%1$s</div>', wp_kses_post( $caption ), esc_attr( $class ) );
	}

	/**
	 * Get Button.
	 *
	 * @access public
	 * @param array  $link button values.
	 * @param string $style button style css class.
	 * @param string $size button size css class.
	 * @param array  $icon button icon class and position.
	 * @return void
	 */
	public function get_button( $link, $style, $size, $icon = array() ) {

		// Bail if Button is empty.
		if ( empty( $link ) ) {
			return;
		}

		$button_class   = 'ui-btn';
		$link['target'] = $link['target'] ? $link['target'] : '_self';
		$icon_class     = '';
		$icon_position  = '';
		$left_icon      = '';
		$right_icon     = '';

		if ( ! empty( $size ) ) :
			$button_class .= ' ' . $size;
		endif;

		if ( ! empty( $style ) ) :
			$button_class .= ' ' . $style;
		endif;

		if ( ! empty( $icon ) && $icon['icon_class'] ) :
			$icon_class    = $icon['icon_class'];
			$icon_position = $icon['icon_position'];

			if ( 'left' === $icon_position ) {
				$left_icon = sprintf( '<i class="%1$s mr-2"></i> ', esc_attr( $icon_class ) );
			} else {
				$right_icon = sprintf( ' <i class="%1$s ml-2"></i>', esc_attr( $icon_class ) );
			}
		endif;

		echo sprintf(
			'<a class="%1$s" href="%2$s" title="Button link for %3$s" target="%4$s">%6$s%5$s%7$s</a>',
			esc_attr( $button_class ),
			esc_url( $link['url'] ),
			esc_attr( $link['title'] ),
			esc_attr( $link['target'] ),
			esc_html( $link['title'] ),
			esc_html( $left_icon ),
			esc_html( $right_icon )
		);
	}

	/**
	 * Get Link.
	 *
	 * @access public
	 * @param array  $link link values.
	 * @param string $class link css class value.
	 * @return void
	 */
	public function get_link( $link, $class = '' ) {

		// Bail if Link is empty.
		if ( empty( $link ) ) {
			return;
		}

		$default_class  = 'item-link';
		$link['target'] = $link['target'] ? $link['target'] : '_self';

		if ( ! empty( $class ) ) :
			$class = $default_class . ' ' . $class;
		endif;

		echo sprintf(
			'<a class="%1$s" href="%2$s" title="Link for %3$s" target="%4$s">%5$s</a>',
			esc_attr( $class ),
			esc_url( $link['url'] ),
			esc_attr( $link['title'] ),
			esc_attr( $link['target'] ),
			esc_html( $link['title'] )
		);
	}

	/**
	 *
	 * Boolean to string.
	 *
	 * @param boolean $b boolean value.
	 * @return string boolean value as string.
	 */
	public function bool( $b ) {
		return $b ? 'true' : 'false';
	}

	/**
	 *
	 * Array to string.
	 *
	 * This function is a workaround to ACF Select fields that sometimes output an array instead of a string.
	 *
	 * @param array $array Array value.
	 * @return string value as string.
	 */
	public function arr2str( $array ) {

		if ( empty( $array ) ) {
			return;
		}

		if ( is_array( $array ) ) {
			$string = $array[0];
		} else {
			$string = $array;
		}

		return $string;
	}

	/**
	 * Validate if url exists.
	 *
	 * @access public
	 * @param string $url url string.
	 * @return string boolean value as string.
	 */
	public function does_url_exists( $url ) {
		$headers = get_headers( $url );
		return stripos( $headers[0], '200 OK' ) ? true : false;
	}

	/**
	 * Font Awesome Kit Setup
	 *
	 * @access public
	 * @return void
	 */
	public function fa_custom_setup_kit() {

		// If the AMP plugin is active, return early.
		if ( wp_rig()->is_amp() ) {
			return;
		}

		$font_awesome_id = get_option( 'options_font_awesome_id' );

		if ( empty( $font_awesome_id ) ) {
			return;
		}

		wp_enqueue_script( 'font-awesome-kit', 'https://kit.fontawesome.com/' . $font_awesome_id . '.js', array(), 'latest', false );
		wp_script_add_data( 'font-awesome-kit', 'defer', true );
	}

	/**
	 * Remove Gutenberg CSS.
	 *
	 * @access public
	 * @return void
	 */
	public function remove_gutenberg_css() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
	}

	/**
	 * Enqueue Carousel script.
	 */
	public function enqueue_carousel_scripts() {
		// If the AMP plugin is active, return early.
		if ( wp_rig()->is_amp() ) {
			return;
		}

		// Enqueue the slick css.
		$css_slick_path = '/assets/libs/slick/slick.min.css';
		if ( file_exists( get_theme_file_path( $css_slick_path ) ) ) {
			wp_enqueue_style(
				'wp-slick',
				get_template_directory_uri() . $css_slick_path,
				array(),
				filemtime( get_theme_file_path( $css_slick_path ) )
			);
		}

		// Enqueue the slick theme css.
		$css_slick_theme_path = '/assets/libs/slick/slick-theme.min.css';
		if ( file_exists( get_theme_file_path( $css_slick_theme_path ) ) ) {
			wp_enqueue_style(
				'wp-slick-theme',
				get_template_directory_uri() . $css_slick_theme_path,
				array(),
				filemtime( get_theme_file_path( $css_slick_theme_path ) )
			);
		}

		// Enqueue the slick script.
		$js_slick_path = '/assets/libs/slick/slick.min.js';
		if ( file_exists( get_theme_file_path( $js_slick_path ) ) ) {
			wp_enqueue_script(
				'wp-slick',
				get_theme_file_uri( $js_slick_path ),
				array( 'jquery' ),
				wp_rig()->get_asset_version( get_theme_file_path( $js_slick_path ) ),
				true
			);
		}

		wp_script_add_data( 'wp-slick', 'defer', false );
	}

	/**
	 * Enqueue admin styles.
	 */
	public function acf_admin_style() {

		// Enqueue the admin acf css.
		$wp_admin_acf_css_uri  = get_template_directory_uri() . '/assets/css/modules/m-admin-acf.min.css';
		$wp_admin_acf_path = get_theme_file_path( '/assets/css/modules/m-admin-acf.min.css' );

		if ( file_exists( $wp_admin_acf_path ) ) {
			wp_enqueue_style(
				'wp-admin-acf',
				$wp_admin_acf_css_uri,
				array(),
				filemtime( $wp_admin_acf_path )
			);
		}
	}

	/**
	 * Enqueue Lightbox script.
	 */
	public function enqueue_lightbox_scripts() {
		// If the AMP plugin is active, return early.
		if ( wp_rig()->is_amp() ) {
			return;
		}

		// Enqueue the slick css.
		$css_lightbox_path = 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css';
		if ( $this->does_url_exists( $css_lightbox_path ) ) {
			wp_enqueue_style(
				'wp-lightbox',
				$css_lightbox_path,
				array(),
				'v2.0.6'
			);
		}

		// Enqueue the slick script.
		$js_lightbox_path = 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js';
		if ( $this->does_url_exists( $js_lightbox_path ) ) {
			wp_enqueue_script(
				'wp-lightbox',
				$js_lightbox_path,
				array(),
				'v2.0.6',
				true
			);
		}

		wp_script_add_data( 'wp-lightbox', 'defer', false );
	}

	/**
	 * Enqueue Tabby script.
	 */
	public function enqueue_tabby_scripts() {
		// If the AMP plugin is active, return early.
		if ( wp_rig()->is_amp() ) {
			return;
		}

		// Enqueue the slick css.
		$wp_tabby_css_uri  = get_template_directory_uri() . '/assets/css/modules/m-tabby.min.css';
		$wp_tabby_css_path = get_theme_file_path( '/assets/css/modules/m-tabby.min.css' );

		if ( file_exists( $wp_tabby_css_path ) ) {
			wp_enqueue_style(
				'wp-tabby',
				$wp_tabby_css_uri,
				array(),
				filemtime( $wp_tabby_css_path )
			);
		}

		// Enqueue the slick script.
		$js_tabby_path = 'https://cdn.jsdelivr.net/gh/cferdinandi/tabby@12.0.3/dist/js/tabby.min.js';
		if ( $this->does_url_exists( $js_tabby_path ) ) {
			wp_enqueue_script(
				'wp-tabby',
				$js_tabby_path,
				array( 'wp-slick' ),
				'v12.0.3',
				true
			);
		}

		wp_script_add_data( 'wp-lightbox', 'defer', false );
	}
}
