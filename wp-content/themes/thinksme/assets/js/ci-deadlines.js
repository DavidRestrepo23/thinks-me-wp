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

		// QA #16 (client, 15/10): the calendar glyph in the field opens a date picker.
		// The glyph itself stays decorative; a real button sits over it and opens a
		// native <input type="date"> (showPicker, with a focus/click fallback for
		// browsers without it). The picked date is written back as DD/MM/YYYY — the
		// format the field documents — and the deadlines are calculated at once.
		var field = input.closest( '.ci-tools__field' );
		var glyph = field ? field.querySelector( '.ci-tools__field-icon' ) : null;

		if ( glyph ) {
			var picker = document.createElement( 'input' );
			picker.type = 'date';
			picker.className = 'ci-tools__date-picker';
			picker.tabIndex = -1;
			picker.setAttribute( 'aria-hidden', 'true' );

			var button = document.createElement( 'button' );
			button.type = 'button';
			button.className = 'ci-tools__date-button';
			button.setAttribute( 'aria-label', 'Choose your financial year end from a calendar' );

			field.appendChild( picker );
			field.appendChild( button );

			button.addEventListener( 'click', function () {
				var current = parse( input.value );

				if ( current ) {
					picker.value = current.year + '-' + pad( current.month ) + '-' + pad( current.day );
				}

				if ( typeof picker.showPicker === 'function' ) {
					try {
						picker.showPicker();
						return;
					} catch ( e ) {
						// Fall through: some browsers refuse showPicker on a hidden input.
					}
				}

				picker.focus();
				picker.click();
			} );

			picker.addEventListener( 'change', function () {
				var parts = String( picker.value ).split( '-' );

				if ( 3 !== parts.length ) {
					return;
				}

				input.value = parts[2] + '/' + parts[1] + '/' + parts[0];
				input.dispatchEvent( new Event( 'input', { bubbles: true } ) );

				if ( typeof form.requestSubmit === 'function' ) {
					form.requestSubmit();
				} else {
					form.dispatchEvent( new Event( 'submit', { cancelable: true } ) );
				}
			} );
		}
	}
} )();
