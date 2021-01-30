/**
 * Internal dependencies
 */
import { isProd } from './gulp/constants';
import { getThemeConfig } from './gulp/utils';
import { safelist, getCSSWhitelistPatterns } from './tailwind-safelist';

const config = getThemeConfig();

module.exports = {
	darkMode: false, // Use ´media´ to enable.
	theme: {
		extend: {
			screens: {
				nav: '992px',
				'nav-max': { max: '991px' },
				'md-max': { max: '767px' },
				'sm-max': { max: '639px' },
			},
			colors: {
				primary: '#00d7c8',
				'primary-light': '#73f3ea',
				'primary-dark': '#00a79b',
				secondary: '#313146',
				'secondary-light': '#3e3e55',
				'secondary-dark': '#232338',
				tertiary: '#9c27b0',
				'tertiary-light': '#c85ddb',
				'tertiary-dark': '#7e1d8f',
			},
			fontFamily: {
				heading: [ 'Oswald', 'sans-serif' ],
				sans: [ 'Poppins', 'sans-serif' ],
			},
			fontSize: {
				sm: [ '12px', '22px' ],
				base: [ '16px', '28px' ],
				lg: [ '20px', '34px' ],
				fs5: [ '20px', '25px' ],
				fs4: [ '30px', '40px' ],
				fs3: [ '40px', '50px' ],
				fs2: [ '50px', '60px' ],
				fs1: [ '60px', '70px' ],
			},
			outline: {
				green: [ '1px dotted #00d7c8', '1px' ],
			},
			boxShadow: {
				'3xl': '10px 4px 44px 3px rgba(0, 0, 0, 0.08)',
			},
			container: {
				center: true,
				padding: {
					DEFAULT: '1rem',
					sm: '2rem',
					md: '3rem',
				},
			},
			zIndex: {
				'-1': '-1',
			},
		},
	},
	variants: {
		extend: {
			scale: [ 'responsive', 'hover', 'focus', 'group-hover' ],
		},
	},
	plugins: [],
	purge: {
		enabled: isProd ? config.dev.tailwindcss.purgecss : false,
		layers: [ 'base', 'components', 'utilities' ],
		preserveHtmlElements: false,
		content: [
			'./**/*.php',
			'./acf-json/*.json',
			'./assets/js/**/*.js',
		],
		options: {
			safelist,
			defaultExtractor: ( content ) => {
				// Capture as liberally as possible, including things like `h-(screen-1.5)`
				const broadMatches = content.match( /[^<>"'`\s]*[^<>"'`\s:]/g ) || [];

				// Capture classes within other delimiters like .block(class="w-1/2") in Pug
				const innerMatches = content.match( /[^<>"'`\s.()]*[^<>"'`\s.():]/g ) || [];

				return broadMatches.concat( innerMatches );
			},
			whitelistPatterns: getCSSWhitelistPatterns(),
		},
	},
};
