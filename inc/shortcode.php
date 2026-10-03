<?php
/**
 * Posts Grid Shortcode
 *
 * Renders a filterable, paginated post grid via AJAX, and a single featured
 * post card to sit above it.
 *
 * Usage: [core_theme_featured_post]
 *        [core_theme_posts_grid per_page="9" exclude="featured"]
 */

// =============================================================================
// Asset enqueueing
// =============================================================================

if ( ! function_exists( 'core_theme_posts_grid_enqueue_assets' ) ) :
	function core_theme_posts_grid_enqueue_assets() {
		$version = wp_get_theme()->get( 'Version' );

		wp_enqueue_style(
			'core-theme-posts-grid',
			get_theme_file_uri( 'assets/css/shortcode.css' ),
			array(),
			$version
		);

		wp_enqueue_script(
			'core-theme-posts-grid',
			get_theme_file_uri( 'assets/js/shortcode.js' ),
			array(),
			$version,
			true
		);
	}
endif;


// =============================================================================
// Helpers
// =============================================================================

if ( ! function_exists( 'core_theme_posts_grid_arrow_svg' ) ) :
	/**
	 * The design's right arrow, stroked in the current text colour.
	 *
	 * Inlined because this markup also travels over AJAX.
	 *
	 * @param int    $size  Rendered width and height in px.
	 * @param string $class Class for the svg element.
	 */
	function core_theme_posts_grid_arrow_svg( $size = 18, $class = 'ipg-arrow' ) {
		return '<svg class="' . esc_attr( $class ) . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M3.6 9H14.4M9.9 13.5L14.4 9L9.9 4.5" stroke="currentColor" stroke-width="1.575" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_post_meta' ) ) :
	/**
	 * Category (first term of the taxonomy) • date, shared by the card and the
	 * featured post.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $taxonomy Taxonomy used for the category label.
	 * @param string $prefix   BEM block name for the classes.
	 */
	function core_theme_posts_grid_post_meta( $post_id, $taxonomy, $prefix ) {
		$terms    = get_the_terms( $post_id, $taxonomy );
		$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

		$html = '<div class="' . $prefix . '__meta">';
		if ( $cat_name ) {
			$html .= '<span class="' . $prefix . '__category">' . esc_html( $cat_name ) . '</span>';
			$html .= '<span class="' . $prefix . '__sep" aria-hidden="true">&bull;</span>';
		}
		$html .= '<time class="' . $prefix . '__date" datetime="' . esc_attr( get_the_date( 'c', $post_id ) ) . '">' . esc_html( get_the_date( 'F j, Y', $post_id ) ) . '</time>';
		$html .= '</div>';

		return $html;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_render_post_item' ) ) :
	/**
	 * Renders a single post card: image → category • date → title → excerpt → Read more.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $taxonomy Taxonomy used for the category label.
	 */
	function core_theme_posts_grid_render_post_item( $post_id, $taxonomy = 'category' ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return '';
		}

		$permalink = get_permalink( $post_id );
		$title     = get_the_title( $post_id );
		$excerpt   = wp_trim_words( get_the_excerpt( $post_id ), 22 );
		$thumbnail = has_post_thumbnail( $post_id )
			? get_the_post_thumbnail( $post_id, 'medium_large', array( 'loading' => 'lazy' ) )
			: '';

		$html = '<article class="ipg-card">';

		if ( $thumbnail ) {
			$html .= '<a href="' . esc_url( $permalink ) . '" class="ipg-card__image" tabindex="-1" aria-hidden="true">' . $thumbnail . '</a>';
		}

		$html .= '<div class="ipg-card__body">';
		$html .= core_theme_posts_grid_post_meta( $post_id, $taxonomy, 'ipg-card' );
		$html .= '<h3 class="ipg-card__title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h3>';

		if ( $excerpt ) {
			$html .= '<p class="ipg-card__excerpt">' . esc_html( $excerpt ) . '</p>';
		}

		$html .= '<a class="ipg-card__link" href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( sprintf( /* translators: %s: post title */ __( 'Read more: %s', 'core' ), $title ) ) . '">';
		$html .= '<span>' . esc_html__( 'Read more', 'core' ) . '</span>';
		$html .= core_theme_posts_grid_arrow_svg( 18, 'ipg-card__arrow' );
		$html .= '</a>';

		$html .= '</div>';
		$html .= '</article>';

		return $html;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_render_posts' ) ) :
	/**
	 * Renders the full grid of post cards for a given WP_Query.
	 *
	 * @param WP_Query $query    The query to render.
	 * @param string   $taxonomy Taxonomy used for the category label.
	 */
	function core_theme_posts_grid_render_posts( $query, $taxonomy = 'category' ) {
		if ( ! $query->have_posts() ) {
			return '<p class="ipg-no-posts">' . esc_html__( 'No posts found.', 'core' ) . '</p>';
		}

		$html = '<div class="ipg-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= core_theme_posts_grid_render_post_item( get_the_ID(), $taxonomy );
		}

		$html .= '</div>';

		wp_reset_postdata();

		return $html;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_resolve_category_ids' ) ) :
	/**
	 * Resolves a comma-separated list of term IDs/slugs into an array of term IDs.
	 */
	function core_theme_posts_grid_resolve_category_ids( $categories_raw, $taxonomy ) {
		$ids = array();

		foreach ( array_filter( array_map( 'trim', explode( ',', (string) $categories_raw ) ), 'strlen' ) as $token ) {
			$term = is_numeric( $token )
				? get_term_by( 'id', (int) $token, $taxonomy )
				: get_term_by( 'slug', sanitize_title( $token ), $taxonomy );

			if ( $term && ! is_wp_error( $term ) ) {
				$ids[] = (int) $term->term_id;
			}
		}

		return array_unique( $ids );
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_pagination_range' ) ) :
	/**
	 * Returns an array of page numbers and '...' placeholders.
	 * Always shows first/last page and current page ± 1 neighbour.
	 */
	function core_theme_posts_grid_pagination_range( $total_pages, $current_page ) {
		$total_pages  = (int) $total_pages;
		$current_page = (int) $current_page;

		if ( $total_pages <= 7 ) {
			return range( 1, $total_pages );
		}

		$left  = max( 2, $current_page - 1 );
		$right = min( $total_pages - 1, $current_page + 1 );
		$pages = array( 1 );

		if ( $left > 2 ) {
			$pages[] = '...';
		}

		for ( $i = $left; $i <= $right; $i++ ) {
			$pages[] = $i;
		}

		if ( $right < $total_pages - 1 ) {
			$pages[] = '...';
		}

		$pages[] = $total_pages;

		return $pages;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_render_pagination' ) ) :
	/**
	 * Renders prev/next arrows + numbered page buttons with ellipsis.
	 */
	function core_theme_posts_grid_render_pagination( $total_pages, $current_page ) {
		$total_pages  = (int) $total_pages;
		$current_page = (int) $current_page;

		if ( $total_pages <= 1 ) {
			return '';
		}

		$svg_prev = core_theme_posts_grid_arrow_svg( 16, 'ipg-arrow ipg-arrow--prev' );
		$svg_next = core_theme_posts_grid_arrow_svg( 16, 'ipg-arrow' );

		$html = '<div class="ipg-pagination">';

		// Prev.
		$prev_page = max( 1, $current_page - 1 );
		$html     .= '<button class="ipg-page-btn ipg-page-arrow"'
			. ( 1 === $current_page ? ' disabled' : '' )
			. ' data-page="' . $prev_page . '" aria-label="' . esc_attr__( 'Previous page', 'core' ) . '">'
			. $svg_prev
			. '</button>';

		// Pages.
		foreach ( core_theme_posts_grid_pagination_range( $total_pages, $current_page ) as $page ) {
			if ( '...' === $page ) {
				$html .= '<span class="ipg-page-ellipsis">&hellip;</span>';
			} else {
				$active = ( (int) $page === $current_page ) ? ' active' : '';
				$html  .= '<button class="ipg-page-btn' . $active . '"' . ( $active ? ' aria-current="page"' : '' ) . ' data-page="' . (int) $page . '" aria-label="' . sprintf( esc_attr__( 'Page %d', 'core' ), (int) $page ) . '">' . (int) $page . '</button>';
			}
		}

		// Next.
		$next_page = min( $total_pages, $current_page + 1 );
		$html     .= '<button class="ipg-page-btn ipg-page-arrow"'
			. ( $current_page === $total_pages ? ' disabled' : '' )
			. ' data-page="' . $next_page . '" aria-label="' . esc_attr__( 'Next page', 'core' ) . '">'
			. $svg_next
			. '</button>';

		$html .= '</div>';

		return $html;
	}
endif;


// =============================================================================
// AJAX handler
// =============================================================================

if ( ! function_exists( 'core_theme_posts_grid_ajax' ) ) :
	function core_theme_posts_grid_ajax() {
		check_ajax_referer( 'core_theme_posts_grid_nonce', 'nonce' );

		$cat        = isset( $_POST['cat'] )        ? absint( $_POST['cat'] )                                        : 0;
		$page       = isset( $_POST['page'] )       ? max( 1, absint( $_POST['page'] ) )                             : 1;
		$per_page   = isset( $_POST['per_page'] )   ? min( 50, max( 1, absint( $_POST['per_page'] ) ) )              : 6;
		$post_type  = isset( $_POST['post_type'] )  ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) )       : 'post';
		$taxonomy   = isset( $_POST['taxonomy'] )   ? sanitize_key( $_POST['taxonomy'] )                             : 'category';
		$categories = isset( $_POST['categories'] ) ? sanitize_text_field( wp_unslash( $_POST['categories'] ) )      : '';
		$exclude    = isset( $_POST['exclude'] )    ? array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['exclude'] ) ) ) ) ) : array();

		if ( ! post_type_exists( $post_type ) ) {
			$post_type = 'post';
		}

		if ( ! taxonomy_exists( $taxonomy ) ) {
			$taxonomy = 'category';
		}

		$allowed_cat_ids = array_filter( array_map( 'absint', explode( ',', $categories ) ) );

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_status'    => 'publish',
			'post__not_in'   => $exclude,
		);

		if ( $cat > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $cat,
				),
			);
		} elseif ( ! empty( $allowed_cat_ids ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $allowed_cat_ids,
				),
			);
		}

		$query = new WP_Query( $args );

		wp_send_json_success( array(
			'html'         => core_theme_posts_grid_render_posts( $query, $taxonomy ),
			'pagination'   => core_theme_posts_grid_render_pagination( (int) $query->max_num_pages, $page ),
			'total_pages'  => (int) $query->max_num_pages,
			'current_page' => $page,
		) );
	}
