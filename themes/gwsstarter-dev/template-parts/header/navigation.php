<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( wp_rig()->is_primary_nav_menu_active() ) :
?>

	<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small nav:flex-grow" aria-label="<?php esc_attr_e( 'Main menu', 'wp-rig' ); ?>"
		<?php
		if ( wp_rig()->is_amp() ) {
			?>
			[class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' "
			<?php
		}
		?>
	>
		<?php
		if ( wp_rig()->is_amp() ) {
			?>
			<amp-state id="siteNavigationMenu">
				<script type="application/json">
					{
						"expanded": false
					}
				</script>
			</amp-state>
			<?php
		}
		?>

		<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'wp-rig' ); ?>" aria-controls="primary-menu" aria-expanded="false"
			<?php
			if ( wp_rig()->is_amp() ) {
				?>
				on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
				[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
				<?php
			}
			?>
		>
			<?php esc_html_e( 'Menu', 'wp-rig' ); ?>
		</button>

		<div class="nav-container">
			<div class="primary-menu-container">
				<?php
				wp_rig()->display_primary_nav_menu(
					array(
						'menu_id' => 'primary-menu',
						'depth'   => 3,
					)
				);
				?>
			</div>
			<?php
			$dark_mode_toggle = get_option( 'dark_mode_toggle' );
			?>
			<div class="secondary-menu-container nav:flex justify-between items-center p-4 nav:p-0">
				<a class="ui-btn btn-primary-light w-full sm:w-auto sm:mr-2 mb-4 sm:mb-0" href="<?php echo esc_url( $login_button_url ); ?>"><?php echo esc_html( $login_button_text ); ?></a>
				<a class="ui-btn btn-primary w-full sm:w-auto" href="<?php echo esc_url( $schedule_demo_button_url ); ?>"><?php echo esc_html( $schedule_demo_button_text ); ?></a>
			</div>
		</div>
	</nav><!-- #site-navigation -->
	<?php
else :
	// Sample Menu.
	get_template_part( 'template-parts/header/navigation-sample' );
endif;
