<?php

if (!class_exists('Sprout_Rss')) {

	class Sprout_Rss extends Sprout_Module {

		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {


			add_filter('get_bloginfo_rss', array($this, 'remove_default_description'));

		}

		/**
		 * Don't show WordPress's default description in rss (if it hasn't been changed)
		 */
		public function remove_default_description($bloginfo_property) {

			$default_tagline = 'Just another WordPress site';
			
			return ($bloginfo_property === $default_tagline) ? '' : $bloginfo_property;

		}

	}

	Sprout::add_module('Sprout_Rss', 'rss');

}

?>