<?php
// This file is generated. Do not modify it manually.
return array(
	'carousel' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/carousel',
		'version' => '0.0.14',
		'title' => 'Carousel',
		'category' => 'core-theme',
		'icon' => 'slides',
		'description' => 'Create flexible sliders and carousels using any inner blocks',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'color' => array(
				'text' => false,
				'background' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => false
				)
			),
			'spacing' => array(
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'status' => array(
				'type' => 'boolean',
				'default' => false
			),
			'blockStyle' => array(
				'type' => 'object'
			),
			'resMode' => array(
				'type' => 'string',
				'default' => 'Desktop'
			),
			'heightType' => array(
				'type' => 'object',
				'default' => array(
					'Desktop' => 'adaptive'
				)
			),
			'gaps' => array(
				'type' => 'object',
				'default' => array(
					'Desktop' => 0,
					'Tablet' => 0,
					'Mobile' => 0
				)
			),
			'visibleItems' => array(
				'type' => 'object',
				'default' => array(
					'Desktop' => 5,
					'Tablet' => 3,
					'Mobile' => 1
				)
			),
			'heights' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'vAligns' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'autoplay' => array(
				'type' => 'boolean',
				'default' => false
			),
			'delay' => array(
				'type' => 'number',
				'default' => 3000
			),
			'loop' => array(
				'type' => 'boolean',
				'default' => true
			),
			'overflowVisible' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'core',
		'editorScript' => array(
			'file:./index.js'
		),
		'editorStyle' => array(
			'file:./index.css'
		),
		'style' => array(
			'file:./style-index.css',
			'core-theme-swiper-style'
		),
		'viewScript' => array(
			'file:./view.js',
			'core-theme-swiper-script'
		)
	),
	'icon' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/icon',
		'version' => '0.0.14',
		'title' => 'Icon',
		'category' => 'core-theme',
		'description' => 'Display SVG icons from library or upload custom SVGs.',
		'supports' => array(
			'reusable' => false,
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'color' => array(
				'__experimentalSkipSerialization' => true,
				'gradients' => true,
				'link' => false,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'__experimentalBorder' => array(
				'__experimentalSkipSerialization' => true,
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true
			),
			'spacing' => array(
				'__experimentalSkipSerialization' => true,
				'margin' => true,
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			),
			'shadow' => array(
				'__experimentalSkipSerialization' => true
			)
		),
		'example' => array(
			
		),
		'attributes' => array(
			'listTitle' => array(
				'type' => 'string'
			),
			'listDesc' => array(
				'type' => 'string'
			),
			'resMode' => array(
				'type' => 'string',
				'default' => 'Desktop'
			),
			'blockStyle' => array(
				'type' => 'object'
			),
			'tagName' => array(
				'type' => 'string',
				'default' => 'div'
			),
			'iconName' => array(
				'type' => 'string',
				'default' => 'wordpress'
			),
			'customSvgCode' => array(
				'type' => 'string'
			),
			'iconType' => array(
				'type' => 'string',
				'default' => 'fill'
			),
			'strokeWidth' => array(
				'type' => 'number',
				'default' => 1.2
			),
			'sizes' => array(
				'type' => 'object',
				'default' => array(
					'Desktop' => 24
				)
			),
			'iconSize' => array(
				'type' => 'number',
				'default' => 24
			),
			'iconMarginTop' => array(
				'type' => 'string'
			),
			'width' => array(
				'type' => 'number'
			),
			'justifyContent' => array(
				'type' => 'string'
			),
			'href' => array(
				'type' => 'string'
			),
			'linkTarget' => array(
				'type' => 'string'
			),
			'linkRel' => array(
				'type' => 'string'
			),
			'showTitle' => array(
				'type' => 'boolean',
				'default' => false
			),
			'heading' => array(
				'type' => 'string'
			),
			'headingTag' => array(
				'type' => 'string',
				'default' => 'p'
			),
			'listGap' => array(
				'type' => 'string'
			),
			'titleColor' => array(
				'type' => 'string'
			),
			'titleSize' => array(
				'type' => 'string'
			),
			'titleFontFamily' => array(
				'type' => 'string'
			),
			'titleFontWeight' => array(
				'type' => 'string'
			),
			'titleMarginBottom' => array(
				'type' => 'string'
			),
			'showDesc' => array(
				'type' => 'boolean',
				'default' => false
			),
			'description' => array(
				'type' => 'string'
			),
			'descTag' => array(
				'type' => 'string',
				'default' => 'p'
			),
			'descColor' => array(
				'type' => 'string'
			),
			'descSize' => array(
				'type' => 'string'
			),
			'descFontFamily' => array(
				'type' => 'string'
			),
			'showButton' => array(
				'type' => 'boolean',
				'default' => false
			),
			'buttonText' => array(
				'type' => 'string'
			),
			'buttonUrl' => array(
				'type' => 'string'
			),
			'buttonLinkTarget' => array(
				'type' => 'string'
			),
			'buttonLinkRel' => array(
				'type' => 'string'
			),
			'buttonMarginTop' => array(
				'type' => 'string'
			),
			'buttonIconName' => array(
				'type' => 'string'
			),
			'buttonCustomSvgCode' => array(
				'type' => 'string'
			),
			'buttonIconType' => array(
				'type' => 'string',
				'default' => 'fill'
			),
			'buttonStrokeWidth' => array(
				'type' => 'number'
			),
			'iconVerticalAlign' => array(
				'type' => 'string',
				'default' => 'center'
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	),
	'image-accordion' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/image-accordion',
		'version' => '0.0.14',
		'title' => 'Image Accordion',
		'category' => 'core-theme',
		'icon' => 'images-alt2',
		'description' => 'Display a series of images in an accordion format.',
		'example' => array(
			
		),
		'supports' => array(
			'anchor' => true,
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'providesContext' => array(
			'core-theme/showTitle' => 'showTitle',
			'core-theme/showDesc' => 'showDesc',
			'core-theme/showBtn' => 'showBtn',
			'core-theme/titleTag' => 'titleTag'
		),
		'attributes' => array(
			'resMode' => array(
				'type' => 'string',
				'default' => 'Desktop'
			),
			'blockStyle' => array(
				'type' => 'object'
			),
			'itemsGap' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'contentMargin' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'contentMaxWidth' => array(
				'type' => 'string'
			),
			'contentVAlign' => array(
				'type' => 'string',
				'default' => 'center'
			),
			'showTitle' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showDesc' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showBtn' => array(
				'type' => 'boolean',
				'default' => true
			),
			'titleTag' => array(
				'type' => 'string',
				'default' => 'h4'
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js'
	),
	'image-accordion-item' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/image-accordion-item',
		'version' => '0.0.14',
		'title' => 'Image Accordion Item',
		'category' => 'core-theme',
		'icon' => 'format-image',
		'description' => 'Individual image, title and button for the Image Accordion block.',
		'example' => array(
			
		),
		'parent' => array(
			'core-theme/image-accordion'
		),
		'usesContext' => array(
			'core-theme/showTitle',
			'core-theme/showDesc',
			'core-theme/showBtn',
			'core-theme/titleTag'
		),
		'supports' => array(
			'anchor' => true,
			'html' => false,
			'align' => false,
			'reusable' => false,
			'color' => array(
				'text' => false,
				'background' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => false
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => false,
					'radius' => false,
					'width' => false
				)
			),
			'spacing' => array(
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'padding' => false
				)
			),
			'shadow' => true
		),
		'attributes' => array(
			'image' => array(
				'type' => 'object',
				'default' => array(
					'id' => '',
					'url' => '',
					'alt' => ''
				)
			),
			'imageTablet' => array(
				'type' => 'object',
				'default' => array(
					'id' => '',
					'url' => '',
					'alt' => ''
				)
			),
			'imageMobile' => array(
				'type' => 'object',
				'default' => array(
					'id' => '',
					'url' => '',
					'alt' => ''
				)
			),
			'itemStyle' => array(
				'type' => 'object'
			),
			'showTitle' => array(
				'type' => 'boolean',
				'default' => true
			),
			'title' => array(
				'type' => 'string'
			),
			'titleTag' => array(
				'type' => 'string',
				'default' => 'h4'
			),
			'showDesc' => array(
				'type' => 'boolean',
				'default' => true
			),
			'description' => array(
				'type' => 'string'
			),
			'showBtn' => array(
				'type' => 'boolean',
				'default' => true
			),
			'btnLabel' => array(
				'type' => 'string',
				'default' => 'Show More'
			),
			'href' => array(
				'type' => 'string',
				'default' => '#'
			),
			'linkTarget' => array(
				'type' => 'string'
			),
			'linkRel' => array(
				'type' => 'string'
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	),
	'slide' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/slide',
		'version' => '0.0.14',
		'title' => 'Slide',
		'category' => 'core-theme',
		'description' => 'Slide child block for carousel.',
		'example' => array(
			
		),
		'parent' => array(
			'core-theme/carousel'
		),
		'attributes' => array(
			'stackRotate' => array(
				'type' => 'number',
				'default' => 0
			),
			'stackOffsetX' => array(
				'type' => 'number',
				'default' => 0
			),
			'stackOffsetY' => array(
				'type' => 'number',
				'default' => 0
			),
			'stackZIndex' => array(
				'type' => 'number',
				'default' => 0
			)
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'color' => array(
				'text' => false,
				'background' => true,
				'gradients' => true
			),
			'background' => array(
				'backgroundImage' => true,
				'backgroundSize' => true
			),
			'spacing' => array(
				'margin' => false,
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'padding' => false
				)
			),
			'__experimentalBorder' => array(
				'__experimentalSkipSerialization' => false,
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true
			),
			'shadow' => array(
				'__experimentalSkipSerialization' => true
			)
		),
		'textdomain' => 'core',
		'editorScript' => array(
			'file:./index.js'
		),
		'editorStyle' => 'file:./index.css'
	),
	'social-share' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/social-share',
		'version' => '0.0.14',
		'title' => 'Social Share',
		'category' => 'core-theme',
		'icon' => 'share',
		'description' => 'Add social sharing buttons for the current post.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'spacing' => array(
				'__experimentalSkipSerialization' => true,
				'margin' => true,
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'showCopyLink' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showLinkedIn' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showTwitter' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showFacebook' => array(
				'type' => 'boolean',
				'default' => true
			),
			'iconSize' => array(
				'type' => 'string',
				'default' => '24px'
			),
			'gap' => array(
				'type' => 'string',
				'default' => '12px'
			),
			'iconColor' => array(
				'type' => 'string'
			),
			'iconBgColor' => array(
				'type' => 'string'
			),
			'iconRadius' => array(
				'type' => 'string'
			),
			'iconPadding' => array(
				'type' => 'string'
			),
			'blockStyle' => array(
				'type' => 'object'
			),
			'justifyContent' => array(
				'type' => 'string'
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'story-card' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/story-card',
		'version' => '0.0.14',
		'title' => 'Story Card',
		'category' => 'core-theme',
		'icon' => 'index-card',
		'description' => 'One card in a Story Cards section: badge icon, image, heading, description and the hand-drawn arrow leading to the next card.',
		'parent' => array(
			'core-theme/story-cards'
		),
		'supports' => array(
			'anchor' => true,
			'html' => false,
			'align' => false,
			'reusable' => false,
			'spacing' => array(
				'margin' => false,
				'padding' => false
			)
		),
		'attributes' => array(
			'icon' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'badgeSide' => array(
				'type' => 'string',
				'default' => 'left'
			),
			'image' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'heading' => array(
				'type' => 'string',
				'default' => ''
			),
			'headingTag' => array(
				'type' => 'string',
				'default' => 'h3'
			),
			'description' => array(
				'type' => 'string',
				'default' => ''
			),
			'lane' => array(
				'type' => 'string',
				'default' => 'auto'
			),
			'offsetY' => array(
				'type' => 'number'
			),
			'arrow' => array(
				'type' => 'string',
				'default' => ''
			),
			'arrowTop' => array(
				'type' => 'number',
				'default' => 0
			),
			'arrowLeft' => array(
				'type' => 'number',
				'default' => 100
			),
			'arrowWidth' => array(
				'type' => 'number',
				'default' => 45
			),
			'arrowRotate' => array(
				'type' => 'number',
				'default' => 0
			),
			'arrowFlipX' => array(
				'type' => 'boolean',
				'default' => false
			),
			'arrowDelay' => array(
				'type' => 'number',
				'default' => 180
			),
			'arrowMobile' => array(
				'type' => 'string',
				'default' => 'auto'
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'story-cards' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/story-cards',
		'version' => '0.0.14',
		'title' => 'Story Cards',
		'category' => 'core-theme',
		'icon' => 'excerpt-view',
		'description' => 'A staggered two-lane run of cards joined by hand-drawn arrows, each revealing on scroll.',
		'example' => array(
			
		),
		'supports' => array(
			'anchor' => true,
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'cardWidth' => array(
				'type' => 'number',
				'default' => 43
			),
			'laneGap' => array(
				'type' => 'number',
				'default' => 0
			),
			'mobileGap' => array(
				'type' => 'number',
				'default' => 70
			),
			'animate' => array(
				'type' => 'boolean',
				'default' => true
			),
			'revealStagger' => array(
				'type' => 'number',
				'default' => 120
			),
			'trailingArrow' => array(
				'type' => 'string',
				'default' => ''
			),
			'trailingLeft' => array(
				'type' => 'number',
				'default' => 41.3
			),
			'trailingOffset' => array(
				'type' => 'number',
				'default' => -13.3
			),
			'trailingWidth' => array(
				'type' => 'number',
				'default' => 19.7
			),
			'trailingDelay' => array(
				'type' => 'number',
				'default' => 180
			),
			'trailingFlipX' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'ticker-gallery' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/ticker-gallery',
		'version' => '0.0.14',
		'title' => 'Ticker Gallery',
		'category' => 'core-theme',
		'icon' => 'images-alt',
		'description' => 'A continuously scrolling row of images, tilted and bottom aligned.',
		'example' => array(
			
		),
		'supports' => array(
			'anchor' => true,
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'images' => array(
				'type' => 'array',
				'default' => array(
					
				),
				'items' => array(
					'type' => 'object'
				)
			),
			'blockStyle' => array(
				'type' => 'object'
			),
			'tallHeight' => array(
				'type' => 'number',
				'default' => 304
			),
			'shortRatio' => array(
				'type' => 'number',
				'default' => 0.88
			),
			'gap' => array(
				'type' => 'number',
				'default' => 44
			),
			'radius' => array(
				'type' => 'number',
				'default' => 24
			),
			'speed' => array(
				'type' => 'number',
				'default' => 40
			),
			'direction' => array(
				'type' => 'string',
				'default' => 'left'
			),
			'pauseOnHover' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'core',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js'
	),
	'annotation' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/annotation-extension',
		'title' => 'Annotation Extension',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js'
	),
	'group-full-height' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/group-full-height-extension',
		'title' => 'Group Force Full Height Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	),
	'group-global-hover' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/group-global-hover-extension',
		'title' => 'Group Global Hover Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	),
	'group-overlay-bg' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/group-overlay-bg-extension',
		'title' => 'Group Overlay Background Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	),
	'highlight' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/highlight-extension',
		'title' => 'Highlight Extension',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	),
	'hover-color' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/hover-color-extension',
		'title' => 'Hover Color Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	),
	'iconic-button' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/iconic-button-extension',
		'title' => 'Iconic Button Extension',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	),
	'kadence-row-divider' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/kadence-row-divider-extension',
		'title' => 'Kadence Row Divider Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	),
	'read-more-button' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/read-more-button-extension',
		'title' => 'Read More Button Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	),
	'text-max-width' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'core-theme/text-max-width-extension',
		'title' => 'Text Max Width Extension',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
	)
);
