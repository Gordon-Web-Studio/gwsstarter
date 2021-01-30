<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

get_header();
wp_rig()->print_styles( 'wp-content' );
?>
	<main class="site-main" id="primary">
		<?php
		if ( have_posts() ) :
			get_template_part( 'template-parts/content/page_header' );

			if ( ! is_singular() ) :
				echo '<div class="inner-container"><div class="flex-grid mb-5"><div class="row">';
			endif;

			while ( have_posts() ) :
				the_post();
				if ( ! is_singular() ) :
					?>
					<div class="col full half-sm one-thirds-md mb-3">
						<?php get_template_part( 'template-parts/cards/post-card' ); ?>
					</div>
					<?php
				else :
					get_template_part( 'template-parts/content/entry', get_post_type() );
				endif;
			endwhile;

			if ( ! is_singular() ) :
				echo '</div></div></div>';
				get_template_part( 'template-parts/content/pagination' );
			endif;

		else :
			get_template_part( 'template-parts/content/error' );
		endif;
		?>
	</main><!-- #primary -->
<?php
if ( is_singular( get_post_type() ) ) {
	get_template_part( 'template-parts/content/related', get_post_type() );
}

get_footer();
