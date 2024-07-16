<?php declare( strict_types=1 );

/**
 * Specific ignores for PHP 8 vs PHP 7.
 *
 * Some built-in PHP functions return different types between PHP 7 and PHP 8.
 *
 * Manually added.
 */
$ignoreErrors = [];

if ( PHP_VERSION_ID < 80000 ) {
	// \_WP_Dependency returns `string` in old versions of WP, `string|bool` in newer versions.
	$ignoreErrors[] = [
		// identifier: generics.notGeneric
		'message' => '#^PHPDoc tag @param for parameter \\$widget contains generic type WP_Widget\\<array\\<string, int\\|string\\>\\> but class WP_Widget is not generic\\.$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Core.php',
	];
	$ignoreErrors[] = [
		// identifier: generics.notGeneric
		'message' => '#^PHPDoc tag @param for parameter \\$widget contains generic type WP_Widget\\<array\\<string, int\\|string\\>\\> but class WP_Widget is not generic\\.$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Blocks/Block_Abstract.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#1 \\$args \\(array\\{name\\?\\: string, id\\?\\: string, id_increment\\?\\: string, description\\?\\: string, class\\?\\: string, before_widget\\: string, after_widget\\: string, before_title\\: string, \\.\\.\\.\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Category\\:\\:widget\\(\\) should be contravariant with parameter \\$args \\(array\\) of method WP_Widget\\:\\:widget\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Category.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#1 \\$instance \\(array\\{display\\-posts\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', exclude\\: string, include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\: int\\|numeric\\-string, new_widget\\: \'list\'\\|\'widget\', single\\: \'\'\\|\'checked\', \\.\\.\\.\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Category\\:\\:form\\(\\) should be contravariant with parameter \\$instance \\(array\\) of method WP_Widget\\:\\:form\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Category.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#1 \\$new_instance \\(array\\{display\\-posts\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', exclude\\: string, include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\: int\\|numeric\\-string, new_widget\\: \'list\'\\|\'widget\', single\\: \'\'\\|\'checked\', \\.\\.\\.\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Category\\:\\:update\\(\\) should be contravariant with parameter \\$new_instance \\(array\\) of method WP_Widget\\:\\:update\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Category.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#2 \\$instance \\(array\\{display\\-posts\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', exclude\\: string, include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\: int\\|numeric\\-string, new_widget\\: \'list\'\\|\'widget\', single\\: \'\'\\|\'checked\', \\.\\.\\.\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Category\\:\\:widget\\(\\) should be contravariant with parameter \\$instance \\(array\\) of method WP_Widget\\:\\:widget\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Category.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#2 \\$old_instance \\(array\\{display\\-posts\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', exclude\\: string, include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\: int\\|numeric\\-string, new_widget\\: \'list\'\\|\'widget\', single\\: \'\'\\|\'checked\', \\.\\.\\.\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Category\\:\\:update\\(\\) should be contravariant with parameter \\$old_instance \\(array\\) of method WP_Widget\\:\\:update\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Category.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#1 \\$args \\(array\\{name\\?\\: string, id\\?\\: string, id_increment\\?\\: string, description\\?\\: string, class\\?\\: string, before_widget\\: string, after_widget\\: string, before_title\\: string, \\.\\.\\.\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Page\\:\\:widget\\(\\) should be contravariant with parameter \\$args \\(array\\) of method WP_Widget\\:\\:widget\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Page.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#1 \\$instance \\(array\\{exclude\\: string, order_by\\: \'menu_order\'\\|\'post_date\'\\|\'post_title\', title\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\?\\: int\\|numeric\\-string, post_type\\?\\: string\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Page\\:\\:form\\(\\) should be contravariant with parameter \\$instance \\(array\\) of method WP_Widget\\:\\:form\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Page.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#1 \\$new_instance \\(array\\{exclude\\: string, order_by\\: \'menu_order\'\\|\'post_date\'\\|\'post_title\', title\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\?\\: int\\|numeric\\-string, post_type\\?\\: string\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Page\\:\\:update\\(\\) should be contravariant with parameter \\$new_instance \\(array\\) of method WP_Widget\\:\\:update\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Page.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#2 \\$instance \\(array\\{exclude\\: string, order_by\\: \'menu_order\'\\|\'post_date\'\\|\'post_title\', title\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\?\\: int\\|numeric\\-string, post_type\\?\\: string\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Page\\:\\:widget\\(\\) should be contravariant with parameter \\$instance \\(array\\) of method WP_Widget\\:\\:widget\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Page.php',
	];
	$ignoreErrors[] = [
		// identifier: method.childParameterType
		'message' => '#^Parameter \\#2 \\$old_instance \\(array\\{exclude\\: string, order_by\\: \'menu_order\'\\|\'post_date\'\\|\'post_title\', title\\?\\: string, display_all\\?\\: \'\'\\|\'checked\', include_childless_parent\\?\\: \'\'\\|\'checked\', include_parent\\?\\: \'\'\\|\'checked\', levels\\?\\: int\\|numeric\\-string, post_type\\?\\: string\\}\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Page\\:\\:update\\(\\) should be contravariant with parameter \\$old_instance \\(array\\) of method WP_Widget\\:\\:update\\(\\)$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Page.php',
	];
	$ignoreErrors[] = [
		// identifier: generics.notGeneric
		'message' => '#^PHPDoc tag @extends contains generic type WP_Widget\\<array\\<string, array\\<string, string\\>\\|int\\|string\\>\\> but class WP_Widget is not generic\\.$#',
		'count'   => 1,
		'path'    => __DIR__ . '/../src/Widget/Widget_Abstract.php',
	];
	$ignoreErrors[] = [
		// identifier: generics.notGeneric
		'message' => '#^PHPDoc tag @param for parameter \\$widget contains generic type WP_Widget\\<array\\<string, int\\|string\\>\\> but class WP_Widget is not generic\\.$#',
		'count'   => 2,
		'path'    => __DIR__ . '/../src/Notice.php',
	];
}

return [ 'parameters' => [ 'ignoreErrors' => $ignoreErrors ] ];
