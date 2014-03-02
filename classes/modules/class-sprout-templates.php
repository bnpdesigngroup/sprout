<?php

if (!class_exists('Sprout_Templates')) {
	/**
	 * Wraps WordPress templates
	 * 
	 * This works by listening for the main template WordPress requests, and then providing a list of suitable "bases" to wrap that request.
	 * Put another way, it turns WordPress's main template requests into content template requests, and then works backwards to figure out
	 * which base should wrap that content.
	 * 
	 * ITS IMPORTANT TO NOTE that if the "content" template (index.php, page.php, etc.) for a main template doesn't exist, the base won't be used. 
	 * In other words, if you have "base-page.php", and no "page.php", "base-page.php" WILL NOT be used for page requests, because the content
	 * template WordPress will request will be "index.php".
	 * 
	 * So if you want to add a new base, make sure to add the content template first.
     */
	class Sprout_Templates extends Sprout_Module {
		private $main_template;
		private $type;


		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_filter('template_include', array ($this, 'locate'), 99);

		}

		/**
		 * Get content template
		 * 
		 * Only useful after the template_include filter has been fired
		 * @return string Path to main template
		 */
		public function get_main_template() {

			return $this->main_template;

		}

		/**
		 * Finds template optionally specific to post/page type, e.g. sidebar-single.php, sidebar-page.php
		 * @param string $template Template part to locate
		 * @param string $type 
		 * @return string $template
		 */
		public function locate($template, $type = NULL) {

			// Initialize variables

			$templates = array ();

			if (is_null($type)) {
				$type = $this->type;
			}

			// If this is the main template (e.g. single.php), note type for other templates

			if (did_action('template_redirect') == 1 && is_null($this->type)) {

				// Get list of bases to wrap this request in

				$templates = $this->get_bases($template);

				// Note the original template as the content template

				$this->main_template = $template;

				// Note the type for template parts

				$this->type = basename($template, '.php');

			} else {

				// This is a part template (e.g. templates/sidebar.php)

				array_unshift($templates, $template); // Default to whatever template provided

				// If type is not the default (index), add a more specific template part

				if ($this->type !== 'index') {
				  $str = substr($template, 0, -4);
				  array_unshift($templates, sprintf($str . '-%s.php', $this->type));
				}

			}

			// Apply filters to templates

			$templates = apply_filters('sprout_locate', $templates);
			$templates = apply_filters('sprout_locate_' . $this->type, $templates);

			// Return first found template

			return locate_template($templates);
			
		}

		/**
		 * Get list of possible bases, arranged from most specific to least specific
		 * @param string $template Main WordPress template
		 * @return string $template
		 */
		public function get_bases($template) {

			// Initialize variables

			$template = preg_replace('|[\w\W]+/themes/[^/]+/|', '', $template); // Strip theme directory
			$bases = array();

			// Add most specific base

			array_push($bases, 'base-' . $template);

			// If template is a specific type, add the general type (base-single-cpt.php => base-single.php)

			if (!in_array($template, array('front-page.php', 'comments-popup.php')) && strpos($template, '-') !== false) {

				array_push($bases, 'base-' . substr($template, 0, strpos($template, '-')));

			}

			// Add the root base

			array_push($bases, 'base.php');

			// Apply filters to bases

			$bases = apply_filters('sprout_get_bases', $bases);

			return $bases;

		}

		/**
		 * Renders template optionally specific to post/page type, e.g. sidebar-single.php, sidebar-page.php
		 * @param string $template Template part to locate
		 * @param string $type
		 */
		public function render($template, $type) {

			include $this->locate($template, $type);

		}

	}

	Sprout::add_module('Sprout_Templates', 'templates');

}

?>