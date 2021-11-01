<?php
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_tshirtb2b' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'new-password' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '_nYm<OLHn_8rrIkU[Pn4>Cfmuxg~!gn7S;Sf#Hg)oruh!.ctsGfq;;]uw&QA{G;o' );
define( 'SECURE_AUTH_KEY',  'I:n+Lc.[OqA=q;KQ2|yz0tY3?kC#+KPkztzc4qpLgta]ZT;p0WgqLAhz)^@l5{b[' );
define( 'LOGGED_IN_KEY',    ')$@htb,vb^+^T4,R g] kjQOq}l>K@e[6]-Pq?Y@ /$^Z&m.d!RkFPjCcf<GsG1J' );
define( 'NONCE_KEY',        'M&XH2i)A2!=I8h)]tT}4#0k*yfAm%4vD*&4I &)*pHS]hCnE)Xi{^HO6HeY#/Lxf' );
define( 'AUTH_SALT',        '@i(o3x@SJO~KI1bTUr9u;:xIX%t;R-n1*C*V+*[b~8cYJ#|TABmAc+tUrFUq%mLN' );
define( 'SECURE_AUTH_SALT', '.v$0l+0^]od)3[>?X83^fE#x3*~@^IT*4eVm[uB?~ ;7h FC:Zfz{yZ>U+qKk h4' );
define( 'LOGGED_IN_SALT',   '`=qEEWR+,M*`-`RgbCA&^&O(fN8XM?tqHliw]xm$p6`*D92auqUnuEZjXC;(]L?f' );
define( 'NONCE_SALT',       'D@{]82t1E:]BJA!B$S4hlKQ@B?&6)D JWzncV>el(SS<Ha+6Y(LZFBe*YpxTa|e:' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
