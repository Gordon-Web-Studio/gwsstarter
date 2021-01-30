const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { Fragment } = wp.element;
const {
	InspectorControls,
	// PanelColorSettings,
	// PanelColorGradientSettings,
	// withColors,
	// getColorClassName,
	// __experimentalUseGradient,
} = wp.blockEditor;
const {
	PanelBody,
	PanelRow,
	// RangeControl,
	SelectControl,
} = wp.components;
const { createHigherOrderComponent } = wp.compose;

// Add new Button Size and Icons Attributes.
function addButtonSizes( settings, name ) {
	if ( typeof settings.attributes !== 'undefined' ) {
		if ( name === 'core/button' ) {
			settings.attributes = Object.assign( settings.attributes, {
				buttonSize: {
					type: 'string',
				},
				buttonIcon: {
					type: 'string',
				},
				buttonIconType: {
					type: 'string',
				},
				buttonIconPos: {
					type: 'string',
				},
			} );
		}
	}
	return settings;
}

addFilter(
	'blocks.registerBlockType',
	'wp_rig/button-custom-sizes',
	addButtonSizes
);

const buttonSizeControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( props.name !== 'core/button' ) {
			return ( <BlockEdit { ...props } /> );
		}

		const { attributes, setAttributes, isSelected } = props;
		const { buttonSize, buttonIcon, buttonIconType, buttonIconPos } = attributes;

		let newClassName = ( attributes.className !== undefined ) ? attributes.className : '';

		const newStyles = { ...props.style };

		if ( buttonSize !== undefined ) {
			newClassName += ' btn-' + buttonSize;
		}

		if ( buttonIcon !== undefined ) {
			newClassName += ' icon icon-' + buttonIcon;
		}

		if ( buttonIcon !== undefined && buttonIconType !== undefined ) {
			newClassName += ' icon-' + buttonIconType;
		}

		if ( buttonIcon !== undefined && buttonIconPos !== undefined ) {
			newClassName += ' icon-' + buttonIconPos;
		}

		const newProps = {
			...props,
			attributes: {
				...attributes,
				className: newClassName,
			},
			style: newStyles,
		};

		return (
			<Fragment>
				<div style={ newStyles } className={ newClassName }>
					{ isSelected && ( props.name === 'core/button' ) &&
						<InspectorControls>
							<PanelBody
								title={ __( 'Button Size', 'wp-rig' ) }
								initialOpen={ false }
							>
								<PanelRow>
									<SelectControl
										label={ __( 'Button Size', 'wp-rig' ) }
										value={ buttonSize }
										options={ [
											{ value: 'nosize', label: __( 'None', 'wp-rig' ) },
											{ value: 'sm', label: __( 'Small', 'wp-rig' ) },
											{ value: 'md', label: __( 'Medium', 'wp-rig' ) },
											{ value: 'lg', label: __( 'Large', 'wp-rig' ) },
											{ value: 'long', label: __( 'Long', 'wp-rig' ) },
											{ value: 'short', label: __( 'Short', 'wp-rig' ) },
										] }
										onChange={ ( buttonSize ) => { // eslint-disable-line
											setAttributes( { buttonSize } );
										} }
									/>
								</PanelRow>
							</PanelBody>
							<PanelBody
								title={ __( 'Button Icon', 'wp-rig' ) }
								initialOpen={ false }
							>
								<PanelRow>
									<SelectControl
										label={ __( 'Button Icon', 'wp-rig' ) }
										value={ buttonIcon }
										options={ [
											{ value: 'noicon', label: __( 'None', 'wp-rig' ) },
											{ value: 'long-arrow-right', label: __( 'Long Arrow Right', 'wp-rig' ) },
											{ value: 'angle-right', label: __( 'Angle Right', 'wp-rig' ) },
											{ value: 'chevron-circle-right', label: __( 'Chevron Circle Right', 'wp-rig' ) },
											{ value: 'arrow-circle-right', label: __( 'Arrow Circle Right', 'wp-rig' ) },
										] }
										onChange={ ( buttonIcon ) => { // eslint-disable-line
											setAttributes( { buttonIcon } );
										} }
									/>
								</PanelRow>
								<PanelRow>
									<SelectControl
										label={ __( 'Button Icon Type', 'wp-rig' ) }
										value={ buttonIconType }
										options={ [
											{ value: 'solid', label: __( 'Solid', 'wp-rig' ) },
											{ value: 'regular', label: __( 'Regular', 'wp-rig' ) },
											{ value: 'light', label: __( 'Light', 'wp-rig' ) },
											{ value: 'duotone', label: __( 'Duotone', 'wp-rig' ) },
										] }
										onChange={ ( buttonIconType ) => { // eslint-disable-line
											setAttributes( { buttonIconType } );
										} }
									/>
								</PanelRow>
								<PanelRow>
									<SelectControl
										label={ __( 'Button Icon Position', 'wp-rig' ) }
										value={ buttonIconPos }
										options={ [
											{ value: 'left', label: __( 'Left', 'wp-rig' ) },
											{ value: 'right', label: __( 'Right', 'wp-rig' ) },
										] }
										onChange={ ( buttonIconPos ) => { // eslint-disable-line
											setAttributes( { buttonIconPos } );
										} }
									/>
								</PanelRow>
							</PanelBody>
						</InspectorControls>
					}
					<BlockEdit { ...newProps } />
				</div>
			</Fragment>
		);
	};
}, 'buttonSizeControls' );

