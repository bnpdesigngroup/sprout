<?php

if (!class_exists('Sprout_Scripts')) {

	class Sprout_Scripts extends Sprout_Module {
		
		private $scripts;
		private $styles;
		private $fallbacks;
		private $last_script_output;

		protected function __construct() {

			$this->scripts = array();
			$this->styles = array();
			$this->fallbacks = array();
			$this->last_script_output = NULL;

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_action('wp_enqueue_scripts', array($this, 'styles'), 100);
			add_action('wp_enqueue_scripts', array($this, 'scripts'), 100);
			add_action('admin_enqueue_scripts', array($this, 'admin_styles'), 100);
			add_action('admin_enqueue_scripts', array($this, 'admin_scripts'), 100);

			add_filter('style_loader_tag', array($this, 'wrap_conditions'), 10, 2);

			add_filter('script_loader_src', array($this, 'insert_fallback'), 10, 2);
			add_action('wp_head', array($this, 'insert_fallback')); // In case script is last one output in header
			add_action('wp_footer', array($this, 'insert_fallback')); // In case script is last one ouput in footer

		}

		/**
		 * Register and enqueue core styles
		 */
		public function styles() {

			// Initialize variables

			$defaults = array(
				'id' => '',
				'src' => '',
				'deps' => array(),
				'version' => false,
				'media' => 'all',
				'conditional' => false,
			);
			$styles = array();

			// Main stylesheet

			array_push($styles, array_replace($defaults, array (
				'id' => 'sprout_app',
				'src' => get_template_directory_uri() . '/assets/css/screen.min.css',
				'conditional' => 'modern',
			)));

			// IE 8 -

			array_push($styles, array_replace($defaults, array (
				'id' => 'sprout_app_ie',
				'src' => get_template_directory_uri() . '/assets/css/ie.min.css',
				'conditional' => 'lte IE 8',
			)));

			// Print only

			array_push($styles, array_replace($defaults, array (
				'id' => 'sprout_app_print',
				'src' => get_template_directory_uri() . '/assets/css/print.min.css',
				'media' => 'print',
			)));

			$styles = apply_filters('sprout_styles', $styles);

			foreach ($styles as $style) {

				// Reapply defaults in case styles were added

				$style = array_replace($defaults, $style);

				// If defining a new style

				if ($style['src']) {

					// Deregister any predefined scripts

					if (wp_style_is($style['id'], 'registered')) {

						wp_deregister_style($style['id']);

					}

					wp_register_style($style['id'], $style['src'], $style['deps'], $style['version'], $style['media']);
					
				}

				wp_enqueue_style($style['id']);

				// Keep style for reference

				$this->styles[$style['id']] = $style;

			}

		}

		/**
		 * Register and enqueue core scripts
		 */
		public function scripts() {

			// Initialize variables

			$defaults = array(
				'id' => '',
				'src' => '',
				'deps' => array(),
				'version' => false,
				'footer' => true,
				'fallback' => false,
				'conditional' => false,
			);
			$scripts = array();

			// Enqueue core scripts

			array_push($scripts, array_replace($defaults, array(
				'id' => 'jquery',
				'src' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
				'footer' => false,
				'fallback' => array(
					'test' => 'window.jQuery',
					'src' => get_template_directory_uri() . '/assets/js/vendor/jquery-1.10.2.min.js'
				),
			)));

			if (is_single() && comments_open() && get_option('thread_comments')) {
				array_push($scripts, array_replace($defaults, array(
					'id' => 'comment-reply',
				)));
			}

			array_push($scripts, array_replace($defaults, array(
				'id' => 'modernizr',
				'src' => get_template_directory_uri() . '/assets/js/vendor/modernizr.custom.min.js',
				'footer' => false,
			)));

			array_push($scripts, array_replace($defaults, array(
				'id' => 'jquerypp',
				'src' => get_template_directory_uri() . '/assets/js/vendor/jquerypp.custom.min.js',
				'deps' => array(
					'jquery',
				),
			)));

			array_push($scripts, array_replace($defaults, array(
				'id' => 'groundwork_scripts',
				'src' => get_template_directory_uri() . '/assets/js/groundwork.min.js',
			)));

			array_push($scripts, array_replace($defaults, array(
				'id' => 'sprout_scripts',
				'src' => get_template_directory_uri() . '/assets/js/front.min.js',
			)));

			$scripts = apply_filters('sprout_scripts', $scripts);

			foreach ($scripts as $script) {

				// Reapply defaults in case scripts were added

				$script = array_replace($defaults, $script);

				// If defining a new script

				if ($script['src']) {

					// Deregister any predefined scripts

					if (wp_script_is($script['id'], 'registered')) {

						wp_deregister_script($script['id']);

					}

					wp_register_script($script['id'], $script['src'], $script['deps'], $script['version'], $script['footer']);
					
				}

				wp_enqueue_script($script['id']);

				// Add fallbacks if any present

				if ($script['fallback']) {

					$script['fallback'] = array_replace(array(
						'output' => false,
					), $script['fallback']);

					$this->fallbacks[$script['id']] = $script['fallback'];

				}

				// Keep script for reference

				$this->scripts[$script['id']] = $script;

			}

		}

		/**
		 * Register and enqueue admin styles
		 */
		public function admin_styles() {

			// Initialize variables

			$defaults = array(
				'id' => '',
				'src' => '',
				'deps' => array(),
				'version' => false,
				'media' => 'all',
				'conditional' => false,
			);
			$styles = array();

			$styles = apply_filters('sprout_admin_styles', $styles);

			foreach ($styles as $style) {

				// Reapply defaults in case styles were added

				$style = array_replace($defaults, $style);

				// If defining a new style

				if ($style['src']) {

					// Deregister any predefined scripts

					if (wp_style_is($style['id'], 'registered')) {

						wp_deregister_style($style['id']);

					}

					wp_register_style($style['id'], $style['src'], $style['deps'], $style['version'], $style['media']);
					
				}

				wp_enqueue_style($style['id']);

				// Keep style for reference

				$this->styles[$style['id']] = $style;

			}

		}

		/**
		 * Register and enqueue admin scripts
		 */
		public function admin_scripts() {

			// Initialize variables

			$defaults = array(
				'id' => '',
				'src' => '',
				'deps' => array(),
				'version' => false,
				'footer' => true,
				'fallback' => false,
				'conditional' => false,
			);
			$scripts = array();

			// Enqueue core scripts

			array_push($scripts, array_replace($defaults, array(
				'id' => 'behave',
				'src' => get_template_directory_uri() . '/assets/js/vendor/behave.min.js',
			)));

			array_push($scripts, array_replace($defaults, array(
				'id' => 'sprout_admin',
				'src' => get_template_directory_uri() . '/assets/js/admin.min.js',
				'deps' => array('jquery', 'behave'),
			)));

			$scripts = apply_filters('sprout_admin_scripts', $scripts);

			foreach ($scripts as $script) {

				// Reapply defaults in case scripts were added

				$script = array_replace($defaults, $script);

				// If defining a new script

				if ($script['src']) {

					// Deregister any predefined scripts

					if (wp_script_is($script['id'], 'registered')) {

						wp_deregister_script($script['id']);

					}

					wp_register_script($script['id'], $script['src'], $script['deps'], $script['version'], $script['footer']);
					
				}

				wp_enqueue_script($script['id']);

				// Add fallbacks if any present

				if ($script['fallback']) {

					$script['fallback'] = array_replace(array(
						'output' => false,
					), $script['fallback']);

					$this->fallbacks[$script['id']] = $script['fallback'];

				}

				// Keep script for reference

				$this->scripts[$script['id']] = $script;

			}

		}

		/**
		 * Wrap IE conditional tags
		 */
		public function wrap_conditions($html, $handle) {

			// Get relevant script

			if (strpos($html, '<script') !== false  && isset($this->scripts[$handle])) {

				$script = $this->scripts[$handle];

			} else if (strpos($html, '<link') !== false && isset($this->styles[$handle])) {

				$script = $this->styles[$handle];

			}

			// If script has conditions, apply them

			if ($script['conditional']) {

				// Does script apply to modern browsers (IE gte 10 or chrome or firefox etc.)?

				$modern = false;

				if (strpos($script['conditional'], 'modern') !== false && strpos($script['conditional'], '!modern') === false) {

					$modern = true;

				}

				// Replace "modern" with false, since any IE parsing IE conditions (lte 9) is NOT modern

				$script['conditional'] = str_replace('modern', 'false', $script['conditional']);

				// Apply conditions

				$html = '<!--[if ' . $script['conditional'] . ']>' . ($modern ? '<!-->' : '') . $html . ($modern ? '<!--' : '') . '<![endif]-->';

			}

			return $html;

		}

		/**
		 * Insert local fallbacks
		 */
		public function insert_fallback($src, $handle = null) {

			// If last script output has a fallback, output fallback

			if (array_key_exists($this->last_script_output, $this->fallbacks)) {

				$script = $this->fallbacks[$this->last_script_output];

				echo '<script>(' . $script['test'] . ') || document.write(\'<script src="' . $script['src'] . '"><\/script>\')</script>' . "\n";

				unset($this->fallbacks[$handle]);

			}

			// Note this script for the next iteration

			if ($handle) {

				$this->last_script_output = $handle;

			}

			return $src;

		}

	}

	Sprout::add_module('Sprout_Scripts', 'scripts');

}

?>