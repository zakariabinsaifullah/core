<?php
/**
 * Core Block Styles
 *
 * Registers custom style variations for core (and third-party) blocks.
 *
 * @package Core
 */

if ( ! function_exists( 'core_theme_block_styles' ) ) :
	/**
	 * Registers all custom block style variations for the theme.
	 */
	function core_theme_block_styles() {
		register_block_style(
			'core/group',
			array(
				'name'  => 'wrap-mobile',
				'label' => __( 'Wrap Mobile', 'core' ),
			)
		);


		register_block_style(
			'core/button',
			array(
				'name'  => 'alternative',
				'label' => __( 'Alternative', 'core' ),
			)
		);

		register_block_style(
			'core/button',
			array(
				'name'  => 'link',
				'label' => __( 'Link', 'core' ),
			)
		);
	}
endif;
add_action( 'init', 'core_theme_block_styles' );
