<?php
/**
 * Block Bindings
 *
 * Registers custom block binding sources that allow blocks to pull
 * dynamic data from theme-specific callbacks.
 *
 * @package Core
 */

if ( ! function_exists( 'core_theme_register_block_bindings' ) ) :
	/**
	 * Registers the "Post Format Name" block binding source.
	 *
	 * Allows blocks to display the human-readable post format label
	 * (e.g. "Video", "Gallery") via the block bindings API.
	 */
	function core_theme_register_block_bindings() {
		register_block_bindings_source(
			'core-theme/format',
			array(
				'label'              => _x(
					'Post format name',
					'Label for the block binding placeholder in the editor',
					'core'
				),
				'get_value_callback' => 'core_theme_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'core_theme_register_block_bindings' );


if ( ! function_exists( 'core_theme_format_binding' ) ) :
	/**
	 * Returns the human-readable post format name for the current post.
	 *
	 * Returns nothing (null) when the post uses the standard format so that
	 * bound blocks render empty rather than showing a label.
	 *
	 * @return string|void Post format name, or nothing for the standard format.
	 */
	function core_theme_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;


if ( ! function_exists( 'core_theme_register_author_binding' ) ) :
	/**
	 * Registers the "Post author" block binding source.
	 *
	 * Lets a Paragraph or Heading in a template show a field of the current
	 * post's author — used for the author's Role under their name in the single
	 * post hero and author box. Bind with:
	 * {"metadata":{"bindings":{"content":{"source":"core-theme/author","args":{"key":"role"}}}}}
	 */
	function core_theme_register_author_binding() {
		register_block_bindings_source(
			'core-theme/author',
			array(
				'label'              => _x( 'Post author', 'Label for the block binding placeholder in the editor', 'core' ),
				'get_value_callback' => 'core_theme_author_binding',
				'uses_context'       => array( 'postId' ),
			)
		);
	}
endif;
add_action( 'init', 'core_theme_register_author_binding' );


if ( ! function_exists( 'core_theme_author_binding' ) ) :
	/**
	 * Returns a field of the post author. `key` is `role` (the Role field on the
	 * user profile) or `name`.
	 *
	 * @param array    $source_args    Binding arguments.
	 * @param WP_Block $block_instance Block being rendered.
	 * @return string|null
	 */
	function core_theme_author_binding( $source_args, $block_instance ) {
		$post_id = isset( $block_instance->context['postId'] ) ? (int) $block_instance->context['postId'] : get_the_ID();
		$author  = $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0;

		if ( ! $author ) {
			return null;
		}

		$key = isset( $source_args['key'] ) ? $source_args['key'] : 'role';

		if ( 'name' === $key ) {
			return esc_html( get_the_author_meta( 'display_name', $author ) );
		}

		$role = get_user_meta( $author, 'core_theme_role', true );
		return '' !== $role ? esc_html( $role ) : null;
	}
endif;


if ( ! function_exists( 'core_theme_user_role_field' ) ) :
	/**
	 * "Role" field on the user profile, e.g. "Partner, CPA". Shown under the
	 * author's name on single posts.
	 *
	 * @param WP_User $user User being edited.
	 */
	function core_theme_user_role_field( $user ) {
		?>
		<h2><?php esc_html_e( 'Author details', 'core' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th><label for="core_theme_role"><?php esc_html_e( 'Role', 'core' ); ?></label></th>
				<td>
					<input type="text" name="core_theme_role" id="core_theme_role" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'core_theme_role', true ) ); ?>" />
					<p class="description"><?php esc_html_e( 'Shown under your name on blog posts, e.g. "Partner, CPA".', 'core' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}
endif;
add_action( 'show_user_profile', 'core_theme_user_role_field' );
add_action( 'edit_user_profile', 'core_theme_user_role_field' );


if ( ! function_exists( 'core_theme_save_user_role_field' ) ) :
	/**
	 * Saves the Role field.
	 *
	 * @param int $user_id User being saved.
	 */
	function core_theme_save_user_role_field( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) || ! isset( $_POST['core_theme_role'] ) ) {
			return;
		}
		check_admin_referer( 'update-user_' . $user_id );
		update_user_meta( $user_id, 'core_theme_role', sanitize_text_field( wp_unslash( $_POST['core_theme_role'] ) ) );
	}
endif;
add_action( 'personal_options_update', 'core_theme_save_user_role_field' );
add_action( 'edit_user_profile_update', 'core_theme_save_user_role_field' );
