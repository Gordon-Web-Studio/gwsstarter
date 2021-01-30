<?php
/**
 * WP_Rig\WP_Rig\Custom_Header\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Header;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Customize_Manager;
use WP_Customize_Image_Control;
use function add_action;
use function add_theme_support;
use function apply_filters;
use function get_header_textcolor;
use function get_theme_support;
use function display_header_text;
use function esc_attr;

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
		return 'custom_header';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_header_support' ) );
		add_action( 'customize_register', array( $this, 'action_customize_register_custom_header_options' ) );
	}

	/**
	 * Adds support for the Custom Logo feature.
	 */
	public function action_add_custom_header_support() {
		add_theme_support(
			'custom-header',
			apply_filters(
				'wp_rig_custom_header_args',
				array(
					'default-image'      => '',
					'default-text-color' => '000000',
					'width'              => 1600,
					'height'             => 250,
					'flex-height'        => true,
					'wp-head-callback'   => array( $this, 'wp_head_callback' ),
				)
			)
		);
	}

	/**
	 * Outputs extra styles for the custom header, if necessary.
	 */
	public function wp_head_callback() {
		$header_text_color = get_header_textcolor();

		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		if ( ! display_header_text() ) {
			echo '<style type="text/css">.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }</style>';
			return;
		}

		echo '<style type="text/css">.site-title a, .site-description { color: #' . esc_attr( $header_text_color ) . '; }</style>';
	}

	/**
	 * Adds a setting and control on the the Customizer for Login and Schedule Demo link on header.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_custom_header_options( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_section(
			'custom_header_options',
			array(
				'title'    => esc_html__( 'Header Options', 'wp-rig' ),
				'priority' => 90, // After Site Identity.
			)
		);

		$wp_customize->add_setting(
			'header_sticky',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'header_sticky',
			array(
				'settings' => 'header_sticky',
				'label'    => esc_html__( 'Enable Sticky Header on Complete Website', 'wp-rig' ),
				'section'  => 'custom_header_options',
				'type'     => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'login_button_text',
			array(
				'default'    => esc_html__( 'Login', 'wp-rig' ),
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'login_button_text',
			array(
				'label'      => esc_html__( 'Login Button Text', 'wp-rig' ),
				'section'    => 'custom_header_options',
				'settings'   => 'login_button_text',
			)
		);

		$wp_customize->add_setting(
			'login_button_url',
			array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_url',
				'type'              => 'option',
			)
		);

		$wp_customize->add_control(
			'login_button_url',
			array(
				'type'        => 'url',
				'section'     => 'custom_header_options',
				'label'       => esc_html__( 'Login Button URL', 'wp-rig' ),
				'description' => esc_html__( 'Add a custom url destination.', 'wp-rig' ),
				'input_attrs' => array(
					'placeholder' => esc_html__( 'http://', 'wp-rig' ),
				),
			)
		);

		$wp_customize->add_setting(
			'schedule_demo_button_text',
			array(
				'default'    => esc_html__( 'Schedule a Demo', 'wp-rig' ),
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'schedule_demo_button_text',
			array(
				'label'      => esc_html__( 'Schedule a Demo Button Text', 'wp-rig' ),
				'section'    => 'custom_header_options',
				'settings'   => 'schedule_demo_button_text',
			)
		);

		$wp_customize->add_setting(
			'schedule_demo_button_url',
			array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_url',
				'type'              => 'option',
			)
		);

		$wp_customize->add_control(
			'schedule_demo_button_url',
			array(
				'type'        => 'url',
				'section'     => 'custom_header_options',
				'label'       => esc_html__( 'Schedule a Demo Button URL', 'wp-rig' ),
				'description' => esc_html__( 'Add a custom url destination.', 'wp-rig' ),
				'input_attrs' => array(
					'placeholder' => esc_html__( 'http://', 'wp-rig' ),
				),
			)
		);

		/**
		 * Sanitize url before it's printed.
		 *
		 * @param string $url URL string.
		 */
		function sanitize_url( $url ) {
			return esc_url_raw( $url );
		}
	}
}
