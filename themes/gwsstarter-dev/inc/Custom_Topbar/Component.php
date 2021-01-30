<?php
/**
 * WP_Rig\WP_Rig\Custom_Topbar\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Topbar;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;
use WP_Customize_Manager;
use WP_Customize_Image_Control;
use function add_action;

/**
 * Class for adding custom header support.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_topbar';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register_custom_topbar' ) );
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
			'set_topbar' => array( $this, 'set_topbar' ),
		);
	}

	/**
	 * Adds a setting and control for topbar in the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_custom_topbar( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_section(
			'custom_topbar',
			array(
				'title'    => esc_html__( 'Topbar', 'wp-rig' ),
				'priority' => 90, // After Site Identity.
			)
		);

		$wp_customize->add_setting(
			'enable_topbar',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'enable_topbar',
			array(
				'settings' => 'enable_topbar',
				'label'    => esc_html__( 'Enable Topbar', 'wp-rig' ),
				'section'  => 'custom_topbar',
				'type'     => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'topbar_home_only',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'topbar_home_only',
			array(
				'settings' => 'topbar_home_only',
				'label'    => esc_html__( 'Topbar in homepage only', 'wp-rig' ),
				'section'  => 'custom_topbar',
				'type'     => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'topbar_text',
			array(
				'default'    => '',
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'topbar_text',
			array(
				'label'      => esc_html__( 'Topbar Text', 'wp-rig' ),
				'section'    => 'custom_topbar',
				'settings'   => 'topbar_text',
			)
		);

		$wp_customize->add_setting(
			'topbar_button_text',
			array(
				'default'    => esc_html__( 'Learn More', 'wp-rig' ),
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'topbar_button_text',
			array(
				'label'      => esc_html__( 'Topbar Button Text', 'wp-rig' ),
				'section'    => 'custom_topbar',
				'settings'   => 'topbar_button_text',
			)
		);

		$wp_customize->add_setting(
			'topbar_button_url',
			array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_url',
				'type'              => 'option',
			)
		);

		$wp_customize->add_control(
			'topbar_button_url',
			array(
				'type'        => 'url',
				'section'     => 'custom_topbar',
				'label'       => esc_html__( 'Topbar Button URL', 'wp-rig' ),
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

	/**
	 * Set Topbar.
	 *
	 * @param array $options topbar setting options.
	 * @return void
	 */
	public function set_topbar( $options ) {

		$topbar_html = '';

		if ( isset( $options ) ) :
			$topbar_text        = $options['topbar_text'];
			$topbar_button_text = $options['topbar_button_text'];
			$topbar_button_url  = $options['topbar_button_url'];

			ob_start();
			?>
			<div class="site-topbar block text-white bg-primary w-full relative top-0 transition z-20">
				<div class="fluid-container flex justify-center items-center h-10 text-sm">
					<span class="inline-block tracking-widest truncate opacity-75"><?php echo esc_html( $topbar_text ); ?></span>
					<?php
					if ( $topbar_button_url && $topbar_button_text ) :
						?>
						<a class="inline-block ml-3" href="<?php echo esc_url( $topbar_button_url ); ?>">
							<span class="text-white hover:text-primary-light"> <?php echo esc_html( $topbar_button_text ); ?> <i class="fas fa-long-arrow-alt-right"></i></span>
						</a>
						<?php
					endif;
					?>
				</div>
			</div>
			<?php
			$topbar_html = ob_get_clean();
		endif;

		if ( $topbar_html ) :
			echo $topbar_html; // phpcs:ignore
		endif;
	}
}
