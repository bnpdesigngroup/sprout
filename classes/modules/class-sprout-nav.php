<?php

if (!class_exists('Sprout_Nav')) {

	class Sprout_Nav extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			register_nav_menus(array(
				'primary_navigation' => __('Primary Navigation', 'sprout'),
			));

			$this->register_hooks();

		}

		public function register_hooks() {

			add_filter('nav_menu_css_class', array ($this, 'nav_menu_css_class'), 10, 2);
			add_filter('nav_menu_item_id', '__return_null');
			add_filter('wp_nav_menu_args', array ($this, 'nav_menu_args'));

		}

		/**
		 * Clean menu classes
		 */
		public function nav_menu_css_class($classes, $item) {

			$slug = sanitize_title($item->title);
			$classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'current', $classes);
			$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

			$classes[] = 'menu-' . $slug;

			$classes = array_filter(array_map('trim', array_unique($classes)));

			return $classes;	

		}

		/**
		 * Clean up wp_nav_menu_args
		 * 
		 * Remove the container
		 * Use Sprout_Nav_Walker by default
		 */
		public function nav_menu_args($args = '') {

			$sprout_nav_menu_args['container'] = false;

			if (!$args['items_wrap']) {
				$sprout_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
			}

			if (!$args['walker']) {
				$sprout_nav_menu_args['walker'] = new Sprout_Nav_Walker();
			}

			return array_merge($args, $sprout_nav_menu_args);

		}

	}

	Sprout::add_module('Sprout_Nav', 'nav');

}

?>