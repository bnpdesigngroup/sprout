<?php

if (!class_exists('Sprout_Urls')) {

	class Sprout_Urls extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			// Don't rewrite root relative urls until options available
			// (plugins may ask for urls before OptionTree is loaded)

			add_action('init', array($this, 'hook_urls'));

			add_action('generate_rewrite_rules', array($this, 'add_rewrites'));

		}

		/**
		 * Start rewriting urls
		 */
		public function hook_urls() {

			$tags = array(
				'plugins_url',
				'stylesheet_directory_uri',
				'template_directory_uri',
				'bloginfo_url',
				'the_permalink',
				'wp_list_pages',
				'wp_list_categories',
				'sprout_wp_nav_menu_item',
				'the_content_more_link',
				'the_tags',
				'get_pagenum_link',
				'get_comment_link',
				'month_link',
				'day_link',
				'year_link',
				'tag_link',
				'the_author_posts_link',
				'script_loader_src',
				'style_loader_src'
			);

			add_filters($tags, array($this, 'get_url'));

		}

		/**
		 * Add URL shortcuts
		 */
		public function add_rewrites(WP_Rewrite $wp_rewrite) {

			$sprout_new_non_wp_rules = array(
				'assets/(.*)'          => substr(HOME_RELATIVE_THEME_URI . '/assets/$1', 1),
				'plugins/(.*)'         => substr(HOME_RELATIVE_PLUGIN_URI . '/$1', 1),
			);

			if (is_child_theme()) {
				$sprout_new_non_wp_rules = array_merge($sprout_new_non_wp_rules, array(
					'assets-child/(.*)' => substr(HOME_RELATIVE_CHILD_THEME_URI . '/assets/$1', 1),
				));
			}

			$sprout_new_non_wp_rules = apply_filters('sprout_rewrite_rules', $sprout_new_non_wp_rules);

			$wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $sprout_new_non_wp_rules);

		}

		/**
		 * Shorten urls and make root relative
		 */
		public function get_url($url) {

			// Initialize variables

			$sprout = Sprout::get_instance();
			$root_relative = $sprout->options->get_option('root_relative');
			$short_urls = $sprout->options->get_option('short_urls');

			// Ensure url is internal before modifying it

			preg_match('|https?://([^/]+)|i', $url, $matches);

			// If url is without domain or domain is the same as site, then url is internal

			if (!isset($matches[1]) || $matches[1] === $_SERVER['SERVER_NAME']) {

				// If url can be shortened, shorten it

				if ($short_urls && preg_match('|themes/[^/]+/assets|', $url)) {

					$patterns = array(
						'|' . HOME_RELATIVE_THEME_URI . '/assets|' => '/assets',
						'|' . HOME_RELATIVE_PLUGIN_URI . '|' => '/plugins',
					);

					if (is_child_theme()) {
						$patterns = array_merge($patterns, array(
							'|' . HOME_RELATIVE_CHILD_THEME_URI . '/assets|' => '/assets-child',
						));
					}

					$url = preg_replace(array_keys($patterns), array_values($patterns), $url);

				}

				// If url can be made root relative, make it so

				if ($root_relative) {

					$root_dir = preg_replace('|(?>https?://)?[^/]+(/.*)$|', '$1', get_site_url());

					$url = wp_make_link_relative($url);

					// Collapse duplicated root dirs (due to WP incorrectly prepending root path)

					$url = str_replace($root_dir . $root_dir, $root_dir, $url);

				}

			}

			return $url;

		}
		
	}

	Sprout::add_module('Sprout_Urls', 'urls');

}

?>