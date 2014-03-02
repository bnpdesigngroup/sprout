<?php

if (!class_exists('Sprout_Comments')) {

	class Sprout_Comments extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_filter('get_avatar', array($this, 'get_avatar'));

		}

		public function get_avatar($avatar) {

			return str_replace("class='avatar", "class='avatar pull-left media-object", $avatar);

		}

	}

	Sprout::add_module('Sprout_Comments', 'comments');

}

?>