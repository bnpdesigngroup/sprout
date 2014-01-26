<?php

if (!class_exists('Sprout')) {

	/**
	 * Main controller
	 */
	class Sprout {
		private static $instance;
		private static $load_modules = array();
		private static $modules;

		/*****************
		 * Class Methods *
		 *****************/

		public static function get_instance() {

			$class = get_called_class();

			if (!self::$instance) {
				self::$instance = new $class();
			}

			return self::$instance;

		}

		/**
		 * Add a module to be loaded
		 * 
		 * Why static? Because this must be done before
		 * modules are instantiated. Also this is defining
		 * relationship between classes, not objects
		 * @param string $module Class name of module
		 */
		public static function add_module($module) {

			if (!in_array($module, self::$load_modules)) {

				array_push(self::$load_modules, $module);

			}

		}

		/**
		 * Remove a module in load queue
		 * @param string $module Class name of module
		 */
		public static function remove_module($module) {

			if (in_array($module, self::$load_modules)) {

				self::$modules = array_diff(self::$modules, array ($module));

			}

		}

		/********************
		 * Instance Methods *
		 ********************/

		/**
		 * Create
		 */
		protected function __construct() {

			// Enable __DIR__ use for php < 5.3

			if (@__DIR__ == '__DIR__') {
				define('__DIR__', dirname(__FILE__));
			}

		}

		public function init() {

			// Add theme supports

			add_theme_support('post-thumbnails');

			// Load text domain

			load_theme_textdomain('sprout', get_template_d . '/lang');

			// Load modules

			foreach (self::$load_modules as $module) {

				// Shorten module name
				
				if (strpos($module, "_") !== false) {
					$name = strtolower(substr($module, strpos($module, "_") + 1));
					
					// Name exists, use full name

					if (self::$modules[$name]) {

						$name = strtolower($module);

					}

				}
				
				self::$modules[$name] = $module::get_instance();
			
			}

			// Initialize modules

			foreach (self::$modules as $module) {

				$module->init();

			}

			$this->register_hooks();

		}

		public function register_hooks() {

		}

		/**
		 * Get module
		 * @param $property Property or module to retrieve
		 * @return object Module
		 */
		public function __get($property) {

			// If a module is requested, return it

			if (isset(self::$modules[$property])) {
				return self::$modules[$property];
			}

		}

	}

}

// Load prerequisite classes

require dirname(__FILE__) . '/class-sprout-module.php';
require dirname(__FILE__) . '/walkers/class-sprout-walker-comments.php';
require dirname(__FILE__) . '/walkers/class-sprout-walker-nav.php';

// Load modules

require dirname(__FILE__) . '/modules/class-sprout-activation.php';
require dirname(__FILE__) . '/modules/class-sprout-body.php';
require dirname(__FILE__) . '/modules/class-sprout-comments.php';
require dirname(__FILE__) . '/modules/class-sprout-head.php';
require dirname(__FILE__) . '/modules/class-sprout-layout.php';
require dirname(__FILE__) . '/modules/class-sprout-nav.php';
require dirname(__FILE__) . '/modules/class-sprout-options.php';
require dirname(__FILE__) . '/modules/class-sprout-scripts.php';
require dirname(__FILE__) . '/modules/class-sprout-search.php';
require dirname(__FILE__) . '/modules/class-sprout-sidebars.php';
require dirname(__FILE__) . '/modules/class-sprout-templates.php';
require dirname(__FILE__) . '/modules/class-sprout-title.php';
require dirname(__FILE__) . '/modules/class-sprout-urls.php';

?>