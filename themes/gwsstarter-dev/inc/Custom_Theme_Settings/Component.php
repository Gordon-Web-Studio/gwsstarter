<?php
/**
 * WP_Rig\WP_Rig\Custom_Theme_Settings\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Theme_Settings;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;
use function add_filter;

/**
 * Class for Custom_Theme_Settings.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_theme_settings';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

		add_action( 'acf/init', array( $this, 'register_theme_settings' ) );
	}

	/**
	 * Register a Theme Settings page.
	 *
	 * @access public
	 * @return void
	 */
	public function register_theme_settings() {

		if ( function_exists( 'acf_add_options_page' ) ) {

			$theme_settings = acf_add_options_page(
				array(
					'page_title' => 'Theme Settings',
					'menu_title' => 'Theme Settings',
					'menu_slug'  => 'theme-settings',
					'capability' => 'edit_posts',
					'redirect'   => true,
				)
			);

			$helpers_fields = acf_add_options_page(
				array(
					'page_title'  => 'Helpers Fields',
					'menu_title'  => 'Helpers',
					'parent_slug' => $theme_settings['menu_slug'],
				)
			);
		}
	}
}
