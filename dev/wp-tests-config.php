<?php


define( 'ABSPATH', "E:/SVN/wordpress/wp/" );
define( 'WP_TESTS_DIR', 'E:/SVN/wordpress-tests/' );
define( 'WP_CONTENT_DIR', 'E:/SVN/wordpress/content/' );
define(	'WP_CONTENT_URL' ,'http://wordpress.loc/content' );


// Test with multisite enabled.
// Alternatively, use the tests/phpunit/multisite.xml configuration file.
define( 'WP_TESTS_MULTISITE', true );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

$_SERVER[ 'REMOTE_ADDR' ] = "127.0.0.1";

define( 'DB_NAME', 'wordpress' );
define( 'DB_USER', 'mat' );
define( 'DB_PASSWORD', 'mypass' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

$table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'wordpress.loc' );
define( 'WP_TESTS_EMAIL', 'mat@matlipe.com' );
define( 'WP_TESTS_TITLE', 'asm testing' );

define('DOMAIN_CURRENT_SITE', 'wordpress.loc/');
define('PATH_CURRENT_SITE', '/asm/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 2);

define( 'WP_PHP_BINARY', 'php' );

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);