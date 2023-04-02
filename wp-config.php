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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'w2' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'X+)OC=buwJFSqwST+hS<m_y=KJfGwI0j(WT-w`u0f=.EWEHo4lJY_lC;~b&rdpbq' );
define( 'SECURE_AUTH_KEY',  'W@ Jnge2Ds/W :sb*j}Itu@gbs,jTE+7Vh##zxH06teh8-0>KUH+jEjw=b%*G:kz' );
define( 'LOGGED_IN_KEY',    '?N-Uc cx5!J$r^i0[ZBZU*8[0<V&=]ed2MD6q+q) 7%!^i76mo=VfRi<z>;LGnCO' );
define( 'NONCE_KEY',        '&B%gEN+X:URC{yl*kZm7M5Zn(9#VPXf{H7lg~*46/H)J@eQg;g=:k@B6z@H[3ZeR' );
define( 'AUTH_SALT',        ']OoXT30rxvqZ|xXl$0xI/5?)<J>TWNnNMmMy&Oi9v~|syB`V1$?+{F)TN?Kv3d4)' );
define( 'SECURE_AUTH_SALT', ' 5*g&0vY(,{:L3g@#ifjd)Yrwv4)vhwE3IN} baX&i,Pli;4X=}=cQ!@Wp^T?<M2' );
define( 'LOGGED_IN_SALT',   'ms0_.;w@[wpX6{MATt8%D,(;sWN@Y$E5_~SZQmx9y)_n&]4@Ty~d:=~{49:ibMz,' );
define( 'NONCE_SALT',       '[OL[t:WJ~aP@TZW/M%RV6|+ENnJpF<+EyBzf52n8wfQ#F2=$r#$ahT_h;*V-stDS' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
