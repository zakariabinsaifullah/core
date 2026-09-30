<?php
/**
 * Testimonials
 *
 * Meta fields, admin UI and the [core_theme_testimonials] shortcode for the
 * Testimonial post type registered in inc/post-types.php.
 *
 * A testimonial is the reviewer's name (post title), a designation and a
 * review message. It has no front-end single view; the shortcode is the only
 * place it is ever rendered.
 *
 * @package Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CORE_THEME_TESTIMONIAL_POST_TYPE = 'core-testimonial';
const CORE_THEME_TESTIMONIAL_DESIGNATION_KEY = '_core_theme_testimonial_designation';
const CORE_THEME_TESTIMONIAL_MESSAGE_KEY = '_core_theme_testimonial_message';
const CORE_THEME_TESTIMONIAL_VARIANT_KEY = '_core_theme_testimonial_variant';

/**
 * Card colours, in the order the design cycles them.
 *
 * White, black, tiber, then repeat — so the fourth card is white again. The
 * tiber is #04262f, the theme's core-theme-tiber.
 *
 * Tilt runs on a two-step alternation rather than this three-step one (see
 * assets/css/testimonials.css), so the two only realign every sixth card.
 */
const CORE_THEME_TESTIMONIAL_VARIANT_CYCLE = array( 'white', 'black', 'tiber' );


if ( ! function_exists( 'core_theme_testimonial_variants' ) ) :
	/**
	 * The selectable card colours.
	 *
	 * @return array<string, string> Slug => label.
	 */
	function core_theme_testimonial_variants() {
		return array(
			'white' => __( 'White', 'core' ),
			'black' => __( 'Black', 'core' ),
			'tiber' => __( 'Tiber', 'core' ),
		);
	}
endif;


if ( ! function_exists( 'core_theme_testimonial_variant_for_index' ) ) :
	/**
	 * The card colour for a position in the list.
	 *
	 * Colour is assigned by position rather than stored per testimonial, so the
	 * pattern in the design holds no matter how many testimonials exist. A
	 * testimonial can opt out with the Card Colour field, which is what
	 * `$override` carries.
	 *
	 * @param int    $index    Zero-based position in the rendered list.
	 * @param string $override Per-testimonial choice, or '' to follow the cycle.
	 * @return string Variant slug.
	 */
	function core_theme_testimonial_variant_for_index( $index, $override = '' ) {
		$variants = core_theme_testimonial_variants();

		if ( '' !== $override && isset( $variants[ $override ] ) ) {
			return $override;
		}

		$cycle = CORE_THEME_TESTIMONIAL_VARIANT_CYCLE;

		return $cycle[ $index % count( $cycle ) ];
	}
endif;


if ( ! function_exists( 'core_theme_testimonial_auto_index' ) ) :
	/**
	 * Where a testimonial falls in the shortcode's default ordering.
	 *
	 * Used by the admin list column to show which colour the automatic cycle
	 * lands on. It assumes the shortcode's own defaults (newest first) — a
	 * shortcode given `order` or `orderby` renders a different sequence, and
	 * the colour shown here is then indicative rather than exact.
	 *
	 * The whole ID list is fetched once per request; the list table calls this
	 * for every row.
	 *
	 * @param int $post_id Testimonial ID.
	 * @return int Zero-based position, or 0 when not found.
	 */
	function core_theme_testimonial_auto_index( $post_id ) {
		static $order = null;

		if ( null === $order ) {
			$ids = get_posts(
				array(
					'post_type'        => CORE_THEME_TESTIMONIAL_POST_TYPE,
					'post_status'      => 'publish',
					'posts_per_page'   => -1,
					'orderby'          => 'date',
					'order'            => 'DESC',
					'fields'           => 'ids',
					'no_found_rows'    => true,
					'suppress_filters' => false,
				)
			);

			$order = array_flip( $ids );
		}

		return isset( $order[ $post_id ] ) ? (int) $order[ $post_id ] : 0;
	}
