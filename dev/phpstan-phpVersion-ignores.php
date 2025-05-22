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

}

return [ 'parameters' => [ 'ignoreErrors' => $ignoreErrors ] ];
