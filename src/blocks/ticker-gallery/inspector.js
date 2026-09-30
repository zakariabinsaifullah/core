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
    const { tallHeight, shortRatio, gap, radius, speed, direction, pauseOnHover, images } = attributes;

    return (
        <InspectorControls>
            <PanelBody title={__('Ticker', 'core')} initialOpen={true}>
                <NativeSelectControl
                    label={__('Direction', 'core')}
                    value={direction}
                    onChange={value => setAttributes({ direction: value })}
                    options={[
                        { label: __('Left', 'core'), value: 'left' },
                        { label: __('Right', 'core'), value: 'right' }
                    ]}
                />
                <RangeControl
                    label={__('Duration (seconds)', 'core')}
                    value={speed}
                    onChange={value => setAttributes({ speed: value })}
                    min={5}
                    max={180}
                    step={1}
                    help={__('How long one full pass takes. Higher is slower.', 'core')}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                <NativeToggleControl
                    label={__('Pause on hover', 'core')}
                    checked={pauseOnHover}
                    onChange={value => setAttributes({ pauseOnHover: value })}
                />
            </PanelBody>

            <PanelBody title={__('Layout', 'core')} initialOpen={false}>
                <RangeControl
                    label={__('Tall image height (px)', 'core')}
                    value={tallHeight}
                    onChange={value => setAttributes({ tallHeight: value })}
                    min={120}
                    max={600}
                    step={4}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                <RangeControl
                    label={__('Short image ratio', 'core')}
                    value={shortRatio}
                    onChange={value => setAttributes({ shortRatio: value })}
                    min={0.5}
                    max={1}
                    step={0.01}
                    help={__('Height of the tilted images relative to the tall ones.', 'core')}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                <RangeControl
                    label={__('Gap (px)', 'core')}
                    value={gap}
                    onChange={value => setAttributes({ gap: value })}
                    min={0}
                    max={120}
                    step={2}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                <RangeControl
                    label={__('Corner radius (px)', 'core')}
                    value={radius}
                    onChange={value => setAttributes({ radius: value })}
                    min={0}
                    max={64}
                    step={1}
                    __next40pxDefaultSize
                    __nextHasNoMarginBottom
                />
                {images?.length > 0 && images.length % 4 !== 0 && (
                    <p style={{ color: '#646970', fontSize: '12px', marginTop: '12px' }}>
                        {__(
                            'The tilt pattern repeats every 4 images. With a multiple of 4 the rhythm never repeats a shape back to back.',
                            'core'
                        )}
                    </p>
                )}
            </PanelBody>
        </InspectorControls>
    );
};

export default Inspector;
