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
} else {
	$ignoreErrors[] = [
		'message'    => '#^Method Advanced_Sidebar_Menu\\\\Blocks\\\\Block_Abstract\\:\\:convert_checkbox_values\\(\\) should return WIDGET_SETTINGS of array\\<string, array\\<string, string\\>\\|int\\|string\\> but returns array\\<string, mixed\\>\\.$#',
		'identifier' => 'return.type',
		'count'      => 1,
		'path'       => __DIR__ . '/../src/Blocks/Block_Abstract.php',
	];
}

return [ 'parameters' => [ 'ignoreErrors' => $ignoreErrors ] ];
