<?php
/**
 * WP_Rig\WP_Rig\Disable_Editors\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Disable_Editors;

use WP_Rig\WP_Rig\Component_Interface;
use function add_filter;
use function add_action;
use function get_page_template_slug;
use function is_admin;
use function get_current_screen;
use function remove_post_type_support;

/**
 * Class for Disable Editors.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'disable_editors';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'gutenberg_can_edit_post_type', array( $this, 'disable_gutenberg' ), 10, 2 );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'disable_gutenberg' ), 10, 2 );
		add_action( 'admin_head', array( $this, 'disable_classic_editor' ) );
	}

	/**
	 * Templates and Page IDs without editor.
	 *
	 * @access public
	 * @param int $id A post ID.
	 * @return bool True if the excluded template or excluded ids matched the given ID, false otherwise.
	 */
	public function disable_editor( $id = false ) {

		$excluded_templates = array(
			'templates/product.php',
		);

		$excluded_ids = array(
			/* get_option( 'page_on_front' ); */
		);

		if ( empty( $id ) ) {
			return false;
		}

		$id        = intval( $id );
		$template  = get_page_template_slug( $id );

		return in_array( $id, $excluded_ids ) || in_array( $template, $excluded_templates );
	}

	/**
	 * Disable Gutenberg by template.
	 *
	 * @access public
	 * @param boolean $can_edit whether Gutenberg should be used to edit.
	 * @param string  $post_type post type value.
	 * @return bool True if Gutenberg should be used to edit, false otherwise.
	 */
	public function disable_gutenberg( $can_edit, $post_type ) {

		if ( ! ( is_admin() && isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ) ) {
			return $can_edit;
		}

		if ( $this->disable_editor( isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '' ) ) {
			$can_edit = false;
		}

		return $can_edit;
	}

	/**
	 * Disable Classic Editor by template.
	 *
	 * @access public
	 * @return void
	 */
	public function disable_classic_editor() {

		$screen = get_current_screen();
		if ( 'page' !== $screen->id || ! isset( $_GET['post'] ) ) {
			return;
		}

		if ( $this->disable_editor( isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '' ) ) {
			remove_post_type_support( 'page', 'editor' );
		}
	}
}
