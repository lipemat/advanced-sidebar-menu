<?php

namespace Advanced_Sidebar_Menu\Traits;

/**
 * Trait Singleton
 *
 * @author Mat Lipe
 * @since  7.7.0
 */
trait Singleton {

	/**
	 * Instance of this class for use as singleton
	 *
	 * @var static
	 */
	protected static $instance;


	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		static::$instance = static::instance();
		if ( method_exists( static::$instance, 'hook' ) ) {
			static::$instance->hook();
		}
	}


	/**
	 * Get (and instantiate, if necessary) the instance of the
	 * class
	 *
	 * @static
	 * @return static
	 */
	public static function instance() {
		if ( ! is_a( static::$instance, __CLASS__ ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
