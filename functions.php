<?php

// Start Sprout after all files loaded

add_action('after_setup_theme', 'sprout_load', 1);

// Load constants

require dirname(__FILE__) . '/constants.php';

// Load OptionTree (dependancy)

require dirname(__FILE__) . '/option-tree/ot-loader.php';

// Load core

require dirname(__FILE__) . '/classes/class-sprout.php';

function sprout_load() {

	$GLOBALS['sprout'] = Sprout::get_instance();

	$GLOBALS['sprout']->init();

}

// Utility functions

function add_filters(array $tags, $callback) {

	foreach ($tags as $tag) {

		add_filter($tag, $callback);

	}

}

?>