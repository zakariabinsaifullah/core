<?php
/**
 * Block & Pattern Categories
 *
 * Registers custom block categories and block pattern categories
 * used throughout this theme.
 *
 * @package Core
 */

if ( ! function_exists( 'core_theme_block_categories' ) ) :
	/**
	 * Adds the "Core" category to the block inserter.
	 *
	 * @param  array                   $block_categories     Existing block categories.
	 * @param  WP_Block_Editor_Context $block_editor_context Current editor context.
	 * @return array
	 */
	function core_theme_block_categories( $block_categories, $block_editor_context ) {
		return array_merge(
			array(
				array(
					'slug'  => 'core-theme',
					'title' => __( 'Core', 'core' ),
				),
			),
			$block_categories

		);
	}
endif;
add_filter( 'block_categories_all', 'core_theme_block_categories', 10, 2 );


if ( ! function_exists( 'core_theme_pattern_categories' ) ) :
	/**
	 * Registers the "Core" block pattern category.
	 */
	function core_theme_pattern_categories() {
		register_block_pattern_category(
			'core-theme',
			array(
				'label'       => __( 'Core', 'core' ),
				'description' => __( 'A collection of Core patterns.', 'core' ),
			)
		);
	}
endif;
add_action( 'init', 'core_theme_pattern_categories' );


// ── ID column on the Categories admin screen ──────────────────────────────────

if ( ! function_exists( 'core_theme_category_columns' ) ) :
	/**
	 * Adds an "ID" column to the Categories list table.
	 *
	 * @param  array $columns Existing column definitions.
	 * @return array
	 */
	function core_theme_category_columns( $columns ) {
		$columns['core_theme_id'] = __( 'ID', 'core' );
		return $columns;
	}
endif;
add_filter( 'manage_edit-category_columns', 'core_theme_category_columns' );

/**
 * Caps the ID column width on the Categories list table.
 */
function core_theme_category_column_width() {
	?>
	<style>
		.wp-list-table .column-core_theme_id {
			width: 100px;
			max-width: 100px;
		}
	</style>
	<?php
}
add_action( 'admin_head-edit-tags.php', 'core_theme_category_column_width' );


if ( ! function_exists( 'core_theme_category_column_content' ) ) :
	/**
	 * Outputs the term ID for the custom column.
	 *
	 * @param  string $content      Column content.
	 * @param  string $column_name  Column key.
	 * @param  int    $term_id      Term ID.
	 * @return string
	 */
	function core_theme_category_column_content( $content, $column_name, $term_id ) {
		if ( 'core_theme_id' === $column_name ) {
			return (string) $term_id;
		}
		return $content;
	}
endif;
add_filter( 'manage_category_custom_column', 'core_theme_category_column_content', 10, 3 );
