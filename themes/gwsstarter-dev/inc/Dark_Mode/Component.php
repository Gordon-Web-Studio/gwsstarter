<?php
/**
 * WP_Rig\WP_Rig\Dark_Mode\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Dark_Mode;

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
		return 'dark_mode';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register_dark_mode' ) );
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
			'set_dark_mode' => array( $this, 'set_dark_mode' ),
		);
	}

	/**
	 * Adds a setting and control for topbar in the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_dark_mode( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_section(
			'dark_mode',
			array(
				'title'    => esc_html__( 'Dark Mode', 'wp-rig' ),
				'priority' => 90, // After Site Identity.
			)
		);

		$wp_customize->add_setting(
			'dark_mode_toggle',
			array(
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'dark_mode_toggle',
			array(
				'settings' => 'dark_mode_toggle',
				'label'    => esc_html__( 'Enable Dark Mode Toggle', 'wp-rig' ),
				'section'  => 'dark_mode',
				'type'     => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'default_colorset_mode',
			array(
				'default'    => 'light',
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			'default_colorset_mode',
			array(
				'label'      => esc_html__( 'Default Colorset Mode', 'wp-rig' ),
				'section'    => 'dark_mode',
				'settings'   => 'default_colorset_mode',
				'type'       => 'radio',
				'choices'    => array(
					'light' => 'Light',
					'dark'  => 'Dark',
				),
			)
		);
	}

	/**
	 * Set Topbar.
	 *
	 * @param array $options topbar setting options.
	 * @return void
	 */
	public function set_dark_mode( $options ) {

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
