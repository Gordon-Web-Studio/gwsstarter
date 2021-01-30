wp.domReady( () => {
	// Heading Block - Register block style.
	wp.blocks.registerBlockStyle(
		'core/heading',
		[
			{
				name: 'default',
				label: 'Default',
				isDefault: true,
			},
			{
				name: 'alt',
				label: 'Alternate',
			},
		]
	);

	// Button Block - unregister block styles.
	wp.blocks.unregisterBlockStyle(
		'core/button',
		[ 'default', 'outline', 'squared', 'fill' ]
	);

	//Button Block - register new block style.
	wp.blocks.registerBlockStyle(
		'core/button',
		[
			{
				name: 'solid-primary',
				label: 'Primary',
				isDefault: true,
			},
			{
				name: 'solid-primary-light',
				label: 'Primary Light',
			},
			{
				name: 'solid-white',
				label: 'White',
			},
			{
				name: 'solid-black',
				label: 'Black',
			},
			{
				name: 'outline-primary',
				label: 'Outline Primary',
			},
			{
				name: 'outline-white',
				label: 'Outline White',
			},
		]
	);
} );
