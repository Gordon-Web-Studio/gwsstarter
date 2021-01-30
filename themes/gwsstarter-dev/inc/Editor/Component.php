<?php
/**
 * WP_Rig\WP_Rig\Editor\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Editor;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'editor';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_support' ) );
	}

	/**
	 * Adds support for various editor features.
	 */
	public function action_add_editor_support() {

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for wide-aligned images.
		add_theme_support( 'align-wide' );

		// Remove core block patterns.
		remove_theme_support( 'core-block-patterns' );

		/**
		 * Add support for color palettes.
		 *
		 * To preserve color behavior across themes, use these naming conventions:
		 * - Use primary and secondary color for main variations.
		 * - Use `theme-[color-name]` naming standard for standard colors (red, blue, etc).
		 * - Use `custom-[color-name]` for non-standard colors.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-colors' );
		 */
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary', 'wp-rig' ),
					'slug'  => 'theme-primary',
					'color' => '#00d7c8',
				),
				array(
					'name'  => __( 'Primary Dark', 'wp-rig' ),
					'slug'  => 'theme-primary-dark',
					'color' => '#00a79b',
				),
				array(
					'name'  => __( 'Primary Light', 'wp-rig' ),
					'slug'  => 'theme-primary-light',
					'color' => '#73f3ea',
				),
				array(
					'name'  => __( 'Secondary', 'wp-rig' ),
					'slug'  => 'theme-secondary',
					'color' => '#313146',
				),
				array(
					'name'  => __( 'Secondary Dark', 'wp-rig' ),
					'slug'  => 'theme-secondary-dark',
					'color' => '#232338',
				),
				array(
					'name'  => __( 'Secondary Light', 'wp-rig' ),
					'slug'  => 'theme-secondary-light',
					'color' => '#3e3e55',
				),
				array(
					'name'  => __( 'Tertiary', 'wp-rig' ),
					'slug'  => 'theme-tertiary',
					'color' => '#9c27b0',
				),
				array(
					'name'  => __( 'Tertiary Dark', 'wp-rig' ),
					'slug'  => 'theme-tertiary-dark',
					'color' => '#7e1d8f',
				),
				array(
					'name'  => __( 'Tertiary Light', 'wp-rig' ),
					'slug'  => 'theme-tertiary-light',
					'color' => '#c85ddb',
				),
				array(
					'name'  => __( 'Black', 'wp-rig' ),
					'slug'  => 'theme-black',
					'color' => '#000000',
				),
				array(
					'name'  => __( 'Gray', 'wp-rig' ),
					'slug'  => 'theme-gray',
					'color' => '#444444',
				),
				array(
					'name'  => __( 'White', 'wp-rig' ),
					'slug'  => 'theme-white',
					'color' => '#ffffff',
				),
			)
		);

		/*
		 * Add support custom font sizes.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-font-sizes' );
		 */
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'wp-rig' ),
					'shortName' => __( 'S', 'wp-rig' ),
					'size'      => 12,
					'slug'      => 'sm',
				),
				array(
					'name'      => __( 'Base', 'wp-rig' ),
					'shortName' => __( 'B', 'wp-rig' ),
					'size'      => 16,
					'slug'      => 'base',
				),
				array(
					'name'      => __( 'Large', 'wp-rig' ),
					'shortName' => __( 'L', 'wp-rig' ),
					'size'      => 20,
					'slug'      => 'lg',
				),
				array(
					'name'      => __( 'H4', 'wp-rig' ),
					'shortName' => __( 'H4', 'wp-rig' ),
					'size'      => 30,
					'slug'      => 'h4',
				),
				array(
					'name'      => __( 'H3', 'wp-rig' ),
					'shortName' => __( 'H3', 'wp-rig' ),
					'size'      => 40,
					'slug'      => 'h3',
				),
				array(
					'name'      => __( 'H2', 'wp-rig' ),
					'shortName' => __( 'H2', 'wp-rig' ),
					'size'      => 60,
					'slug'      => 'h2',
				),
				array(
					'name'      => __( 'H1', 'wp-rig' ),
					'shortName' => __( 'H1', 'wp-rig' ),
					'size'      => 80,
					'slug'      => 'h1',
				),
			)
		);
	}
}
