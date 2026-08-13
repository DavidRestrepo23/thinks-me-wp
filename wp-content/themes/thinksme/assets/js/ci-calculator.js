/**
 * Corporate tax estimate (template-parts/ci-calculator.php).
 *
 * Reads the rate and each scheme's exemption bands off the markup — they are ACF
 * defaults in inc/ci-content.php, because they are IRAS's numbers and they change
 * with the Budget. Nothing about the schemes is hard-coded here.
 *
 * Without this file the section is still a working GET form: the schemes are
 * native radios and the button takes the visitor to the contact page with what
 * they typed. With it, submitting computes in place instead — the same
 * enhancement contract ci-tools.js and roa-block.js have.
 *
 * The maths is IRAS's own shape: exemption applies to the *bottom* of chargeable
 * income, band by band, and the flat rate is charged on what is left.
 *
 *   exempt = Σ min(remaining, band) × percent
 *   tax    = (income − exempt) × rate
 *
 * Rounded to whole dollars on purpose: an estimate printed to the cent claims a
 * precision it doesn't have.
 */
( function () {
	var form = document.querySelector( '.ci-calc' );

	if ( ! form ) {
		return;
	}

	var input  = form.querySelector( '.ci-calc__input' );
	var result = form.querySelector( '.ci-calc__result' );
	var amount = form.querySelector( '[data-calc-amount]' );
	var saved  = form.querySelector( '[data-calc-saved]' );
	var error  = form.querySelector( '[data-calc-error]' );

	if ( ! input || ! result || ! amount ) {
		return;
	}

	var rate     = parseFloat( form.getAttribute( 'data-rate' ) );
	var currency = form.getAttribute( 'data-currency' ) || '';

	if ( ! isFinite( rate ) ) {
		return;
	}

	function bands() {
		var checked = form.querySelector( 'input[name="scheme"]:checked' );

		if ( ! checked ) {
			return [];
		}

		try {
			var parsed = JSON.parse( checked.getAttribute( 'data-bands' ) || '[]' );

			return Array.isArray( parsed ) ? parsed : [];
		} catch ( e ) {
			// A malformed attribute means no exemption rather than no estimate: the
			// flat rate on the whole amount is still a true, if pessimistic, figure.
			return [];
		}
	}

	// "1,250,000" and "S$ 1250000" both mean the same thing to someone typing an
	// amount, so everything that isn't a digit or a decimal point comes off first.
	function parseAmount( value ) {
		var cleaned = String( value ).replace( /[^0-9.]/g, '' );

		if ( '' === cleaned ) {
			return NaN;
		}

		return parseFloat( cleaned );
	}

	function format( value ) {
		return currency + Math.round( value ).toLocaleString( 'en-SG' );
	}

	function show( element, visible ) {
		if ( ! element ) {
			return;
		}

		if ( visible ) {
			element.removeAttribute( 'hidden' );
		} else {
			element.setAttribute( 'hidden', '' );
		}
	}

	function estimate() {
		var income = parseAmount( input.value );

		if ( ! isFinite( income ) || income < 0 ) {
			show( result, false );
			show( error, true );

			return;
		}

		var remaining = income;
		var exempt    = 0;
		var list      = bands();

		for ( var i = 0; i < list.length && remaining > 0; i++ ) {
			var band    = parseFloat( list[ i ][ 0 ] );
			var percent = parseFloat( list[ i ][ 1 ] );

			if ( ! isFinite( band ) || ! isFinite( percent ) ) {
				continue;
			}

			exempt   += Math.min( remaining, band ) * ( percent / 100 );
			remaining = remaining - Math.min( remaining, band );
		}

		var taxable = Math.max( 0, income - exempt );

		amount.textContent = format( taxable * ( rate / 100 ) );

		if ( saved ) {
			saved.textContent = format( exempt * ( rate / 100 ) );
		}

		show( error, false );
		show( result, true );
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		estimate();
	} );

	// Switching scheme with a figure already on screen re-runs it, so the two
	// exemptions can be compared without retyping. An empty field stays quiet
	// rather than turning a scheme change into an error.
	var schemes = form.querySelectorAll( 'input[name="scheme"]' );

	for ( var i = 0; i < schemes.length; i++ ) {
		schemes[ i ].addEventListener( 'change', function () {
			if ( '' !== input.value.trim() ) {
				estimate();
			}
		} );
	}

	// A figure left on screen from the previous income is worse than none: it
	// reads as the answer to what is in the field now.
	input.addEventListener( 'input', function () {
		show( result, false );
		show( error, false );
	} );
} )();
