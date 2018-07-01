<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class for field functionality
 */
class ACFTC_Field {

	private $render_partial;

	private $nesting_level;
	private $indent_count;
	private $indent = '';
	private $quick_link_id = '';
	private $the_field_method = 'the_field';
	private $get_field_method = 'get_field';
	private $get_field_object_method = 'get_field_object';

	private $id = null; // only used if posts table and required for flexible layouts
	private $label;
	private $name;
	private $type;

	/**
	 * All unserialized field data to be used in partials for edge cases.
	 * Needs to be public to sort fields.
	 */
	public $settings;

	private $location;


	/**
	 * Constructor
	 *
	 * @param $nesting_level	int
	 * @param $indent_count		int
	 * @param $location			string
	 * @param $field_data_obj	object
	 * @param $field_id			int
	 */
	function __construct( $nesting_level = 0, $indent_count = 0 , $location = '', $field_data_obj = null, $field_id = null ) {

		$this->nesting_level = $nesting_level;
		$this->indent_count = $indent_count;

		$this->location = $location;

		// if location set to options page, add the options parameter
		if ($this->location == 'options_page') {
			$this->location = '\', \'option';

		// else set location to an empty string
		} else {
			$this->location = '';
		}

		// if field is nested
		if ( 0 < $this->nesting_level ) {

			// calc indent string
			$this->indent = $this->get_indent();

			// use ACF sub field methods instead
			$this->the_field_method = 'the_sub_field';
			$this->get_field_method = 'get_sub_field';
			$this->get_field_object_method = 'get_sub_field_object';

		}

		if ( "postmeta" == ACFTC_Core::$db_table ) {
			$this->construct_from_postmeta_table( $field_data_obj );
		} elseif ( "posts" == ACFTC_Core::$db_table ) {
			$this->construct_from_posts_table( $field_data_obj );
		}

		// variable name that is used in code rendered
		$this->var_name = $this->get_var_name( $this->name );

		// partial to use for rendering
		$this->render_partial = $this->get_render_partial();

	}

	// Set field properties using data from postmeta table
	private function construct_from_postmeta_table( $field_data_obj ) {

		if ( !empty( $field_data_obj ) ) {

			// unserialize meta values
			$this->settings = unserialize( $field_data_obj->meta_value );

			// to do : note absence of ID property here
			$this->label = $this->settings['label'];
			$this->name = $this->settings['name'];
			$this->type = $this->settings['type'];

			// if field is not nested
			if ( 0 == $this->nesting_level ) {

				// get quick link id
				$this->quick_link_id = $this->settings['key'];

			}

		}

	}


	// Set field properties using data from posts table
	private function construct_from_posts_table( $field_data_obj ) {

		if ( !empty( $field_data_obj ) ) {

			// unserialize content
			$this->settings = unserialize( $field_data_obj->post_content );

			$this->id = $field_data_obj->ID; // required for flexible layout
			$this->label = $field_data_obj->post_title;
			$this->name = $field_data_obj->post_excerpt;
			$this->type = $this->settings['type'];

			// if field is not nested
			if ( 0 == $this->nesting_level ) {

				// get quick link id
				$this->quick_link_id = $this->id;

			}

		}

	}


	// Get indent string for nested fields
	private function get_indent() {

		$indent = '';

		for ( $i = $this->indent_count; $i > 0 ; $i-- ) {
			$indent .= '	';
		}

		return $indent;

	}

	// Get the variable name
		private function get_var_name( $name ) {

			// Replace any hyphens with underscores
			$var_name = str_replace('-', '_', $name);

			// Replace any other special chars with underscores
			$var_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $var_name);

			// Replace multiple underscores with single
			$var_name = preg_replace('/_+/', '_', $var_name);

			return $var_name;

		}

	// Get the path to the partial used for rendering the field
	private function get_render_partial() {

		if ( !empty( $this->type ) ) {

			// Basic types
			if ( in_array( $this->type, ACFTC_Core::$basic_types ) ) {
				$render_partial = ACFTC_Core::$plugin_path . 'render/basic.php';
			}
			// Field types with their own partial
			else {
				$render_partial = ACFTC_Core::$plugin_path . 'render/' . $this->type . '.php';
			}

			return $render_partial;

		}

	}


	// Render theme PHP for field
	public function render_field() {

		if ( !empty($this->type) ) {

			// Ignore these fields tyles
			$ignore_field_types = array( 'tab', 'message', 'accordion', 'enhanced_message', 'row' );

			// Bail early for these ignored field types
			if ( in_array( $this->type, $ignore_field_types )) {
				return;
			}

			if ( 0 == $this->nesting_level ) {

				// open field meta div
				echo '<div class="acftc-field-meta">';

				// dev - debug label
				//echo htmlspecialchars('<h2>'. $this->label .'</h2>');

				// dev - debug field partial
				//echo htmlspecialchars('<h2>'. $this->render_partial .'</h2>');

				// code block title - simple version
				echo '<span class="acftc-field-meta__title" data-pseudo-content="'. $this->label .'"></span>';

				// close field meta div
				echo '</div>';

				// open div for field code wrapper (used for the button etc)
				echo '<div class="acftc-field-code" id="acftc-' . $this->quick_link_id . '">';

				// copy button
				echo '<a href="#" class="acftc-field__copy" title="Copy to Clipboard"></a>';

				// PHP code block for field
				echo '<pre class="line-numbers"><code class="language-php">';

			}

			// Field supported by TC (free)
			if ( file_exists( $this->render_partial ) ) {
				include( $this->render_partial );
			}
			// Field supported by TC Pro only
			elseif ( in_array( $this->type, ACFTC_Core::$tc_pro_field_types ) ) {
				echo $this->indent . htmlspecialchars( "<?php // Upgrade to ACF Theme Code Pro for " . $this->type . " field support. ?>" ) . "\n";
				echo $this->indent . htmlspecialchars( "<?php // Visit http://www.hookturn.io for more information. ?>" ) . "\n";
			}
			// Field not supported at all (yet)
			else {
				echo $this->indent . htmlspecialchars( "<?php // The " . $this->type  . " field type is not supported in this version of the plugin. ?>" ) . "\n";
				echo $this->indent . htmlspecialchars( "<?php // Contact http://www.hookturn.io to request support for this field type. ?>" ) . "\n";
			}

			if ( 0 == $this->nesting_level ) {

				// close PHP code block
				echo '</div></code></pre>';
			}

		}

	}

}
