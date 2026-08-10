<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'nq%NKT4LaEYw+_Z#vYpXhkVTI7-Sx-0oiQQ7xDy>zLQI,?V?j,i6V&S>}[,>=F*s' );
define( 'SECURE_AUTH_KEY',   'dj,Q$ULuRM B4iWQrj?S(?x]:hJMhGf8b}1)7xMr2}?qn44k?2gfGDB$slnIKD*$' );
define( 'LOGGED_IN_KEY',     '*s~ozu):Yn#iNH?5G.t:jDaA8i/l!gX*@]I)v#=Yk^e~N7D!>2<9>!Ln`Nf]8QV0' );
define( 'NONCE_KEY',         '.^v|8uh(9_Ei,rdC>,@*ss_=}#6fXQ}:(lel?8|I }T>K^HtTY`?,L`4JX{A<_ g' );
define( 'AUTH_SALT',         '<GMl2?xjtJl~gW47/MK$A~/F!0Le^W6hKmj^:T/y[{/~0@$@vk}/RD_WKtAR(5fr' );
define( 'SECURE_AUTH_SALT',  'pG+7%Y$c`ufY};^?X@DdYO*$y+&Wv+9GSv5:FWvvXy#_= qYCgNw*KvF2suQmXa!' );
define( 'LOGGED_IN_SALT',    '>,~O4cB(AwmO`pu&,/K] W*jbKn(,HJ=_%E,h6$EO#tZM^Y<.awJh;g*7B=LEB57' );
define( 'NONCE_SALT',        'VCpxE~#LTAS q/8V^f.tUVpf4X%&.|Kh?QIf^np6aiTkf:j.rGM%ifG3D.FSI5`z' );
define( 'WP_CACHE_KEY_SALT', 'KU94:]/HzF[]j|FQU`Vl}vr#@/c.Dg~:MY>oXgO-Jz$gL_>4#D`I(5ag7 ?~7`g5' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
