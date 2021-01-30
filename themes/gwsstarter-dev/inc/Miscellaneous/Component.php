<?php
/**
 * WP_Rig\WP_Rig\Miscellaneous\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Miscellaneous;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function add_filter;
use function add_action;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for Miscellaneous.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'miscellaneous';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_global_script' ) );
		add_filter( 'get_the_archive_title', array( $this, 'archive_title_remove_prefix' ) );
		add_action( 'init', array( $this, 'wp_gutenberg_register_files' ) );
	}

	/**
	 * Enqueues a global js scripts.
	 */
	public function action_enqueue_global_script() {

		// If the AMP plugin is active, return early.
		if ( wp_rig()->is_amp() ) {
			return;
		}

		// Enqueue the navigation script.
		wp_enqueue_script(
			'wp-global',
			get_theme_file_uri( '/assets/js/global.min.js' ),
			array(),
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/js/global.min.js' ) ),
			false
		);
		wp_script_add_data( 'wp-global', 'defer', true );
		wp_script_add_data( 'wp-global', 'precache', true );
	}

	/**
	 * Removing `Archive: %` text from the_archive_title()
	 *
	 * @param string $title where prefix is going to be remove.
	 */
	public function archive_title_remove_prefix( $title ) {

		if ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		}

		return $title;
	}

	/**
	 * Gutenberg register files.
	 */
	public function wp_gutenberg_register_files() {

		// Script file.
		wp_register_script(
			'wp-block-script',
			get_theme_file_uri( '/assets/js/block-script.min.js' ),
			array( 'wp-blocks', 'wp-edit-post' ),
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/js/block-script.min.js' ) ),
			false
		);

		// Register block editor script.
		register_block_type(
			'wp/block-files',
			array(
				'editor_script' => 'wp-block-script',
			)
		);
	}
}
