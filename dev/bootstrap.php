<?php

$GLOBALS['wp_tests_options']['active_plugins'][] = 'advanced-sidebar-menu/advanced-sidebar-menu.php';
$GLOBALS['wp_tests_options']['active_plugins'][] = 'advanced-sidebar-menu-pro/advanced-sidebar-menu-pro.php';

$GLOBALS['wp_tests_options']['permalink_structure'] = '%postname%/';


/**
 * Call protected/private method of a class.
 *
 * @param object &$object    Instantiated object that we will run method on.
 * @param string $methodName Method name to call
 * @param array  $parameters Array of parameters to pass into method.
 *
 * @return mixed Method return.
 */
function call_private_method(&$object, $methodName, array $parameters = [])
{
	$reflection = new \ReflectionClass(get_class($object));
	$method = $reflection->getMethod($methodName);
	$method->setAccessible(true);

	return $method->invokeArgs($object, $parameters);
}



require( 'wp-tests-config.php' );

global $wp_version; // wp's test suite doesn't globalize this, but we depend on it for loading core

require 'E:/SVN/wordpress-tests/includes/bootstrap-no-install.php';
