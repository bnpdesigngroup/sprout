<?php

if (!class_exists('Sprout_Routes')) {

	class Sprout_Routes extends Sprout_Module {
		
		protected function __construct() {

		}

		public function init() {

			$this->register_hooks();

		}

		public function register_hooks() {

			// Override WP formatter

			remove_filter('the_content', 'wpautop');
			remove_filter('the_content', 'wptexturize');
			add_filter('the_content', array ($this, 'format'), 99);

			// Grid shortcodes

			add_shortcode('row', array($this, 'grid_shortcode'));
			add_shortcode('row-nested', array($this, 'grid_shortcode'));
			$this->add_shortcode_permutations(
				array ('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve'), 
				array ('whole', 'half', 'third', 'fourth', 'fifth', 'sixth', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelvth',
					'halfs', 'thirds', 'fourths', 'fifths', 'sixths', 'eighths', 'ninths', 'tenths', 'elevenths', 'twelvths'),
				array($this, 'grid_shortcode')
			);

			// Quote shortcode

			add_shortcode('quote', array($this, 'quote_shortcode'));
			add_shortcode('blockquote', array($this, 'quote_shortcode'));

			// Icon shortcode

			add_shortcode('icon', array($this, 'icon_shortcode'));

			// Link shortcode

			add_shortcode('link', array($this, 'link_shortcode'));

			// Button shortcodes

			add_shortcode('button', array($this, 'button_shortcode'));
			add_shortcode('button-group', array($this, 'button_shortcode'));

			// Embed shortcodes

			add_shortcode('youtube', array($this, 'embed_shortcode'));

		}

		/**
		 * Format content (internal)
		 * @param string $content Content to format
		 * @return string Formatted content
		 */
		private function format($content) {

			$new_content = '';
			$pattern_tag = 'noautop|row|list';
			$pattern_full = '/(\[(?>' . $pattern_tag . ')\].*?\[\/(?>' . $pattern_tag . ')\])/is';
			$pattern_contents = '/\[(?>' . $pattern_tag . ')\](.*?)\[\/(?>' . $pattern_tag . ')\]/is';
			$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

			foreach ($pieces as $piece) {

				if (preg_match($pattern_contents, $piece, $matches)) {

					$new_content .= $matches[1];

				} else {

					$new_content .= wptexturize(wpautop($piece));

				}

			}

			return $new_content;

		}

		/**
		 * Add all permutations of $arra and $arrb
		 * @param array $arra
		 * @param array $arrb
		 * @param mixed $callback
		 */
		public function add_shortcode_permutations($arra, $arrb, $callback) {

			foreach ($arra as $parta) {

				foreach ($arrb as $partb) {

					add_shortcode($parta . '-' . $partb, $callback);

				}

			}

		}

		/**
		 * Parse attributes for layout classes (internal)
		 * @param array $atts Attributes to parse
		 * @return string CSS class
		 */
		private function get_layout_classes($atts) {

			$options = array (
				'shift' => array (

				),
				'pad' => array (
					'none' => 'no-pad',
					'half' => 'half-pad',
					'single' => 'pad',
					'double' => 'double-pad',
					'triple' => 'triple-pad',
				),
				'gap' => array (
					'none' => 'no-gap',
					'half' => 'half-gap',
					'single' => 'gap',
					'double' => 'double-gap',
					'triple' => 'triple-gap',
				),
			);

			$class = array ();

			// Apply shifts

			if (isset($atts['shift'])) {

				$class[] = $attrs['shift'];

			}

			// Apply layout properties for each breakpoint

			$breakpoints = array (
				null,
				'mobile',
				'small-tablet',
				'large-tablet',
				'desktop',
				'large-desktop',
			);

			foreach ($breakpoints as $bp) {

				// Apply adapters

				if (!is_null($bp) && isset($atts['size_' . str_replace('-', '_', $bp)])) {

					$class[] = strtolower(htmlspecialchars($atts['cols_' . str_replace('-', '_', $bp)])) . '-up-' . str_replace('_', '-', $bp);

				}

				// Apply for each direction

				$directions = array (
					null,
					'top',
					'right',
					'bottom',
					'left',
				);

				foreach($directions as $dir) {

					$attr_suffix = (!is_null($dir) ? '_' . $dir : '') . (!is_null($bp) ? '_' . str_replace('-', '_', $bp) : '');
					$class_suffix = (!is_null($dir) ? '-' . $dir : '') . (!is_null($bp) ? '-' . str_replace('_', '-', $bp) : '');

					// Padding

					if (isset($atts['pad' . $attr_suffix]) && isset($options['pad'][strtolower($atts['pad' . $attr_suffix])])) {

						$class[] = $options['pad'][strtolower($atts['pad' . $attr_suffix])] . (is_null($dir) ? 'ded' : '') . $class_suffix;

					}

					// Gaps

					if (isset($atts['gap' . $attr_suffix]) && isset($options['gap'][strtolower($atts['gap' . $attr_suffix])])) {

						$class[] = $options['gap'][strtolower($atts['gap' . $attr_suffix])] . (is_null($dir) ? 'ped' : '') . $class_suffix;

					}

				}

			}

			// Add arbitrary classes

			if (isset($atts['class']) && preg_match('/^[^"]*$/', $atts['class'])) {

				$class[] = strtolower($atts['class']);

			}

			return implode(' ', $class);

		}

		/**
		 * Outputs rows or columns
		 */
		public function grid_shortcode($atts, $content, $tag) {

			if (substr($tag, 0, 3) == 'row') {

				return '<div class="row">' . do_shortcode($content) . '</div>';

			} else {

				$options = array (
				);

				$class = array ();

				// Set base col class

				$class = explode('-', $tag);

				// Apply minimum breakpoint

				if (isset($atts['break'])) {

					$class[] = $atts['break'];

				}

				// Add positional/layout classes

				$class[] = $this->get_layout_class($atts);

				return '<div class="' . implode(' ', $class) . '">' . do_shortcode($content) . '</div>';

			}

		}

		/**
		 * Produce list (ordered or unordered)
		 */
		public function list_shortcode($atts, $content, $tag) {

			switch (strtolower($tag)) {

				case 'list':

					$options = array (
						'type' => array (
							'ordered' => 'ol',
							'unordered' => 'ul',
						),
						'style' => array (
							'unstyled' => 'unstyled',
							'list' => 'list',
						),
					);

					$tag = 'ul';
					$class = array ();

					// Set list type

					if (isset($atts['type']) && isset($options['type'][strtolower($atts['type'])])) {

						$tag = $options['type'][strtolower($atts['type'])];

					}

					// Set list style

					if (isset($atts['style']) && isset($options['style'][strtolower($atts['style'])])) {

						$class[] = $options['style'][strtolower($atts['style'])];

					}

					// Add positional/layout classes + arbitrary classes

					$class[] = $this->get_layout_class($atts);

					// Turn classes into html attribute

					$class = trim(implode(' ', $class));

					if ($class != '') {

						$class = ' class="' . $class . '"';

					}

					return '<' . $tag . $class . '>' . do_shortcode($content) . '</' . $tag . '>';

				case 'item':

					$options = array (
						'type' => array (
							'info' => 'info',
							'success' => 'success',
							'alert' => 'alert',
							'question' => 'question',
							'warning' => 'warning',
							'error' => 'error',
						),
						'text' => '/^["]*$/',
					);

					$class = array ();

					// Set item type

					if (isset($atts['type']) && isset($options['type'][strtolower($atts['type'])])) {

						$class[] = $options['type'][strtolower($atts['type'])];

					}

					// Set item content

					if (isset($atts['text'])) {

						$content = $atts['text'];

					}

					// Add positional/layout classes + arbitrary classes

					$class[] = $this->get_layout_class($atts);

					// Turn classes into html attribute

					$class = trim(implode(' ', $class));

					if ($class != '') {

						$class = ' class="' . $class . '"';

					}

					return '<li' . $class . '>' . do_shortcode($content) . '</li>';


				default:

					return $content;

			}

		}

		/**
		 * Produce quote elements
		 */
		public function quote_shortcode($atts, $content, $tag) {

			// Get enclosing tag

			switch (strtolower($tag)) {

				case 'quote':

					$tag = 'q';

					break;

				case 'blockquote':

					$tag = 'blockquote';

					$content .= '<cite>' . $atts['author'] . '</cite>';

					break;

				default:

					return $content;

			}

			// Add positional/layout classes + arbitrary classes

			$class = $this->get_layout_class($atts);

			// Turn classes into html attribute

			if ($class != '') {

				$class = ' class="' . $class . '"';

			}

			return '<' . $tag . $class . '>' . do_shortcode($content) . '</' . $tag . '>';

		}

		public function icon_shortcode($atts) {

			$options = array (
				'type' => '/^[^"]*$/',
				'size' => array (
					'2x' => 'icon-2x',
					'3x' => 'icon-3x',
					'4x' => 'icon-4x',
				),
				'style' => '/^[^"]*$/'
			);

			$class = array ();

			// Set icon type

			if (isset($atts['type']) && preg_match($options['type'], $atts['type'])) {

				$class[] = 'icon-' . strtolower($atts['type']);

			}

			// Set icon size

			if (isset($atts['size']) && isset($options['size'][strtolower($atts['size'])])) {

				$class[] = $options['size'][strtolower($atts['size'])];

			}

			// Set icon style

			if (isset($atts['style']) && preg_match($options['style'], $atts['style'])) {

				$class[] = strtolower($atts['style']);

			}

			// Add positional/layout classes + arbitrary classes

			$class[] = $this->get_layout_class($atts);

			return '<i class="' . implode(' ', $class) . '"></i>';

		}

		/**
		 * Produce a link
		 */
		public function link_shortcode($atts, $content) {

			$options = array (
				'url' => '/^[^"]*$/',
				'rel' => '/^[^"]*$/',
				'target' => '/^[^"]*$/',
				'size' => array (
					'small' => 'small',
					'large' => 'large',
				),
				'color' => array (
					'red' => 'red',
					'orange' => 'orange',
					'yellow' => 'yellow',
					'green' => 'green',
					'teal' => 'turquoise',
					'turquoise' => 'turquoise',
					'blue' => 'blue',
					'purple' => 'purple',
					'pink' => 'pink',
					'asphalt' => 'asphalt',
					'charcoal' => 'charcoal',
					'white' => 'white',
					'black' => 'black',
				),
				'style' => '/^[^"]*$/',
				'text' => '/^["]*$/',
			);

			$class = array ();
			$href = '';
			$rel = '';
			$target = '';

			// Set link href

			if (isset($atts['url']) && preg_match($options['url'], $atts['url'])) {
				
				$href = ' href="' . preg_replace('/^~/', get_site_url(), $atts['url']) . '"';
			
			}

			// Set link rel

			if (isset($atts['rel']) && preg_match($options['rel'], $atts['rel'])) {

				$rel = ' rel="' . $atts['rel'] . '"';

			}

			// Set link target

			if (isset($atts['target']) && preg_match($options['target'], $atts['text'])) {

				$target = ' target="' . $atts['target'] . '"';

			}

			// Set link size

			if (isset($atts['size']) && isset($options['size'][strtolower($atts['size'])])) {

				$class[] = $options['size'][strtolower($atts['size'])];

			}

			// Set link color

			if (isset($atts['color']) && isset($options['color'][strtolower($atts['color'])])) {

				$class[] = $options['color'][strtolower($atts['color'])];

			}

			// Set link style

			if (isset($atts['style']) && preg_match($options['style'], $atts['style'])) {

				$class[] = strtolower($atts['style']);

			}

			// Add positional/layout classes + arbitrary classes

			$class[] = $this->get_layout_class($atts);

			// Turn class into html attribute

			$class = trim(implode(' ', $class));

			if ($class != '') {

				$class = ' class="' . $class . '"';

			}

			// Set link content

			if (isset($atts['text']) && preg_match($options['text'], $atts['text'])) {

				$content = $atts['text'];

			}

			return '<a' . $href . $rel . $target . $class . '>' . do_shortcode($content) . '</a>';

		}

		/**
		 * Produce a html button
		 */
		public function button_shortcode($atts, $content, $tag) {

			switch (strtolower($tag)) {

				case 'button': 

					// If link, defer to link shortcode

					if (isset($atts['url'])) {

						$link = sprout_link_shortcode($atts, $content);

						return str_replace('<a', '<a role="button"', $link);

					}

					$options = array (
						'type' => array (
							'info' => 'info',
							'success' => 'success',
							'alert' => 'alert',
							'question' => 'question',
							'warning' => 'warning',
							'error' => 'error',
						),
						'size' => array (
							'small' => 'small',
							'large' => 'large'
						),
						'color' => array (
							'red' => 'red',
							'orange' => 'orange',
							'yellow' => 'yellow',
							'green' => 'green',
							'teal' => 'turquoise',
							'turquoise' => 'turquoise',
							'blue' => 'blue',
							'purple' => 'purple',
							'pink' => 'pink',
							'asphalt' => 'asphalt',
							'charcoal' => 'charcoal',
							'white' => 'white',
							'black' => 'black',
						),
						'style' => '/^[^"]*$/',
						'state' => array (
							'active' => ' aria-selected="true"',
							'disabled' => ' disabled="disabled"',
						),
						'text' => '/^[^"]*$/',
					);

					$class = array ();
					$state = '';
					$href = '';

					// set button type

					if (isset($atts['type']) && isset($options['type'][strtolower($atts['type'])])) {

						$class[] = $options['type'][strtolower($atts['type'])];

					}

					// Set button size

					if (isset($atts['size']) && isset($options['size'][strtolower($atts['size'])])) {

						$class[] = $options['size'][strtolower($atts['size'])];

					}

					// Set button color

					if (isset($atts['color']) && isset($options['color'][strtolower($atts['color'])])) {

						$class[] = $options['color'][strtolower($atts['color'])];

					}

					// Set button style

					if (isset($atts['style']) && preg_match($options['style'], $atts['style'])) {

						$class[] = strtolower($atts['style']);

					}

					// Set button state

					if (isset($atts['state']) && isset($options['state'][strtolower($atts['state'])])) {

						$state = $options['state'][strtolower($atts['state'])];

					}

					// Add positional/layout classes + arbitrary classes

					$class[] = $this->get_layout_class($atts);

					// Turn classes into html attribute

					$class = trim(implode(' ', $class));

					if ($class != '') {

						$class = ' class="' . $class . '"';

					}

					// Set button content

					if (isset($atts['text'])) {

						$content = $atts['text'];

					}

					return '<button' . $class .  $state . '>' . do_shortcode($content) . '</button>';

				case 'button-group':

					// Process content shortcodes first

					$content = do_shortcode($content);

					// Wrap buttons in lists

					$content = str_replace(array ('<button', '</button>'), array ('<li><button', '</button></li>'), $content);

					// Add positional/layout classes + arbitrary classes

					$class = $this->get_layout_class($atts);

					// Turn classes into html attribute

					if ($class != '') {

						$class = ' ' . $class;

					}

					return '<ul class="button-group' . $class . '">' . $content . '</ul>'; 

				default:

					return $content;

			}

		}

		/**
		 * Produce a responsive embedded widget
		 */
		public function embed_shortcode($atts, $content, $tag) {

			$src = '';

			switch (strtolower($tag)) {

				case 'youtube':

					if (!isset($atts['id'])) {

						return false;

					}

					$src = '//www.youtube.com/embed/' . $atts['id'];

					break;

				default:

					return;

			}

			// Add positional/layout classes + arbitrary classes

			$class = $this->get_layout_class($atts);

			// Turn classes into html attribute

			if ($class != '') {

				$class = ' ' . $class;

			}

			return '<div class="embed ' . strtolower($tag) . $class . '"><iframe src="' . $src . '" frameborder="0" allowfullscreen></iframe></div>'; 

		}

	}

	Sprout::add_module('Sprout_Shortcodes', 'shortcodes');

}

?>