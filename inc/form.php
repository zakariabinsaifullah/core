<?php
/**
 * Contact Settings — admin page under Appearance menu.
 *
 * Options:
 *   core_theme_phone_number        – Phone Number
 *   core_theme_form_shortcode      – Form Shortcode
 *   core_theme_form_title          – Panel heading
 *   core_theme_form_description    – Panel description paragraph
 *   core_theme_book_call_link      – Booking URL behind the panel's "Book a call" tab
 *
 * @package Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Enqueue front-end assets ───────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', 'core_theme_form_panel_assets' );

function core_theme_form_panel_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'core-theme-form-panel',
		get_theme_file_uri( 'assets/css/form-panel.css' ),
		array(),
		$version
	);

	wp_enqueue_script(
		'core-theme-form-panel',
		get_theme_file_uri( 'assets/js/form-panel.js' ),
		array(),
		$version,
		true
	);
}

// ── Inject panel HTML into every page footer ───────────────────────────────────

add_action( 'wp_footer', 'core_theme_form_panel_html' );

function core_theme_form_panel_html() {
	$phone       = get_option( 'core_theme_phone_number', '' );
	$shortcode   = get_option( 'core_theme_form_shortcode', '' );
	$title       = get_option( 'core_theme_form_title', 'Contact us' );
	$description = get_option( 'core_theme_form_description', '' );
	$book_link   = get_option( 'core_theme_book_call_link', '' );

	// Don't render the panel if neither option is set.
	if ( ! $phone && ! $shortcode ) {
		return;
	}
	?>
	<div id="core-theme-form-overlay" class="core-theme-form-overlay" aria-hidden="true"></div>

	<div
		id="core-theme-contact"
		class="core-theme-form-panel"
		role="dialog"
		aria-modal="true"
		aria-label="<?php echo esc_attr( $title ? $title : __( 'Contact us', 'core' ) ); ?>"
		aria-hidden="true"
	>
		<div class="core-theme-form-panel__header">
			<?php if ( $phone ) : ?>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>" class="core-theme-form-panel__phone">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M3.62 7.79C5.06 10.62 7.38 12.93 10.21 14.38L12.41 12.18C12.68 11.91 13.08 11.82 13.43 11.94C14.55 12.31 15.76 12.51 17 12.51C17.55 12.51 18 12.96 18 13.51V17C18 17.55 17.55 18 17 18C7.61 18 0 10.39 0 1C0 0.45 0.45 0 1 0H4.5C5.05 0 5.5 0.45 5.5 1C5.5 2.25 5.7 3.45 6.07 4.57C6.18 4.92 6.1 5.31 5.82 5.59L3.62 7.79Z" fill="currentColor"/>
				</svg>
				<?php echo esc_html( $phone ); ?>
			</a>
			<?php else : ?>
			<span></span>
			<?php endif; ?>

			<button class="core-theme-form-panel__close" aria-label="<?php esc_attr_e( 'Close form', 'core' ); ?>">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</button>
		</div>

		<div class="core-theme-form-panel__body">
			<?php
			/*
			 * The two tabs only make sense as a pair: with no booking URL set
			 * there is nowhere for the second one to go, and a lone "Message
			 * us" tab above the form it already describes says nothing. So the
			 * whole row is dropped and the panel opens straight onto the title.
			 */
			if ( $book_link ) :
				?>
				<nav class="core-theme-form-panel__tabs" aria-label="<?php esc_attr_e( 'Contact options', 'core' ); ?>">
					<span class="core-theme-form-panel__tab is-active" aria-current="true">
						<svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<rect x="1.75" y="3.75" width="16.5" height="12.5" rx="2" stroke="currentColor" stroke-width="1.6"/>
							<path d="M2.5 5L10 10.5L17.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<?php esc_html_e( 'Message us', 'core' ); ?>
					</span>

					<?php
					/*
					 * A scheduling page belongs in its own tab; an on-page
					 * target does not — opening #book in a new tab would just
					 * reload the site there.
					 */
					$book_in_new_tab = 0 !== strpos( $book_link, '#' );
					?>
					<a
						class="core-theme-form-panel__tab"
						href="<?php echo esc_url( $book_link ); ?>"
						<?php echo $book_in_new_tab ? 'target="_blank" rel="noopener"' : ''; ?>
					>
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<rect x="2.75" y="3.75" width="14.5" height="13.5" rx="2" stroke="currentColor" stroke-width="1.6"/>
							<path d="M2.75 8H17.25M6.5 2.5V5M13.5 2.5V5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
						</svg>
						<?php esc_html_e( 'Book a call', 'core' ); ?>
					</a>
				</nav>
			<?php endif; ?>

			<?php if ( $title ) : ?>
				<h2 class="core-theme-form-panel__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<p class="core-theme-form-panel__desc"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( $shortcode ) : ?>
				<?php echo do_shortcode( $shortcode ); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

// ── Register settings ──────────────────────────────────────────────────────────

add_action( 'admin_init', 'core_theme_form_register_settings' );

function core_theme_form_register_settings() {
	register_setting(
		'core_theme_form_group',
		'core_theme_phone_number',
		array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '' )
	);

	register_setting(
		'core_theme_form_group',
		'core_theme_form_shortcode',
		array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '' )
	);

	register_setting(
		'core_theme_form_group',
		'core_theme_form_title',
		array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => 'Contact us' )
	);

	register_setting(
		'core_theme_form_group',
		'core_theme_form_description',
		array( 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field', 'default' => '' )
	);

	// Stored as plain text rather than run through esc_url_raw, so a fragment
	// such as #book — or any other non-absolute target — survives saving.
	register_setting(
		'core_theme_form_group',
		'core_theme_book_call_link',
		array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '' )
	);
}

