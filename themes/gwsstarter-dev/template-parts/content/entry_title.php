<?php
/**
 * Template part for displaying a post's title
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

global $wp_query;

if ( is_singular( get_post_type() ) ) {
	the_title( '<h1 class="entry-title text-center three-quarters-md box-center">', '</h1>' );
} else {
	the_title( '<h3 class="entry-title h5 mt-0"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
}
