<?php

if (!class_exists('Sprout_Admin')) {

	class Sprout_Admin extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			add_action('admin_init', array($this, 'remove_dashboard_widgets'));

		}

		/**
		 * Remove unnecessary dashboard widgets
		 *
		 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
		 */
		public function remove_dashboard_widgets() {
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
			remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
			remove_meta_box('dashboard_primary', 'dashboard', 'normal');
			remove_meta_box('dashboard_secondary', 'dashboard', 'normal');	
		}

	}

	Sprout::add_module('Sprout_Admin');

}

?>