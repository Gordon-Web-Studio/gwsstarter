/**
 * File global.js.
 *
 * This file holds some smalls js scripts.
 */

window.addEventListener( 'load', () => {
	// Sticky Header.
	const isSticky = document.body.classList.contains( 'is-sticky' );

	if ( isSticky ) {
		const pageYOffset = 350;

		scrollingHeader( pageYOffset );

		window.addEventListener( 'scroll', () => {
			scrollingHeader( pageYOffset );
		} );

		function scrollingHeader() {
			const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
			if ( scrollTop > pageYOffset ) {
				document.body.classList.add( 'scrolled' );
			} else {
				document.body.classList.remove( 'scrolled' );
			}
		}
	}

	// Get image natural width and height and add helper classes to .ui-media-container when image is portrait or landscape.
	const imgContainer = document.querySelectorAll( '.ui-media-container' );
	imgContainer.forEach( checkImgOrientation );

	noClick();
} );

window.addEventListener( 'resize', () => {
	// Logo Changer.
	changeLogo();
} );

// Logo Changer.
changeLogo();

// Some helpers functions.
function toggleClass( elem, className ) { // eslint-disable-line
	elem.classList.toggle( className );
}

function addClass( elem, className ) { // eslint-disable-line
	elem.classList.add( className );
}

function removeClass( elem, className ) { // eslint-disable-line
	elem.classList.remove( className );
}

function toggleParentClass( elem, className ) { // eslint-disable-line
	elem.parentNode.classList.toggle( className );
}

function addParentClass( elem, className ) { // eslint-disable-line
	elem.parentNode.classList.add( className );
}

function removeParentClass( elem, className ) { // eslint-disable-line
	elem.parentNode.classList.remove( className );
}

function getClosest( elem, selector ) { // eslint-disable-line
	for ( ; elem && elem !== document; elem = elem.parentNode ) {
		if ( elem.matches( selector ) ) {
			return elem;
		}
	}
	return null;
}

function checkImgOrientation( imgContainer ) {
	const img = imgContainer.querySelector( 'img' );

	if ( img ) {
		const image = new Image();
		if ( img.classList.contains( 'lazy' ) ) {
			image.src = img.getAttribute( 'data-src' );
		} else {
			image.src = img.src;
		}

		image.onload = function() {
			if ( image.naturalWidth >= image.naturalHeight ) {
				addClass( imgContainer, 'img-landscape' );
			} else {
				addClass( imgContainer, 'img-portrait' );
			}
		};
	}
}

function toggleCollapse( trigger ) { // eslint-disable-line
	trigger.addEventListener( 'click', function() {
		const elm = this;
		const selector = elm.getAttribute( 'data-target' );

		// First hide all collapsable containers grouped by class .collapse
		const groupClass = elm.getAttribute( 'data-group' );
		if ( groupClass ) {
			collapse( groupClass, 'hide' );
			collapse( '[data-toggle="collapse"]', 'hide' );
		}

		if ( selector ) {
			collapse( selector, 'toggle' );
			collapse( '[data-target="' + selector + '"]', 'toggle' );
		}
	} );

	// map our commands to the classList methods
	const fnmap = {
		toggle: 'toggle',
		show: 'add',
		hide: 'remove',
	};

	const collapse = ( selector, cmd ) => {
		const targets = Array.from( document.querySelectorAll( selector ) );
		targets.forEach( ( target ) => {
			target.classList[ fnmap[ cmd ] ]( 'show' );
		} );
	};
}

function simulateClick( elem ) { // eslint-disable-line
	// Create our event (with options)
	const evt = new MouseEvent( 'click', {
		bubbles: true,
		cancelable: true,
		view: window,
	} );

	// If cancelled, don't dispatch our event
	const canceled = ! elem.dispatchEvent( evt ); // eslint-disable-line
}

// Get real window width
function getWindowWidth() {
	let windowWidth = 0;
	if ( typeof window.innerWidth === 'number' ) {
		windowWidth = window.innerWidth;
	} else if ( document.documentElement && document.documentElement.clientWidth ) {
		windowWidth = document.documentElement.clientWidth;
	} else if ( document.body && document.body.clientWidth ) {
		windowWidth = document.body.clientWidth;
	}
	return windowWidth;
}

// Responsive issues.
function isMobile() {
	const ww = getWindowWidth();
	if ( ww < 768 ) {
		return true;
	} else if ( ww >= 768 ) {
		return false;
	}
}

// Check if body class has transparency.
function isTransparent() {
	if ( document.body.classList.contains( 'is-transparent' ) ) {
		return true;
	}
	return false;
}

// Check if body class has scrolled.
function isScrolled() {
	if ( document.body.classList.contains( 'scrolled' ) ) {
		return true;
	}
	return false;
}

// Logo Changer.
function changeLogo() {
	const customLogo = document.querySelector( '.custom-logo' );

	if ( customLogo ) {
		const logoDefault = customLogo.getAttribute( 'data-logo-default' ),
			logoTransparent = customLogo.getAttribute( 'data-logo-transparent' ),
			logoMobile = customLogo.getAttribute( 'data-logo-mobile' ),
			logoMobileTransparent = customLogo.getAttribute( 'data-logo-mobile-transparent' );

		if ( logoTransparent || logoMobile || logoMobileTransparent ) {
			if ( isMobile() && ! isTransparent() ) {
				document.body.classList.add( 'is-mobile' );
				if ( logoMobile ) {
					customLogo.setAttribute( 'src', logoMobile );
				} else {
					customLogo.setAttribute( 'src', logoDefault );
				}
			} else if ( isMobile() && isTransparent() ) {
				document.body.classList.add( 'is-mobile' );
				if ( logoMobileTransparent ) {
					customLogo.setAttribute( 'src', logoMobileTransparent );
				} else if ( logoTransparent ) {
					customLogo.setAttribute( 'src', logoTransparent );
				} else {
					customLogo.setAttribute( 'src', logoDefault );
				}
			} else if ( ! isMobile() && isTransparent() ) {
				document.body.classList.remove( 'is-mobile' );
				if ( logoTransparent ) {
					customLogo.setAttribute( 'src', logoTransparent );
				} else {
					customLogo.setAttribute( 'src', logoDefault );
				}
			} else {
				document.body.classList.remove( 'is-mobile' );
				customLogo.setAttribute( 'src', logoDefault );
			}

			if ( isTransparent() ) {
				window.addEventListener( 'scroll', () => {
					if ( isScrolled() && ! isMobile() ) {
						customLogo.setAttribute( 'src', logoDefault );
					} else if ( isScrolled() && isMobile() ) {
						if ( logoMobile ) {
							customLogo.setAttribute( 'src', logoMobile );
						} else {
							customLogo.setAttribute( 'src', logoDefault );
						}
					} else if ( ! isScrolled() && isMobile() ) {
						if ( logoMobileTransparent ) {
							customLogo.setAttribute( 'src', logoMobileTransparent );
						} else if ( logoTransparent ) {
							customLogo.setAttribute( 'src', logoTransparent );
						} else {
							customLogo.setAttribute( 'src', logoDefault );
						}
					} else if ( logoTransparent ) {
						customLogo.setAttribute( 'src', logoTransparent );
					} else {
						customLogo.setAttribute( 'src', logoDefault );
					}
				} );
			}
		}
	}
}

function noClick() {
	const allAnchors = document.querySelectorAll( '.noclick > a' );

	allAnchors.forEach( function( el ) {
		el.addEventListener( 'click', function( e ) {
			e.preventDefault();
		} );
	} );
}
