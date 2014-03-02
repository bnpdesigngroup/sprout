<?php

if (!class_exists('Sprout_Excerpts')) {

	class Sprout_Excerpts extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_filter('excerpt_length', array($this, 'get_excerpt_length'));
			add_filter('excerpt_more', array($this, 'get_excerpt_more'));

		}

		public function get_excerpt_length($length) {
			return POST_EXCERPT_LENGTH;
		}

		public function get_excerpt_more($more) {
			return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sprout') . '</a>';	
		}
	}

	Sprout::add_module('Sprout_Excerpts', 'excerpts');

}

?>