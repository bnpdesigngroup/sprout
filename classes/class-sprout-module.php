<?php

if (!class_exists('Sprout_Module')) {

	/**
	 * Sprout Module, singleton pattern
	 */
	abstract class Sprout_Module {
		private static $instances;

		/*****************
		 * Class Methods *
		 *****************/

		/**
		 * Get instance of singleton
		 */
		public static function get_instance() {

			$class = get_called_class();

			if (!isset(self::$instances[$class])) {
				self::$instances[$class] = new $class();
			}

			return self::$instances[$class];

		}

		/********************
		 * Instance Methods *
		 ********************/

		abstract public function init();

		abstract public function register_hooks();

	}

}

?>