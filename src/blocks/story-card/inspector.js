/**
 * WordPress dependencies
 */
import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { NativeSelectControl, NativeToggleControl } from '../../components';

const Inspector = props => {
    const { attributes, setAttributes } = props;
    const {
        icon,
        badgeSide,
        headingTag,
        lane,
        offsetY,
        arrow,
        arrowTop,
        arrowLeft,
        arrowWidth,
        arrowRotate,
        arrowFlipX,
        arrowDelay,
        arrowMobile
    } = attributes;

    // PHP hands the registry over in inc/arrows.php, so a new SVG dropped into
    // assets/svg/arrows/ appears here with no change to this file.
    const arrows = window.coreThemeArrows || [];

    const arrowOptions = [
        { label: __('None', 'core'), value: '' },
        ...arrows.map(item => ({ label: item.label, value: item.slug }))
    ];

    return (
        <InspectorControls>
            <PanelBody title={__('Top Icon', 'core')} initialOpen={true}>
                {icon?.url && (
                    <div className="core-theme-story-card__icon-preview">
                        <img src={icon.url} alt="" />
                    </div>
                )}

                {/*
                 * A direct upload rather than an icon library: the icon is a
                 * finished, styled SVG file of the author's own. Uploads are
                 * sanitised on the way in by core_theme_sanitize_svg_upload().
                 */}
                <MediaUploadCheck>
                    <MediaUpload
                        onSelect={media => setAttributes({ icon: { id: media.id, url: media.url } })}
                        allowedTypes={['image/svg+xml', 'image']}
                        value={icon?.id}
                        render={({ open }) => (
                            <div className="core-theme-story-card__icon-actions">
                                <Button variant="secondary" onClick={open}>
                                    {icon?.url ? __('Replace SVG', 'core') : __('Upload SVG', 'core')}
                                </Button>
                                {icon?.url && (
                                    <Button variant="tertiary" isDestructive onClick={() => setAttributes({ icon: {} })}>
                                        {__('Remove', 'core')}
                                    </Button>
                                )}
                            </div>
                        )}
                    />
                </MediaUploadCheck>

                <NativeSelectControl
                    label={__('Icon Corner', 'core')}
                    value={badgeSide}
                    onChange={value => setAttributes({ badgeSide: value })}
                    options={[
                        { label: __('Top left', 'core'), value: 'left' },
                        { label: __('Top right', 'core'), value: 'right' }
                    ]}
                    help={__('The design puts it on whichever corner faces the gutter.', 'core')}
                />
            </PanelBody>

            <PanelBody title={__('Card', 'core')} initialOpen={false}>
                <NativeSelectControl
                    label={__('Heading Tag', 'core')}
                    value={headingTag}
                    onChange={value => setAttributes({ headingTag: value })}
                    options={[
                        { label: 'H2', value: 'h2' },
                        { label: 'H3', value: 'h3' },
                        { label: 'H4', value: 'h4' },
                        { label: 'H5', value: 'h5' },
                        { label: 'H6', value: 'h6' },
                        { label: __('Paragraph', 'core'), value: 'p' }
                    ]}
                />
                <NativeSelectControl
                    label={__('Lane', 'core')}
                    value={lane}
                    onChange={value => setAttributes({ lane: value })}
                    options={[
                        { label: __('Automatic', 'core'), value: 'auto' },
                        { label: __('Left', 'core'), value: 'left' },
                        { label: __('Right', 'core'), value: 'right' }
                    ]}
                    help={__('Automatic alternates right, left, right down the section.', 'core')}
                />
                <RangeControl
                    label={__('Top Position (% of section width)', 'core')}
                    value={offsetY}
                    onChange={value => setAttributes({ offsetY: value })}
                    allowReset
                    min={0}
                    max={250}
                    step={0.5}
                    help={__(
                        'Distance from the section’s top, as a share of its width, so the composition holds at any size. Reset for automatic — the card then sits just below the one before it. Applied on the frontend only.',
                        'core'
                    )}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
            </PanelBody>

            <PanelBody title={__('Connector Arrow', 'core')} initialOpen={false}>
                <NativeSelectControl
                    label={__('Arrow', 'core')}
                    value={arrow}
                    onChange={value => setAttributes({ arrow: value })}
                    options={arrowOptions}
                    help={__('Drawn from this card toward the next one.', 'core')}
                />

                {arrow && (
                    <>
                        <RangeControl
                            label={__('Position — Top (% of card height)', 'core')}
                            value={arrowTop}
                            onChange={value => setAttributes({ arrowTop: value ?? 0 })}
                            step={0.5}
                            min={-80}
                            max={180}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <RangeControl
                            label={__('Position — Left (% of card width)', 'core')}
                            value={arrowLeft}
                            onChange={value => setAttributes({ arrowLeft: value ?? 0 })}
                            min={-120}
                            max={220}
                            step={0.5}
                            help={__(
                                'Values outside 0–100 push the arrow beyond the card, which the design does often.',
                                'core'
                            )}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <RangeControl
                            label={__('Width (% of card)', 'core')}
                            value={arrowWidth}
                            onChange={value => setAttributes({ arrowWidth: value ?? 45 })}
                            min={5}
                            max={120}
                            step={1}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <RangeControl
                            label={__('Rotation (deg)', 'core')}
                            value={arrowRotate}
                            onChange={value => setAttributes({ arrowRotate: value ?? 0 })}
                            min={-180}
                            max={180}
                            step={1}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <NativeToggleControl
                            label={__('Flip Horizontally', 'core')}
                            checked={arrowFlipX}
                            onChange={value => setAttributes({ arrowFlipX: value })}
                        />
                        <RangeControl
                            label={__('Draw Delay (ms)', 'core')}
                            value={arrowDelay}
                            onChange={value => setAttributes({ arrowDelay: value ?? 180 })}
                            min={0}
                            max={800}
                            step={10}
                            help={__('How long after its card the arrow starts drawing.', 'core')}
                            __next40pxDefaultSize
                            __nextHasNoMarginBottom
                        />
                        <NativeSelectControl
                            label={__('On Mobile', 'core')}
                            value={arrowMobile}
                            onChange={value => setAttributes({ arrowMobile: value })}
                            options={[
                                { label: __('Automatic', 'core'), value: 'auto' },
                                { label: __('Always show', 'core'), value: 'show' },
                                { label: __('Always hide', 'core'), value: 'hide' }
                            ]}
                            help={__(
                                'Automatic hides card arrows below 900px, where the cards stack; the section’s trailing arrow stays.',
                                'core'
                            )}
                        />
                    </>
                )}
            </PanelBody>
        </InspectorControls>
    );
};

export default Inspector;
