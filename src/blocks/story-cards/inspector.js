/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { NativeSelectControl, NativeToggleControl } from '../../components';

const Inspector = props => {
    const { attributes, setAttributes } = props;
    const {
        cardWidth,
        laneGap,
        mobileGap,
        animate,
        revealStagger,
        trailingArrow,
        trailingLeft,
        trailingOffset,
        trailingWidth,
        trailingDelay,
        trailingFlipX
    } = attributes;

    const arrows = window.coreThemeArrows || [];
    const arrowOptions = [
        { label: __('None', 'core'), value: '' },
        ...arrows.map(item => ({ label: item.label, value: item.slug }))
    ];

    return (
        <InspectorControls>
            <PanelBody title={__('Layout', 'core')} initialOpen={true}>
                <RangeControl
                    label={__('Card Width (%)', 'core')}
                    value={cardWidth}
                    onChange={value => setAttributes({ cardWidth: value ?? 40 })}
                    min={25}
                    max={70}
                    step={1}
                    help={__(
                        'Share of the section each card takes. The two lanes sit either side of the gutter the arrows live in.',
                        'core'
                    )}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                <RangeControl
                    label={__('Gap Between Cards (px)', 'core')}
                    value={laneGap}
                    onChange={value => setAttributes({ laneGap: value ?? 0 })}
                    min={0}
                    max={200}
                    step={4}
                    help={__(
                        'Baseline spacing before each card’s own Vertical Offset is applied. The design needs none — the offsets carry the whole stagger.',
                        'core'
                    )}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                <RangeControl
                    label={__('Gap on Mobile (px)', 'core')}
                    value={mobileGap}
                    onChange={value => setAttributes({ mobileGap: value ?? 70 })}
                    min={0}
                    max={160}
                    step={2}
                    help={__('Spacing between the stacked cards below 900px, where the offsets no longer apply.', 'core')}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
            </PanelBody>

            <PanelBody title={__('Trailing Arrow', 'core')} initialOpen={false}>
                <NativeSelectControl
                    label={__('Arrow', 'core')}
                    value={trailingArrow}
                    onChange={value => setAttributes({ trailingArrow: value })}
                    options={arrowOptions}
                    help={__(
                        'The long curve running out of the bottom of the section. It belongs to the section rather than to a card, and it is the one arrow kept on a phone.',
                        'core'
                    )}
                />

                {trailingArrow && (
                    <>
                        <RangeControl
                            label={__('Position — Left (%)', 'core')}
                            value={trailingLeft}
                            onChange={value => setAttributes({ trailingLeft: value ?? 41 })}
                            min={-40}
                            max={140}
                            step={1}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <RangeControl
                            label={__('Position — From Bottom (%)', 'core')}
                            value={trailingOffset}
                            onChange={value => setAttributes({ trailingOffset: value ?? -13 })}
                            min={-60}
                            max={40}
                            step={1}
                            help={__('Negative pulls the arrow up over the last card.', 'core')}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <RangeControl
                            label={__('Width (%)', 'core')}
                            value={trailingWidth}
                            onChange={value => setAttributes({ trailingWidth: value ?? 20 })}
                            min={4}
                            max={80}
                            step={1}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <NativeToggleControl
                            label={__('Flip Horizontally', 'core')}
                            checked={trailingFlipX}
                            onChange={value => setAttributes({ trailingFlipX: value })}
                            help={__('Mirrors the curve for compositions that run the other way.', 'core')}
                        />
                        <RangeControl
                            label={__('Draw Delay (ms)', 'core')}
                            value={trailingDelay}
                            onChange={value => setAttributes({ trailingDelay: value ?? 180 })}
                            min={0}
                            max={1200}
                            step={10}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                    </>
                )}
            </PanelBody>

            <PanelBody title={__('Scroll Animation', 'core')} initialOpen={false}>
                <NativeToggleControl
                    label={__('Reveal on Scroll', 'core')}
                    checked={animate}
                    onChange={value => setAttributes({ animate: value })}
                    help={__(
                        'Cards fade and rise into place one at a time, each arrow drawing in behind its card. Frontend only.',
                        'core'
                    )}
                />
                {animate && (
                    <RangeControl
                        label={__('Minimum Gap Between Cards (ms)', 'core')}
                        value={revealStagger}
                        onChange={value => setAttributes({ revealStagger: value ?? 120 })}
                        min={0}
                        max={600}
                        step={10}
                        help={__(
                            'Holds cards apart when several enter the screen at once, so a fast scroll still reveals them in order rather than together.',
                            'core'
                        )}
                        __next40pxDefaultSize
                        __nextHasNoMarginBottom
                    />
                )}
            </PanelBody>
        </InspectorControls>
    );
};

export default Inspector;