endif;
add_action( 'wp_ajax_core_theme_posts_grid', 'core_theme_posts_grid_ajax' );
add_action( 'wp_ajax_nopriv_core_theme_posts_grid', 'core_theme_posts_grid_ajax' );


// =============================================================================
// Shortcode
// =============================================================================

if ( ! function_exists( 'core_theme_get_featured_post_id' ) ) :
	/**
	 * The post the featured card shows: the given ID when it is a published
	 * post of the type, otherwise the newest sticky post, otherwise the newest
	 * post. 0 when there is none.
	 *
	 * @param int|string $id        Requested post ID, or empty.
	 * @param string     $post_type Post type.
	 */
	function core_theme_get_featured_post_id( $id = '', $post_type = 'post' ) {
		$id = absint( $id );
		if ( $id && 'publish' === get_post_status( $id ) && get_post_type( $id ) === $post_type ) {
			return $id;
		}

		$base = array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'fields'              => 'ids',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		$sticky = 'post' === $post_type ? get_option( 'sticky_posts', array() ) : array();
		if ( $sticky ) {
			$ids = get_posts( $base + array( 'post__in' => array_map( 'absint', $sticky ) ) );
			if ( $ids ) {
				return (int) $ids[0];
			}
		}

		$ids = get_posts( $base );
		return $ids ? (int) $ids[0] : 0;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_resolve_exclude' ) ) :
	/**
	 * Turns the grid's `exclude` attribute into post IDs. The token `featured`
	 * resolves to whatever [core_theme_featured_post] shows without an id.
	 */
	function core_theme_posts_grid_resolve_exclude( $exclude_raw, $post_type ) {
		$ids = array();
		foreach ( array_filter( array_map( 'trim', explode( ',', (string) $exclude_raw ) ), 'strlen' ) as $token ) {
			if ( 'featured' === strtolower( $token ) ) {
				$ids[] = core_theme_get_featured_post_id( '', $post_type );
			} else {
				$ids[] = absint( $token );
			}
		}
		return array_values( array_unique( array_filter( $ids ) ) );
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_resolve_taxonomy' ) ) :
	/**
	 * Returns the primary hierarchical taxonomy for a post type.
	 */
	function core_theme_posts_grid_resolve_taxonomy( $post_type ) {
		foreach ( get_object_taxonomies( $post_type, 'objects' ) as $tax ) {
			if ( $tax->public && $tax->hierarchical ) {
				return $tax->name;
			}
		}
		return 'category';
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_resolve_allowed_cats' ) ) :
	/**
	 * Resolves allowed category IDs, falling back to all non-empty terms.
	 */
	function core_theme_posts_grid_resolve_allowed_cats( $categories_raw, $taxonomy ) {
		$ids = core_theme_posts_grid_resolve_category_ids( $categories_raw, $taxonomy );

		if ( empty( $ids ) ) {
			$all = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true, 'fields' => 'ids' ) );
			$ids = is_wp_error( $all ) ? array() : array_map( 'intval', $all );
		}

		return $ids;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_render_tabs' ) ) :
	/**
	 * Renders the filter tab buttons for a given set of terms.
	 *
	 * @param array $terms Array of WP_Term objects.
	 */
	function core_theme_posts_grid_render_tabs( $terms ) {
		if ( empty( $terms ) ) {
			return '';
		}

		$html  = '<div class="ipg-nav">';
		$html .= '<button type="button" class="ipg-filter-btn active" aria-pressed="true" data-cat="0">' . esc_html__( 'All', 'core' ) . '</button>';
		foreach ( $terms as $term ) {
			$html .= '<button type="button" class="ipg-filter-btn" aria-pressed="false" data-cat="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</button>';
		}
		$html .= '</div>';

		return $html;
	}
endif;


if ( ! function_exists( 'core_theme_posts_grid_shortcode' ) ) :
	/**
	 * [core_theme_posts_grid per_page="9" post_type="post" categories="4,9" exclude="featured" id=""]
	 *
	 * `per_page`   — posts per page (default 9).
	 * `categories` — comma-separated term IDs or slugs; omit for all categories.
	 * `exclude`    — comma-separated post IDs to leave out; the word `featured`
	 *                stands for the post [core_theme_featured_post] shows.
	 * `id`         — when set, tabs are omitted and the grid listens for a remote
	 *                core-theme:filter event fired by [core_theme_posts_tabs for="<id>"].
	 */
	function core_theme_posts_grid_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'per_page'   => 9,
				'post_type'  => 'post',
				'categories' => '',
				'exclude'    => '',
				'id'         => '',
			),
			$atts,
			'core_theme_posts_grid'
		);

		$per_page  = min( 50, max( 1, (int) $atts['per_page'] ) );
		$post_type = sanitize_key( $atts['post_type'] );
		$grid_id   = sanitize_html_class( $atts['id'] );

		if ( ! post_type_exists( $post_type ) ) {
			$post_type = 'post';
		}

		$taxonomy        = core_theme_posts_grid_resolve_taxonomy( $post_type );
		$allowed_cat_ids = core_theme_posts_grid_resolve_allowed_cats( $atts['categories'], $taxonomy );
		$exclude_ids     = core_theme_posts_grid_resolve_exclude( $atts['exclude'], $post_type );

		if ( empty( $allowed_cat_ids ) ) {
			return '<p class="ipg-no-posts">' . esc_html__( 'No categories found.', 'core' ) . '</p>';
		}

		// Initial query (page 1, no category filter).
		$query = new WP_Query( array(
			'post_type'      => $post_type,
			'posts_per_page' => $per_page,
			'paged'          => 1,
			'post_status'    => 'publish',
			'post__not_in'   => $exclude_ids,
			'tax_query'      => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $allowed_cat_ids,
				),
			),
		) );

		core_theme_posts_grid_enqueue_assets();

		$config = wp_json_encode( array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'core_theme_posts_grid_nonce' ),
			'perPage'    => $per_page,
			'postType'   => $post_type,
			'taxonomy'   => $taxonomy,
			'categories' => implode( ',', $allowed_cat_ids ),
			'exclude'    => implode( ',', $exclude_ids ),
		) );

		$grid_id_attr = $grid_id ? ' data-grid-id="' . esc_attr( $grid_id ) . '"' : '';
		$html = '<div class="ipg-wrapper" data-config="' . esc_attr( $config ) . '"' . $grid_id_attr . '>';

		// Embed tabs only in self-contained mode (no id attribute).
		if ( ! $grid_id ) {
			$terms = get_terms( array(
				'taxonomy'   => $taxonomy,
				'include'    => $allowed_cat_ids,
				'orderby'    => 'include',
				'hide_empty' => true,
			) );
			$html .= core_theme_posts_grid_render_tabs( is_wp_error( $terms ) ? array() : $terms );
		}

		$html .= '<div class="ipg-posts">' . core_theme_posts_grid_render_posts( $query, $taxonomy ) . '</div>';
		$html .= '<div class="ipg-pagination-wrap">' . core_theme_posts_grid_render_pagination( (int) $query->max_num_pages, 1 ) . '</div>';

		$html .= '</div>';

		wp_reset_postdata();

		return $html;
	}
