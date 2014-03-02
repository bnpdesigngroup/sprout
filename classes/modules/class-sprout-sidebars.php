<?php

if (!class_exists('Sprout_Sidebars')) {

	class Sprout_Sidebars extends Sprout_Module {
		private $sidebars;

		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_action('widgets_init', array($this, 'register_sidebars'));

		}

		/**
		 * Register built in and custom sidebars
		 */
		public function register_sidebars() {

			// Initialize variables

			$sprout = Sprout::get_instance();
			$sidebars = array();
			$custom_sidebars = array();
			$defaults = array (
				'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			);

			// Primary sidebar

			array_push($sidebars, array_replace($defaults, array(
				'name' => __('Primary', 'sprout'),
				'id'   => 'sidebar-primary',
				'selectable' => true,
			)));

			// Add custom sidebars, if any are defined

			$custom_sidebars = explode(',', $sprout->options->get_option('sidebars', ''));

			foreach ($custom_sidebars as $sidebar) {

				$id = trim($sidebar);

				array_push($sidebars, array_replace($defaults, array(
					'name' => $sidebar,
					'id'   => 'sidebar-' . strtolower(str_replace(array('_', ' '), '-', $sidebar)),
					'selectable' => true,
				)));

			}

			// Footer sidebar

			array_push($sidebars, array_replace($defaults, array(
				'name' => __('Footer', 'sprout'),
				'id'   => 'sidebar-footer',
			)));

			// Offer chance to override sidebars

			$sidebars = apply_filters('sprout_sidebars', $sidebars);

			// Register sidebars

			foreach ($sidebars as $sidebar) {

				register_sidebar($sidebar);

			}	

			// Save sidebars for reference

			$this->sidebars = $sidebars;

		}

		/**
		 * Get selectable sidebars
		 */
		public function get_selectable_sidebars() {

			$selectable_sidebars = array_filter((array) $this->sidebars, function($sidebar) {

				return isset($sidebar['selectable']) && $sidebar['selectable'];

			});

			return apply_filters('sprout_get_selectable_sidebars', $selectable_sidebars);

		}

		/**
		 * Get sidebar for current page/post
		 */
		public function get_sidebar() {

			// If layout is not calculated, calculate it

			if (is_null($this->sidebar)) {

				$sidebar = false;

				// Get global default layout

				$default = 'sidebar-primary';

				// Get context specific layout

				if (is_front_page()) {

					$sidebar = ot_get_option('home_sidebar', null);

				} else if (is_home()) {

					$sidebar = ot_get_option('blog_sidebar', null);

				} else if (is_archive()) {

					$sidebar = ot_get_option('archive_sidebar', null);

				} else if (is_search()) {

					$sidebar = ot_get_option('archive_sidebar', null);

				} else if (is_404()) {

					$sidebar = ot_get_option('404_sidebar', null);

				} else {

					$sidebar = ot_get_option(get_post_type() . '_sidebar', null);

				}

				// Get page/post specific sidebar

				if (is_singular()) {

					$single_sidebar = get_post_meta($GLOBALS['post']->ID, 'single_sidebar', true);

					if ($single_sidebar) {

						$sidebar = $single_sidebar;

					}

				}

				if (!$sidebar) {

					$sidebar = $default;

				}

				$this->sidebar = $sidebar;

			}

			return apply_filters('sprout_get_sidebar', $this->sidebar);;

		}

	}

	Sprout::add_module('Sprout_Sidebars', 'sidebars');

}

?>