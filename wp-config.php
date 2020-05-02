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
define( 'DB_NAME', 'techbrand_support' );

/** MySQL database username */
define( 'DB_USER', 'techbrand' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Techbrand@2020' );

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
define( 'AUTH_KEY',         'T(C~&)=0vrp$l2*KE>1W x 3(6G{lu)932,y,<WjF.jgXJXZ0<<.d3&^hl/d a3|' );
define( 'SECURE_AUTH_KEY',  'Zcr@TIO%*]unzO_xHw@CB+vi)Pix+LLa>ENU6QE <sHPe5nDC!>z:n}A}ji;6R7W' );
define( 'LOGGED_IN_KEY',    '4J:UVY45Ew-AK[`UptOuNF*Lz!<tF_Ao`>Wuw+b:p%V>l9HY*!H[f]P8ab5s0p+E' );
define( 'NONCE_KEY',        '|GKK=~uA)}eLEZOGQ`E&iTd*YC]-k-cEHf<8suZ(>L}a377v/HU)J5kv9Hh@C*//' );
define( 'AUTH_SALT',        '9Mp5zs(1PHoh*w_75U<CX#`k4lM.YyTp%6[+i!u{14P!Gjp/1KgHzb.mtT)o${=$' );
define( 'SECURE_AUTH_SALT', '7[]4Q ~Q?J`;p&?):VMO@6vj93p-b$`{G:m=[aq=J4# 7/uMKbdN%YbBI{wRhGt7' );
define( 'LOGGED_IN_SALT',   'MB&XgKd[5Dj3NPZM_aGP/}Pe!/#FbFb}72JSPhcW?4XrQ,V8TheytCNgB<z+v3iE' );
define( 'NONCE_SALT',       '[g6fEdfBp^Bw1:Y*nLBCUB!:kHCZK=>3|0hjHP$^[5Om^5%Ar8tnSup !*$C1o-0' );

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
