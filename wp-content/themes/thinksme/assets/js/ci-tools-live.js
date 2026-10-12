/**
 * The three free tools in template-parts/ci-tools.php that compute in place
 * (QA #10, #54). Each form names its tool in `data-tool`:
 *
 *   name-check  A format check on the proposed name, then a look-up against
 *               ACRA's "Information on Corporate Entities" register, which
 *               data.gov.sg publishes as one dataset per first letter and serves
 *               with CORS from its public API. Identical names (ignoring the
 *               Pte. Ltd. suffix, punctuation and "&"/"and") are flagged with
 *               their status; close matches are listed. If the API can't be
 *               reached the format check still stands on its own.
 *   ssic        Searches SSIC 2025 — titles plus SingStat's alphabetical index
 *               of alternative titles — shipped as assets/data/ssic-2025.json and
 *               only fetched on the first search.
 *   tax         Corporate tax at `data-rate` under each scheme in `data-schemes`
 *               ([label, [[band, exempt %], …]]), IRAS's bottom-up banding — the
 *               same maths as assets/js/ci-calculator.js.
 *
 * Without this file each form stays the plain GET to the contact page.
 */
( function () {
	var forms = document.querySelectorAll( 'form[data-tool]' );

	if ( ! forms.length ) {
		return;
	}

	var API = 'https://data.gov.sg/api/action/datastore_search';

	// One dataset per first character of the entity name (data.gov.sg collection 2).
	var ACRA = {
		A: 'd_8575e84912df3c28995b8e6e0e05205a', B: 'd_3a3807c023c61ddfba947dc069eb53f2',
		C: 'd_c0650f23e94c42e7a20921f4c5b75c24', D: 'd_acbc938ec77af18f94cecc4a7c9ec720',
		E: 'd_124a9bd407c7a25f8335b93b86e50fdd', F: 'd_4526d47d6714d3b052eed4a30b8b1ed6',
		G: 'd_b58303c68e9cf0d2ae93b73ffdbfbfa1', H: 'd_fa2ed456cf2b8597bb7e064b08fc3c7c',
		I: 'd_85518d970b8178975850457f60f1e738', J: 'd_478f45a9c541cbe679ca55d1cd2b970b',
		K: 'd_5573b0db0575db32190a2ad27919a7aa', L: 'd_a2141adf93ec2a3c2ec2837b78d6d46e',
		M: 'd_9af9317c646a1c881bb5591c91817cc6', N: 'd_67e99e6eabc4aad9b5d48663b579746a',
		O: 'd_5c4ef48b025fdfbc80056401f06e3df9', P: 'd_181005ca270b45408b4cdfc954980ca2',
		Q: 'd_4130f1d9d365d9f1633536e959f62bb7', R: 'd_2b8c54b2a490d2fa36b925289e5d9572',
		S: 'd_df7d2d661c0c11a7c367c9ee4bf896c1', T: 'd_72f37e5c5d192951ddc5513c2b134482',
		U: 'd_0cc5f52a1f298b916f317800251057f3', V: 'd_e97e8e7fc55b85a38babf66b0fa46b73',
		W: 'd_af2042c77ffaf0db5d75561ce9ef5688', X: 'd_1cd970d8351b42be4a308d628a6dd9d3',
		Y: 'd_31af23fdb79119ed185c256f03cb5773', Z: 'd_4e3db8955fdcda6f9944097bef3d2724',
		OTHERS: 'd_300ddc8da4e8f7bdc1bfc62d0d99e2e7'
	};

	// Words ACRA refers to another authority before approving a name. Indicative,
	// not exhaustive: the disclaimer says so and BizFile+ has the final word.
	var RESTRICTED = [
		{ words: [ 'bank', 'banking', 'finance', 'financing', 'financial', 'insurance', 'assurance', 'reinsurance', 'securities', 'trust', 'fund', 'funds' ], who: 'the Monetary Authority of Singapore (MAS)' },
		{ words: [ 'law', 'legal', 'lawyer', 'lawyers', 'solicitor', 'solicitors', 'advocate', 'advocates', 'attorney', 'notary' ], who: 'the Ministry of Law' },
		{ words: [ 'clinic', 'medical', 'hospital', 'dental', 'pharmacy', 'surgery', 'surgical' ], who: 'the Ministry of Health' },
		{ words: [ 'school', 'college', 'university', 'academy', 'institute', 'education', 'kindergarten', 'preschool' ], who: 'the education authorities (MOE / CPE / ECDA)' },
		{ words: [ 'architect', 'architects', 'architecture', 'architectural' ], who: 'the Board of Architects' },
		{ words: [ 'audit', 'auditing', 'auditor', 'auditors', 'accountants' ], who: 'ACRA\'s Public Accountants Oversight Committee' },
		{ words: [ 'government', 'ministry', 'temasek', 'parliament' ], who: 'the relevant government agency' },
		{ words: [ 'cooperative', 'co-operative' ], who: 'the Registry of Co-operative Societies' }
	];

	var SUFFIX = /[\s,]*(PTE\.?\s*LTD\.?|PRIVATE\s+LIMITED|PTE\.?\s+LIMITED|LIMITED\s+LIABILITY\s+PARTNERSHIP|LLP|LIMITED|LTD\.?|BERHAD|BHD\.?)\s*$/i;

	function el( tag, cls, text ) {
		var node = document.createElement( tag );

		if ( cls ) {
			node.className = cls;
		}

		if ( null !== text && undefined !== text ) {
			node.textContent = text;
		}

		return node;
	}

	function status( kind, text ) {
		return el( 'p', 'ci-tools__status ci-tools__status--' + kind, text );
	}

	function toggle( node, visible ) {
		if ( ! node ) {
			return;
		}

		if ( visible ) {
			node.removeAttribute( 'hidden' );
		} else {
			node.setAttribute( 'hidden', '' );
		}
	}

	function stripAccents( text ) {
		return String( text ).normalize ? String( text ).normalize( 'NFD' ).replace( /[̀-ͯ]/g, '' ) : String( text );
	}

	/* ------------------------------------------------------------------ name */

	// ACRA compares names without the company-type suffix, punctuation or case,
	// and treats "&" and "and" alike.
	function core( name ) {
		var value = stripAccents( name ).toUpperCase().trim();

		while ( SUFFIX.test( value ) ) {
			value = value.replace( SUFFIX, '' );
		}

		return value
			.replace( /&/g, ' AND ' )
			.replace( /[^A-Z0-9 ]+/g, ' ' )
			.replace( /\s+/g, ' ' )
			.trim();
	}

	function formatChecks( raw ) {
		var checks = [];
		var value  = raw.trim();
		var words  = core( value ).toLowerCase().split( ' ' );

		if ( /[^A-Za-z0-9 &@'’.,\-()\/À-ſ]/.test( value ) ) {
			checks.push( status( 'warn', 'It contains characters ACRA does not accept in a company name. Stick to letters, numbers, spaces and & @ \' . , - ( ).' ) );
		}

		if ( ! SUFFIX.test( value ) ) {
			checks.push( status( 'info', 'A private company name must end with "Pte. Ltd." (or "Private Limited"), so it would be registered as "' + value.replace( /\s+/g, ' ' ) + ' Pte. Ltd."' ) );
		}

		for ( var r = 0; r < RESTRICTED.length; r++ ) {
			for ( var w = 0; w < RESTRICTED[ r ].words.length; w++ ) {
				var word = RESTRICTED[ r ].words[ w ];

				if ( ( ' ' + words.join( ' ' ) + ' ' ).indexOf( ' ' + word.replace( '-', ' ' ) + ' ' ) > -1 ) {
					checks.push( status( 'warn', 'The word "' + word.charAt( 0 ).toUpperCase() + word.slice( 1 ) + '" is usually referred to ' + RESTRICTED[ r ].who + ' for approval, which can add time to registration.' ) );
					break;
				}
			}
		}

		return checks;
	}

	function isLive( record ) {
		return /live|registered|existing/i.test( record.entity_status_description || '' );
	}

	function searchRegister( name ) {
		var key     = core( name );
		var first   = key.charAt( 0 );
		var dataset = /[A-Z]/.test( first ) ? ACRA[ first ] : ACRA.OTHERS;
		var url     = API + '?resource_id=' + dataset + '&limit=100'
			+ '&fields=' + encodeURIComponent( 'uen,entity_name,entity_status_description' )
			+ '&q=' + encodeURIComponent( JSON.stringify( { entity_name: key } ) );

		return fetch( url ).then( function ( response ) {
			if ( ! response.ok ) {
				throw new Error( 'HTTP ' + response.status );
			}

			return response.json();
		} ).then( function ( json ) {
			if ( ! json || ! json.success || ! json.result ) {
				throw new Error( 'Bad response' );
			}

			return json.result.records || [];
		} );
	}

	function runNameCheck( form, input, result, error ) {
		var raw = input.value.trim();

		result.innerHTML = '';

		if ( core( raw ).length < 2 ) {
			toggle( result, false );
			toggle( error, true );
			return;
		}

		toggle( error, false );

		var checks  = formatChecks( raw );
		var pending = status( 'info', 'Checking ACRA\'s register…' );

		result.appendChild( pending );
		toggle( result, true );

		searchRegister( raw ).then( function ( records ) {
			var key       = core( raw );
			var identical = records.filter( function ( r ) { return core( r.entity_name ) === key; } );
			var live      = identical.filter( isLive );
			var similar   = records.filter( function ( r ) {
				var other = core( r.entity_name );
				return other !== key && isLive( r ) && ( other.indexOf( key ) === 0 || key.indexOf( other ) === 0 );
			} ).slice( 0, 5 );

			result.innerHTML = '';

			if ( live.length ) {
				result.appendChild( status( 'bad', '"' + live[0].entity_name + '" is already registered with ACRA (UEN ' + live[0].uen + '), so this name is not available.' ) );
			} else if ( identical.length ) {
				result.appendChild( status( 'warn', 'A company named "' + identical[0].entity_name + '" was registered before and is now ' + ( identical[0].entity_status_description || 'inactive' ).toLowerCase() + '. The name may be reusable; we\'ll confirm it with ACRA.' ) );
			} else {
				result.appendChild( status( 'ok', 'No identical name found in ACRA\'s register.' ) );
			}

			for ( var c = 0; c < checks.length; c++ ) {
				result.appendChild( checks[ c ] );
			}

			if ( similar.length ) {
				result.appendChild( el( 'p', 'ci-tools__result-heading', 'Similar names already registered' ) );

				for ( var s = 0; s < similar.length; s++ ) {
					var row = el( 'div', 'ci-tools__row' );
					row.appendChild( el( 'span', 'ci-tools__row-main', similar[ s ].entity_name ) );
					row.appendChild( el( 'span', 'ci-tools__row-note', similar[ s ].uen ) );
					result.appendChild( row );
				}
			}
		} ).catch( function () {
			result.innerHTML = '';
			result.appendChild( status( 'warn', 'We couldn\'t reach ACRA\'s register just now, so only the format was checked. Try again in a moment, or send us the name and we\'ll check it for you.' ) );

			for ( var c = 0; c < checks.length; c++ ) {
				result.appendChild( checks[ c ] );
			}
		} );
	}

	/* ------------------------------------------------------------------ ssic */

	var ssicIndex = null;
	var STOP      = [ 'and', 'or', 'of', 'the', 'for', 'in', 'a', 'an', 'to', 'my', 'our', 'business', 'company', 'services', 'service', 'activities', 'activity', 'e', 'g' ];

	function normal( text ) {
		return stripAccents( text ).toLowerCase().replace( /[^a-z0-9 ]+/g, ' ' ).replace( /\s+/g, ' ' ).trim();
	}

	function loadSsic( src ) {
		if ( ssicIndex ) {
			return Promise.resolve( ssicIndex );
		}

		return fetch( src ).then( function ( r ) {
			return r.json();
		} ).then( function ( json ) {
			ssicIndex = ( json.codes || [] ).map( function ( row ) {
				var alts = row[2] ? row[2].split( '|' ) : [];

				return {
					code: row[0],
					title: row[1],
					alts: alts,
					titleWords: normal( row[1] ).split( ' ' ),
					altText: alts.map( normal ),
					titleText: normal( row[1] )
				};
			} );

			return ssicIndex;
		} );
	}

	function stem( word ) {
		return word.length > 4 ? word.replace( /(ies|es|s|ing|ers|er)$/, '' ) : word;
	}

	function scoreSsic( entry, phrase, tokens ) {
		var score    = 0;
		var matched  = 0;
		var bestAlt  = '';
		var bestHits = 0;

		if ( entry.titleText.indexOf( phrase ) > -1 ) {
			score += 12;
		}

		for ( var t = 0; t < tokens.length; t++ ) {
			var tok = stem( tokens[ t ] );
			var hit = false;

			for ( var w = 0; w < entry.titleWords.length; w++ ) {
				if ( entry.titleWords[ w ].indexOf( tok ) === 0 ) {
					score += 4;
					hit = true;
					break;
				}
			}

			if ( hit ) {
				matched++;
			}
		}

		for ( var a = 0; a < entry.altText.length; a++ ) {
			var text = entry.altText[ a ];
			var hits = 0;

			if ( text.indexOf( phrase ) > -1 ) {
				hits += tokens.length + 2;
			}

			for ( var k = 0; k < tokens.length; k++ ) {
				if ( ( ' ' + text ).indexOf( ' ' + stem( tokens[ k ] ) ) > -1 ) {
					hits++;
				}
			}

			if ( hits > bestHits ) {
				bestHits = hits;
				bestAlt  = entry.alts[ a ];
			}
		}

		score += bestHits * 2;

		if ( tokens.length && ( matched === tokens.length || bestHits >= tokens.length ) ) {
			score += 6;
		}

		// "n.e.c." catch-alls only win when nothing more specific does.
		if ( /n\.e\.c\./.test( entry.title ) ) {
			score -= 1;
		}

		return { score: score, alt: bestAlt };
	}

	function searchSsic( index, query ) {
		var phrases = query.split( /[,;\/]+/ ).map( normal ).filter( Boolean );
		var seen    = {};
		var out     = [];

		for ( var p = 0; p < phrases.length; p++ ) {
			var tokens = phrases[ p ].split( ' ' ).filter( function ( w ) {
				return w.length > 1 && STOP.indexOf( w ) === -1;
			} );

			if ( ! tokens.length ) {
				continue;
			}

			var scored = [];

			for ( var i = 0; i < index.length; i++ ) {
				var s = scoreSsic( index[ i ], phrases[ p ], tokens );

				if ( s.score >= 6 ) {
					scored.push( { entry: index[ i ], score: s.score, alt: s.alt } );
				}
			}

			scored.sort( function ( x, y ) { return y.score - x.score; } );

			var limit = phrases.length > 1 ? 3 : 6;

			for ( var j = 0; j < scored.length && limit > 0; j++ ) {
				if ( seen[ scored[ j ].entry.code ] ) {
					continue;
				}

				seen[ scored[ j ].entry.code ] = true;
				out.push( scored[ j ] );
				limit--;
			}
		}

		return out;
	}

	function runSsic( form, input, result, error ) {
		var query = input.value.trim();

		result.innerHTML = '';

		if ( normal( query ).length < 2 ) {
			toggle( result, false );
			toggle( error, true );
			return;
		}

		toggle( error, false );
		result.appendChild( status( 'info', 'Searching SSIC codes…' ) );
		toggle( result, true );

		loadSsic( form.getAttribute( 'data-ssic-src' ) ).then( function ( index ) {
			var matches = searchSsic( index, query );

			result.innerHTML = '';

			if ( ! matches.length ) {
				result.appendChild( status( 'warn', 'No SSIC code matched that description. Try simpler words (e.g. "bakery", "IT consultancy", "online retail") or ask us and we\'ll suggest the right codes.' ) );
				return;
			}

			result.appendChild( el( 'p', 'ci-tools__result-heading', 'Suggested SSIC 2025 codes' ) );

			// SSIC 2025 has no single "online retail" code: online sellers are coded by
			// what they sell, so a bare channel word is worth a nudge.
			if ( /\b(online|e ?commerce|internet|web) ?(retail|shop|store|sell|sales)?\b/.test( normal( query ) ) && normal( query ).split( ' ' ).length <= 3 ) {
				result.insertBefore( status( 'info', 'Under SSIC 2025, online shops are classified by what they sell. Add the product for a sharper match, e.g. "online retail clothing".' ), result.firstChild );
			}

			for ( var m = 0; m < matches.length; m++ ) {
				var row  = el( 'div', 'ci-tools__row ci-tools__row--code' );
				var main = el( 'span', 'ci-tools__row-main', matches[ m ].entry.title );

				if ( matches[ m ].alt && normal( matches[ m ].alt ) !== matches[ m ].entry.titleText ) {
					main.appendChild( el( 'span', 'ci-tools__row-note', 'Includes: ' + matches[ m ].alt ) );
				}

				row.appendChild( el( 'span', 'ci-tools__code', matches[ m ].entry.code ) );
				row.appendChild( main );
				result.appendChild( row );
			}
		} ).catch( function () {
			result.innerHTML = '';
			result.appendChild( status( 'warn', 'The SSIC list couldn\'t be loaded just now. Please try again.' ) );
		} );
	}

	/* ------------------------------------------------------------------- tax */

	function parseAmount( value ) {
		var cleaned = String( value ).replace( /[^0-9.]/g, '' );

		return '' === cleaned ? NaN : parseFloat( cleaned );
	}

	function money( value ) {
		return 'S$' + Math.round( value ).toLocaleString( 'en-SG' );
	}

	function runTax( form, input, result, error ) {
		var income  = parseAmount( input.value );
		var rate    = parseFloat( form.getAttribute( 'data-rate' ) ) || 17;
		var schemes = [];

		try {
			schemes = JSON.parse( form.getAttribute( 'data-schemes' ) || '[]' );
		} catch ( e ) {
			schemes = [];
		}

		result.innerHTML = '';

		if ( ! isFinite( income ) || income < 0 ) {
			toggle( result, false );
			toggle( error, true );
			return;
		}

		toggle( error, false );
		result.appendChild( el( 'p', 'ci-tools__result-heading', 'Estimated tax payable on ' + money( income ) ) );

		var list = schemes.length ? schemes : [ { label: 'Without exemptions', bands: [] } ];

		for ( var s = 0; s < list.length; s++ ) {
			var remaining = income;
			var exempt    = 0;
			var bands     = list[ s ].bands || [];

			for ( var b = 0; b < bands.length && remaining > 0; b++ ) {
				var slice = Math.min( remaining, bands[ b ][0] );
				exempt   += slice * ( bands[ b ][1] / 100 );
				remaining -= slice;
			}

			var tax  = ( income - exempt ) * ( rate / 100 );
			var row  = el( 'div', 'ci-tools__row' );
			var main = el( 'span', 'ci-tools__row-main', list[ s ].label );

			main.appendChild( el( 'span', 'ci-tools__row-note', 'Exemption saves ' + money( exempt * ( rate / 100 ) ) + ' · effective rate ' + ( income ? ( tax / income * 100 ).toFixed( 1 ) : '0.0' ) + '%' ) );
			row.appendChild( main );
			row.appendChild( el( 'span', 'ci-tools__row-value', money( tax ) ) );
			result.appendChild( row );
		}

		toggle( result, true );
	}

	/* ----------------------------------------------------------------- wire-up */

	for ( var f = 0; f < forms.length; f++ ) {
		( function ( form ) {
			var tool   = form.getAttribute( 'data-tool' );
			var input  = form.querySelector( 'input' );
			var panel  = form.parentNode;
			var result = panel.querySelector( '[data-tool-result]' );
			var error  = panel.querySelector( '[data-tool-error]' );

			if ( ! input || ! result ) {
				return;
			}

			// No error line in the template means the generic one.
			if ( ! error ) {
				error = el( 'p', 'ci-tools__status ci-tools__status--warn', 'tax' === tool ? 'Enter an amount in S$.' : 'Type at least two letters.' );
				error.setAttribute( 'role', 'alert' );
				error.setAttribute( 'hidden', '' );
				result.parentNode.insertBefore( error, result.nextSibling );
			}

			if ( 'tax' === tool ) {
				// Thousands separators while typing, like the other calculators (QA #21-22).
				input.addEventListener( 'input', function () {
					var digits = input.value.replace( /[^0-9.]/g, '' );
					var parts  = digits.split( '.' );

					if ( '' === parts[0] ) {
						return;
					}

					input.value = parseInt( parts[0], 10 ).toLocaleString( 'en-SG' ) + ( parts.length > 1 ? '.' + parts[1].slice( 0, 2 ) : '' );
				} );
			}

			form.addEventListener( 'submit', function ( event ) {
				event.preventDefault();

				if ( 'name-check' === tool ) {
					runNameCheck( form, input, result, error );
				} else if ( 'ssic' === tool ) {
					runSsic( form, input, result, error );
				} else if ( 'tax' === tool ) {
					runTax( form, input, result, error );
				}
			} );
		} )( forms[ f ] );
	}
} )();
