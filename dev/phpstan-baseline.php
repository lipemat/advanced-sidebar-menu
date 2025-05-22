<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Accessing `is_page` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'identifier' => 'lipemat.noUnknownProperty',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Accessing `is_single` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'identifier' => 'lipemat.noUnknownProperty',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Accessing `is_singular` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'identifier' => 'lipemat.noUnknownProperty',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Accessing `queried_object_id` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'identifier' => 'lipemat.noUnknownProperty',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Accessing `queried_object` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'identifier' => 'lipemat.noUnknownProperty',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:add_jetpack_support\\(\\) has parameter \\$blocks with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:add_jetpack_support\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:exclude_from_legacy_widgets\\(\\) has parameter \\$blocks with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:exclude_from_legacy_widgets\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:js_config\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:js_config\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:short_circuit_widget_blocks\\(\\) has parameter \\$instance with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:short_circuit_widget_blocks\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$args of method Advanced_Sidebar_Menu\\\\Widget\\\\Widget\\<WIDGET_SETTINGS of array\\<string, array\\<string, string\\>\\|int\\|string\\>,DEFAULTS of array\\<string, array\\<string, string\\>\\|int\\|string\\>\\>\\:\\:widget\\(\\) expects array\\{name\\?\\: string, id\\?\\: string, id_increment\\?\\: string, description\\?\\: string, class\\?\\: string, before_widget\\: string, after_widget\\: string, before_title\\: string, \\.\\.\\.\\}, non\\-empty\\-array given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$widget of static method Advanced_Sidebar_Menu\\\\__Temp_Id_Proxy\\:\\:factory\\(\\) expects Advanced_Sidebar_Menu\\\\Widget\\\\Widget\\<array\\{\\}, array\\{\\}\\>, Advanced_Sidebar_Menu\\\\Widget\\\\WidgetWithId\\<WIDGET_SETTINGS of array\\<string, array\\<string, string\\>\\|int\\|string\\>, DEFAULTS of array\\<string, array\\<string, string\\>\\|int\\|string\\>\\> given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Returning false in a method without a `bool` return type\\. Return `null` with `\\<type\\>\\|null` or add `bool` to the return type\\.$#',
	'identifier' => 'lipemat.returnNullOverFalse',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Cache\\:\\:add_child_pages\\(\\) has parameter \\$child_pages with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Cache.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Cache\\:\\:get_child_pages\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Cache.php',
];
$ignoreErrors[] = [
	'message' => '#^Construct empty\\(\\) is not allowed\\. Use more strict comparison\\.$#',
	'identifier' => 'empty.notAllowed',
	'count' => 6,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Control structures using `switch` should not be used\\.$#',
	'identifier' => 'lipemat.noSwitch',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:add_list_item_classes\\(\\) has parameter \\$classes with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:add_list_item_classes\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:get_args\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:get_current_page_id\\(\\) has no return type specified\\.$#',
	'identifier' => 'missingType.return',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Property Advanced_Sidebar_Menu\\\\List_Pages\\:\\:\\$args type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	'message' => '#^Control structures using `switch` should not be used\\.$#',
	'identifier' => 'lipemat.noSwitch',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Menus\\\\Category\\:\\:get_included_term_ids\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Menus\\\\Category\\:\\:get_list_categories_args\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Only booleans are allowed in a negated boolean, mixed given\\.$#',
	'identifier' => 'booleanNot.exprNotBoolean',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Only booleans are allowed in an elseif condition, mixed given\\.$#',
	'identifier' => 'elseif.condNotBoolean',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Only booleans are allowed in an if condition, mixed given\\.$#',
	'identifier' => 'if.condNotBoolean',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Returning false in a method without a `bool` return type\\. Return `null` with `\\<type\\>\\|null` or add `bool` to the return type\\.$#',
	'identifier' => 'lipemat.returnNullOverFalse',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Strict comparison using \\!\\=\\= between null and int\\|string will always evaluate to true\\.$#',
	'identifier' => 'notIdentical.alwaysTrue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	'message' => '#^Property Advanced_Sidebar_Menu\\\\Menus\\\\Menu_Abstract\\:\\:\\$current with generic class Advanced_Sidebar_Menu\\\\Menus\\\\Menu_Abstract does not specify its types\\: SETTINGS$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Menu_Abstract.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:get_features\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:plugin_action_link\\(\\) has parameter \\$actions with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:plugin_action_link\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:preview\\(\\) has no return type specified\\.$#',
	'identifier' => 'missingType.return',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:pro_version_warning\\(\\) has no return type specified\\.$#',
	'identifier' => 'missingType.return',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	'message' => '#^Construct empty\\(\\) is not allowed\\. Use more strict comparison\\.$#',
	'identifier' => 'empty.notAllowed',
	'count' => 1,
	'path' => __DIR__ . '/../src/Walkers/Category_Walker.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Walkers\\\\Category_Walker\\:\\:start_lvl\\(\\) has parameter \\$args with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Walkers/Category_Walker.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Walkers\\\\Page_Walker\\:\\:end_el\\(\\) has parameter \\$args with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/Walkers/Page_Walker.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
