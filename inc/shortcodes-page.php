<?php
/**
 * Core — Shortcodes Reference Page
 *
 * Adds an admin page under Appearance that showcases the shortcodes
 * bundled with this theme, each with a one-click copy button.
 *
 * @package Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Register the shortcode catalogue ──────────────────────────────────────────

if ( ! function_exists( 'core_theme_get_shortcodes' ) ) :
	/**
	 * Returns the list of theme shortcodes to display on the reference page.
	 *
	 * Each entry: label, tag, description, one or more copy-ready examples,
	 * and an optional list of attributes (name => description).
	 *
	 * Examples run from the bare tag — every attribute is optional on both
	 * shortcodes — to a form spelling out each attribute at its default.
	 */
	function core_theme_get_shortcodes() {
		return array(
			array(
				'title'       => __( 'Opening Roles', 'core' ),
				'tag'         => 'opening_roles',
				'description' => __( 'Lists open roles as cards: job type, number of open positions, the role title and an Apply now link. Only roles switched on in the Active column of All Open Roles are listed. Add roles under <code>Open Roles</code> (each has Active, Vacancies and Apply Link) and their categories under <code>Open Roles &rarr; Job Types</code>.', 'core' ),
				'examples'    => array(
					array(
						'label' => __( 'Basic usage', 'core' ),
						'note'  => __( 'Every attribute is optional — this shows every active role, newest first, three per row.', 'core' ),
						'code'  => '[opening_roles]',
					),
					array(
						'label' => __( 'One team only', 'core' ),
						'note'  => __( 'Limit to one or more Job Types by slug or ID.', 'core' ),
						'code'  => '[opening_roles job_type="tax"]',
					),
					array(
						'label' => __( 'In the order you set', 'core' ),
						'note'  => __( 'Sort by title, or by date with <code>order="ASC"</code> for oldest first.', 'core' ),
						'code'  => '[opening_roles orderby="title" order="ASC"]',
					),
					array(
						'label' => __( 'All optional attributes', 'core' ),
						'note'  => __( 'Each attribute shown at its default value.', 'core' ),
						'code'  => '[opening_roles columns="3" count="-1" job_type="" orderby="date" order="DESC" empty="There are no open roles right now."]',
					),
				),
				'attrs'       => array(
					array( 'name' => 'columns',  'default' => '3',    'desc' => __( 'Cards per row on desktop, 1&ndash;4. Tablet and mobile always show one per row.', 'core' ) ),
					array( 'name' => 'count',    'default' => '-1',   'desc' => __( 'How many roles to show. <code>-1</code> shows every active one.', 'core' ) ),
					array( 'name' => 'job_type', 'default' => '',     'desc' => __( 'Comma-separated Job Type slugs or IDs. Empty shows every type.', 'core' ) ),
					array( 'name' => 'orderby',  'default' => 'date', 'desc' => __( '<code>date</code> or <code>title</code>.', 'core' ) ),
					array( 'name' => 'order',    'default' => 'DESC', 'desc' => __( 'Sort direction &mdash; <code>ASC</code> or <code>DESC</code>.', 'core' ) ),
					array( 'name' => 'empty',    'default' => '',     'desc' => __( 'Message shown when no role is open. Set it to an empty value to show nothing.', 'core' ) ),
				),
			),
			array(
				'title'       => __( 'Featured Post', 'core' ),
				'tag'         => 'core_theme_featured_post',
				'description' => __( 'One post as a wide card for the top of the blog: featured image on the left; a Featured tag, category and date, title, excerpt and a Read more button on the right. Without an <code>id</code> it shows the newest <strong>sticky</strong> post (Post settings &rarr; Stick to the top of the blog), or the newest post when none is sticky.', 'core' ),
				'examples'    => array(
					array(
						'label' => __( 'Basic usage', 'core' ),
						'note'  => __( 'Shows the newest sticky post. Make a post sticky to feature it.', 'core' ),
						'code'  => '[core_theme_featured_post]',
					),
					array(
						'label' => __( 'A specific post', 'core' ),
						'note'  => __( 'Pick the post by ID (shown in the URL when editing it).', 'core' ),
						'code'  => '[core_theme_featured_post id="123"]',
					),
					array(
						'label' => __( 'Paired with the grid', 'core' ),
						'note'  => __( 'Put the grid after it with <code>exclude="featured"</code> so the featured post is not repeated in the grid. If you set an <code>id</code> here, use the same ID in the grid&rsquo;s <code>exclude</code>.', 'core' ),
						'code'  => "[core_theme_featured_post]\n[core_theme_posts_grid per_page=\"9\" exclude=\"featured\"]",
					),
					array(
						'label' => __( 'All optional attributes', 'core' ),
						'note'  => __( 'Each attribute shown at its default value.', 'core' ),
						'code'  => '[core_theme_featured_post id="" post_type="post" label="Featured" button="Read more"]',
					),
				),
				'attrs'       => array(
					array( 'name' => 'id',        'default' => '',          'desc' => __( 'Post ID to feature. Empty uses the newest sticky post, then the newest post.', 'core' ) ),
					array( 'name' => 'post_type', 'default' => 'post',      'desc' => __( 'Which post type to pick from.', 'core' ) ),
					array( 'name' => 'label',     'default' => 'Featured',  'desc' => __( 'Text of the small tag above the title. Empty hides the tag.', 'core' ) ),
					array( 'name' => 'button',    'default' => 'Read more', 'desc' => __( 'Button label.', 'core' ) ),
				),
			),
			array(
				'title'       => __( 'Posts Grid', 'core' ),
				'tag'         => 'core_theme_posts_grid',
				'description' => __( 'Renders posts as white cards in a three-column grid (one column on tablet and mobile): featured image, category and date, title, excerpt and a Read more link. Category pills and pagination filter the grid in place, without reloading the page. Designed for a light section.', 'core' ),
				'examples'    => array(
					array(
						'label' => __( 'Basic usage', 'core' ),
						'note'  => __( 'Every attribute is optional — this shows the 9 newest posts with a pill for every category that has posts in it.', 'core' ),
						'code'  => '[core_theme_posts_grid]',
					),
					array(
						'label' => __( 'Blog page, under the featured post', 'core' ),
						'note'  => __( 'Leaves out the post [core_theme_featured_post] is showing, so it is not listed twice.', 'core' ),
						'code'  => '[core_theme_posts_grid per_page="9" exclude="featured"]',
					),
					array(
						'label' => __( 'Only certain categories', 'core' ),
						'note'  => __( 'Limits both the posts and the pills to the categories you name, by slug or by ID.', 'core' ),
						'code'  => '[core_theme_posts_grid per_page="6" categories="business-tax,outsourced-cfo"]',
					),
					array(
						'label' => __( 'Pills somewhere else on the page', 'core' ),
						'note'  => __( 'Give the grid an <code>id</code> and it renders without pills; a separate [core_theme_posts_tabs] block with a matching <code>for</code> then drives it.', 'core' ),
						'code'  => '[core_theme_posts_grid id="blog" per_page="9"]',
					),
					array(
						'label' => __( 'All optional attributes', 'core' ),
						'note'  => __( 'Each attribute shown at its default value.', 'core' ),
						'code'  => '[core_theme_posts_grid per_page="9" post_type="post" categories="" exclude="" id=""]',
					),
				),
				'attrs'       => array(
					array( 'name' => 'per_page',   'default' => '9',    'desc' => __( 'Posts per page, up to 50. The rest are reached through the pagination beneath the grid.', 'core' ) ),
					array( 'name' => 'post_type',  'default' => 'post', 'desc' => __( 'Which post type to list. The pills follow that type&rsquo;s own hierarchical taxonomy.', 'core' ) ),
					array( 'name' => 'categories', 'default' => '',     'desc' => __( 'Comma-separated term slugs or IDs. Leave empty for every category that has posts in it.', 'core' ) ),
					array( 'name' => 'exclude',    'default' => '',     'desc' => __( 'Comma-separated post IDs to leave out. The word <code>featured</code> stands for the post [core_theme_featured_post] shows without an id.', 'core' ) ),
					array( 'name' => 'id',         'default' => '',     'desc' => __( 'Set this to move the pills out of the grid and into a [core_theme_posts_tabs] block with the same value in its <code>for</code> attribute.', 'core' ) ),
				),
			),
			array(
				'title'       => __( 'Posts Tabs', 'core' ),
				'tag'         => 'core_theme_posts_tabs',
				'description' => __( 'The category pills on their own, for driving a [core_theme_posts_grid] placed elsewhere on the page. Only needed when the two have to sit in separate blocks &mdash; a grid without an <code>id</code> already draws its own tabs.', 'core' ),
				'examples'    => array(
					array(
						'label' => __( 'Paired with a grid', 'core' ),
						'note'  => __( 'The <code>for</code> here must match the <code>id</code> on the grid, and <code>categories</code> and <code>post_type</code> must match what the grid was given.', 'core' ),
						'code'  => '[core_theme_posts_tabs for="blog"]',
					),
					array(
						'label' => __( 'All optional attributes', 'core' ),
						'note'  => __( 'Only <code>for</code> is required — without it nothing renders.', 'core' ),
						'code'  => '[core_theme_posts_tabs for="blog" post_type="post" categories=""]',
					),
				),
				'attrs'       => array(
					array( 'name' => 'for',        'default' => '',     'desc' => __( 'Required. The <code>id</code> of the grid these tabs control.', 'core' ) ),
					array( 'name' => 'post_type',  'default' => 'post', 'desc' => __( 'Must match the grid&rsquo;s <code>post_type</code>.', 'core' ) ),
					array( 'name' => 'categories', 'default' => '',     'desc' => __( 'Must match the grid&rsquo;s <code>categories</code>, so both show the same set of tabs.', 'core' ) ),
				),
			),
			array(
				'title'       => __( 'Related Posts', 'core' ),
				'tag'         => 'core_theme_related_posts',
				'description' => __( 'A row of blog cards for the single post template: posts that share a category with the post being read, newest first, topped up with the newest other posts when there are not enough. Same card as the Posts Grid. Used in the Single Posts template under &ldquo;Related Articles&rdquo;.', 'core' ),
				'examples'    => array(
					array(
						'label' => __( 'Basic usage', 'core' ),
						'note'  => __( 'Three related cards. Place it in the single post template.', 'core' ),
						'code'  => '[core_theme_related_posts]',
					),
					array(
						'label' => __( 'All optional attributes', 'core' ),
						'note'  => __( 'Each attribute shown at its default value.', 'core' ),
						'code'  => '[core_theme_related_posts count="3" post_type="post"]',
					),
				),
				'attrs'       => array(
					array( 'name' => 'count',     'default' => '3',    'desc' => __( 'How many cards, up to 12. Laid out three per row on desktop, one per row on tablet and mobile.', 'core' ) ),
					array( 'name' => 'post_type', 'default' => 'post', 'desc' => __( 'Which post type to pick from.', 'core' ) ),
				),
			),
			array(
				'title'       => __( 'Testimonials', 'core' ),
				'tag'         => 'core_theme_testimonials',
				'description' => __( 'Renders published testimonials as a swipeable deck of tilted cards, each showing the quote icon, the review message, the reviewer name and their designation. Add entries under <code>Testimonials</code> in the admin menu. Autoplay, speed, loop and pagination default to whatever is set in <code>Testimonials &rarr; Settings</code>; the attributes below override them per shortcode.', 'core' ),
				'examples'    => array(
					array(
						'label' => __( 'Basic usage', 'core' ),
						'note'  => __( 'Every attribute is optional — this shows all published testimonials, newest first, autoplaying.', 'core' ),
						'code'  => '[core_theme_testimonials]',
					),
					array(
						'label' => __( 'A fixed set, in the order you arranged them', 'core' ),
						'note'  => __( 'Pair <code>orderby="menu_order"</code> with the Order field on each testimonial to control the sequence by hand.', 'core' ),
						'code'  => '[core_theme_testimonials count="6" order="ASC" orderby="menu_order"]',
					),
					array(
						'label' => __( 'Overriding the settings for one carousel', 'core' ),
						'note'  => __( 'Autoplay this instance regardless of what <code>Testimonials &rarr; Settings</code> says.', 'core' ),
						'code'  => '[core_theme_testimonials autoplay="yes" speed="4000" loop="yes" pagination="yes"]',
					),
				),
				'attrs'       => array(
					array( 'name' => 'count',      'default' => '-1',         'desc' => __( 'How many testimonials to show. <code>-1</code> shows every published one.', 'core' ) ),
					array( 'name' => 'order',      'default' => 'DESC',       'desc' => __( 'Sort direction &mdash; <code>ASC</code> or <code>DESC</code>.', 'core' ) ),
					array( 'name' => 'orderby',    'default' => 'date',       'desc' => __( 'Any WP_Query orderby value. Use <code>menu_order</code> to order them by hand, or <code>rand</code> to shuffle.', 'core' ) ),
					array( 'name' => 'autoplay',   'default' => '(settings)', 'desc' => __( 'Advance on its own &mdash; <code>yes</code> or <code>no</code>. Off by default, and always off for visitors who have asked for reduced motion.', 'core' ) ),
					array( 'name' => 'speed',      'default' => '(settings)', 'desc' => __( 'Milliseconds each card is held when autoplaying. Values below 1000 are raised to 1000.', 'core' ) ),
					array( 'name' => 'loop',       'default' => '(settings)', 'desc' => __( 'Wrap around from the last card to the first &mdash; <code>yes</code> or <code>no</code>.', 'core' ) ),
					array( 'name' => 'pagination', 'default' => '(settings)', 'desc' => __( 'Show the dots beneath the carousel &mdash; <code>yes</code> or <code>no</code>.', 'core' ) ),
				),
			),
		);
	}
