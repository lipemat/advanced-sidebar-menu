<?php

// Test with multisite enabled.
// Alternatively, use the tests/phpunit/multisite.xml configuration file.
define( 'WP_TESTS_MULTISITE', true );

// Force known bugs to be run.
// Tests with an associated Trac ticket that is still open are normally skipped.
// define( 'WP_TESTS_FORCE_KNOWN_BUGS', true );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// ** MySQL settings ** //

// This configuration file will be used by the copy of WordPress being tested.
// wordpress/wp-config.php will be ignored.

// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.

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
define( 'WP_TESTS_TITLE', 'Advanced Sidebar Menu Testing' );

define('DOMAIN_CURRENT_SITE', 'wordpress.loc');
define('PATH_CURRENT_SITE', '/asm/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 3);

define( 'WP_PHP_BINARY', 'php' );

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 0);