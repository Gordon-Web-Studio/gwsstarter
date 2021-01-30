<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php
	if ( ! wp_rig()->is_amp() ) {
		?>
		<script>document.documentElement.classList.remove( 'no-js' );</script>
		<?php
	}

	wp_head();
	?>
</head>
<?php
// Transparent Header.
$header_transparent_class = get_field( 'header_transparent' ) ? ' is-transparent' : '';

// Sticky Header.
$global_header_sticky = get_option( 'header_sticky' );
$page_header_sticky   = get_field( 'header_sticky' );
$header_sticky        = false;
$header_class         = '';

if ( 'default' !== $page_header_sticky && $page_header_sticky ) {
	$header_sticky = 'enabled' === $page_header_sticky ? true : false;
} else {
	$header_sticky = $global_header_sticky;
}

$header_sticky_class = $header_sticky ? ' is-sticky' : '';
$header_class        = $header_transparent_class . $header_sticky_class;
?>
<body <?php body_class( $header_class ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wp-rig' ); ?></a>
	<?php
	$enable_topbar                        = get_option( 'enable_topbar' );
	$topbar_home_only                     = get_option( 'topbar_home_only' );
	$topbar_options                       = array();
	$topbar_options['topbar_text']        = get_option( 'topbar_text' );
	$topbar_options['topbar_button_text'] = get_option( 'topbar_button_text' );
	$topbar_options['topbar_button_url']  = get_option( 'topbar_button_url' );

	if ( $enable_topbar && $topbar_home_only ) {
		if ( is_home() ) {
			wp_rig()->set_topbar( $topbar_options );
		}
	} elseif ( $enable_topbar && ! $topbar_home_only ) {
		wp_rig()->set_topbar( $topbar_options );
	}
	?>
	<header id="masthead" class="site-header z-30 bg-white">
		<div class="container flex justify-between items-center relative transition h-16 nav:h-24">

			<?php get_template_part( 'template-parts/header/custom_header' ); ?>

			<div class="logo w-32 sm:w-40 nav:w-auto">
				<?php get_template_part( 'template-parts/header/branding' ); ?>
			</div>

			<?php get_template_part( 'template-parts/header/navigation' ); ?>

		</div>
	</header><!-- #masthead -->
