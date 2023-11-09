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
		'message' => '#^Casting to string something that\'s already string.$#',
		'count'   => 2,
		'path'    => __DIR__ . '/../src/Scripts.php',
	];
}

return [ 'parameters' => [ 'ignoreErrors' => $ignoreErrors ] ];
