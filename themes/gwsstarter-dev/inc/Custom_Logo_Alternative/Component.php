<?php
/**
 * WP_Rig\WP_Rig\Custom_Logo_Alternative\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Logo_Alternative;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Customize_Manager;
use WP_Customize_Image_Control;
use function add_action;

/**
 * Class for adding custom header support.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_logo_alternative';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register_custom_logo_alternative' ) );
	}

	/**
	 * Adds a setting and control for logo alternative the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_custom_logo_alternative( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_section(
			'custom_logo_alternative',
			array(
				'title'    => __( 'Alternative Logos', 'wp-rig' ),
				'priority' => 90, // After Site Identity.
			)
		);

		$wp_customize->add_setting(
			'logo_transparent',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'logo_transparent',
				array(
					'label'      => __( 'Upload a logo for transparent header', 'wp-rig' ),
					'section'    => 'custom_logo_alternative',
					'settings'   => 'logo_transparent',
				)
			)
		);

		$wp_customize->add_setting(
			'logo_mobile',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'logo_mobile',
				array(
					'label'      => __( 'Upload a mobile version logo', 'wp-rig' ),
					'section'    => 'custom_logo_alternative',
					'settings'   => 'logo_mobile',
				)
			)
		);

		$wp_customize->add_setting(
			'logo_mobile_transparent',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'logo_mobile_transparent',
				array(
					'label'      => __( 'Upload a mobile version logo for transparent header', 'wp-rig' ),
					'section'    => 'custom_logo_alternative',
					'settings'   => 'logo_mobile_transparent',
				)
			)
		);
	}
}
