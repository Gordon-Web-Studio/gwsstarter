<?php
/**
 * Template part for displaying post using wp facets
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( have_posts() ) :

	while ( have_posts() ) :

		the_post();

		$image = get_the_post_thumbnail_url( get_the_ID(), 'news-grid-medium' );

		if ( ! $image ) {
			$image = get_field( 'grid_fallback_image', 'options' );
			$image = $image['sizes']['medium'];
		}
		?>
		<article <?php post_class( 'article-card' ); ?>>
			<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
				<div class="article-card--image">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
				</div>
				<?php
				if ( get_the_title( $post->ID ) || get_the_excerpt( $post->ID ) ) {
					?>
					<div class="article-card--content">
						<?php
						if ( get_the_title( $post->ID ) ) {
							?>
							<h3 class="text-black is-style-alt">
								<?php echo esc_html( get_the_title( $post->ID ) ); ?>
							</h3>
							<?php
						}

						if ( get_the_excerpt( $post->ID ) ) {
							?>
							<p class="text-black">
								<?php echo wp_kses_post( get_the_excerpt( $post->ID ) ); ?>
							</p>
							<?php
						}
						?>
						<p class="underline font-bold">
							<?php esc_html_e( 'Read More', 'wp-rig' ); ?>
						</p>
					</div>
					<?php
				}
				?>
			</a>
		</article>
		<?php
	endwhile;
else :
	?>
	<h3>
		<?php esc_html_e( 'Sorry, no posts matched your criteria.', 'wp-rig' ); ?>
	</h3>
	<?php
endif;
?>
