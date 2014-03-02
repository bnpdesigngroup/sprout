<?php

if (!class_exists('Sprout_Head')) {

	class Sprout_Head extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_action('wp_loaded', array($this, 'cleanup'));

			add_filter('the_generator', '__return_false');
			add_filter('language_attributes', array($this, 'language_attributes'));
			add_filter('wp_title', array($this, 'wp_title'), 10);
			add_filter('style_loader_tag', array($this, 'style_tag'));


		}

		/**
		 * Clean up wp_head()
		 *
		 * Remove unnecessary <link>'s
		 * Remove inline CSS used by Recent Comments widget
		 * Remove inline CSS used by posts with galleries
		 * Remove self-closing tag and change ''s to "'s on rel_canonical()
		 */
		public function cleanup() {

			global $wp_widget_factory;

			// Originally from http://wpengineer.com/1438/wordpress-header/
			remove_action('wp_head', 'feed_links', 2);
			remove_action('wp_head', 'feed_links_extra', 3);
			remove_action('wp_head', 'rsd_link');
			remove_action('wp_head', 'wlwmanifest_link');
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
			remove_action('wp_head', 'wp_generator');
			remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
			remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));


			if (!class_exists('WPSEO_Frontend')) {
				remove_action('wp_head', 'rel_canonical');
				add_action('wp_head', array($this, 'rel_canonical'));
			}

		}

		public function rel_canonical() {

			global $wp_the_query;

			if (!is_singular()) {
				return;
			}

			if (!$id = $wp_the_query->get_queried_object_id()) {
				return;
			}

			$link = get_permalink($id);
			echo "\t<link rel=\"canonical\" href=\"$link\">\n";	

		}

		/**
		 * Clean up language_attributes() used in <html> tag
		 *
		 * Change lang="en-US" to lang="en"
		 * Remove dir="ltr"
		 */
		public function language_attributes() {

			$attributes = array();
			$output = '';

			if (function_exists('is_rtl')) {
				if (is_rtl() == 'rtl') {
					$attributes[] = 'dir="rtl"';
				}
			}

			$lang = get_bloginfo('language');

			if ($lang && $lang !== 'en-US') {
				$attributes[] = "lang=\"$lang\"";
			} else {
				$attributes[] = 'lang="en"';
			}

			$output = implode(' ', $attributes);
			$output = apply_filters('sprout_language_attributes', $output);

			return $output;

		}

		/**
		 * Manage output of wp_title()
		 */
		public function wp_title($title) {

			if (is_feed()) {
				return $title;
			}

			$title .= get_bloginfo('name');

			return $title;

		}

		/**
		 * Clean up output of stylesheet <link> tags
		 */
		public function style_tag($input) {

			preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);

			// Only display media if it is meaningful
			
			$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

			return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
		}

	}

	Sprout::add_module('Sprout_Head', 'head');

}

?>