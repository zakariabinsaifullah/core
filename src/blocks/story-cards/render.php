<?php
/**
 * Story Cards block render template.
 *
 * Dynamic for one reason: the trailing arrow — the long curve that runs out of
 * the bottom of the section in the design — belongs to the section rather than
 * to any card. The last card already carries its own connector, and anchoring
 * the trailer here is also what lets the narrow-screen rule keep exactly this
 * one arrow and drop the rest.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content (the cards).
 * @var WP_Block $block      Block instance.
 */

$card_width = (float) ( $attributes['cardWidth'] ?? 40 );
$lane_gap   = (float) ( $attributes['laneGap'] ?? 0 );
$animate    = ! empty( $attributes['animate'] );
$stagger    = (int) ( $attributes['revealStagger'] ?? 120 );

$trailing        = $attributes['trailingArrow'] ?? '';
$trailing_markup = $trailing ? core_theme_arrow_svg( $trailing ) : '';

$styles = array(
	'--core-theme-card-width:' . $card_width . '%',
	'--core-theme-lane-gap:' . $lane_gap . 'px',
	'--core-theme-mobile-gap:' . (float) ( $attributes['mobileGap'] ?? 70 ) . 'px',
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'         => 'core-theme-story-cards',
		'style'         => implode( ';', $styles ),
		// view.js reads these; it does nothing at all when animation is off.
		'data-animate'  => $animate ? 'true' : 'false',
		'data-stagger'  => (string) $stagger,
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by get_block_wrapper_attributes(). ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inner blocks, already rendered and escaped. ?>

	<?php
	if ( $trailing_markup ) :
		/*
		 * Anchored to the bottom of the section: `top: 100%` plus a percentage
		 * margin, which — like every other measurement in this block —
		 * resolves against the section's width, so the trailer keeps its place
		 * in the composition at any size.
		 */
		$trailing_styles = array(
			'--core-theme-trailing-left:' . (float) ( $attributes['trailingLeft'] ?? 41 ) . '%',
			'--core-theme-trailing-offset:' . (float) ( $attributes['trailingOffset'] ?? -13 ) . '%',
			'--core-theme-trailing-width:' . (float) ( $attributes['trailingWidth'] ?? 20 ) . '%',
			'--core-theme-trailing-flip:' . ( ! empty( $attributes['trailingFlipX'] ) ? '-1' : '1' ),
			'--core-theme-arrow-angle:' . core_theme_arrow_angle( $trailing ) . 'deg',
		);
		?>
		<span
			class="core-theme-story-cards__trailing"
			style="<?php echo esc_attr( implode( ';', $trailing_styles ) ); ?>"
			data-arrow-delay="<?php echo esc_attr( (int) ( $attributes['trailingDelay'] ?? 180 ) ); ?>"
			aria-hidden="true"
		>
			<span class="core-theme-story-card__connector-art">
				<?php echo $trailing_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned artwork from assets/svg/arrows/. ?>
			</span>
		</span>
	<?php endif; ?>
</div>