endif;

// ── Add page under Appearance ─────────────────────────────────────────────────

add_action( 'admin_menu', 'core_theme_shortcodes_add_menu' );

if ( ! function_exists( 'core_theme_shortcodes_add_menu' ) ) :
	function core_theme_shortcodes_add_menu() {
		add_theme_page(
			__( 'Core Shortcodes', 'core' ),
			__( 'Core', 'core' ),
			'edit_theme_options',
			'core-theme-shortcodes',
			'core_theme_shortcodes_render_page'
		);
	}
endif;

// ── Enqueue admin assets on the Shortcodes page ────────────────────────────────

add_action( 'admin_enqueue_scripts', 'core_theme_shortcodes_admin_assets' );

if ( ! function_exists( 'core_theme_shortcodes_admin_assets' ) ) :
	function core_theme_shortcodes_admin_assets( $hook ) {
		if ( 'appearance_page_core-theme-shortcodes' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'core-theme-shortcodes-copy',
			get_theme_file_uri( 'assets/js/shortcodes-copy.js' ),
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
endif;

// ── Render the page ───────────────────────────────────────────────────────────

if ( ! function_exists( 'core_theme_shortcodes_render_page' ) ) :
	function core_theme_shortcodes_render_page() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$shortcodes = core_theme_get_shortcodes();
		?>
		<div class="wrap psr-wrap">

			<style>
				.psr-wrap { max-width: 960px; }
				.psr-header {
					display: flex;
					align-items: center;
					gap: 14px;
					margin: 24px 0 32px;
				}
				.psr-header__logo {
					width: 30px;
					height: 30px;
					display: flex;
					align-items: center;
					justify-content: center;
					flex-shrink: 0;
				}
				.psr-header__logo svg { display: block; }
				.psr-header__text h1 {
					margin: 0;
					font-size: 22px;
					font-weight: 600;
					line-height: 1.2;
					color: #1d2327;
				}
				.psr-header__text p {
					margin: 4px 0 0;
					color: #646970;
					font-size: 13px;
				}

				.psr-grid {
					display: flex;
					flex-direction: column;
					gap: 24px;
				}

				.psr-card {
					background: #fff;
					border: 1px solid #e2e4e7;
					border-radius: 12px;
					overflow: hidden;
				}
				.psr-card__head {
					padding: 20px 24px 16px;
					border-bottom: 1px solid #f0f0f0;
				}
				.psr-card__title-row {
					display: flex;
					align-items: center;
					gap: 10px;
					margin-bottom: 6px;
				}
				.psr-card__title {
					font-size: 16px;
					font-weight: 600;
					color: #1d2327;
					margin: 0;
				}
				.psr-card__badge {
					font-size: 11px;
					font-weight: 500;
					background: #f0f0f1;
					color: #646970;
					padding: 2px 8px;
					border-radius: 20px;
					font-family: monospace;
					letter-spacing: 0;
				}
				.psr-card__desc {
					margin: 0;
					color: #646970;
					font-size: 13px;
					line-height: 1.6;
				}
				.psr-card__desc code {
					background: #f6f7f7;
					padding: 1px 5px;
					border-radius: 3px;
					font-size: 12px;
					color: #2c3338;
				}

				.psr-card__body { padding: 20px 24px; }

				.psr-example-label {
					font-size: 11px;
					font-weight: 600;
					text-transform: uppercase;
					letter-spacing: .06em;
					color: #646970;
					margin: 0 0 8px;
				}
				.psr-example-note {
					margin: -4px 0 8px;
					color: #646970;
					font-size: 12px;
					line-height: 1.6;
				}
				.psr-example-note code {
					background: #f6f7f7;
					padding: 1px 5px;
					border-radius: 3px;
					font-size: 11px;
					color: #2c3338;
				}
				.psr-example-row {
					display: flex;
					align-items: stretch;
					gap: 0;
					border: 1px solid #e2e4e7;
					border-radius: 8px;
					overflow: hidden;
					margin-bottom: 20px;
				}
				.psr-example-row:last-of-type { margin-bottom: 24px; }
				.psr-example-code {
					flex: 1;
					background: #f6f7f7;
					padding: 12px 16px;
					margin: 0;
					font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
					font-size: 13px;
					color: #2c3338;
					white-space: pre-wrap;
					word-break: break-all;
					border: none;
					line-height: 1.6;
				}
				.psr-copy-btn {
					flex-shrink: 0;
					padding: 0 16px;
					background: #fff;
					border: none;
					border-left: 1px solid #e2e4e7;
					cursor: pointer;
					font-size: 12px;
					font-weight: 500;
					color: #2271b1;
					display: flex;
					align-items: center;
					gap: 6px;
					transition: background .15s, color .15s;
					white-space: nowrap;
				}
				.psr-copy-btn:hover { background: #f0f6fc; }
				.psr-copy-btn.copied { color: #00a32a; }
				.psr-copy-btn svg { flex-shrink: 0; }

				.psr-attrs-label {
					font-size: 11px;
					font-weight: 600;
					text-transform: uppercase;
					letter-spacing: .06em;
					color: #646970;
					margin: 0 0 10px;
				}
				.psr-attrs {
					border: 1px solid #e2e4e7;
					border-radius: 8px;
					overflow: hidden;
				}
				.psr-attr {
					display: grid;
					grid-template-columns: 160px 100px 1fr;
					gap: 0;
					border-bottom: 1px solid #f0f0f0;
				}
				.psr-attr:last-child { border-bottom: none; }
				.psr-attr__name,
				.psr-attr__default,
				.psr-attr__desc {
					padding: 10px 14px;
					font-size: 13px;
					line-height: 1.5;
				}
				.psr-attr__name {
					font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
					font-size: 12px;
					color: #9333ea;
					background: #faf5ff;
					font-weight: 500;
					border-right: 1px solid #f0f0f0;
				}
				.psr-attr__default {
					font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
					font-size: 12px;
					color: #646970;
					background: #fafafa;
					border-right: 1px solid #f0f0f0;
				}
				.psr-attr__desc { color: #3c434a; }
				.psr-attr__desc code {
					background: #f6f7f7;
					padding: 1px 5px;
					border-radius: 3px;
					font-size: 12px;
					color: #2c3338;
				}

				.psr-attr-head {
					display: grid;
					grid-template-columns: 160px 100px 1fr;
					background: #f6f7f7;
					border-bottom: 1px solid #e2e4e7;
				}
				.psr-attr-head span {
					padding: 7px 14px;
					font-size: 11px;
					font-weight: 600;
					text-transform: uppercase;
					letter-spacing: .06em;
					color: #646970;
				}
				.psr-attr-head span:not(:last-child) {
					border-right: 1px solid #e2e4e7;
				}

				.psr-footer {
					margin-top: 32px;
					padding: 16px 20px;
					background: #f6f7f7;
					border: 1px solid #e2e4e7;
					border-radius: 10px;
					font-size: 13px;
					color: #646970;
					line-height: 1.6;
				}
				.psr-footer strong { color: #1d2327; }
			</style>

			<div class="psr-header">
				<div class="psr-header__logo">
					<svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" fill="#8fa4b7"/>
					</svg>
				</div>
				<div class="psr-header__text">
					<h1><?php esc_html_e( 'Core Shortcodes', 'core' ); ?></h1>
					<p><?php esc_html_e( 'All shortcodes available in this theme. Click Copy to grab the code.', 'core' ); ?></p>
				</div>
			</div>

			<div class="psr-grid">
				<?php foreach ( $shortcodes as $sc ) : ?>
				<div class="psr-card">
					<div class="psr-card__head">
						<div class="psr-card__title-row">
							<h2 class="psr-card__title"><?php echo esc_html( $sc['title'] ); ?></h2>
							<span class="psr-card__badge"><?php echo esc_html( '[' . $sc['tag'] . ']' ); ?></span>
						</div>
						<p class="psr-card__desc"><?php echo wp_kses( $sc['description'], array( 'code' => array(), 'strong' => array() ) ); ?></p>
					</div>

					<div class="psr-card__body">
						<?php foreach ( $sc['examples'] as $example ) : ?>
						<p class="psr-example-label"><?php echo esc_html( $example['label'] ); ?></p>
						<?php if ( ! empty( $example['note'] ) ) : ?>
						<p class="psr-example-note"><?php echo wp_kses( $example['note'], array( 'code' => array() ) ); ?></p>
						<?php endif; ?>
						<div class="psr-example-row">
							<pre class="psr-example-code"><?php echo esc_html( $example['code'] ); ?></pre>
							<button
								type="button"
								class="psr-copy-btn"
								data-code="<?php echo esc_attr( $example['code'] ); ?>"
							>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
									<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
								</svg>
								<?php esc_html_e( 'Copy', 'core' ); ?>
							</button>
						</div>
						<?php endforeach; ?>

						<?php if ( ! empty( $sc['attrs'] ) ) : ?>
						<p class="psr-attrs-label"><?php esc_html_e( 'Attributes', 'core' ); ?></p>
						<div class="psr-attrs">
							<div class="psr-attr-head">
								<span><?php esc_html_e( 'Attribute', 'core' ); ?></span>
								<span><?php esc_html_e( 'Default', 'core' ); ?></span>
								<span><?php esc_html_e( 'Description', 'core' ); ?></span>
							</div>
							<?php foreach ( $sc['attrs'] as $attr ) : ?>
							<div class="psr-attr">
								<div class="psr-attr__name"><?php echo esc_html( $attr['name'] ); ?></div>
								<div class="psr-attr__default"><?php echo esc_html( $attr['default'] ); ?></div>
								<div class="psr-attr__desc"><?php echo wp_kses( $attr['desc'], array( 'code' => array() ) ); ?></div>
							</div>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="psr-footer">
				<strong><?php esc_html_e( 'Tip:', 'core' ); ?></strong>
				<?php esc_html_e( 'Shortcodes can be placed in any post, page, or widget that supports shortcodes. In the block editor, use the Shortcode block.', 'core' ); ?>
			</div>

		</div>
		<?php
	}
endif;