endif;
add_shortcode( 'core_theme_posts_grid', 'core_theme_posts_grid_shortcode' );
// Backwards-compatible alias: some content used the callback name as the tag.
add_shortcode( 'core_theme_posts_grid_shortcode', 'core_theme_posts_grid_shortcode' );


if ( ! function_exists( 'core_theme_posts_tabs_shortcode' ) ) :
	/**
	 * [core_theme_posts_tabs for="blog" post_type="post" categories="4,9"]
	 *
	 * Renders standalone filter tabs that control a remote [core_theme_posts_grid id="blog"].
	 * `for`        — must match the `id` of the target [core_theme_posts_grid].
	 * `categories` — must match the `categories` passed to the target grid.
	 * `post_type`  — must match the `post_type` of the target grid.
	 */
	function core_theme_posts_tabs_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'for'        => '',
				'post_type'  => 'post',
				'categories' => '',
			),
			$atts,
			'core_theme_posts_tabs'
		);

		$grid_id   = sanitize_html_class( $atts['for'] );
		$post_type = sanitize_key( $atts['post_type'] );

		if ( ! $grid_id ) {
			return '';
		}

		if ( ! post_type_exists( $post_type ) ) {
			$post_type = 'post';
		}

		$taxonomy        = core_theme_posts_grid_resolve_taxonomy( $post_type );
		$allowed_cat_ids = core_theme_posts_grid_resolve_allowed_cats( $atts['categories'], $taxonomy );

		if ( empty( $allowed_cat_ids ) ) {
			return '';
		}

		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'include'    => $allowed_cat_ids,
			'orderby'    => 'include',
			'hide_empty' => true,
		) );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '';
		}

		core_theme_posts_grid_enqueue_assets();

		$html  = '<div class="ipg-tabs-remote" data-for="' . esc_attr( $grid_id ) . '">';
		$html .= core_theme_posts_grid_render_tabs( $terms );
		$html .= '</div>';

		return $html;
	}
