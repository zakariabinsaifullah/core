<?php
/**
 * Asset Enqueueing
 *
 * Registers and enqueues stylesheets and scripts for the front end
 * and the block editor.
 *
 * @package Core
 */

if ( ! function_exists( 'core_theme_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheets on the front end and registers
	 * shared vendor assets (Swiper) that blocks can depend on.
	 */
	function core_theme_enqueue_styles() {
		$theme_version = wp_get_theme()->get( 'Version' );

		wp_enqueue_style(
			'core-theme-root-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			$theme_version
		);

		// Register Swiper so blocks can declare it as a dependency.
		wp_register_style(
			'core-theme-swiper-style',
			get_parent_theme_file_uri( 'assets/css/swiper-bundle.min.css' ),
			array(),
			$theme_version
		);

		wp_register_script(
			'core-theme-swiper-script',
			get_parent_theme_file_uri( 'assets/js/swiper-bundle.min.js' ),
			array(),
			$theme_version,
			true
		);

		// Eased scrolling for links to a section on the same page.
		wp_enqueue_script(
			'core-theme-smooth-scroll',
			get_parent_theme_file_uri( 'assets/js/smooth-scroll.js' ),
			array(),
			$theme_version,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'core_theme_enqueue_styles' );


if ( ! function_exists( 'core_theme_enqueue_block_styles' ) ) :
	/**
	 * Enqueues the shared block stylesheet in both the editor and on the front end.
	 */
	function core_theme_enqueue_block_styles() {
		wp_enqueue_style(
			'core-theme-block-style',
			get_parent_theme_file_uri( 'assets/css/blocks.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'enqueue_block_assets', 'core_theme_enqueue_block_styles' );