addFilter(
	'editor.BlockEdit',
	'wp_rig/button-size-control',
	buttonSizeControls
);

// Save Button Size.
function buttonApplyExtraClass( extraProps, blockType, attributes ) {
	const { buttonSize, buttonIcon, buttonIconType, buttonIconPos } = attributes;

	if ( typeof buttonSize !== undefined && buttonSize ) {
		extraProps.className = extraProps.className + ' btn-' + buttonSize;
	}

	if ( typeof buttonIcon !== undefined && buttonIcon ) {
		extraProps.className = extraProps.className + ' icon icon-' + buttonIcon;
	}

	if (
		( typeof buttonIcon !== undefined && buttonIcon ) &&
		( typeof buttonIconType !== undefined && buttonIconType )
	) {
		extraProps.className = extraProps.className + ' icon-' + buttonIconType;
	}

	if (
		( typeof buttonIcon !== undefined && buttonIcon ) &&
		( typeof buttonIconPos !== undefined && buttonIconPos )
	) {
		extraProps.className = extraProps.className + ' icon-' + buttonIconPos;
	}

	return extraProps;
}

addFilter(
	'blocks.getSaveContent.extraProps',
	'wp_rig/button-apply-class',
	buttonApplyExtraClass
);

// Add new List type.
function addListSizes( settings, name ) {
	if ( typeof settings.attributes !== 'undefined' ) {
		if ( name === 'core/list' ) {
			settings.attributes = Object.assign( settings.attributes, {
				listType: {
					type: 'string',
				},
			} );
		}
	}
	return settings;
}

addFilter(
	'blocks.registerBlockType',
	'wp_rig/list-custom-types',
	addListSizes
);

const listTypeControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( props.name !== 'core/list' ) {
			return ( <BlockEdit { ...props } /> );
		}

		const { attributes, setAttributes, isSelected } = props;
		const { listType } = attributes;

		let newClassName = ( attributes.className !== undefined ) ? attributes.className : '';

		const newStyles = { ...props.style };

		if ( listType !== undefined ) {
			if ( ! newClassName.includes( listType ) ) {
				let newClassNameArray = newClassName.split( ' ' );

				newClassNameArray = newClassNameArray.map( function( item ) {
					return item.includes( 'wp-block-list-' ) ? listType : item;
				} );

				newClassName = newClassNameArray.join( ' ' );

				if ( ! newClassName ) {
					newClassName += ' ' + listType;
				}
			}
		}

		const newProps = {
			...props,
			attributes: {
				...attributes,
				className: newClassName,
			},
			style: newStyles,
		};

		return (
			<Fragment>
				<div style={ newStyles } className={ newClassName }>
					{ isSelected && ( props.name === 'core/list' ) &&
						<InspectorControls>
							<PanelBody
								title={ __( 'List Type', 'wp-rig' ) }
								initialOpen={ false }
							>
								<PanelRow>
									<SelectControl
										label={ __( 'List Type', 'wp-rig' ) }
										value={ listType }
										options={ [
											{ value: 'wp-block-list-default', label: __( 'Default', 'wp-rig' ) },
											{ value: 'wp-block-list-check', label: __( 'Checklist', 'wp-rig' ) },
										] }
										onChange={ ( listType ) => { // eslint-disable-line
											setAttributes( { listType } );
										} }
									/>
								</PanelRow>
							</PanelBody>
						</InspectorControls>
					}
					<BlockEdit { ...newProps } />
				</div>
			</Fragment>
		);
	};
}, 'listTypeControls' );

addFilter(
	'editor.BlockEdit',
	'wp_rig/list-type-control',
	listTypeControls
);

// Save List Type.
function listApplyExtraClass( extraProps, blockType, attributes ) {
	const { listType } = attributes;

	let className = ( extraProps.className !== undefined ) ? extraProps.className : '';

	if ( typeof listType !== undefined && listType ) {
		className += ' ' + listType;
	}

	extraProps.className = className;

	return extraProps;
}

addFilter(
	'blocks.getSaveContent.extraProps',
	'wp_rig/list-apply-class',
	listApplyExtraClass
);
