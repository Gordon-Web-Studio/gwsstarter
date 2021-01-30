<?php
/**
 * WP_Rig\WP_Rig\Custom_Block_Category\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Block_Category;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function add_filter;
use function add_action;
use function wp_enqueue_script;

/**
 * Class for Custom_Block_Category.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_block_category';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'block_categories', array( $this, 'filter_acf_block_categories' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'svg_custom_icon_scripts' ) );
	}

	/**
	 * Filters the `block categories` values.
	 *
	 * @param array  $categories List of existing block categories.
	 * @param object $post The post object.
	 * @return array The filtered array of block categories.
	 */
	public function filter_acf_block_categories( $categories, $post ) {
		/*
		 * Optional.
		// if ( $post->post_type !== 'post' ) {
		//	 return $categories;
		// }
		*/

		array_unshift(
			$categories,
			array(
				'slug' => 'custom-blocks',
				'title' => __( 'Custom Blocks', 'wp-rig' ),
				// 'icon'  => 'layout'
			)
		);
		return $categories;
	}

	/**
	 * Enqueue frontend and editor JavaScript and CSS
	 */
	public function svg_custom_icon_scripts() {

		// Enqueue block JS.
		wp_enqueue_script(
			'svg-icons',
			get_theme_file_uri( '/assets/js/svg-icons.min.js' ),
			array( 'wp-blocks' ),
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/js/svg-icons.min.js' ) ),
			true
		);
	}
}
