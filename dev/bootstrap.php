<?php

$GLOBALS['wp_tests_options']['active_plugins'][] = 'advanced-sidebar-menu/advanced-sidebar-menu.php';
$GLOBALS['wp_tests_options']['active_plugins'][] = 'advanced-sidebar-menu-pro/advanced-sidebar-menu-pro.php';

$GLOBALS['wp_tests_options']['permalink_structure'] = '%postname%/';


require 'wp-tests-config.php';

global $wp_version; // wp's test suite doesn't globalize this, but we depend on it for loading core

require 'E:/SVN/wp-unit/includes/bootstrap-no-install.php';
