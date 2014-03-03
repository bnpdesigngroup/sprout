<?php

// Start Sprout after all files loaded

add_action('after_setup_theme', 'sprout_load', 1);

// Load constants

require dirname(__FILE__) . '/constants.php';

// Load TGM Plugin Activation (dependancy)

require dirname(__FILE__) . '/includes/tgm-plugin-activation/class-tgm-plugin-activation.php';

// Load OptionTree (dependancy)

require dirname(__FILE__) . '/includes/option-tree/ot-loader.php';

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

/**
 * Insert one or more values at offset in array
 * @param array The input array
 * @param int The offset to insert at
 * @param mixed One or more values to insert
 * @return int New number of elements in array
 */
function array_insert(array &$array, $offset) {

	$values = array_slice(func_get_args(), 2);

	$arrA = array_slice($array, 0, $offset);
	$arrB = array_slice($array, $offset);
	$array = array_merge($arrA, $values, $arrB);

	return count($array);

}

/**
 * Remove a value from the array
 * @param array The input array
 * @param int The offset to remove
 * @return int New number of elements in array
 */
function array_remove(array &$array, $offset) {

	unset($array[$offset]);

	$array = array_merge($array);

	return count($array);

}

/**
 * Searches the array for a given value in a given key and returns corresponding key if successful
 * @param string Key to search in each element
 * @param mixed The searched value
 * @param array The array
 * @param strict Check type of needle as well as value
 * @return mixed Key of first matched element on success, false otherwise
 */
function array_search_by_key($key, $needle, array $haystack, $strict = false) {

	foreach ($haystack as $k => $v) {

		if (is_array($v) && isset($v[$key])) {

			if ($v[$key] === $needle || (!$strict && $v[$key] == $needle)) {

				return $k;

			}

		}

	}

	return false;

}

?>