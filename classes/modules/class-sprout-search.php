<?php

if (!class_exists('Sprout_Search')) {

	class Sprout_Search extends Sprout_Module {

		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			$sprout = Sprout::get_instance();

			// Get option "nice_search"

			add_filter('request', array($this, 'request'));
			add_filter('get_search_form', array($this, 'get_search_form'));
			add_action('template_redirect', array($this, 'nice_search_redirect'));

		}


		/**
		 * Fix for empty search queries redirecting to home page
		 *
		 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
		 * @link http://core.trac.wordpress.org/ticket/11330
		 */		
		public function request($query_vars) {

			if (isset($_GET['s']) && empty($_GET['s'])) {

				$query_vars['s'] = ' ';

			}

			return $query_vars;

		}

		/**
		 * Override WordPress's searchform with our searchform.php
		 * from the templates/parts directory. 
		 * Requires WordPress 3.6+
		 */
		public function get_search_form($form) {

			ob_start();
			locate_template('templates/searchform.php', true, false); // Echo, not just locate
			$form = ob_get_clean();

			return $form;

		}

		/**
		 * Redirects search results from /?s=query to /search/query/, converts %20 to +
		 *
		 * @link http://txfx.net/wordpress-plugins/nice-search/
		 */
		public function nice_search_redirect() {

			global $wp_rewrite;

			$sprout = Sprout::get_instance();

			if (!$sprout->options->get_option('nice_search')) {

				return;

			}

			if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
				return;
			}

			$search_base = $wp_rewrite->search_base;

			if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {

				wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
				exit();

			}

		}

	}

	Sprout::add_module('Sprout_Search', 'search');

}

?>