/**
 * The Compliance Deadline Calculator in the free-tools panel (Corporate Secretary,
 * template-parts/ci-tools.php). The visitor types a financial year end and each
 * deadline the form names in `data-deadlines` is printed under it.
 *
 * The deadlines are defaults in inc/ci-content.php, not constants here: each is
 * either `months` after the FYE or a `fixed` [day, month] in the following year.
 * A month-based deadline lands on the same day of the target month — or on that
 * month's last day when the FYE is itself a month end, which is how ACRA counts:
 * 31/12/26 + 7 months is 31/07/27, and 30/06/26 + 7 months is 31/01/27.
 *
 * Accepts DD/MM/YYYY with "/", "." or "-", and a two-digit year, and answers in
 * the shape it was given (31.12.26 → 31.07.27). Without this file the form is the
 * plain GET to the contact page it always was.
 */
( function () {
	var forms = document.querySelectorAll( 'form[data-deadlines]' );

	for ( var f = 0; f < forms.length; f++ ) {
		init( forms[ f ] );
	}

	function daysIn( year, month ) {
		// Day 0 of the next month is the last day of this one; month is 1-based.
		return new Date( year, month, 0 ).getDate();
	}

	function parse( value ) {
		var match = String( value ).trim().match( /^(\d{1,2})\s*([\/.\-])\s*(\d{1,2})\s*\2\s*(\d{2}|\d{4})$/ );

		if ( ! match ) {
			return null;
		}

		var day   = parseInt( match[1], 10 );
		var month = parseInt( match[3], 10 );
		var year  = parseInt( match[4], 10 );

		if ( 2 === match[4].length ) {
			year += 2000;
		}

		if ( month < 1 || month > 12 || day < 1 || day > daysIn( year, month ) ) {
			return null;
		}

		return {
			day: day,
			month: month,
			year: year,
			separator: match[2],
			shortYear: 2 === match[4].length
		};
	}

	function addMonths( date, months ) {
		var index = ( date.month - 1 ) + months;
		var year  = date.year + Math.floor( index / 12 );
		var month = ( index % 12 ) + 1;
		var last  = daysIn( year, month );
		var day   = date.day === daysIn( date.year, date.month ) ? last : Math.min( date.day, last );

		return { day: day, month: month, year: year };
	}

	function pad( n ) {
		return n < 10 ? '0' + n : String( n );
	}

	function format( date, like ) {
		var year = like.shortYear ? pad( date.year % 100 ) : String( date.year );

		return pad( date.day ) + like.separator + pad( date.month ) + like.separator + year;
	}

	function init( form ) {
		var input  = form.querySelector( 'input' );
		var panel  = form.parentNode;
		var result = panel.querySelector( '[data-deadlines-result]' );
		var error  = panel.querySelector( '[data-deadlines-error]' );
		var list;

		try {
			list = JSON.parse( form.getAttribute( 'data-deadlines' ) || '[]' );
		} catch ( e ) {
			return;
		}

		if ( ! input || ! result || ! Array.isArray( list ) || ! list.length ) {
			return;
		}

		function toggle( element, visible ) {
			if ( ! element ) {
				return;
			}

			if ( visible ) {
				element.removeAttribute( 'hidden' );
			} else {
				element.setAttribute( 'hidden', '' );
			}
		}

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			var fye = parse( input.value );

			if ( ! fye ) {
				toggle( result, false );
				toggle( error, true );

				return;
			}

			for ( var i = 0; i < list.length; i++ ) {
				var cell = result.querySelector( '[data-deadline="' + i + '"]' );
				var due;

				if ( ! cell ) {
					continue;
				}

				if ( list[ i ].fixed ) {
					due = { day: list[ i ].fixed[0], month: list[ i ].fixed[1], year: fye.year + 1 };
				} else {
					due = addMonths( fye, parseInt( list[ i ].months, 10 ) || 0 );
				}

				cell.textContent = format( due, fye );
			}

			toggle( error, false );
			toggle( result, true );
		} );

		// A date left on screen from the previous FYE reads as the answer to the new one.
		input.addEventListener( 'input', function () {
			toggle( result, false );
			toggle( error, false );
		} );
	}
} )();
