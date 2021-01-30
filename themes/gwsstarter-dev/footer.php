<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

	<footer id="colophon" class="site-footer border-t border-gray-100 pt-6">
		<div class="fluid-container">
			<div class="grid grid-cols-1 nav:grid-cols-4 gap-4">
				<?php
				get_template_part( 'template-parts/footer/footer', 'info' );
				get_template_part( 'template-parts/footer/footer', 'menu' );
				get_template_part( 'template-parts/footer/footer', 'contact' );
				?>
			</div>
			<div class="footer-copyright container text-center py-8">
				Copyright &copy <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> - All Right Reserved.
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
