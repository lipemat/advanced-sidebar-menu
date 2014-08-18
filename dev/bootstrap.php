<?php

/**
$GLOBALS['wp_tests_options']['active_plugins'][] = 'the-events-calendar/the-events-calendar.php';
$GLOBALS['wp_tests_options']['active_plugins'][] = 'events-importer-ical/events-importer-ical.php';

$pro_path = getenv( 'TRIBE_PRO_DIRECTORY' );
if ( !empty($pro_path) ) {
	$GLOBALS['wp_tests_options']['active_plugins'][] = $pro_path;
}
$community_path = getenv( 'TRIBE_COMMUNITY_DIRECTORY' );
if ( !empty($community_path) ) {
	$GLOBALS['wp_tests_options']['active_plugins'][] = $community_path;
}
$GLOBALS['wp_tests_options']['permalink_structure'] = '%postname%/';
 *
 * **/

require( 'wp-tests-config.php' );

define( 'ABSPATH', "E:/Copy/htdocs/wordpress/" );
define( 'WP_TESTS_DIR', 'E:/SVN/wordpress-tests/' );

global $wp_version; // wp's test suite doesn't globalize this, but we depend on it for loading core


require 'E:/SVN/wordpress-tests/includes/bootstrap-no-install.php';
