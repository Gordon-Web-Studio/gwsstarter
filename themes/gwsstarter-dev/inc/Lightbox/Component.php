<?php
/**
 * WP_Rig\WP_Rig\Lightbox\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Lightbox;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use function wp_add_inline_script;

/**
 * Class for Lightbox.
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'lightbox';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_rig()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'initiate_lightbox_script'   => array( $this, 'initiate_lightbox_script' ),
		);
	}

	/**
	 * Initiate carousel script.
	 *
	 * @access public
	 * @param string  $block_id The block ID.
	 * @param string  $block_slug The block slug.
	 * @param string  $selector target item for lightbox.
	 * @param boolean $loop activate infinte loop.
	 * @return void
	 */
	public function initiate_lightbox_script( $block_id, $block_slug, $selector = '.glightbox', $loop = 1 ) {

		// Main lightbox settings.
		$lightbox_id       = str_replace( '-', '_', $block_id );
		$lightbox_initiate = '
		jQuery( function( $ ) {

			/**
			 * initializeLightbox
			 *
			 * Adds lightbox script initializer to the block HTML.
			 *
			 * @return  void
			 */
			var initialize_lightbox_' . $lightbox_id . ' = function() {

				const lightbox_' . $lightbox_id . ' = GLightbox({
					selector: "#' . $block_id . ' ' . $selector . '",
					loop: ' . wp_rig()->bool( $loop ) . '
				});
			}

			// Initialize each block on page load (front end).
			$(document).ready(function(){
				if ( $( "#' . $block_id . '" ) ) {
					initialize_lightbox_' . $lightbox_id . '();
				}
			});

			// Initialize dynamic block preview (editor).
			if ( window.acf ) {
				window.acf.addAction( "render_block_preview/type=' . $block_slug . '", initialize_lightbox_' . $lightbox_id . ' );
			}
		} );
		';

		wp_add_inline_script( 'wp-lightbox', $lightbox_initiate );
	}
}