endif;
add_shortcode( 'core_theme_posts_tabs', 'core_theme_posts_tabs_shortcode' );


if ( ! function_exists( 'core_theme_featured_post_shortcode' ) ) :
	/**
	 * [core_theme_featured_post id="" post_type="post" label="Featured" button="Read more"]
	 *
	 * One post as a wide card: image on the left; tag, category • date, title,
	 * excerpt and a Read more button on the right. Without `id` it shows the
	 * newest sticky post, or the newest post when none is sticky.
	 */
	function core_theme_featured_post_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'        => '',
				'post_type' => 'post',
				'label'     => __( 'Featured', 'core' ),
				'button'    => __( 'Read more', 'core' ),
			),
			$atts,
			'core_theme_featured_post'
		);

		$post_type = sanitize_key( $atts['post_type'] );
		if ( ! post_type_exists( $post_type ) ) {
			$post_type = 'post';
		}

		$post_id = core_theme_get_featured_post_id( $atts['id'], $post_type );
		if ( ! $post_id ) {
			return '';
		}

		core_theme_posts_grid_enqueue_assets();

		$taxonomy  = core_theme_posts_grid_resolve_taxonomy( $post_type );
		$permalink = get_permalink( $post_id );
		$title     = get_the_title( $post_id );
		$excerpt   = wp_trim_words( get_the_excerpt( $post_id ), 40 );

		$html = '<article class="ipg-featured">';

		if ( has_post_thumbnail( $post_id ) ) {
			$html .= '<a href="' . esc_url( $permalink ) . '" class="ipg-featured__image" tabindex="-1" aria-hidden="true">';
			$html .= get_the_post_thumbnail( $post_id, 'large' );
			$html .= '</a>';
		}

		$html .= '<div class="ipg-featured__body">';

		if ( '' !== trim( $atts['label'] ) ) {
			$html .= '<span class="ipg-featured__tag">' . esc_html( $atts['label'] ) . '</span>';
		}

		$html .= core_theme_posts_grid_post_meta( $post_id, $taxonomy, 'ipg-featured' );
		$html .= '<h2 class="ipg-featured__title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h2>';

		if ( $excerpt ) {
			$html .= '<p class="ipg-featured__excerpt">' . esc_html( $excerpt ) . '</p>';
		}

		/*
		 * `wp-element-button` is what theme.json's styles.elements.button
		 * compiles to, so this is the theme's primary button by construction.
		 */
		$html .= '<a class="wp-element-button ipg-featured__button" href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( sprintf( /* translators: %s: post title */ __( 'Read more: %s', 'core' ), $title ) ) . '">' . esc_html( $atts['button'] ) . '</a>';

		$html .= '</div>';
		$html .= '</article>';

		return $html;
	}
endif;
add_shortcode( 'core_theme_featured_post', 'core_theme_featured_post_shortcode' );
