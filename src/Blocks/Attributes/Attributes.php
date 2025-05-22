<?php

namespace Advanced_Sidebar_Menu\Blocks\Attributes;

/**
 * Rules an Attribute class must follow.
 *
 * @author OnPoint Plugins
 * @since  9.7.0
 *
 * @phpstan-import-type COMMON_ATTR from Common
 *
 * @template SETTINGS of array<string, string|int|bool|array<string, string>>
 */
interface Attributes {

	/**
	 * Get the finished arguments in widget format.
	 *
	 * @phpstan-return \Union<\Required<SETTINGS>, COMMON_ATTR>
	 */
	public function get_args(): array;


	/**
	 * Construct the class from the given arguments.
	 *
	 * @param array<string, mixed> $instance - The instance to parse.
	 *
	 * @return Attributes<SETTINGS>
	 */
	public static function factory( array $instance );
}