// ── Add menu page under Appearance ────────────────────────────────────────────

add_action( 'admin_menu', 'core_theme_form_add_menu' );

function core_theme_form_add_menu() {
	add_theme_page(
		__( 'Form Settings', 'core' ),
		__( 'Form', 'core' ),
		'manage_options',
		'core-theme-form',
		'core_theme_form_render_page'
	);
}

// ── Enqueue admin assets on the Form Settings page ────────────────────────────

add_action( 'admin_enqueue_scripts', 'core_theme_form_admin_assets' );

function core_theme_form_admin_assets( $hook ) {
	if ( 'appearance_page_core-theme-form' !== $hook ) {
		return;
	}

	wp_enqueue_script(
		'core-theme-form-settings',
		get_theme_file_uri( 'assets/js/form-settings.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}

// ── Render settings page ───────────────────────────────────────────────────────

function core_theme_form_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Form Settings', 'core' ); ?></h1>

		<?php settings_errors( 'core_theme_form_group' ); ?>

		<?php /* ── Trigger ID hint ── */ ?>
		<div style="
			background: #f0f6fc;
			border-left: 4px solid #2271b1;
			border-radius: 0 4px 4px 0;
			padding: 14px 18px;
			margin: 16px 0 24px;
			max-width: 600px;
		">
			<p style="margin: 0 0 8px; font-weight: 600; color: #1d2327;">
				<?php esc_html_e( 'How to open this form panel', 'core' ); ?>
			</p>
			<p style="margin: 0 0 10px; color: #3c434a; font-size: 13px;">
				<?php esc_html_e( 'Add the following ID as the href value on any link or button to open the slide-in form:', 'core' ); ?>
			</p>
			<div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
				<code id="core-theme-trigger-id" style="
					background: #1d2327;
					color: #7dd3fc;
					padding: 6px 14px;
					border-radius: 4px;
					font-size: 14px;
					font-family: monospace;
					letter-spacing: 0.5px;
					user-select: all;
				">#core-theme-contact</code>
				<button
					type="button"
					class="core-theme-copy-button"
					data-copy="#core-theme-contact"
					data-label="<?php esc_attr_e( 'Copy', 'core' ); ?>"
					data-copied="<?php esc_attr_e( '✓ Copied!', 'core' ); ?>"
					style="
						background: #2271b1;
						color: #fff;
						border: none;
						border-radius: 4px;
						padding: 5px 14px;
						font-size: 13px;
						cursor: pointer;
					"
				><?php esc_html_e( 'Copy', 'core' ); ?></button>
			</div>
			<p style="margin: 10px 0 0; color: #646970; font-size: 12px;">
				<?php esc_html_e( 'Example:', 'core' ); ?>
				<code style="background:#eee; padding: 2px 6px; border-radius: 3px;">&lt;a href="#core-theme-contact"&gt;Get in Touch&lt;/a&gt;</code>
			</p>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields( 'core_theme_form_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="core_theme_phone_number">
							<?php esc_html_e( 'Phone Number', 'core' ); ?>
						</label>
					</th>
					<td>
						<input
							type="text"
							id="core_theme_phone_number"
							name="core_theme_phone_number"
							value="<?php echo esc_attr( get_option( 'core_theme_phone_number' ) ); ?>"
							class="regular-text"
							placeholder="e.g. 818-408-7117"
						/>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="core_theme_form_title">
							<?php esc_html_e( 'Form Title', 'core' ); ?>
						</label>
					</th>
					<td>
						<input
							type="text"
							id="core_theme_form_title"
							name="core_theme_form_title"
							value="<?php echo esc_attr( get_option( 'core_theme_form_title', 'Contact us' ) ); ?>"
							class="regular-text"
							placeholder="<?php esc_attr_e( 'Contact us', 'core' ); ?>"
						/>
						<p class="description">
							<?php esc_html_e( 'Heading displayed at the top of the slide-in panel.', 'core' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="core_theme_form_description">
							<?php esc_html_e( 'Form Description', 'core' ); ?>
						</label>
					</th>
					<td>
						<textarea
							id="core_theme_form_description"
							name="core_theme_form_description"
							class="regular-text"
							rows="4"
							placeholder="<?php esc_attr_e( 'We are here to help you...', 'core' ); ?>"
						><?php echo esc_textarea( get_option( 'core_theme_form_description', '' ) ); ?></textarea>
						<p class="description">
							<?php esc_html_e( 'Short paragraph shown below the title inside the panel.', 'core' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="core_theme_book_call_link">
							<?php esc_html_e( 'Book a Call Link', 'core' ); ?>
						</label>
					</th>
					<td>
						<?php /* Deliberately not type="url": that would have the browser reject an on-page target like #book. */ ?>
						<input
							type="text"
							id="core_theme_book_call_link"
							name="core_theme_book_call_link"
							value="<?php echo esc_attr( get_option( 'core_theme_book_call_link', '' ) ); ?>"
							class="regular-text"
							placeholder="https://calendly.com/your-link"
						/>
						<p class="description">
							<?php esc_html_e( 'Destination for the panel\'s "Book a call" tab — a scheduling page, or an on-page target such as #book. Leave this empty and the panel shows no tabs at all.', 'core' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="core_theme_form_shortcode">
							<?php esc_html_e( 'Form Shortcode', 'core' ); ?>
						</label>
					</th>
					<td>
						<input
							type="text"
							id="core_theme_form_shortcode"
							name="core_theme_form_shortcode"
							value="<?php echo esc_attr( get_option( 'core_theme_form_shortcode' ) ); ?>"
							class="regular-text"
						/>
						<p class="description">
							<?php esc_html_e( 'Enter the shortcode, e.g. [gravityform id="1" title="false"]', 'core' ); ?>
						</p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
