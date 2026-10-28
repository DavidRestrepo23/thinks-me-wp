<?php
/**
 * Plugin Name: ThinkSME Post Importer
 * Description: Importa los posts publicados de thinksme.sg (vía REST API) a este WordPress: contenido, fecha, slug, categorías, tags, imagen destacada, imágenes internas y SEO de Yoast. Herramientas → ThinkSME Importer.
 * Version: 1.0.0
 * Author: David Restrepo
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TSME_Importer {

	const META_SRC = '_tsme_source_id';   // ID del post en el sitio origen.
	const META_URL = '_tsme_source_url';  // URL original de cada imagen importada.
	const PER_PAGE = 5;                   // Posts por lote (evita timeouts al bajar imágenes).

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'wp_ajax_tsme_import_batch', array( __CLASS__, 'ajax_batch' ) );
		add_action( 'wp_ajax_tsme_rollback', array( __CLASS__, 'ajax_rollback' ) );
	}

	public static function menu() {
		add_management_page( 'ThinkSME Importer', 'ThinkSME Importer', 'manage_options', 'tsme-importer', array( __CLASS__, 'page' ) );
	}

	/* ------------------------------------------------------------------ UI */

	public static function page() {
		$nonce = wp_create_nonce( 'tsme' );
		?>
		<div class="wrap">
			<h1>ThinkSME Post Importer</h1>
			<p>Lee los posts publicados del sitio origen por la REST API y los crea aquí. Los posts ya importados (o con un slug que ya existe en este sitio) se omiten, así que puedes correrlo varias veces sin duplicar.</p>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="tsme-source">Sitio origen</label></th>
					<td><input type="url" id="tsme-source" class="regular-text" value="https://thinksme.sg"></td>
				</tr>
				<tr>
					<th scope="row">Opciones</th>
					<td>
						<label><input type="checkbox" id="tsme-images" checked> Descargar imágenes (destacada + internas) a la biblioteca de medios</label><br>
						<label><input type="checkbox" id="tsme-strip-h1" checked> Quitar el H1 duplicado al inicio del contenido (el tema ya imprime el título)</label><br>
						<label><input type="checkbox" id="tsme-clean" checked> Limpiar markup heredado de Wix (clases, ids, data-*, spans/divs vacíos)</label><br>
						<label><input type="checkbox" id="tsme-links" checked> Reescribir enlaces internos a este sitio (y corregir los viejos <code>/post/slug</code> de Wix)</label>
					</td>
				</tr>
			</table>

			<p>
				<button class="button button-primary" id="tsme-run">Importar posts</button>
				<button class="button" id="tsme-rollback" style="margin-left:8px">Deshacer importación</button>
			</p>

			<pre id="tsme-log" style="background:#fff;border:1px solid #c3c4c7;padding:12px;max-height:480px;overflow:auto;white-space:pre-wrap"></pre>
		</div>
		<script>
		(function () {
			const nonce = <?php echo wp_json_encode( $nonce ); ?>;
			const out = document.getElementById('tsme-log');
			const log = (t) => { out.textContent += t + "\n"; out.scrollTop = out.scrollHeight; };
			const val = (id) => document.getElementById(id).checked ? '1' : '0';
			const buttons = [document.getElementById('tsme-run'), document.getElementById('tsme-rollback')];
			const lock = (on) => buttons.forEach(b => b.disabled = on);

			async function post(data) {
				const fd = new FormData();
				Object.entries(data).forEach(([k, v]) => fd.append(k, v));
				fd.append('_ajax_nonce', nonce);
				const r = await fetch(ajaxurl, { method: 'POST', body: fd, credentials: 'same-origin' });
				const text = await r.text();
				try { return JSON.parse(text); } catch (e) { return { success: false, data: 'Respuesta inválida del servidor (HTTP ' + r.status + '): ' + text.slice(0, 300) }; }
			}

			document.getElementById('tsme-run').addEventListener('click', async () => {
				lock(true); out.textContent = '';
				let page = 1, totalPages = 1, done = 0;
				while (page <= totalPages) {
					log('— Lote ' + page + (totalPages > 1 ? ' de ' + totalPages : '') + ' …');
					const j = await post({
						action: 'tsme_import_batch', page,
						source: document.getElementById('tsme-source').value,
						images: val('tsme-images'), strip_h1: val('tsme-strip-h1'),
						clean: val('tsme-clean'), links: val('tsme-links')
					});
					if (!j.success) { log('✗ Error: ' + j.data); lock(false); return; }
					if (page === 1) log('Posts publicados en el origen: ' + j.data.total);
					totalPages = j.data.total_pages;
					j.data.log.forEach(log);
					done += j.data.log.length;
					page++;
				}
				log('\nListo. ' + done + ' posts procesados.');
				lock(false);
			});

			document.getElementById('tsme-rollback').addEventListener('click', async () => {
				if (!confirm('Esto borra definitivamente todos los posts e imágenes creados por este importador. ¿Continuar?')) return;
				lock(true);
				const j = await post({ action: 'tsme_rollback' });
				log(j.success ? j.data : '✗ Error: ' + j.data);
				lock(false);
			});
		})();
		</script>
		<?php
	}

	/* ---------------------------------------------------------------- AJAX */

	public static function ajax_batch() {
		check_ajax_referer( 'tsme' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Sin permisos.' );
		}
		@set_time_limit( 300 );

		$source = untrailingslashit( esc_url_raw( wp_unslash( $_POST['source'] ?? '' ) ) );
		if ( ! $source ) {
			wp_send_json_error( 'URL de origen inválida.' );
		}
		$page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
		$opts = array(
			'images'   => '1' === ( $_POST['images'] ?? '' ),
			'strip_h1' => '1' === ( $_POST['strip_h1'] ?? '' ),
			'clean'    => '1' === ( $_POST['clean'] ?? '' ),
			'links'    => '1' === ( $_POST['links'] ?? '' ),
			'source'   => $source,
		);

		$url = $source . '/wp-json/wp/v2/posts?' . http_build_query(
			array(
				'per_page' => self::PER_PAGE,
				'page'     => $page,
				'orderby'  => 'date',
				'order'    => 'asc',
				'status'   => 'publish',
			)
		) . '&_embed=wp:featuredmedia,wp:term';

		$res = wp_remote_get( $url, array( 'timeout' => 60 ) );
		if ( is_wp_error( $res ) ) {
			wp_send_json_error( 'No se pudo conectar con el origen: ' . $res->get_error_message() );
		}
		$code = wp_remote_retrieve_response_code( $res );
		if ( 200 !== $code ) {
			wp_send_json_error( "El origen respondió HTTP $code en $url" );
		}

		$posts = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( ! is_array( $posts ) ) {
			wp_send_json_error( 'El origen no devolvió JSON válido (¿API bloqueada por un plugin de seguridad o Cloudflare?).' );
		}

		$log = array();
		foreach ( $posts as $p ) {
			try {
				$log[] = self::import_post( $p, $opts );
			} catch ( Throwable $e ) {
				$log[] = '✗ ' . ( $p['slug'] ?? '?' ) . ': ' . $e->getMessage();
			}
		}

		wp_send_json_success(
			array(
				'total'       => (int) wp_remote_retrieve_header( $res, 'x-wp-total' ),
				'total_pages' => max( 1, (int) wp_remote_retrieve_header( $res, 'x-wp-totalpages' ) ),
				'log'         => $log,
			)
		);
	}

	public static function ajax_rollback() {
		check_ajax_referer( 'tsme' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Sin permisos.' );
		}
		@set_time_limit( 300 );

		$posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'meta_key' => self::META_SRC, 'fields' => 'ids', 'numberposts' => -1 ) );
		foreach ( $posts as $id ) {
			wp_delete_post( $id, true );
		}
		$media = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'any', 'meta_key' => self::META_URL, 'fields' => 'ids', 'numberposts' => -1 ) );
		foreach ( $media as $id ) {
			wp_delete_attachment( $id, true );
		}
		wp_send_json_success( sprintf( 'Deshecho: %d posts y %d imágenes eliminados.', count( $posts ), count( $media ) ) );
	}

	/* ------------------------------------------------------------- Import */

	private static function import_post( array $p, array $opts ) {
		$src_id = (int) $p['id'];
		$title  = html_entity_decode( $p['title']['rendered'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$slug   = $p['slug'];

		$already = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'meta_key' => self::META_SRC, 'meta_value' => $src_id, 'fields' => 'ids', 'numberposts' => 1 ) );
		if ( $already ) {
			return "• Omitido (ya importado): $title";
		}
		if ( get_page_by_path( $slug, OBJECT, 'post' ) ) {
			return "• Omitido (ya existe un post con el slug \"$slug\"): $title";
		}

		$post_id = wp_insert_post(
			wp_slash(
				array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'post_title'     => $title,
					'post_name'      => $slug,
					'post_content'   => $p['content']['rendered'] ?? '',
					'post_date'      => str_replace( 'T', ' ', $p['date'] ),
					'post_date_gmt'  => str_replace( 'T', ' ', $p['date_gmt'] ),
					'comment_status' => $p['comment_status'] ?? 'closed',
					'ping_status'    => $p['ping_status'] ?? 'closed',
					'post_author'    => get_current_user_id(),
				)
			),
			true
		);
		if ( is_wp_error( $post_id ) ) {
			return "✗ $title: " . $post_id->get_error_message();
		}
		update_post_meta( $post_id, self::META_SRC, $src_id );
		update_post_meta( $post_id, '_tsme_source_link', esc_url_raw( $p['link'] ?? '' ) );

		$stats = array( 'img_ok' => 0, 'img_fail' => 0 );

		// Contenido: limpieza, imágenes y enlaces.
		$content = self::process_html( $p['content']['rendered'] ?? '', $title, $post_id, $opts, $stats );
		wp_update_post( wp_slash( array( 'ID' => $post_id, 'post_content' => $content ) ) );

		// Formato y sticky.
		if ( ! empty( $p['format'] ) && 'standard' !== $p['format'] ) {
			set_post_format( $post_id, $p['format'] );
		}
		if ( ! empty( $p['sticky'] ) ) {
			stick_post( $post_id );
		}

		// Categorías y tags.
		$terms = array( 'category' => array(), 'post_tag' => array() );
		foreach ( $p['_embedded']['wp:term'] ?? array() as $group ) {
			foreach ( (array) $group as $t ) {
				$tax = $t['taxonomy'] ?? '';
				if ( ! isset( $terms[ $tax ] ) ) {
					continue;
				}
				$existing = get_term_by( 'slug', $t['slug'], $tax );
				if ( $existing ) {
					$terms[ $tax ][] = (int) $existing->term_id;
				} else {
					$new = wp_insert_term( html_entity_decode( $t['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ), $tax, array( 'slug' => $t['slug'] ) );
					if ( ! is_wp_error( $new ) ) {
						$terms[ $tax ][] = (int) $new['term_id'];
					}
				}
			}
		}
		if ( $terms['category'] ) {
			wp_set_post_terms( $post_id, $terms['category'], 'category' );
		}
		if ( $terms['post_tag'] ) {
			wp_set_post_terms( $post_id, $terms['post_tag'], 'post_tag' );
		}

		// Imagen destacada.
		$featured = '';
		$fm       = $p['_embedded']['wp:featuredmedia'][0] ?? null;
		if ( $opts['images'] && is_array( $fm ) && ! empty( $fm['source_url'] ) ) {
			$att = self::sideload( $fm['source_url'], $post_id, $fm['alt_text'] ?? '' );
			if ( is_wp_error( $att ) ) {
				$featured = ' · destacada ✗ (' . $att->get_error_message() . ')';
			} else {
				set_post_thumbnail( $post_id, $att );
				$featured = ' · destacada ✓';
			}
		}

		// SEO de Yoast (se guarda aunque Yoast no esté activo todavía).
		$y = $p['yoast_head_json'] ?? array();
		if ( ! empty( $y['title'] ) ) {
			update_post_meta( $post_id, '_yoast_wpseo_title', wp_slash( html_entity_decode( $y['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		}
		if ( ! empty( $y['description'] ) ) {
			update_post_meta( $post_id, '_yoast_wpseo_metadesc', wp_slash( html_entity_decode( $y['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		}

		$img = $opts['images'] ? sprintf( ' · imágenes internas %d ✓%s', $stats['img_ok'], $stats['img_fail'] ? ", {$stats['img_fail']} ✗" : '' ) : '';
		return sprintf( '✓ %s (%s)%s%s · %d cat, %d tags', $title, substr( $p['date'], 0, 10 ), $featured, $img, count( $terms['category'] ), count( $terms['post_tag'] ) );
	}

	/* ------------------------------------------------------------ HTML ops */

	private static function process_html( $html, $title, $post_id, array $opts, array &$stats ) {
		if ( '' === trim( $html ) ) {
			return $html;
		}
		libxml_use_internal_errors( true );
		$doc = new DOMDocument( '1.0', 'UTF-8' );
		$doc->loadHTML( '<?xml encoding="UTF-8"?><div id="tsme-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		$xp   = new DOMXPath( $doc );
		$root = $xp->query( '//div[@id="tsme-root"]' )->item( 0 );
		if ( ! $root ) {
			return $html;
		}

		// 1. H1 duplicado (mismo texto que el título).
		if ( $opts['strip_h1'] ) {
			$h1 = $xp->query( './/h1', $root )->item( 0 );
			if ( $h1 && self::norm( $h1->textContent ) === self::norm( $title ) ) {
				$h1->parentNode->removeChild( $h1 );
			}
		}

		// 2. Limpieza de markup Wix.
		if ( $opts['clean'] ) {
			foreach ( iterator_to_array( $xp->query( './/*', $root ) ) as $el ) {
				$names = array();
				foreach ( $el->attributes as $a ) {
					$names[] = $a->nodeName;
				}
				foreach ( $names as $n ) {
					$drop = in_array( $n, array( 'class', 'id', 'dir', 'tabindex', 'role', 'aria-level' ), true ) || 0 === strpos( $n, 'data-' );
					if ( 'style' === $n && preg_match( '/^\s*font-weight:\s*400;?\s*$/i', $el->getAttribute( 'style' ) ) ) {
						$drop = true;
					}
					// Las clases de alineación de WordPress sí sirven.
					if ( 'class' === $n && 'img' === $el->nodeName ) {
						$keep = preg_grep( '/^align(none|left|right|center)$/', preg_split( '/\s+/', $el->getAttribute( 'class' ) ) );
						if ( $keep ) {
							$el->setAttribute( 'class', implode( ' ', $keep ) );
							$drop = false;
						}
					}
					if ( $drop ) {
						$el->removeAttribute( $n );
					}
				}
			}
			// Desenvolver span/div sin atributos (wrappers vacíos de Wix).
			foreach ( iterator_to_array( $xp->query( './/span[not(@*)] | .//div[not(@*)]', $root ) ) as $el ) {
				while ( $el->firstChild ) {
					$el->parentNode->insertBefore( $el->firstChild, $el );
				}
				$el->parentNode->removeChild( $el );
			}
		}

		// 3. Imágenes internas → biblioteca local.
		if ( $opts['images'] ) {
			foreach ( iterator_to_array( $xp->query( './/img[@src]', $root ) ) as $img ) {
				$src = self::absolute( $img->getAttribute( 'src' ), $opts['source'] );
				if ( ! $src || 0 === strpos( $src, 'data:' ) ) {
					continue;
				}
				$att = self::sideload( $src, $post_id, $img->getAttribute( 'alt' ) );
				if ( is_wp_error( $att ) ) {
					$stats['img_fail']++;
					continue;
				}
				$stats['img_ok']++;
				$img->setAttribute( 'src', wp_get_attachment_url( $att ) );
				$img->removeAttribute( 'srcset' );
				$img->removeAttribute( 'sizes' );
				$img->removeAttribute( 'data-pin-url' );
				$img->removeAttribute( 'data-pin-media' );
				$img->setAttribute( 'class', trim( $img->getAttribute( 'class' ) . ' wp-image-' . $att ) );
			}
		}

		// 4. Enlaces internos → este sitio.
		if ( $opts['links'] ) {
			$src_host = self::bare_host( wp_parse_url( $opts['source'], PHP_URL_HOST ) );
			foreach ( $xp->query( './/a[@href]', $root ) as $a ) {
				$href = $a->getAttribute( 'href' );
				$u    = wp_parse_url( $href );
				if ( empty( $u['host'] ) || self::bare_host( $u['host'] ) !== $src_host ) {
					continue;
				}
				$path = $u['path'] ?? '/';
				if ( 0 === strpos( $path, '/wp-content/' ) ) {
					continue; // Archivos del origen: se dejan apuntando al original.
				}
				$path = preg_replace( '#^/post/#', '/', $path ); // Slugs viejos de Wix.
				if ( '/' !== $path && false === strpos( basename( $path ), '.' ) ) {
					$path = trailingslashit( $path );
				}
				$new = home_url( $path );
				if ( ! empty( $u['query'] ) ) {
					$new .= '?' . $u['query'];
				}
				if ( ! empty( $u['fragment'] ) ) {
					$new .= '#' . $u['fragment'];
				}
				$a->setAttribute( 'href', $new );
			}
		}

		$out = '';
		foreach ( $root->childNodes as $child ) {
			$out .= $doc->saveHTML( $child );
		}
		return trim( $out );
	}

	/* -------------------------------------------------------------- Media */

	private static function sideload( $url, $post_id, $alt = '' ) {
		// Wix sirve versiones recortadas (/v1/fill/...); pedimos el original.
		if ( preg_match( '#^(https?://static\.wixstatic\.com/media/[^/?]+)#', $url, $m ) ) {
			$url = $m[1];
		}

		$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'any', 'meta_key' => self::META_URL, 'meta_value' => $url, 'fields' => 'ids', 'numberposts' => 1 ) );
		if ( $existing ) {
			return (int) $existing[0];
		}

		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$tmp = download_url( $url, 60 );
		if ( is_wp_error( $tmp ) ) {
			return $tmp;
		}

		$name = str_replace( '~', '-', wp_basename( (string) wp_parse_url( $url, PHP_URL_PATH ) ) );
		if ( ! preg_match( '/\.(jpe?g|png|gif|webp|avif)$/i', $name ) ) {
			$name .= '.jpg';
		}

		$id = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), $post_id );
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp );
			return $id;
		}
		update_post_meta( $id, self::META_URL, $url );
		if ( $alt ) {
			update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
		}
		return (int) $id;
	}

	/* ------------------------------------------------------------ Helpers */

	private static function absolute( $src, $base ) {
		$src = trim( $src );
		if ( '' === $src ) {
			return '';
		}
		if ( 0 === strpos( $src, '//' ) ) {
			return 'https:' . $src;
		}
		if ( 0 === strpos( $src, '/' ) ) {
			return $base . $src;
		}
		return $src;
	}

	private static function bare_host( $host ) {
		return preg_replace( '/^www\./i', '', strtolower( (string) $host ) );
	}

	private static function norm( $s ) {
		return strtolower( trim( preg_replace( '/\s+/u', ' ', html_entity_decode( $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) ) );
	}
}

TSME_Importer::init();
