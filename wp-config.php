<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

if ( strstr($_SERVER['SERVER_NAME'], 'localhost') )
  define('ENVIRONMENT', 'development');
elseif ( strpos($_SERVER['SERVER_NAME'],'testing')===0
          || strpos($_SERVER['SERVER_NAME'],'staging')===0
          || strpos($_SERVER['SERVER_NAME'],'tia')===0 )
  define('ENVIRONMENT', 'testing');
else
  define('ENVIRONMENT', 'production');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

if ( ENVIRONMENT == 'development' ) {
  define('DB_NAME', 'tia');
  define('DB_USER', 'root');
  define('DB_PASSWORD', '');
  define('DB_HOST', 'localhost');

} elseif ( ENVIRONMENT == 'testing') {
  define('DB_NAME', '361053_tia');
  define('DB_USER', '361053_tia');
  define('DB_PASSWORD', 'Th1s1sAwes0me!');
  define('DB_HOST', 'mysql51-001.wc1.ord1.stabletransit.com');

} else {
  define('DB_NAME', '');
  define('DB_USER', '');
  define('DB_PASSWORD', '');
  define('DB_HOST', '');
  
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'klZFe,BU[W42},<08o.|uOTSEszi)O@ZIi9,PO1^.?/^.,gS-,K)CoE>?K<YE$ly');
define('SECURE_AUTH_KEY',  '?h;fy2q!K?w(%?>%[GN+KJsacawU_K@2|GhYWOw)polwruPTANNDy)0Nx:u}Zgtq');
define('LOGGED_IN_KEY',    'd;$pXwqZggS1itXVrP4w;[F7?Ahy!UQ967kcdpYO-JjGp!%eC8S2LI-H8*My<C6e');
define('NONCE_KEY',        'jN&B8?2u:ci{jSJ_IldlT{>1DL$195f`1wGbBzk{^H=^vo@BG>c|q<HEOK-p}j&V');
define('AUTH_SALT',        'st6?V+^Abgv8ruo|-fT4wA9aL9-><d~P-[}hH!-}W6d6@M3[JI*1D~_&ii nFR1p');
define('SECURE_AUTH_SALT', 'z7-@V(]vu]V@&}m*x#=4d+0; +hm;1^mxEU])JoELxP!]$E4,wne.]:Nt`#,Ju#S');
define('LOGGED_IN_SALT',   '9UODt@6SlE^{8?Xb-15S~o%KtcSVKhaqD-xJZ9(=Cw!B,,U//4=D|_TtszbHpKEL');
define('NONCE_SALT',       '|I|1_P+Xc9EjrZkRI+y:G:$-):%|&=eaik/:!o+Gxnmt$s}h_@0bagY+0/D-=aR}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
