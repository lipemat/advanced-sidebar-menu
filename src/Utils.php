<?php

namespace Advanced_Sidebar_Menu;

use Advanced_Sidebar_Menu\Traits\Singleton;

/**
 * Various helpers for the Advanced Sidebar Menu plugin.
 *
 * @author OnPoint Plugins
 * @since  8.4.1
 */
class Utils {
	use Singleton;

	/**
	 * Is a widget's checkbox checked?
	 *
	 * Checks first for a value then verifies the value = checked.
	 *
	 * @param string $name     - name of checkbox.
	 * @param array<string, mixed> $settings - Widget settings.
	 *
	 * @return bool
	 */
	public function is_checked( $name, array $settings ) {
		// Handle array type names (e.g. open-links[all]).
		preg_match( '/(?<field>\S*?)\[(?<key>\S*?)]/', $name, $array );
		if ( ! empty( $array['field'] ) && ! empty( $array['key'] ) ) {
			return isset( $settings[ $array['field'] ][ $array['key'] ] ) && 'checked' === $settings[ $array['field'] ][ $array['key'] ];
		}

		// Standard non array names.
		return isset( $settings[ $name ] ) && 'checked' === $settings[ $name ];
	}


	/**
	 * Is a setting available and not an empty string?
	 *
	 * @since 9.5.0
	 *
	 * @param array<string, mixed> $settings - Settings to compare against.
	 * @param string               $key      - Key of settings which may be available.
	 *
	 * @return bool
	 */
	public function is_empty( array $settings, string $key ): bool {
		return ! isset( $settings[ $key ] ) || '' === $settings[ $key ];
	}


	/**
	 * Apply a callback to all elements of an array recursively.
	 *
	 * Like `array_walk_recursive` except returns the result as
	 * a new array instead of requiring you pass the array element by reference
	 * and alter it directly.
	 *
	 * @since 8.6.5
	 *
	 * @param callable $callback   - Callback for each element in each level of array.
	 * @param array<string, mixed> $to_recurse - Array to recurse.
	 *
	 * @return array<string, mixed>
	 */
	public function array_map_recursive( callable $callback, array $to_recurse ): array {
		$output = [];
		foreach ( $to_recurse as $key => $data ) {
			if ( \is_array( $data ) ) {
				$output[ $key ] = $this->array_map_recursive( $callback, $data );
			} else {
				$output[ $key ] = $callback( $data );
			}
		}

		return $output;
	}
}
