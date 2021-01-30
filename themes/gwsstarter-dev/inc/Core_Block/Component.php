<?php
/**
 * WP_Rig\WP_Rig\Core_Block\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Core_Block;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function add_action;
use function wp_enqueue_script;
use function get_theme_file_path;

/**
 * Class for Core_Block.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'core_block';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_core_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_core_filters' ) );
	}

	/**
	 * Register core_blocks {
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_core_blocks() {

		$js_core_blocks_path = '/assets/js/core-blocks.min.js';
		if ( file_exists( get_theme_file_path( $js_core_blocks_path ) ) ) {
			wp_enqueue_script(
				'wp-core-blocks',
				get_theme_file_uri( $js_core_blocks_path ),
				array( 'wp-blocks', 'wp-dom' ),
				wp_rig()->get_asset_version( get_theme_file_path( $js_core_blocks_path ) ),
				true
			);
		}
	}

	/**
	 * Register some gutenberg filters
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_core_filters() {

		$js_core_blocks_path = '/assets/js/core-filters.min.js';
		if ( file_exists( get_theme_file_path( $js_core_blocks_path ) ) ) {
			wp_enqueue_script(
				'wp-core-filters',
				get_theme_file_uri( $js_core_blocks_path ),
				array( 'wp-edit-post', 'wp-dom' ),
				wp_rig()->get_asset_version( get_theme_file_path( $js_core_blocks_path ) ),
				true
			);
		}
	}
}
