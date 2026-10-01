/**
 * Smooth scrolling for in-page links.
 *
 * Replaces the browser's fixed-speed `scroll-behavior: smooth` for links that
 * point at an element on the same page (`#section`, or this page's URL plus a
 * hash). The jump eases in and out, takes longer for longer distances (capped),
 * lands below any `scroll-margin-top` and the logged-in admin bar, stops as soon
 * as the visitor scrolls, touches or presses a key, then moves keyboard focus to
 * the target so keyboard and screen-reader users land in the right place.
 *
 * Visitors who prefer reduced motion get an instant jump. The CSS rule in
 * blocks.css stays as a fallback for when this script doesn't run.
 *
 * Opt a link out with `data-no-smooth-scroll`.
 */
( function () {
	'use strict';

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	var root = document.documentElement;
	var frame = null;
	var cancelEvents = [ 'wheel', 'touchstart', 'keydown', 'mousedown' ];

	// Starts gently, speeds up, then settles softly at the target.
	function ease( t ) {
		return t < 0.5 ? 4 * t * t * t : 1 - Math.pow( -2 * t + 2, 3 ) / 2;
	}

	// ~420ms for a short hop, growing with distance, never past 1.1s.
	function durationFor( distance ) {
		return Math.min( 1100, 300 + Math.sqrt( Math.abs( distance ) ) * 12 );
	}

	function findTarget( hash ) {
		if ( ! hash || hash === '#' ) {
			return null;
		}
		var id = hash.slice( 1 );
		try {
			id = decodeURIComponent( id );
		} catch ( e ) {}
		return document.getElementById( id ) || document.getElementsByName( id )[ 0 ] || null;
	}

	// The fixed admin bar covers the top of the page for logged-in users.
	function topOffset() {
		var bar = document.getElementById( 'wpadminbar' );
		return bar && window.getComputedStyle( bar ).position === 'fixed' ? bar.offsetHeight : 0;
	}

	function targetY( el ) {
		var margin = parseFloat( window.getComputedStyle( el ).scrollMarginTop ) || 0;
		var y = el.getBoundingClientRect().top + window.pageYOffset - margin - topOffset();
		var max = root.scrollHeight - window.innerHeight;
		return Math.max( 0, Math.min( Math.round( y ), max ) );
	}

	function stop() {
		if ( frame !== null ) {
			window.cancelAnimationFrame( frame );
			frame = null;
		}
		cancelEvents.forEach( function ( type ) {
			window.removeEventListener( type, stop, { passive: true } );
		} );
		// Hand scrolling back to the CSS rule.
		root.style.scrollBehavior = '';
	}

	function scrollToY( y, done ) {
		stop();

		var start = window.pageYOffset;
		var distance = y - start;

		// Per-frame jumps must be instant, or the CSS smooth rule would
		// animate each one on top of this animation.
		root.style.scrollBehavior = 'auto';

		// Background tabs get no animation frames, so jump straight there.
		if ( reduceMotion.matches || document.hidden || Math.abs( distance ) < 2 ) {
			window.scrollTo( 0, y );
			stop();
			done();
			return;
		}

		var duration = durationFor( distance );
		var startTime = null;

		cancelEvents.forEach( function ( type ) {
			window.addEventListener( type, stop, { passive: true } );
		} );

		function step( now ) {
			if ( startTime === null ) {
				startTime = now;
			}
			var progress = Math.min( 1, ( now - startTime ) / duration );
			window.scrollTo( 0, start + distance * ease( progress ) );

			if ( progress < 1 ) {
				frame = window.requestAnimationFrame( step );
			} else {
				stop();
				done();
			}
		}

		frame = window.requestAnimationFrame( step );
	}

	function focusTarget( el ) {
		if ( ! el.matches( 'a[href], button, input, select, textarea, [tabindex]' ) ) {
			el.setAttribute( 'tabindex', '-1' );
		}
		el.focus( { preventScroll: true } );
	}

	function samePage( link ) {
		var strip = function ( path ) {
			return path.replace( /\/+$/, '' );
		};
		return (
			link.origin === window.location.origin &&
			strip( link.pathname ) === strip( window.location.pathname ) &&
			link.search === window.location.search
		);
	}

	document.addEventListener( 'click', function ( event ) {
		if ( event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) {
			return;
		}

		var link = event.target.closest ? event.target.closest( 'a[href*="#"]' ) : null;
		if (
			! link ||
			( link.target && link.target !== '_self' ) ||
			link.hasAttribute( 'download' ) ||
			link.hasAttribute( 'data-no-smooth-scroll' ) ||
			link.classList.contains( 'skip-link' ) ||
			link.getAttribute( 'role' ) === 'tab' ||
			! samePage( link )
		) {
			return;
		}

		var target = findTarget( link.hash );
		if ( ! target ) {
			return;
		}

		event.preventDefault();

		if ( window.location.hash !== link.hash ) {
			window.history.pushState( null, '', link.hash );
		}

		scrollToY( targetY( target ), function () {
			focusTarget( target );
		} );
	} );
} )();
