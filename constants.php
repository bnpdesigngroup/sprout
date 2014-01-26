<?php

define('THEME_URI', get_template_directory_uri());
define('THEME_PATH', get_template_directory());
define('HOME_RELATIVE_THEME_URI', str_replace(get_home_url(), '', THEME_URI));
define('HOME_RELATIVE_CONTENT_URI', str_replace(get_home_url(), '', content_url()));
define('HOME_RELATIVE_PLUGIN_URI', str_replace(get_home_url(), '', plugins_url()));
define('THEME_SLUG', basename(THEME_URI));

if (is_child_theme()) {
	define('CHILD_THEME_URI', get_stylesheet_directory_uri());
	define('CHILD_THEME_PATH', get_stylesheet_directory());
	define('HOME_RELATIVE_CHILD_THEME_URI', str_replace(get_home_url(), '', CHILD_THEME_URI));
	define('CHILD_THEME_SLUG', basename(CHILD_THEME_URI));
}

?>