endif;


// =============================================================================
// Meta
// =============================================================================

if ( ! function_exists( 'core_theme_register_testimonial_meta' ) ) :
	/**
	 * Registers the testimonial meta so it is sanitised consistently and
	 * deleted with the post.
	 */
	function core_theme_register_testimonial_meta() {
		// The first argument is the post type: register_post_meta() sets
		// `object_subtype` from it and ignores any passed in $args, so naming
		// the type here is what scopes the meta to testimonials.
		$common = array(
			'single'        => true,
			'type'          => 'string',
			'show_in_rest'  => false,
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		);

		register_post_meta(
			CORE_THEME_TESTIMONIAL_POST_TYPE,
			CORE_THEME_TESTIMONIAL_DESIGNATION_KEY,
			array_merge(
				$common,
				array(
					'description'       => __( 'Reviewer designation.', 'core' ),
					'sanitize_callback' => 'sanitize_text_field',
				)
			)
		);

		register_post_meta(
			CORE_THEME_TESTIMONIAL_POST_TYPE,
			CORE_THEME_TESTIMONIAL_MESSAGE_KEY,
			array_merge(
				$common,
				array(
					'description'       => __( 'Review message.', 'core' ),
					// Line breaks are meaningful here, so the message keeps them
					// and is escaped on output rather than stripped on input.
					'sanitize_callback' => 'sanitize_textarea_field',
				)
			)
		);

		register_post_meta(
			CORE_THEME_TESTIMONIAL_POST_TYPE,
			CORE_THEME_TESTIMONIAL_VARIANT_KEY,
			array_merge(
				$common,
				array(
					'description'       => __( 'Card colour override.', 'core' ),
					'sanitize_callback' => 'sanitize_key',
				)
			)
		);
	}
endif;
add_action( 'init', 'core_theme_register_testimonial_meta' );


if ( ! function_exists( 'core_theme_testimonial_meta_box' ) ) :
	/**
	 * Adds the testimonial details box to the edit screen.
	 */
	function core_theme_testimonial_meta_box() {
		add_meta_box(
			'core-theme-testimonial-details',
			__( 'Testimonial Details', 'core' ),
			'core_theme_render_testimonial_meta_box',
			CORE_THEME_TESTIMONIAL_POST_TYPE,
			'normal',
			'high'
		);
	}
endif;
add_action( 'add_meta_boxes', 'core_theme_testimonial_meta_box' );


if ( ! function_exists( 'core_theme_render_testimonial_meta_box' ) ) :
	/**
	 * Renders the designation, message and colour fields.
	 *
	 * @param WP_Post $post Current post.
	 */
	function core_theme_render_testimonial_meta_box( $post ) {
		wp_nonce_field( 'core_theme_save_testimonial', 'core_theme_testimonial_nonce' );

		$designation = get_post_meta( $post->ID, CORE_THEME_TESTIMONIAL_DESIGNATION_KEY, true );
		$message     = get_post_meta( $post->ID, CORE_THEME_TESTIMONIAL_MESSAGE_KEY, true );
		$variant     = get_post_meta( $post->ID, CORE_THEME_TESTIMONIAL_VARIANT_KEY, true );
		?>
		<p>
			<label for="core-theme-testimonial-designation"><strong><?php esc_html_e( 'Designation', 'core' ); ?></strong></label><br />
			<input
				type="text"
				id="core-theme-testimonial-designation"
				name="core_theme_testimonial_designation"
				class="widefat"
				value="<?php echo esc_attr( $designation ); ?>"
				placeholder="<?php esc_attr_e( 'Customer', 'core' ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Shown under the reviewer name.', 'core' ); ?></span>
		</p>
		<p>
			<label for="core-theme-testimonial-message"><strong><?php esc_html_e( 'Review Message', 'core' ); ?></strong></label><br />
			<textarea
				id="core-theme-testimonial-message"
				name="core_theme_testimonial_message"
				class="widefat"
				rows="6"
				placeholder="<?php esc_attr_e( 'What the reviewer said…', 'core' ); ?>"
			><?php echo esc_textarea( $message ); ?></textarea>
		</p>
		<p>
			<label for="core-theme-testimonial-variant"><strong><?php esc_html_e( 'Card Colour', 'core' ); ?></strong></label><br />
			<select id="core-theme-testimonial-variant" name="core_theme_testimonial_variant">
				<option value=""><?php esc_html_e( 'Automatic (follows the design pattern)', 'core' ); ?></option>
				<?php foreach ( core_theme_testimonial_variants() as $slug => $label ) : ?>
					<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $variant, $slug ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span class="description">
				<?php esc_html_e( 'Leave automatic unless this testimonial must always be a particular colour. The quote icon follows the card colour either way.', 'core' ); ?>
			</span>
		</p>
		<?php
	}
