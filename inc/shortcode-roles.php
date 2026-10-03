<?php
/**
 * Open Roles Shortcode
 *
 * Renders active open roles as a grid of cards: job type, open positions,
 * role title and an Apply link.
 *
 * Usage: [opening_roles]
 *        [opening_roles columns="3" count="-1" job_type="tax,accounting"]
 *
 * @package Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'core_theme_roles_grid_enqueue_assets' ) ) :
	/**
	 * Enqueues the role card styles.
	 */
	function core_theme_roles_grid_enqueue_assets() {
		wp_enqueue_style(
			'core-theme-roles-grid',
			get_theme_file_uri( 'assets/css/roles-grid.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;


if ( ! function_exists( 'core_theme_roles_active_ids' ) ) :
	/**
	 * IDs of the roles that should be listed: published and switched on in
	 * the Active column. Roles created before that toggle existed have no
	 * meta and count as active.
	 *
	 * @param int    $limit     Maximum roles, or -1 for all.
	 * @param string $order     ASC or DESC.
	 * @param string $orderby   date or title.
	 * @param int[]  $type_ids  Job Type term IDs to limit to; empty for all.
	 * @return int[] Post IDs.
	 */
	function core_theme_roles_active_ids( $limit, $order, $orderby, $type_ids = array() ) {
		$args = array(
			'post_type'        => 'open-role',
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'order'            => $order,
			'orderby'          => $orderby,
			'fields'           => 'ids',
			'no_found_rows'    => true,
			'suppress_filters' => false,
		);

		if ( $type_ids ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'core-theme-job-type',
					'field'    => 'term_id',
					'terms'    => $type_ids,
				),
			);
		}

		$ids = array_values( array_filter( get_posts( $args ), 'core_theme_open_role_is_active' ) );

		// Limit after the active filter, so inactive roles never use up slots.
		return $limit > 0 ? array_slice( $ids, 0, $limit ) : $ids;
	}
endif;


if ( ! function_exists( 'core_theme_roles_render_card' ) ) :
	/**
	 * Renders one role card.
	 *
	 * @param int $post_id Role ID.
	 * @return string Markup.
	 */
	function core_theme_roles_render_card( $post_id ) {
		$types      = get_the_terms( $post_id, 'core-theme-job-type' );
		$type_label = is_array( $types ) ? implode( ', ', wp_list_pluck( $types, 'name' ) ) : '';
		$vacancies  = (int) get_post_meta( $post_id, 'core_theme_role_vacancies', true );
		$apply      = (string) get_post_meta( $post_id, 'core_theme_role_apply_link', true );
		$title      = get_the_title( $post_id );

		/*
		 * esc_url() returns '' for anything it will not allow through, so the
		 * escaped value decides whether there is a link at all.
		 */
		$apply_url = '' !== $apply ? esc_url( $apply ) : '';

		// Scheduling pages and job boards open in a new tab; same-site links do not.
		$host     = wp_parse_url( $apply_url, PHP_URL_HOST );
		$external = $host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $host;

		ob_start();
		?>
		<article class="core-role">
			<?php if ( '' !== $type_label || $vacancies > 0 ) : ?>
				<div class="core-role__meta">
					<?php if ( '' !== $type_label ) : ?>
						<span class="core-role__type"><?php echo esc_html( $type_label ); ?></span>
					<?php endif; ?>
					<?php if ( $vacancies > 0 ) : ?>
						<span class="core-role__count">
							<?php
							/* translators: %d: number of open positions. */
							echo esc_html( sprintf( _n( '%d open', '%d open', $vacancies, 'core' ), $vacancies ) );
							?>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<h3 class="core-role__title"><?php echo esc_html( $title ); ?></h3>

			<?php if ( '' !== $apply_url ) : ?>
				<a
					class="core-role__apply"
					href="<?php echo $apply_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %s: role title */ __( 'Apply for %s', 'core' ), $title ) ); ?>"
					<?php echo $external ? 'target="_blank" rel="noopener"' : ''; ?>
				>
					<span><?php esc_html_e( 'Apply now', 'core' ); ?></span>
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M3.6 9H14.4M9.9 13.5L14.4 9L9.9 4.5" stroke="currentColor" stroke-width="1.575" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
			<?php endif; ?>
		</article>
		<?php
		return (string) ob_get_clean();
	}
endif;


if ( ! function_exists( 'core_theme_opening_roles_shortcode' ) ) :
	/**
	 * [opening_roles columns="3" count="-1" job_type="" order="DESC" orderby="date"]
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Markup, or a short note when no role is open.
	 */
	function core_theme_opening_roles_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'columns'  => 3,
				'count'    => -1,
				'per_page' => '', // Older name for `count`, still honoured.
				'job_type' => '',
				'order'    => 'DESC',
				'orderby'  => 'date',
				'empty'    => __( 'There are no open roles right now.', 'core' ),
			),
			$atts,
			'opening_roles'
		);

		$columns = min( 4, max( 1, (int) $atts['columns'] ) );
		$count   = '' !== $atts['per_page'] ? (int) $atts['per_page'] : (int) $atts['count'];
		$order   = 'ASC' === strtoupper( $atts['order'] ) ? 'ASC' : 'DESC';
		$orderby = in_array( $atts['orderby'], array( 'date', 'title' ), true ) ? $atts['orderby'] : 'date';

		$type_ids = array();
		foreach ( array_filter( array_map( 'trim', explode( ',', (string) $atts['job_type'] ) ), 'strlen' ) as $token ) {
			$term = is_numeric( $token )
				? get_term_by( 'id', (int) $token, 'core-theme-job-type' )
				: get_term_by( 'slug', sanitize_title( $token ), 'core-theme-job-type' );
			if ( $term && ! is_wp_error( $term ) ) {
				$type_ids[] = (int) $term->term_id;
			}
		}

		$ids = core_theme_roles_active_ids( $count, $order, $orderby, $type_ids );

		core_theme_roles_grid_enqueue_assets();

		if ( ! $ids ) {
			return '' !== trim( $atts['empty'] ) ? '<p class="core-roles__empty">' . esc_html( $atts['empty'] ) . '</p>' : '';
		}

		$html = '<div class="core-roles" style="--core-roles-columns:' . (int) $columns . '">';
		foreach ( $ids as $id ) {
			$html .= core_theme_roles_render_card( $id );
		}
		$html .= '</div>';

		return $html;
	}
endif;
add_shortcode( 'opening_roles', 'core_theme_opening_roles_shortcode' );
