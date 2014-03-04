<?php

if (!class_exists('Sprout_Images')) {

	class Sprout_Images extends Sprout_Module {

		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_action('init', array($this, 'add_image_sizes'));

		}

		public function add_image_sizes() {

			$defaults = array(
				'id' => '',
				'width' => 0,
				'height' => 0,
				'crop' => false,
			);
			$image_sizes = array();

			$image_sizes = apply_filters('sprout_image_sizes', $image_sizes);

			foreach ($image_sizes as $image_size) {

				$image_size = array_replace($defaults, $image_size);

				add_image_size($image_size['id'], $image_size['width'], $image_size['height'], $image_size['crop']);

			}

		}

	}

	Sprout::add_module('Sprout_Images', 'images');

}

?>