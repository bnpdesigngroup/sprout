<?php

if (!class_exists('Sprout_Title')) {

	class Sprout_Title extends Sprout_Module {
		private $title;

		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

		}

		public function get_title() {

			// If title is not calculated, calculate it

			if (is_null($this->title)) {

				$title = false;

				if (is_home()) {
					if (get_option('page_for_posts', true)) {
						$title = get_the_title(get_option('page_for_posts', true));
					} else {
						$title = __('Latest Posts', 'sprout');
					}
				} elseif (is_archive()) {
					$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
					if ($term) {
						$title = $term->name;
					} elseif (is_post_type_archive()) {
						$title = get_queried_object()->labels->name;
					} elseif (is_day()) {
						$title = sprintf(__('Daily Archives: %s', 'sprout'), get_the_date());
					} elseif (is_month()) {
						$title = sprintf(__('Monthly Archives: %s', 'sprout'), get_the_date('F Y'));
					} elseif (is_year()) {
						$title = sprintf(__('Yearly Archives: %s', 'sprout'), get_the_date('Y'));
					} elseif (is_author()) {
						$author = get_queried_object();
						$title = sprintf(__('Author Archives: %s', 'sprout'), $author->display_name);
					} else {
						$title = single_cat_title('', false);
					}
				} elseif (is_search()) {
					$title = sprintf(__('Search Results for &#8220;%s&#8221;', 'sprout'), get_search_query());
				} elseif (is_404()) {
					$title = __('Not Found', 'sprout');
				} else {
					$title = get_the_title();
				}

				$this->title = $title;

			}

			return apply_filters('sprout_get_title', $this->title);;

		}

	}

	Sprout::add_module('Sprout_Title', 'title');

}

?>