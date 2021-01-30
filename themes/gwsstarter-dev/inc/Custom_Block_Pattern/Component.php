<?php
/**
 * WP_Rig\WP_Rig\Custom_Block_Pattern\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Block_Pattern;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;

/**
 * Class for Custom_Block_Pattern.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_block_pattern';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'init', array( $this, 'register_block_patterns' ) );
		add_action( 'init', array( $this, 'register_block_pattern_categories' ) );
	}

	/**
	 * Register block pattern values.
	 *
	 * @access public
	 * @return void
	 */
	public function register_block_patterns() {

		if ( class_exists( 'WP_Block_Patterns_Registry' ) ) {

			$bg_image_placeholder  = get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg';
			$logo_placeholder      = get_template_directory_uri() . '/assets/images/logo.svg';
			$logo_gray_placeholder = get_template_directory_uri() . '/assets/images/logo-grayscale.svg';

			register_block_pattern(
				'wprig/hero-with-media-content',
				array(
					'title'       => __( 'Hero Demo', 'wp-rig' ),
					'description' => _x( 'A Hero Demo.', 'Block Pattern Description for Hero Demo.', 'wp-rig' ),
					'content'     => "",
					'categories'  => array( 'hero', 'media-content' ),
				)
			);
		}
	}

	/**
	 * Register block pattern categories.
	 *
	 * @access public
	 * @return void
	 */
	public function register_block_pattern_categories() {

		if ( class_exists( 'WP_Block_Patterns_Registry' ) ) {

			register_block_pattern_category(
				'hero',
				array( 'label' => _x( 'Hero', 'Block pattern category', 'wp-rig' ) )
			);

			register_block_pattern_category(
				'slideshow',
				array( 'label' => _x( 'Slideshow', 'Block pattern category', 'wp-rig' ) )
			);

			register_block_pattern_category(
				'media-content',
				array( 'label' => _x( 'Media Content', 'Block pattern category', 'wp-rig' ) )
			);

			register_block_pattern_category(
				'grid',
				array( 'label' => _x( 'Grid', 'Block pattern category', 'wp-rig' ) )
			);

			register_block_pattern_category(
				'layout',
				array( 'label' => _x( 'Layout', 'Block pattern category', 'wp-rig' ) )
			);

			register_block_pattern_category(
				'content',
				array( 'label' => _x( 'Content', 'Block pattern category', 'wp-rig' ) )
			);

			register_block_pattern_category(
				'video',
				array( 'label' => _x( 'Video', 'Block pattern category', 'wp-rig' ) )
			);
		}
	}
}
