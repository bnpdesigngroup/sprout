<?php

if (!class_exists('Sprout_Layout')) {

	class Sprout_Layout extends Sprout_Module {

		private $layout;

		protected function __construct() {

		}

		public function init() {

		}

		public function register_hooks() {

		}

		/**
		 * Get the layout for this query
		 * @return string
		 */
		public function get_layout() {

			// If layout is not calculated, calculate it

			if (is_null($this->layout)) {

				$layout = false;

				// Get global default layout

				$default = ot_get_option('global_layout', 'sidebar-right');

				// Get context specific layout

				if (is_front_page()) {

					$layout = ot_get_option('home_layout', null);

				} else if (is_home()) {

					$layout = ot_get_option('blog_layout', null);

				} else if (is_archive()) {

					$layout = ot_get_option('archive_layout', null);

				} else if (is_search()) {

					$layout = ot_get_option('archive_layout', null);

				} else if (is_404()) {

					$layout = ot_get_option('404_layout', null);

				} else {

					$layout = ot_get_option(get_post_type() . '_layout', null);

				}

				// Get page/post specific layout

				if (is_singular()) {

					$single_layout = get_post_meta($GLOBALS['post']->ID, 'single_layout', true);

					if ($single_layout) {

						$layout = $single_layout;

					}

				}

				if (!$layout) {

					$layout = $default;

				}

				$this->layout = $layout;

			}

			return apply_filters('sprout_get_layout', $this->layout);;

		}

		/**
		 * Get the class for the main content for this layout
		 * @return string
		 */
		public function get_main_class() {

			$layout = $this->get_layout();

			$class = array();

			if ($this->has_sidebar()) {
			
				$size = ot_get_option('main_size', 9);

				$sizes = array (
					'one',
					'two',
					'three',
					'four',
					'five',
					'six',
					'seven',
					'eight',
					'nine',
					'ten',
					'eleven',
				);

				$class[] = $sizes[$size - 1] . '-twelfths';

				if ($layout == 'sidebar-left') {

					$class[] = 'right-' . $sizes[(12 - $size) - 1];

				}

			} else {

				// Classes on full width pages - No need to calculate
			
				$class[] = 'whole';
			
			}

			$class = implode(' ', $class);

			return apply_filters('sprout_main_class', $class);

		}

		/**
		 * Get the class for the sidebar for this layout
		 * @return string
		 */
		public function get_sidebar_class() {

			$layout = $this->get_layout();

			$class = array();
			
			$size = 12 - ot_get_option('main_size', 9);

			$sizes = array (
				'one',
				'two',
				'three',
				'four',
				'five',
				'six',
				'seven',
				'eight',
				'nine',
				'ten',
				'eleven',
			);

			$class[] = $sizes[$size - 1] . '-twelfths';

			if ($layout == 'sidebar-left') {

				$class[] = 'left-' . $sizes[(12 - $size) - 1];

			}

			$class = implode(' ', $class);

			return apply_filters('sprout_sidebar_class', $class);

		}

		/**
		 * Whether or not this particular layout has a sidebar
		 * @return bool
		 */
		public function has_sidebar() {

			$layout = $this->get_layout();

			return apply_filters('sprout_has_sidebar', in_array($layout, array ('sidebar-left', 'sidebar-right')));

		}
		
	}

	Sprout::add_module('Sprout_Layout');

}

?>