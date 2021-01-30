<?php
/**
 * Template part for displaying the header branding
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<div class="site-branding">
	<?php
	$custom_logo_id          = get_theme_mod( 'custom_logo' );
	$custom_logo_url         = wp_get_attachment_url( $custom_logo_id );
	$logo_transparent        = get_option( 'logo_transparent' );
	$logo_mobile             = get_option( 'logo_mobile' );
	$logo_mobile_transparent = get_option( 'logo_mobile_transparent' );
	$logo_alts               = array(
		'class'             => 'custom-logo',
		'data-logo-default' => $custom_logo_url,
	);

	if ( $logo_transparent ) {
		$logo_alts['data-logo-transparent'] = $logo_transparent;
	}

	if ( $logo_mobile ) {
		$logo_alts['data-logo-mobile'] = $logo_mobile;
	}

	if ( $logo_mobile_transparent ) {
		$logo_alts['data-logo-mobile-transparent'] = $logo_mobile_transparent;
	}

	if ( $custom_logo_id ) :
		printf(
			'<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
			esc_url( home_url( '/' ) ),
			wp_get_attachment_image( $custom_logo_id, 'full', false, $logo_alts )
		);
	else :
		printf(
			'<a href="%1$s" class="custom-logo-link" rel="home"><img class="default-logo" src="%2$s" alt="%3$s"></a>',
			esc_url( home_url( '/' ) ),
			esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ),
			esc_html( get_bloginfo( 'name' ) )
		);
	endif;
	?>
</div><!-- .site-branding -->
