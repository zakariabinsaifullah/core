/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { NativeToggleControl, NativeSelectControl, NativeResponsiveControl, NativeUnitControl } from '../../components';

const HEADING_TAGS = [
    { label: __('H1', 'core'), value: 'h1' },
    { label: __('H2', 'core'), value: 'h2' },
    { label: __('H3', 'core'), value: 'h3' },
    { label: __('H4', 'core'), value: 'h4' },
    { label: __('H5', 'core'), value: 'h5' },
    { label: __('H6', 'core'), value: 'h6' },
    { label: __('Paragraph', 'core'), value: 'p' },
    { label: __('Div', 'core'), value: 'div' }
];

const Inspector = props => {
    const { attributes, setAttributes } = props;
    const { showTitle, showDesc, showBtn, titleTag, itemsGap, contentMargin, contentMaxWidth, contentVAlign, resMode } = attributes;

    return (
        <InspectorControls>
            <PanelBody title={__('Settings', 'core')} initialOpen={true}>
                <NativeToggleControl
                    label={__('Show Title', 'core')}
                    checked={showTitle}
                    onChange={value => setAttributes({ showTitle: value })}
                />
                <NativeToggleControl
                    label={__('Show Description', 'core')}
                    checked={showDesc}
                    onChange={value => setAttributes({ showDesc: value })}
                />
                <NativeToggleControl
                    label={__('Show Button', 'core')}
                    checked={showBtn}
                    onChange={value => setAttributes({ showBtn: value })}
                />
                {showTitle && (
                    <NativeSelectControl
                        label={__('Select Title Tag', 'core')}
                        value={titleTag}
                        onChange={value => setAttributes({ titleTag: value })}
                        options={HEADING_TAGS}
                    />
                )}
                <NativeResponsiveControl label={__('Items Gap', 'core')} props={props}>
                    <NativeUnitControl
                        value={itemsGap?.[resMode]}
                        onChange={value => setAttributes({ itemsGap: { ...itemsGap, [resMode]: value } })}
                    />
                </NativeResponsiveControl>
                {/* Spacing between an expanded item's title, description and button. */}
                <NativeResponsiveControl label={__('Content Margin', 'core')} props={props}>
                    <NativeUnitControl
                        value={contentMargin?.[resMode]}
                        onChange={value => setAttributes({ contentMargin: { ...contentMargin, [resMode]: value } })}
                        placeholder="24"
                    />
                </NativeResponsiveControl>
                {/* Where the content sits vertically once an item is expanded. */}
                <NativeSelectControl
                    label={__('Content Vertical Position', 'core')}
                    value={contentVAlign}
                    onChange={value => setAttributes({ contentVAlign: value })}
                    options={[
                        { label: __('Top', 'core'), value: 'top' },
                        { label: __('Middle', 'core'), value: 'center' },
                        { label: __('Bottom', 'core'), value: 'bottom' }
                    ]}
                />
                <NativeUnitControl
                    label={__('Content Max Width', 'core')}
                    value={contentMaxWidth}
                    onChange={value => setAttributes({ contentMaxWidth: value })}
                    units={[
                        { label: 'px', value: 'px' },
                        { label: '%', value: '%' },
                        { label: 'em', value: 'em' },
                        { label: 'rem', value: 'rem' }
                    ]}
                />
            </PanelBody>
        </InspectorControls>
    );
};

export default Inspector;
