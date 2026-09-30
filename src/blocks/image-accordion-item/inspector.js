/**
 * WordPress dependencies
 */
import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { NativeTextControl, NativeTextareaControl, NativeToggleControl } from '../../components';

/**
 * Strips every tag except <br>.
 *
 * The canvas fields already enforce this through RichText's
 * `allowedFormats={[]}`, but these inspector inputs write straight to the
 * attribute, so without this they would be a way around it — and the help
 * text below would be a lie.
 *
 * Requires a letter after the angle bracket so ordinary prose survives:
 * "a < b > c" is left alone, while <p>, </p> and <script> are removed. This
 * is an input guard for consistency, not a security boundary — WordPress's
 * own kses still governs what untrusted roles are allowed to save.
 *
 * @param {string} value Raw field value.
 * @return {string} Value with only line-break tags left intact.
 */
const allowOnlyLineBreaks = value => (value || '').replace(/<\/?(?!br\b)[a-zA-Z][^>]*>/gi, '');

const BR_HELP = __('Use <br> for a line break. Other HTML is removed.', 'core');

const EMPTY_IMAGE = { id: '', url: '', alt: '' };

/**
 * One image slot: preview, upload/replace and remove.
 *
 * @param {Object}   props
 * @param {string}   props.label    Field label.
 * @param {Object}   props.value    Current image ({id, url, alt}).
 * @param {Function} props.onChange Receives the new image object.
 * @param {string}   props.help     Optional help text.
 */
const ImageField = ({ label, value, onChange, help }) => (
    <div className="core-theme-accordion-image">
        <p className="core-theme-accordion-image__label">{label}</p>
        {value?.url && <img src={value.url} alt="" />}
        <MediaUploadCheck>
            <MediaUpload
                onSelect={media => onChange({ id: media.id, url: media.url, alt: media.alt })}
                allowedTypes={['image']}
                value={value?.id}
                render={({ open }) => (
                    <Button variant="secondary" onClick={open}>
                        {value?.url ? __('Replace Image', 'core') : __('Upload Image', 'core')}
                    </Button>
                )}
            />
        </MediaUploadCheck>
        {value?.url && (
            <Button variant="link" isDestructive onClick={() => onChange({ ...EMPTY_IMAGE })}>
                {__('Remove Image', 'core')}
            </Button>
        )}
        {help && <p className="core-theme-accordion-image__help">{help}</p>}
    </div>
);

const Inspector = props => {
    const { attributes, setAttributes } = props;
    const { image, imageTablet, imageMobile, showTitle, title, showDesc, description, showBtn, btnLabel, href, linkTarget } = attributes;

    return (
        <InspectorControls>
            <PanelBody title={__('Content', 'core')} initialOpen={true}>
                <ImageField
                    label={__('Image (Desktop)', 'core')}
                    value={image}
                    onChange={value => setAttributes({ image: value })}
                />
                <ImageField
                    label={__('Image (Tablet)', 'core')}
                    value={imageTablet}
                    onChange={value => setAttributes({ imageTablet: value })}
                    help={__('Used up to 781px. Falls back to the desktop image.', 'core')}
                />
                <ImageField
                    label={__('Image (Mobile)', 'core')}
                    value={imageMobile}
                    onChange={value => setAttributes({ imageMobile: value })}
                    help={__('Used up to 599px. Falls back to tablet, then desktop.', 'core')}
                />
                {showTitle && (
                    <NativeTextControl
                        label={__('Heading', 'core')}
                        value={title}
                        placeholder={__('Accordion title..', 'core')}
                        help={BR_HELP}
                        onChange={value => setAttributes({ title: allowOnlyLineBreaks(value) })}
                    />
                )}
                {showDesc && (
                    <NativeTextareaControl
                        label={__('Description', 'core')}
                        value={description}
                        placeholder={__('Accordion description..', 'core')}
                        help={BR_HELP}
                        onChange={value => setAttributes({ description: allowOnlyLineBreaks(value) })}
                    />
                )}
                {showBtn && (
                    <>
                        <NativeTextControl
                            label={__('Button Label', 'core')}
                            value={btnLabel}
                            placeholder={__('Show More', 'core')}
                            onChange={value => setAttributes({ btnLabel: value })}
                        />
                        <NativeTextControl
                            label={__('Button Link', 'core')}
                            value={href}
                            placeholder="https://"
                            help={__('Only applies on the live site — the editor preview link stays disabled.', 'core')}
                            onChange={value => setAttributes({ href: value })}
                        />
                        <NativeToggleControl
                            label={__('Open in new tab', 'core')}
                            checked={linkTarget === '_blank'}
                            onChange={value =>
                                setAttributes({
                                    linkTarget: value ? '_blank' : '',
                                    linkRel: value ? 'noreferrer noopener' : ''
                                })
                            }
                        />
                    </>
                )}
            </PanelBody>
        </InspectorControls>
    );
};

export default Inspector;
