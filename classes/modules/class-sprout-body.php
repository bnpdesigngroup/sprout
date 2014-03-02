<?php

if (!class_exists('Sprout_Body')) {

	class Sprout_Body extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_filter('body_class', array($this, 'clean_class'));

		}

		/**
		 * Add and remove body_class() classes
		 */
		public function clean_class($classes) {

			// Add post/page slug

			if (is_single() || is_page() && !is_front_page()) {
				$classes[] = basename(get_permalink());
			}

			// Remove unnecessary classes

			$home_id_class = 'page-id-' . get_option('page_on_front');
			$remove_classes = array(
				'page-template-default',
				$home_id_class
			);
			$classes = array_diff($classes, $remove_classes);

			return $classes;
		}

	}

	Sprout::add_module('Sprout_Body', 'body');

}

?>