endif;


if ( ! function_exists( 'core_theme_save_testimonial_meta' ) ) :
	/**
	 * Saves the testimonial fields.
	 *
	 * @param int $post_id Post ID.
	 */
	function core_theme_save_testimonial_meta( $post_id ) {
		if ( ! isset( $_POST['core_theme_testimonial_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['core_theme_testimonial_nonce'] ) ), 'core_theme_save_testimonial' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$designation = isset( $_POST['core_theme_testimonial_designation'] )
			? sanitize_text_field( wp_unslash( $_POST['core_theme_testimonial_designation'] ) )
			: '';

		$message = isset( $_POST['core_theme_testimonial_message'] )
			? sanitize_textarea_field( wp_unslash( $_POST['core_theme_testimonial_message'] ) )
			: '';

		$variant = isset( $_POST['core_theme_testimonial_variant'] )
			? sanitize_key( wp_unslash( $_POST['core_theme_testimonial_variant'] ) )
			: '';

		if ( ! isset( core_theme_testimonial_variants()[ $variant ] ) ) {
			$variant = '';
		}

		update_post_meta( $post_id, CORE_THEME_TESTIMONIAL_DESIGNATION_KEY, $designation );
		update_post_meta( $post_id, CORE_THEME_TESTIMONIAL_MESSAGE_KEY, $message );
		update_post_meta( $post_id, CORE_THEME_TESTIMONIAL_VARIANT_KEY, $variant );
	}
endif;
add_action( 'save_post_' . CORE_THEME_TESTIMONIAL_POST_TYPE, 'core_theme_save_testimonial_meta' );


if ( ! function_exists( 'core_theme_testimonial_admin_columns' ) ) :
	/**
	 * Shows the designation in the list table, so reviewers are
	 * distinguishable at a glance.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	function core_theme_testimonial_admin_columns( $columns ) {
		$date = $columns['date'] ?? null;
		unset( $columns['date'] );

		$columns['core_theme_designation'] = __( 'Designation', 'core' );
		$columns['core_theme_colour']      = __( 'Card Colour', 'core' );

		if ( $date ) {
			$columns['date'] = $date;
		}

		return $columns;
	}
endif;
add_filter( 'manage_' . CORE_THEME_TESTIMONIAL_POST_TYPE . '_posts_columns', 'core_theme_testimonial_admin_columns' );


if ( ! function_exists( 'core_theme_testimonial_admin_column_content' ) ) :
	/**
	 * Fills the custom list table column.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	function core_theme_testimonial_admin_column_content( $column, $post_id ) {
		if ( 'core_theme_designation' === $column ) {
			echo esc_html( get_post_meta( $post_id, CORE_THEME_TESTIMONIAL_DESIGNATION_KEY, true ) );
			return;
		}

		if ( 'core_theme_colour' !== $column ) {
			return;
		}

		$override = (string) get_post_meta( $post_id, CORE_THEME_TESTIMONIAL_VARIANT_KEY, true );
		$index    = core_theme_testimonial_auto_index( $post_id );
		$variant  = core_theme_testimonial_variant_for_index( $index, $override );
		$labels   = core_theme_testimonial_variants();

		$swatch = array(
			'white' => '#ffffff',
			'black' => '#000000',
			'tiber' => '#04262f',
		);

		printf(
			'<span style="display:inline-block;width:14px;height:14px;border-radius:3px;border:1px solid #c3c4c7;background:%1$s;vertical-align:-2px;margin-right:6px;"></span>%2$s',
			esc_attr( $swatch[ $variant ] ?? '#ffffff' ),
			esc_html( $labels[ $variant ] ?? $variant )
		);

		if ( '' === $override ) {
			echo ' <span style="color:#646970;">' . esc_html__( '(automatic)', 'core' ) . '</span>';
		}
	}
endif;
add_action( 'manage_' . CORE_THEME_TESTIMONIAL_POST_TYPE . '_posts_custom_column', 'core_theme_testimonial_admin_column_content', 10, 2 );


// =============================================================================
// Settings
// =============================================================================

const CORE_THEME_TESTIMONIAL_OPTION = 'core_theme_testimonial_settings';


if ( ! function_exists( 'core_theme_testimonial_settings' ) ) :
	/**
	 * Carousel settings, with defaults filled in.
	 *
	 * These are the shortcode's defaults; an attribute written into the
	 * shortcode still wins over them.
	 *
	 * @return array{autoplay: bool, speed: int, loop: bool, pagination: bool}
	 */
	function core_theme_testimonial_settings() {
		$saved = get_option( CORE_THEME_TESTIMONIAL_OPTION, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return array(
			'autoplay'   => ! empty( $saved['autoplay'] ),
			'speed'      => isset( $saved['speed'] ) ? max( 1000, (int) $saved['speed'] ) : 5000,

			/*
			 * Off by default. Looping makes Swiper ignore centeredSlidesBounds,
			 * which is what keeps the first card flush with the left edge, so
			 * the default favours the design's framing over endless scrolling.
			 */
			'loop'       => ! empty( $saved['loop'] ),
			'pagination' => ! isset( $saved['pagination'] ) ? true : ! empty( $saved['pagination'] ),
		);
	}
endif;


if ( ! function_exists( 'core_theme_testimonial_sanitize_settings' ) ) :
	/**
	 * Sanitises the settings form.
	 *
	 * @param mixed $input Raw submission.
	 * @return array Clean settings.
	 */
	function core_theme_testimonial_sanitize_settings( $input ) {
		$input = is_array( $input ) ? $input : array();

		return array(
			'autoplay'   => ! empty( $input['autoplay'] ) ? 1 : 0,
			'speed'      => isset( $input['speed'] ) ? max( 1000, (int) $input['speed'] ) : 5000,
			'loop'       => ! empty( $input['loop'] ) ? 1 : 0,
			'pagination' => ! empty( $input['pagination'] ) ? 1 : 0,
		);
	}
endif;


if ( ! function_exists( 'core_theme_testimonial_register_settings' ) ) :
	/**
	 * Registers the settings store.
	 */
	function core_theme_testimonial_register_settings() {
		register_setting(
			'core_theme_testimonial_settings_group',
			CORE_THEME_TESTIMONIAL_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'core_theme_testimonial_sanitize_settings',
				'default'           => array(),
			)
		);
	}
endif;
add_action( 'admin_init', 'core_theme_testimonial_register_settings' );


if ( ! function_exists( 'core_theme_testimonial_settings_menu' ) ) :
	/**
	 * Adds the settings screen under the Testimonials menu.
	 */
	function core_theme_testimonial_settings_menu() {
		add_submenu_page(
			'edit.php?post_type=' . CORE_THEME_TESTIMONIAL_POST_TYPE,
			__( 'Testimonial Settings', 'core' ),
			__( 'Settings', 'core' ),
			'manage_options',
			'core-theme-testimonial-settings',
			'core_theme_testimonial_render_settings_page'
		);
	}
endif;
add_action( 'admin_menu', 'core_theme_testimonial_settings_menu' );


if ( ! function_exists( 'core_theme_testimonial_render_settings_page' ) ) :
	/**
	 * Renders the carousel settings form.
	 */
	function core_theme_testimonial_render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = core_theme_testimonial_settings();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Testimonial Settings', 'core' ); ?></h1>
			<p>
				<?php esc_html_e( 'Defaults for the testimonials carousel. An attribute written into the shortcode overrides whatever is set here.', 'core' ); ?>
				<code>[core_theme_testimonials]</code>
			</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'core_theme_testimonial_settings_group' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Autoplay', 'core' ); ?></th>
						<td>
							<label>
								<input
									type="checkbox"
									name="<?php echo esc_attr( CORE_THEME_TESTIMONIAL_OPTION ); ?>[autoplay]"
									value="1"
									<?php checked( $settings['autoplay'] ); ?>
								/>
								<?php esc_html_e( 'Advance the cards automatically', 'core' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Always off for visitors who have asked their system for reduced motion.', 'core' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="core-theme-testimonial-speed"><?php esc_html_e( 'Autoplay Speed', 'core' ); ?></label>
						</th>
						<td>
							<input
								type="number"
								id="core-theme-testimonial-speed"
								name="<?php echo esc_attr( CORE_THEME_TESTIMONIAL_OPTION ); ?>[speed]"
								value="<?php echo esc_attr( (string) $settings['speed'] ); ?>"
								min="1000"
								step="500"
								class="small-text"
							/>
							<?php esc_html_e( 'milliseconds', 'core' ); ?>
							<p class="description"><?php esc_html_e( 'How long each card is held. Minimum 1000.', 'core' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Loop', 'core' ); ?></th>
						<td>
							<label>
								<input
									type="checkbox"
									name="<?php echo esc_attr( CORE_THEME_TESTIMONIAL_OPTION ); ?>[loop]"
									value="1"
									<?php checked( $settings['loop'] ); ?>
								/>
								<?php esc_html_e( 'Wrap around from the last card to the first', 'core' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Off by default. Looping makes the deck start part-way in rather than flush with the left edge, because an endless track has no start or end to align to.', 'core' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Pagination', 'core' ); ?></th>
						<td>
							<label>
								<input
									type="checkbox"
									name="<?php echo esc_attr( CORE_THEME_TESTIMONIAL_OPTION ); ?>[pagination]"
									value="1"
									<?php checked( $settings['pagination'] ); ?>
								/>
								<?php esc_html_e( 'Show the dots beneath the carousel', 'core' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
endif;


// =============================================================================
// Shortcode
// =============================================================================

if ( ! function_exists( 'core_theme_testimonials_shortcode' ) ) :
	/**
	 * Renders the testimonials carousel.
	 *
	 * Usage: [core_theme_testimonials count="-1" order="DESC" orderby="date" autoplay="yes" speed="5000"]
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Markup, or '' when there is nothing to show.
	 */
	function core_theme_testimonials_shortcode( $atts ) {
		// Settings supply the defaults; a shortcode attribute overrides them.
		$settings = core_theme_testimonial_settings();

		$atts = shortcode_atts(
			array(
				'count'      => -1,
				'order'      => 'DESC',
				'orderby'    => 'date',
				'autoplay'   => $settings['autoplay'] ? 'yes' : 'no',
				'speed'      => $settings['speed'],
				'loop'       => $settings['loop'] ? 'yes' : 'no',
				'pagination' => $settings['pagination'] ? 'yes' : 'no',
			),
			$atts,
			'core_theme_testimonials'
		);

		$query = new WP_Query(
			array(
				'post_type'              => CORE_THEME_TESTIMONIAL_POST_TYPE,
				'post_status'            => 'publish',
				'posts_per_page'         => (int) $atts['count'],
				'order'                  => 'ASC' === strtoupper( $atts['order'] ) ? 'ASC' : 'DESC',
				'orderby'                => sanitize_key( $atts['orderby'] ),
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
			)
		);

		if ( ! $query->have_posts() ) {
			return '';
		}

		core_theme_enqueue_testimonial_assets();

		$options = wp_json_encode(
			array(
				'autoplay' => 'yes' === $atts['autoplay'],
				'delay'    => max( 1000, (int) $atts['speed'] ),
				'loop'     => 'yes' === $atts['loop'],
			)
		);

		ob_start();
		?>
		<div class="core-theme-testimonials">
			<div class="swiper core-theme-testimonials__swiper" data-core-theme-testimonials="<?php echo esc_attr( $options ); ?>">
				<div class="swiper-wrapper">
					<?php
					$index = 0;
					while ( $query->have_posts() ) :
						$query->the_post();
						$post_id = get_the_ID();

						$variant = core_theme_testimonial_variant_for_index(
							$index,
							(string) get_post_meta( $post_id, CORE_THEME_TESTIMONIAL_VARIANT_KEY, true )
						);

						$message     = (string) get_post_meta( $post_id, CORE_THEME_TESTIMONIAL_MESSAGE_KEY, true );
						$designation = (string) get_post_meta( $post_id, CORE_THEME_TESTIMONIAL_DESIGNATION_KEY, true );
						?>
						<div class="swiper-slide core-theme-testimonial is-<?php echo esc_attr( $variant ); ?>">
							<figure class="core-theme-testimonial__card">
								<span class="core-theme-testimonial__quote" aria-hidden="true"></span>
								<?php if ( '' !== $message ) : ?>
									<blockquote class="core-theme-testimonial__message">
										<?php echo nl2br( esc_html( $message ) ); ?>
									</blockquote>
								<?php endif; ?>
								<figcaption class="core-theme-testimonial__author">
									<span class="core-theme-testimonial__name"><?php the_title(); ?></span>
									<?php if ( '' !== $designation ) : ?>
										<span class="core-theme-testimonial__designation"><?php echo esc_html( $designation ); ?></span>
									<?php endif; ?>
								</figcaption>
							</figure>
						</div>
						<?php
						++$index;
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
			<?php if ( 'yes' === $atts['pagination'] ) : ?>
				<div class="core-theme-testimonials__pagination swiper-pagination"></div>
			<?php endif; ?>
		</div>
		<?php
		return (string) ob_get_clean();
	}
endif;
add_shortcode( 'core_theme_testimonials', 'core_theme_testimonials_shortcode' );


if ( ! function_exists( 'core_theme_enqueue_testimonial_assets' ) ) :
	/**
	 * Enqueues the carousel assets, and hands the stylesheet the quote glyph
	 * URL so the badge can be tinted per card colour rather than shipping a
	 * separate icon for each.
	 */
	function core_theme_enqueue_testimonial_assets() {
		$version = wp_get_theme()->get( 'Version' );

		wp_enqueue_style( 'core-theme-swiper-style' );
		wp_enqueue_script( 'core-theme-swiper-script' );

		wp_enqueue_style(
			'core-theme-testimonials',
			get_theme_file_uri( 'assets/css/testimonials.css' ),
			array( 'core-theme-swiper-style' ),
			$version
		);

		wp_enqueue_script(
			'core-theme-testimonials',
			get_theme_file_uri( 'assets/js/testimonials.js' ),
			array( 'core-theme-swiper-script' ),
			$version,
			true
		);

		$glyph = get_theme_file_uri( 'assets/images/testimonials/quote-glyph.png' );

		wp_add_inline_style(
			'core-theme-testimonials',
			'.core-theme-testimonials{--core-theme-testimonial-quote:url("' . esc_url_raw( $glyph ) . '");}'
		);
	}
endif;
