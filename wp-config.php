<?php

/** Enable W3 Total Cache */


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "lar_nftassets" );

/** MySQL database username */
define( 'DB_USER', "root" );

/** MySQL database password */
define( 'DB_PASSWORD', "" );

/** MySQL hostname */
define( 'DB_HOST', "localhost" );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'zrjf7u5rtd8pul1bpwfmro5r8ov2tastiksrrouemulrvq338kbtfa1070eoa7dm' );
define( 'SECURE_AUTH_KEY',  'daf1rhoghz6ftivkun6kc3xnbqlmrkjxt6tvyzzxhrcrrauiapza3xmsjrgjqqrr' );
define( 'LOGGED_IN_KEY',    'g0pa1yhh2pozdmonevdicmxzzhzxqnfyh0uoz7uwrv9h3btgnxfz83lngwno7gqy' );
define( 'NONCE_KEY',        'jsjjxqkcqbf7n19celvyqgnfyfd6xuxzfzamh9rtbv0bvpiuew7lfv30znybpns4' );
define( 'AUTH_SALT',        'itxuspyx85zyrs2fs7jjffws7itolkuggcpb8e6ywql7fqwv8z1mri6fppg6gpds' );
define( 'SECURE_AUTH_SALT', 'srz9uqdf2xqopt6bdkpampeaiyhksorrso51folyycnjq0pckcs8jj1gzkkxuhxc' );
define( 'LOGGED_IN_SALT',   'nole9wpzsatf68wgidq78ay5c3nddkyxrqem5eyujmsqjem18kxlr14xlbmpjs1m' );
define( 'NONCE_SALT',       'utghejq9gtp8meekembuxizk6yljatii562buynmntdm2gaac7iiey6dr1clw2vh' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

// log php errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true);
define( 'WP_DEBUG_DISPLAY', false );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
