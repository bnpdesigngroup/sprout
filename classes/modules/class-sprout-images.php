<?php

if (!class_exists('Sprout_Images')) {

	class Sprout_Images extends Sprout_Module {

		private $selectable_sizes;

		protected function __construct() {

			$this->selectable_sizes = array();

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_action('init', array($this, 'add_image_sizes'));

			add_filter('image_size_names_choose', array($this, 'add_selectable_sizes'));

		}

		public function add_image_sizes() {

			$defaults = array(
				'id' => '',
				'name' => false,
				'width' => 0,
				'height' => 0,
				'crop' => false,
			);
			$image_sizes = array();

			$image_sizes = apply_filters('sprout_image_sizes', $image_sizes);

			foreach ($image_sizes as $image_size) {

				$image_size = array_replace($defaults, $image_size);

				add_image_size($image_size['id'], $image_size['width'], $image_size['height'], $image_size['crop']);

				if ($image_size['name']) {

					$this->selectable_sizes[$image_size['id']] = $image_size['name'];

				}

			}

		}

		public function add_selectable_sizes($sizes) {

			$sizes = array_merge($sizes, $this->selectable_sizes);

			$sizes = apply_filters('sprout_selectable_sizes', $sizes);

			return $sizes;

		}

	}

	Sprout::add_module('Sprout_Images', 'images');

}

?>