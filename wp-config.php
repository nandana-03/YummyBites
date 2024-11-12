<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'YummyBites' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         '7EzIlrsyMXRnnU7jmWbXPjQnuravd787H6s23u4a7aIT9VIiMiRIAnGAEKfk3ssT' );
define( 'SECURE_AUTH_KEY',  'QB6LKyJsTrtopMSA08dKG1vGUB5EioOVMfczSYLVf7wnZowCsvFZ8Nk81YqIFf5b' );
define( 'LOGGED_IN_KEY',    'rWPJU9K4sDGueyYI7hbvuh58Qs3qB0wk2wr4oPHWtGoi7vZasFZ6g1zcYRkrOy7r' );
define( 'NONCE_KEY',        'S9Rk6tyAKyVCdeTIOpoqzcHfXC8iETT7cmFXVZFlFtFm8N59Lz4ORpmwIB9dxfdy' );
define( 'AUTH_SALT',        'Hi1M4ztEFlTTryBcVpeMpBnF4ry905gAk3k4dpXdUybg0hFwkFUfnap7owntvCYd' );
define( 'SECURE_AUTH_SALT', 'qv3hzrRO0bfr1c7M7d8Rul9UZcDKM916rMU8Y6Pzd1NKgVFo6ozrmrrXcZAEnXV6' );
define( 'LOGGED_IN_SALT',   's2ut6NE6azwWg4VCN6xD3PMIBkw0XdpQFowlG9D0x2DWxeqscCIprkllsLZJ7wKf' );
define( 'NONCE_SALT',       'UjnAMxq6SuUcxKNylf0duF5Dofy62Wu2hjMhwpXiTGezetA6wTixoWclUbs5LuAz' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
