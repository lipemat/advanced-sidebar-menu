<?php

namespace Advanced_Sidebar_Menu\Blocks\Attributes;

use Advanced_Sidebar_Menu\Traits\Singleton;

/**
 * Utility functions for working with attributes.
 *
 * @author OnPoint Plugins
 * @since  9.7.0
 *
 * @phpstan-type BOOL_CHECKED 'checked'|''
 */
class Utils {
	use Singleton;

	/**
	 * Convert checked string values to actual boolean values.
	 *
	 * @param string               $key  - The key to check.
	 * @param array<string, mixed> $attr - The attribute array.
	 *
	 * @return bool
	 */
	public function get_checked_bool( string $key, array $attr ): bool {
		if ( ! isset( $attr[ $key ] ) ) {
			return false;
		}

		return true === $attr[ $key ] || 'checked' === $attr[ $key ];
	}


	/**
	 * Convert a boolean value to a 'checked' string format.
	 *
	 * @param bool $value - Boolean value to convert to 'checked' string format.
	 *
	 * @phpstan-return BOOL_CHECKED
	 * @return ''|'checked'
	 */
	public function get_bool_checked( bool $value ): string {
		return $value ? 'checked' : '';
	}


	/**
	 * Checkboxes are saved as `true` on the Gutenberg side.
	 * The widgets expect the values to be `checked`.
	 *
	 * @param array<string, array<string, string|bool>|int|string|bool> $attr - Attribute values pre-converted.
	 *
	 * @return array<string, string|int|array<string, string|int>>
	 */
	public function convert_all_checkboxes( array $attr ): array {
		return \Advanced_Sidebar_Menu\Utils::instance()->array_map_recursive( function( $value ) {
			if ( \is_bool( $value ) ) {
				return $this->get_bool_checked( $value );
			}
			return $value;
		}, $attr );
	}
}
