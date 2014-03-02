<?php

if (!class_exists('Sprout_Options')) {

	class Sprout_Options extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			// Initialize variables

			$sprout = Sprout::get_instance();

			add_filter('ot_show_pages', '__return_false');
			add_filter('ot_show_new_layout', '__return_false');
			add_filter('ot_theme_mode', '__return_true');

			add_filter('ot_radio_images', array($this, 'radio_images'), 1);
			add_filter('css_option_file_path', array($this, 'get_generated_css_path'), 1);

			add_filter('ot_recognized_sidebars', array($sprout->sidebars, 'get_selectable_sidebars'), 1);
			add_filter('sprout_get_selectable_sidebars', array($this, 'selectable_sidebars'), 1);

			// Setting controls

			add_action('admin_init', array($this, 'register_theme_options'), 1);
			add_action('admin_init', array($this, 'register_meta_boxes'), 1);

			// Post-save operations

			add_action('ot_after_theme_options_save', array($this, 'after_save'), 1);

			// Activation / deactivation

			add_action('after_switch_theme', array($this, 'activation'));
			add_action('switch_theme', array($this, 'deactivation'));

		}

		/**
		 * Get option
		 * @param string $option_id ID of option to retrieve
		 * @param mixed $default Default if option doesn't exist
		 * @return mixed Option value
		 */
		public function get_option($option_id, $default = NULL) {

			return ot_get_option($option_id, $default);

		}

		/**
		 * Register theme options page
		 */
		public function register_theme_options() {

			// Get saved settings

			$saved_settings = get_option( 'option_tree_settings', array() );
			
			// Add options for each post type under layouts

			$custom_post_types = get_post_types(array(
				'public' => true,
			), 'objects');

			$post_type_options = array();

			foreach ($custom_post_types as $key => $post_type) {

				$post_type_options[] = array(
					'id'          => $key . '_layout',
					'label'       => $post_type->labels->singular_name . ' Layout',
					'desc'        => 'Choose the default layout for ' . strtolower($post_type->labels->name),
					'std'         => '',
					'type'        => 'radio-image',
					'section'     => 'layout',
					'rows'        => '',
					'post_type'   => '',
					'taxonomy'    => '',
					'min_max_step'=> '',
					'class'       => '',
				);

				$post_type_options[] = array(
					'id'          => $key . '_sidebar',
					'label'       => $post_type->labels->singular_name . ' Sidebar',
					'desc'        => 'Choose the default sidebar for ' . strtolower($post_type->labels->name),
					'std'         => '',
					'type'        => 'sidebar_select',
					'section'     => 'layout',
					'rows'        => '',
					'post_type'   => '',
					'taxonomy'    => '',
					'min_max_step'=> '',
					'class'       => '',
				);

			}

			// Settings to show on theme options page

			$custom_settings = array( 
				'contextual_help' => array( 
					'content'       => array( 
						array(
							'id'        => 'general_help',
							'title'     => 'General',
							'content'   => '<p>Help content goes here!</p>'
						)
					),
					'sidebar'       => '<p>Sidebar content goes here!</p>'
				),
				'sections'        => array( 
					array(
						'id'          => 'general',
						'title'       => 'General'
					),
					array(
						'id'          => 'layout',
						'title'       => 'Layout'
					),
					array(
						'id'          => 'style',
						'title'       => 'Style'
					),
					array(
						'id' => 'social_media',
						'title' => 'Social Media'
					),
				),
				'settings'        => array( 
					array(
						'id'          => 'logo',
						'label'       => 'Logo',
						'desc'        => '',
						'std'         => '',
						'type'        => 'upload',
						'section'     => 'general',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'retina_logo',
						'label'       => 'Retina Logo',
						'desc'        => '',
						'std'         => '',
						'type'        => 'upload',
						'section'     => 'general',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'favicon',
						'label'       => 'Fav Icon',
						'desc'        => '',
						'std'         => '',
						'type'        => 'upload',
						'section'     => 'general',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'header_tagline',
						'label'       => 'Tagline',
						'desc'        => '',
						'std'         => '',
						'type'        => 'textarea',
						'section'     => 'general',
						'rows'        => '6',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'header_contact',
						'label'       => 'Contact Information',
						'desc'        => '',
						'std'         => '',
						'type'        => 'textarea',
						'section'     => 'general',
						'rows'        => '6',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'notice',
						'label'       => 'Notice',
						'desc'        => '',
						'std'         => '',
						'type'        => 'textarea',
						'section'     => 'general',
						'rows'        => '6',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'root_relative',
						'label'       => 'Root Relative URLs (Requires permalinks)',
						'desc'        => 'Would you like urls to be root relative? ("' . get_home_url() . '/path/to/file" => "/path/to/file")',
						'std'         => '',
						'type'        => 'checkbox',
						'section'     => 'general',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
						'choices'     => array(
							array(
								'value'   =>  true,
								'label'   =>  'Yes'
							)
						), 
					),
					array(
						'id'          => 'short_urls',
						'label'       => 'Theme Shortcuts (Requires permalinks)',
						'desc'        => 'Would you like urls to be shortened? ("' . THEME_URI . '/assets" => "' . get_site_url() . '/assets")',
						'std'         => '',
						'type'        => 'checkbox',
						'section'     => 'general',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
						'choices'     => array(
							array(
								'value'   =>  true,
								'label'   =>  'Yes'
							)
						), 
					),
					array(
						'id'          => 'nice_search',
						'label'       => 'Nice Search (Requires permalinks)',
						'desc'        => 'Would you like search requests cleaned up? ("' . get_site_url() . '/?s=my%20search%20term" => "' . get_site_url() . '/search/my+search+term")',
						'std'         => '',
						'type'        => 'checkbox',
						'section'     => 'general',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
						'choices'     => array(
							array(
								'value'   =>  true,
								'label'   =>  'Yes'
							)
						), 
					),
					array(
						'id'          => 'global_layout',
						'label'       => 'Default Layout',
						'desc'        => 'Choose the default layout',
						'std'         => 'right',
						'type'        => 'radio-image',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
						'choices'     => array(
							array(
								'value'       => 'full-width',
								'label'       => 'Full Width (no sidebar)',
								'src'         => get_template_directory_uri() . '/assets/img/options/layout/full-width.png'
							),
							array(
								'value'       => 'sidebar-left',
								'label'       => 'Left Sidebar',
								'src'         => get_template_directory_uri() . '/assets/img/options/layout/left-sidebar.png'
							),
							array(
								'value'       => 'sidebar-right',
								'label'       => 'Right Sidebar',
								'src'         => get_template_directory_uri() . '/assets/img/options/layout/right-sidebar.png'
							)
						),
					),
					array(
						'id'          => 'main_size',
						'label'       => 'Content-Sidebar Proportions',
						'desc'        => 'Choose the content size',
						'std'         => '9',
						'type'        => 'numeric_slider',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '1,11',
						'class'       => '',
					),
					array(
						'id'          => 'sidebars',
						'label'       => 'Custom Sidebars',
						'desc'        => 'Enter custom sidebars here, separated with commas',
						'std'         => '',
						'type'        => 'textarea_simple',
						'section'     => 'layout',
						'rows'        => '4',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',				
					),
					array(
						'id'          => 'home_layout',
						'label'       => 'Home Layout',
						'desc'        => 'Choose the default layout for the home page',
						'std'         => '',
						'type'        => 'radio-image',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'home_sidebar',
						'label'       => 'Home Sidebar',
						'desc'        => 'Choose the default sidebar for the home page',
						'std'         => '',
						'type'        => 'sidebar_select',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'blog_layout',
						'label'       => 'Blog Layout',
						'desc'        => 'Choose the default layout for the blog page',
						'std'         => '',
						'type'        => 'radio-image',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'blog_sidebar',
						'label'       => 'Blog Sidebar',
						'desc'        => 'Choose the default sidebar for the blog page',
						'std'         => '',
						'type'        => 'sidebar_select',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'archive_layout',
						'label'       => 'Archive Layout',
						'desc'        => 'Choose the default layout for archives',
						'std'         => '',
						'type'        => 'radio-image',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'archive_sidebar',
						'label'       => 'Archive Sidebar',
						'desc'        => 'Choose the default sidebar for the archives',
						'std'         => '',
						'type'        => 'sidebar_select',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'search_layout',
						'label'       => 'Search Layout',
						'desc'        => 'Choose the default layout for search page',
						'std'         => '',
						'type'        => 'radio-image',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'search_sidebar',
						'label'       => 'Search Sidebar',
						'desc'        => 'Choose the default sidebar for the search page',
						'std'         => '',
						'type'        => 'sidebar_select',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => '404_layout',
						'label'       => '404 Page Layout',
						'desc'        => 'Choose the default layout for the "Not Found" page',
						'std'         => '',
						'type'        => 'radio-image',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => '404_sidebar',
						'label'       => '404 Page Sidebar',
						'desc'        => 'Choose the default sidebar for the "Not Found" page',
						'std'         => '',
						'type'        => 'sidebar_select',
						'section'     => 'layout',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'background_html',
						'label'       => 'Html Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_body',
						'label'       => 'Body Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_header',
						'label'       => 'Header Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_inner_header',
						'label'       => 'Inner Header Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_content',
						'label'       => 'Content Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_inner_content',
						'label'       => 'Inner Content Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_footer',
						'label'       => 'Footer Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'background_inner_footer',
						'label'       => 'Inner Footer Background',
						'desc'        => '',
						'std'         => '',
						'type'        => 'background',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => ''
					),
					array(
						'id'          => 'css',
						'label'       => 'Custom CSS',
						'desc'        => '',
						'std'         => '',
						'type'        => 'css',
						'section'     => 'style',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
					),
					array(
						'id'          => 'social_links',
						'label'       => 'Social Links',
						'desc'        => '',
						'std'         => '',
						'type'        => 'list-item',
						'section'     => 'social_media',
						'rows'        => '',
						'post_type'   => '',
						'taxonomy'    => '',
						'min_max_step'=> '',
						'class'       => '',
						'settings'    => array( 
							array(
								'id'          => 'url',
								'label'       => 'Link',
								'desc'        => '',
								'std'         => '',
								'type'        => 'text',
								'rows'        => '',
								'post_type'   => '',
								'taxonomy'    => '',
								'min_max_step'=> '',
								'class'       => ''
							),
						),
					),

				)
			);

			// Add in custom post type layout options
			
			$custom_settings['settings'] = array_merge($custom_settings['settings'], $post_type_options);

			// Allow settings to be filtered before saving
			
			$custom_settings = apply_filters('sprout_theme_options', $custom_settings);
			
			// If settings don't match then update the database

			if ( $saved_settings !== $custom_settings ) {
				update_option( 'option_tree_settings', $custom_settings ); 
			}

		}

		/**
		 * Register theme post metaboxes
		 */
		public function register_meta_boxes() {

			$meta_boxes = array(
				array(
					'id'        => 'sprout_options',
					'title'     => 'Display Options',
					'desc'      => '',
					'pages'     => get_post_types(),
					'context'   => 'normal',
					'priority'  => 'high',
					'fields'    => array(
						array(
							'id'          => 'single_layout',
							'label'       => 'Layout',
							'desc'        => 'Choose a layout for this page',
							'std'         => '',
							'type'        => 'radio-image',
							'section'     => 'general',
							'rows'        => '',
							'post_type'   => '',
							'taxonomy'    => '',
							'min_max_step'=> '',
							'class'       => '',
						),
						array(
							'id'          => 'single_sidebar',
							'label'       => 'Sidebar',
							'desc'        => 'Choose a sidebar for this page',
							'std'         => '',
							'type'        => 'sidebar_select',
							'section'     => 'general',
							'rows'        => '',
							'post_type'   => '',
							'taxonomy'    => '',
							'min_max_step'=> '',
							'class'       => '',
						),
					)
				),
			);

			$meta_boxes = apply_filters('sprout_meta_boxes', $meta_boxes);

			// Register each meta box

			foreach($meta_boxes as $meta_box) {

				ot_register_meta_box($meta_box);

			}

		}

		public function radio_images($radio_images) {

			$radio_images = array(
				array(
					'value'       => '',
					'label'       => 'Default',
					'src'         => get_template_directory_uri() . '/assets/img/options/layout/default.png',
				),
				array(
					'value'       => 'full-width',
					'label'       => 'Full Width (no sidebar)',
					'src'         => get_template_directory_uri() . '/assets/img/options/layout/full-width.png'
				),
				array(
					'value'       => 'sidebar-left',
					'label'       => 'Left Sidebar',
					'src'         => get_template_directory_uri() . '/assets/img/options/layout/left-sidebar.png'
				),
				array(
					'value'       => 'sidebar-right',
					'label'       => 'Right Sidebar',
					'src'         => get_template_directory_uri() . '/assets/img/options/layout/right-sidebar.png'
				),
			);

			return $radio_images;

		}

		/**
		 * Transform sidebars into id => name pairs
		 */
		public function selectable_sidebars($sidebars) {

			$ot_sidebars = array();

			foreach ($sidebars as $sidebar) {

				$ot_sidebars[$sidebar['id']] = $sidebar['name'];

			}

			return $ot_sidebars;

		}

		/**
		 * Store generated css in main assets
		 * Avoids conflict of paths when switching themes
		 */
		public function get_generated_css_path($path) {

			return get_template_directory() . '/assets/css/dynamic.css';

		}

		/**
		 * After theme options saved
		 */
		public function after_save($options) {

			// Add auto-generated css

			ot_insert_css_with_markers('generated', '
				html { {{background_html}} } 
				body { {{background_body}} } 
				header[role="banner"] { {{background_header}} } 
				#inner_header { {{background_inner_header}} } 
				[role="document"] { {{background_content}} }
				#inner_content { {{background_inner_content}} } 
				footer[role="contentinfo"] { {{background_footer}} }
				#inner_footer { {{background_inner_footer}} }'); 
			
			// Flush rewrite

			flush_rewrite_rules();

			do_action('sprout_after_theme_options_save', $options);

		}

		/**
		 * Run when theme is activated
		 */
		public function activation() {

		}

		/**
		 * Run when theme is deactivated
		 */
		public function deactivation() {

		}

	}

	Sprout::add_module('Sprout_Options', 'options');

}

?>