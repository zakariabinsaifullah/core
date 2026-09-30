import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { Button, PanelBody } from '@wordpress/components';

import {
    NativeToggleGroupControl,
    NativeRangeControl,
    NativeToggleControl,
    NativeResponsiveControl,
    NativeUnitControl
} from '../../components';
import { resolveResponsive } from './utils';

/**
 * Tablet and Mobile inherit Desktop until they are given a value of their own,
 * so once one is pinned it needs a way back to the inherited value.
 */
const InheritReset = ({ device, value, onReset }) => {
    if (device === 'Desktop' || value === undefined || value === '') {
        return null;
    }

    return (
        <Button variant="link" onClick={onReset}>
            {__('Use desktop value', 'core')}
        </Button>
    );
};

const Inspector = props => {
    const { attributes, setAttributes } = props;
    const { resMode, heightType, heights, vAligns, autoplay, loop, delay, visibleItems, overflowVisible } = attributes;

    // What the device currently in preview actually renders with.
    const currentHeightType = resolveResponsive(heightType, resMode) || 'adaptive';
    const currentHeight = resolveResponsive(heights, resMode) || '';
    const currentVAlign = resolveResponsive(vAligns, resMode) || 'top';

    return (
        <InspectorControls group="settings">
            <PanelBody title={__('General', 'core')} initialOpen={true}>
                <NativeResponsiveControl label={__('Height Type', 'core')} props={props}>
                    <NativeToggleGroupControl
                        value={currentHeightType}
                        onChange={value => setAttributes({ heightType: { ...heightType, [resMode]: value } })}
                        options={[
                            { label: __('Adaptive', 'core'), value: 'adaptive' },
                            { label: __('Fixed', 'core'), value: 'fixed' }
                        ]}
                    />
                    <InheritReset
                        device={resMode}
                        value={heightType?.[resMode]}
                        onReset={() => setAttributes({ heightType: { ...heightType, [resMode]: undefined } })}
                    />
                </NativeResponsiveControl>
                {currentHeightType === 'fixed' && (
                    <>
                        <NativeResponsiveControl label={__('Height', 'core')} props={props}>
                            <NativeUnitControl
                                label={__('Height', 'core')}
                                value={currentHeight}
                                onChange={value => {
                                    const newHeights = { ...heights, [resMode]: value };
                                    setAttributes({ heights: newHeights });
                                }}
                            />
                            <InheritReset
                                device={resMode}
                                value={heights?.[resMode]}
                                onReset={() => setAttributes({ heights: { ...heights, [resMode]: undefined } })}
                            />
                        </NativeResponsiveControl>
                        <NativeResponsiveControl label={__('Vertical Align', 'core')} props={props}>
                            <NativeToggleGroupControl
                                value={currentVAlign}
                                onChange={value => setAttributes({ vAligns: { ...vAligns, [resMode]: value } })}
                                options={[
                                    { label: __('Top', 'core'), value: 'top' },
                                    { label: __('Middle', 'core'), value: 'middle' },
                                    { label: __('Bottom', 'core'), value: 'bottom' }
                                ]}
                            />
                            <InheritReset
                                device={resMode}
                                value={vAligns?.[resMode]}
                                onReset={() => setAttributes({ vAligns: { ...vAligns, [resMode]: undefined } })}
                            />
                        </NativeResponsiveControl>
                    </>
                )}
            </PanelBody>
            <PanelBody title={__('Options', 'core')} initialOpen={false}>
                <NativeResponsiveControl label={__('Visible Items', 'core')} props={props}>
                    <NativeRangeControl
                        value={visibleItems?.[resMode]}
                        onChange={value => setAttributes({ visibleItems: { ...visibleItems, [resMode]: value } })}
                        min={1}
                        max={10}
                        step={1}
                    />
                </NativeResponsiveControl>
                <p style={{ fontSize: '12px', fontStyle: 'italic', color: '#757575' }}>
                    {__('Rotate, offset and z-index are set per card — select a Slide and open its own settings.', 'core')}
                </p>
                <NativeToggleControl
                    label={__('Loop', 'core')}
                    checked={loop}
                    onChange={value => setAttributes({ loop: value })}
                />
                <NativeToggleControl
                    label={__('Autoplay', 'core')}
                    checked={autoplay}
                    onChange={value => setAttributes({ autoplay: value })}
                />
                <NativeToggleControl
                    label={__('Make overflow visible', 'core')}
                    checked={overflowVisible}
                    onChange={value => setAttributes({ overflowVisible: value })}
                />
                {autoplay && (
                    <NativeRangeControl
                        label={__('Delay (ms)', 'core')}
                        value={delay}
                        onChange={value => setAttributes({ delay: value })}
                        min={1000}
                        max={10000}
                        step={500}
                    />
                )}
            </PanelBody>
        </InspectorControls>
    );
};

export default Inspector;
