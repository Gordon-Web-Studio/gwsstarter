<?php
/**
 * WP_Rig\WP_Rig\Custom_Block_Template\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Custom_Block_Template;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;
use function get_post_type_object;

/**
 * Class for Custom_Block_Templates.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_block_template';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

		add_action( 'init', array( $this, 'cpt_block_templates' ) );
	}

	/**
	 * Asign block template to Product Page Template.
	 *
	 * @access public
	 * @return void
	 */
	public function cpt_block_templates() {

		$template = array(
			array(
				'acf/container',
				array(
					'data'            => array(
						'content_container'     => 'container',
						'layout_text_align'     => 'center',
						'layout_content_valign' => 'center',
					),
					'align'           => 'full',
					'mode'            => 'preview',
					'backgroundColor' => 'theme-primary',
					'textColor'       => 'theme-white',
				),
				array(
					array(
						'acf/content-media',
						array(
							'data'      => array(
								'media_type'                            => 'image',
								'media_image_image_url'                 => get_template_directory_uri() . '/assets/images/bg-img-placeholder.jpg',
								'media_container_media_aspect_ratio'    => 'square',
								'media_container_media_obj_fit'         => 'cover',
								'media_container_media_obj_pos'         => 'center center',
								'media_container_media_aspect_ratio_sm' => 'square',
								'media_container_media_obj_fit_sm'      => 'cover',
								'media_container_media_obj_pos_sm'      => 'center center',
								'style_presets'                         => 'preset-1',
								'invert_order'                          => '0',
								'invert_order_sm'                       => '0',
								'column_width'                          => 'half-half',
								'content_container'                     => 'fluid-container',
								'layout_text_align'                     => 'left',
								'layout_content_valign'                 => 'center',
								'layout_height'                         => 'min-height',
								'layout_height_unit'                    => 'px',
								'layout_min_height'                     => '500',
								'layout_padding_top'                    => '0px',
								'layout_padding_bottom'                 => '0px',
							),
							'align'     => 'full',
							'mode'      => 'preview',
						),
						array(
							array(
								'core/paragraph',
								array(
									'className' => 'block-tagline',
									'content'   => __( 'Content Type', 'wp-rig' ),
								),
							),
							array(
								'core/heading',
								array(
									'className' => 'block-headline is-style-alt',
									'content'   => __( 'XMED Oxygen & Medical Equipment', 'wp-rig' ),
									'level'     => '2',
								),
							),
							array(
								'core/paragraph',
								array(
									'className' => 'block-tagline',
									'content'   => __( '“Gordon Web Studio is a partner you can depend on. We’ve more than doubled the revenue we did five years ago.”', 'wp-rig' ),
									'fontSize'  => 'lg',
								),
							),
							array(
								'core/paragraph',
								array(
									'content'   => __( 'John Skoro<br>Founder & President, XMED', 'wp-rig' ),
								),
							),
						),
					),
				),
			),
			array(
				'acf/container',
				array(
					'data'            => array(
						'content_container'     => 'container',
						'layout_content_valign' => 'center',
					),
					'align'           => 'full',
					'mode'            => 'preview',
				),
				array(
					array(
						'genesis-blocks/gb-columns',
						array(
							'columns'    => 2,
							'layout'     => 'gb-2-col-wideright',
							'columnsGap' => 4,
							'align'      => 'full',
						),
						array(
							array(
								'genesis-blocks/gb-column',
								array(),
								array(
									array(
										'core/group',
										array(
											'className' => 'relative bg-white shadow-3xl py-2 px-8 rounded-2xl',
										),
										array(
											array(
												'core/image',
												array(
													'className' => 'w-80 mb-4 img-contain',
													'align'     => 'full',
													'sizeSlug'  => 'full',
													'url'       => get_template_directory_uri() . '/assets/images/logo.svg',
												),
											),
											array(
												'core/heading',
												array(
													'textAlign' => 'left',
													'className' => 'mt-12 mb-6',
													'content'   => __( 'Key Results', 'wp-rig' ),
													'level'     => '4',
													'fontSize'  => 'lg',
												),
											),
											array(
												'core/list',
												array(
													'listType' => 'wp-block-list-check',
													'values'   => '<li>' . __( 'Result One', 'wp-rig' ) . '</li><li>' . __( 'Result Two', 'wp-rig' ) . '</li><li>' . __( 'Result Three', 'wp-rig' ) . '</li><li>' . __( 'Result Four', 'wp-rig' ) . '</li>',
												),
											),
											array(
												'genesis-blocks/gb-spacer',
											),
											array(
												'genesis-blocks/gb-sharing',
												array(
													'linkedin'         => true,
													'email'            => true,
													'shareButtonStyle' => 'gb-share-icon-only',
													'shareButtonShape' => 'gb-share-shape-rounded',
													'shareButtonColor' => 'gb-share-color-social',
												),
											),
										),
									),
								),
							),
							array(
								'genesis-blocks/gb-column',
								array(
									'className' => 'pt-6',
								),
								array(
									array(
										'core/heading',
										array(
											'textColor' => 'theme-primary',
											'className' => 'is-style-alt',
											'content'   => __( 'Overview', 'wp-rig' ),
											'level'     => '3',
										),
									),
									array(
										'core/paragraph',
										array(
											'content'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque tellus lacus, placerat quis varius vitae, facilisis in mauris. Sed et lacus volutpat mauris tincidunt porta. Morbi aliquet, ligula id suscipit rhoncus, tortor nisi vehicula urna, id condimentum magna est ut est. Aliquam ut suscipit urna. Donec scelerisque lobortis dolor ac vulputate. Morbi euismod porttitor sem, id commodo turpis varius ac. Phasellus scelerisque massa et urna interdum consequat. Fusce nec auctor dolor, et pharetra libero. Proin luctus rutrum ullamcorper. Sed pretium tortor ut urna maximus imperdiet. Mauris fermentum dui quis feugiat faucibus.',
										),
									),
									array(
										'core/paragraph',
										array(
											'content'   => 'Phasellus et arcu eget ligula tincidunt commodo eu non purus. Vivamus turpis ante, ullamcorper placerat ultricies in, placerat id lorem. Duis convallis porta tortor eu finibus. Cras velit elit, tincidunt et ante eu, consequat tincidunt massa. Proin et magna id mi dignissim iaculis at ut lectus. Proin nec accumsan mi. Nunc nec lacus quis velit lacinia aliquam ac eget est. Suspendisse consequat dolor et purus eleifend luctus. Mauris tincidunt, enim luctus molestie scelerisque, metus orci eleifend neque, vel tempus erat est nec tortor. Aenean sodales porta condimentum.',
										),
									),
									array(
										'core/heading',
										array(
											'textColor' => 'theme-primary',
											'className' => 'is-style-alt',
											'content'   => __( 'Challenges', 'wp-rig' ),
											'level'     => '3',
										),
									),
									array(
										'core/paragraph',
										array(
											'content'   => 'Vestibulum ut sapien vitae lectus dapibus consectetur. Integer suscipit vehicula porttitor. Maecenas dui tortor, tincidunt eget imperdiet vitae, ultrices eu tortor. Mauris id porta ligula, at facilisis lacus. Cras consectetur pellentesque nibh, a aliquam elit efficitur in. Sed placerat fringilla sagittis. Pellentesque et enim massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sit amet tortor vestibulum, ultricies massa non, blandit sem. Nullam vel aliquet magna.',
										),
									),
									array(
										'core/paragraph',
										array(
											'content'   => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean pretium ullamcorper erat sit amet maximus. Vivamus vitae vulputate libero. Phasellus vitae lacinia velit. Sed auctor justo diam, nec ornare ipsum rutrum non. Duis rhoncus varius est et bibendum. Nunc turpis arcu, commodo a accumsan et, pellentesque sit amet tortor. Aenean semper lacinia enim, et gravida odio. Nam ac viverra velit, in accumsan augue.',
										),
									),
									array(
										'core/heading',
										array(
											'textColor' => 'theme-primary',
											'className' => 'is-style-alt',
											'content'   => __( 'Results', 'wp-rig' ),
											'level'     => '3',
										),
									),
									array(
										'core/paragraph',
										array(
											'content'   => 'Aenean egestas dignissim velit nec ultrices. Duis et porta enim, ornare accumsan diam. Vivamus eu justo felis. Nam tempor sit amet erat eu tristique. Cras tristique urna nec turpis interdum euismod. In eget euismod elit, eu efficitur elit. Nulla id risus ornare, pretium ligula ut, mattis purus.',
										),
									),
								),
							),
						),
					),
				),
			),
		);

		/**
		 * Assigning template to Success Story custom post type.
		 */
		$success_story_type_object           = get_post_type_object( 'success-story' );
		if ( isset( $success_story_type_object ) ) {
			$success_story_type_object->template = $template;
		}

		/**
		 * Assigning template to Whitepaper custom post type.
		 */
		$whitepaper_type_object           = get_post_type_object( 'whitepaper' );
		if ( isset( $whitepaper_type_object ) ) {
			$whitepaper_type_object->template = $template;
		}

		/**
		 * Assigning template to Event custom post type.
		 */
		$event_type_object           = get_post_type_object( 'event' );
		if ( isset( $event_type_object ) ) {
			$event_type_object->template = $template;
		}
	}
}
