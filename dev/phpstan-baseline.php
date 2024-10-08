<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	// identifier: lipemat.noUnknownProperty
	'message' => '#^Accessing `is_page` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noUnknownProperty
	'message' => '#^Accessing `is_single` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noUnknownProperty
	'message' => '#^Accessing `is_singular` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noUnknownProperty
	'message' => '#^Accessing `queried_object_id` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noUnknownProperty
	'message' => '#^Accessing `queried_object` property on unknown `\\$GLOBALS\\[\'wp_query\'\\]` can skip important errors\\. Make sure the type is known\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noUnknownMethodCaller
	'message' => '#^Calling `widget` method on unknown `\\$widget` can skip important errors\\. Make sure the type is known\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:add_jetpack_support\\(\\) has parameter \\$blocks with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:add_jetpack_support\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:exclude_from_legacy_widgets\\(\\) has parameter \\$blocks with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:exclude_from_legacy_widgets\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:get_attributes\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:get_block_support\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.return
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:get_widget_class\\(\\) has no return type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:js_config\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:js_config\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:short_circuit_widget_blocks\\(\\) has parameter \\$instance with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:short_circuit_widget_blocks\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.returnNullOverFalse
	'message' => '#^Returning false in a method without a `bool` return type\\. Return `null` with `\\<type\\>\\|null` or add `bool` to the return type\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Block_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Categories\\:\\:get_block_support\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Categories.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Pages\\:\\:get_block_support\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Blocks/Pages.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Cache\\:\\:add_child_pages\\(\\) has parameter \\$child_pages with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Cache.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Cache\\:\\:get_child_pages\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Cache.php',
];
$ignoreErrors[] = [
	// identifier: empty.notAllowed
	'message' => '#^Construct empty\\(\\) is not allowed\\. Use more strict comparison\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noSwitch
	'message' => '#^Control structures using `switch` should not be used\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:add_list_item_classes\\(\\) has parameter \\$classes with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:add_list_item_classes\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:get_args\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: missingType.return
	'message' => '#^Method Advanced_Sidebar_Menu\\\\List_Pages\\:\\:get_current_page_id\\(\\) has no return type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Property Advanced_Sidebar_Menu\\\\List_Pages\\:\\:\\$args type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/List_Pages.php',
];
$ignoreErrors[] = [
	// identifier: empty.notAllowed
	'message' => '#^Construct empty\\(\\) is not allowed\\. Use more strict comparison\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.noSwitch
	'message' => '#^Control structures using `switch` should not be used\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Menus\\\\Category\\:\\:get_included_term_ids\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Menus\\\\Category\\:\\:get_list_categories_args\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: booleanNot.exprNotBoolean
	'message' => '#^Only booleans are allowed in a negated boolean, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: elseif.condNotBoolean
	'message' => '#^Only booleans are allowed in an elseif condition, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: if.condNotBoolean
	'message' => '#^Only booleans are allowed in an if condition, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: lipemat.returnNullOverFalse
	'message' => '#^Returning false in a method without a `bool` return type\\. Return `null` with `\\<type\\>\\|null` or add `bool` to the return type\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Category.php',
];
$ignoreErrors[] = [
	// identifier: missingType.generics
	'message' => '#^Property Advanced_Sidebar_Menu\\\\Menus\\\\Menu_Abstract\\:\\:\\$current with generic class Advanced_Sidebar_Menu\\\\Menus\\\\Menu_Abstract does not specify its types\\: SETTINGS$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Menus/Menu_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:get_features\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:plugin_action_link\\(\\) has parameter \\$actions with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:plugin_action_link\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	// identifier: missingType.return
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:preview\\(\\) has no return type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	// identifier: missingType.return
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Notice\\:\\:pro_version_warning\\(\\) has no return type specified\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Notice.php',
];
$ignoreErrors[] = [
	// identifier: empty.notAllowed
	'message' => '#^Construct empty\\(\\) is not allowed\\. Use more strict comparison\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Walkers/Category_Walker.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Walkers\\\\Category_Walker\\:\\:start_lvl\\(\\) has parameter \\$args with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Walkers/Category_Walker.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Walkers\\\\Page_Walker\\:\\:end_el\\(\\) has parameter \\$args with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Walkers/Page_Walker.php',
];
$ignoreErrors[] = [
	// identifier: staticMethod.deprecated
	'message' => '#^Call to deprecated method factory\\(\\) of class Advanced_Sidebar_Menu\\\\Menus\\\\Category\\:
In favor of using factory on the specific class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Category.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Property Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\<array\\<string, int\\|string\\>,array\\<string, int\\|string\\>\\>\\:\\:\\$widget_settings type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Category.php',
];
$ignoreErrors[] = [
	// identifier: staticMethod.deprecated
	'message' => '#^Call to deprecated method factory\\(\\) of class Advanced_Sidebar_Menu\\\\Menus\\\\Page\\:
In favor of using factory on the specific class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Page.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Property Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\<array\\<string, int\\|string\\>,array\\<string, int\\|string\\>\\>\\:\\:\\$widget_settings type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Page.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\:\\:set_instance\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Widget_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: return.unresolvableType
	'message' => '#^PHPDoc tag @return contains unresolvable type\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Widget_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: property.unresolvableType
	'message' => '#^PHPDoc tag @var for property Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\:\\:\\$widget_settings contains unresolvable type\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Widget_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: property.phpDocType
	'message' => '#^PHPDoc tag @var for property Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\:\\:\\$widget_settings with type mixed is not subtype of native type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Widget_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Property Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\:\\:\\$widget_settings type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Widget_Abstract.php',
];
$ignoreErrors[] = [
	// identifier: method.childReturnType
	'message' => '#^Return type \\(array\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Widget_Abstract\\:\\:set_instance\\(\\) should be covariant with return type \\(array\\<string, mixed\\>\\) of method Advanced_Sidebar_Menu\\\\Widget\\\\Widget\\<SETTINGS of array\\<string, mixed\\>,DEFAULTS of array\\<string, mixed\\>\\>\\:\\:set_instance\\(\\)$#',
	'count' => 1,
	'path' => __DIR__ . '/../src/Widget/Widget_Abstract.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
