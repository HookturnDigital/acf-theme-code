<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class for field functionality
 */
class ACFTC_Field {

	protected $render_partial;

	protected $nesting_level;
	protected $indent_count;
	protected $indent = '';

	protected $exclude_html_wrappers = false;

	protected $quick_link_id = '';
	protected $the_field_method = 'the_field';
	protected $get_field_method = 'get_field';
	protected $get_field_object_method = 'get_field_object';

	protected $id = null; // only used if posts table and required for flexible layouts
	protected $label;
	protected $name;
	protected $type;
	protected $var_name;

	/**
	 * All unserialized field data to be used in partials for edge cases.
	 * Needs to be public to sort fields.
	 */
	public $settings;

	protected $location_rule_param = ''; // eg. 'block'
	protected $location_rendered_param = ''; // eg. 'acf/example'

	protected $clone = false;


	/**
	 * Constructor
	 *
	 * @param array $args Array of arguments.
	 */
	function __construct( $args = array() ) {

		$default_args = array(
			'nesting_level' => 0,
			'indent_count' => 0,
			'location_rule_param' => '',
			'field_data_obj' => null,
			'clone_parent_acftc_field' => null,
			'exclude_html_wrapper' => false // Change to true for debug
		);

		$args = array_merge( $default_args, $args ); 

		$this->nesting_level = $args['nesting_level'];
		$this->indent_count = $args['indent_count'];

		$this->location_rule_param = $args['location_rule_param'];
		$this->location_rendered_param = $this->get_location_rendered_param();

		// Calc indent string
		$this->indent = $this->get_indent();

		// If field is nested
		if ( 0 < $this->nesting_level ) {

			// Use ACF sub field methods instead
			$this->the_field_method = 'the_sub_field';
			$this->get_field_method = 'get_sub_field';
			$this->get_field_object_method = 'get_sub_field_object';

		}

		if ( "postmeta" == ACFTC_Core::$db_table ) {
			$this->construct_from_postmeta_table( $args['field_data_obj'] );
		} elseif ( "posts" == ACFTC_Core::$db_table ) {
			$this->construct_from_posts_table( $args['field_data_obj'] );
		}

		// variable name that is used in code rendered
		$this->var_name = $this->get_var_name( $this->name );

		// cloned fields
		if ( $args['clone_parent_acftc_field'] ) {

			$this->clone = true;
			$clone_settings = $args['clone_parent_acftc_field']->settings;

			// Reset location rule paramater
			$this->location_rule_param = $args['location_rule_param'];

			if ( 1 === $clone_settings['prefix_name'] ) {
				$this->name = $args['clone_parent_acftc_field']->name . '_' . $this->name;
			}

		}

		// partial to use for rendering
		$this->render_partial = $this->get_render_partial();

		$this->exclude_html_wrappers = $args['exclude_html_wrappers'];

	}

	// Set field properties using data from postmeta table
	private function construct_from_postmeta_table( $field_data_obj ) {

		if ( empty( $field_data_obj ) ) {
			return false;
		}

		// if repeater add on is used, field data will be in an array
		if ( is_array( $field_data_obj ) ) {

			// Put all field data including sub fields in settings.
			// This is necessary to support nested repeaters created with the
			// Repeater Add On and is only done is this case.
			$this->settings = $field_data_obj;

			// TODO : note absence of ID property here
			$this->label = $field_data_obj['label'];
			$this->name = $field_data_obj['name'];
			$this->type = $field_data_obj['type'];

		}
		// field data is an object
		else {

			// unserialize meta values
			$this->settings = unserialize( $field_data_obj->meta_value );

			// TODO : note absence of ID property here
			$this->label = $this->settings['label'];
			$this->name = $this->settings['name'];
			$this->type = $this->settings['type'];

		}

		// if field is not nested
		if ( 0 == $this->nesting_level ) {

			// get quick link id
			$this->quick_link_id = $this->settings['key'];

		}

	}


	// Set field properties using data from posts table
	private function construct_from_posts_table( $field_data_obj ) {

		if ( empty( $field_data_obj ) ) {
			return false;
		}

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

	/**
	 * Get location paramater to be rendered
	 * 
	 * @return string Containing a variable name or value
	 **/
	protected function get_location_rendered_param() {

		// If location set to options page, add the options parameter
		if ( $this->location_rule_param == 'options_page') {

			return ', \'option\'';

		} else {

			return '';

		}

	}

	// Get the path to the partial used for rendering the field
	protected function get_render_partial() {

		if ( $this->type ) {

            // Basic field types with a shared partial
            if ( in_array( $this->type, ACFTC_Core::$field_types_basic ) ) {

                $render_partial = ACFTC_PLUGIN_DIR_PATH . 'render/basic.php';
                
            }

			// Field types with their own partial
			else {

                $render_partial = ACFTC_PLUGIN_DIR_PATH . 'render/' . $this->type . '.php';
                
			}

			return $render_partial;

		}

	}

	/**
	 * Is ignored field type
	 *
	 * @param string $field_type
	 * @return bool
	 **/
	protected function is_ignored_field_type( $field_type = '' ) {

		return in_array( $field_type, ACFTC_Core::$ignored_field_types ); 

	}

	/**
	 * Is debugging
	 *
	 * @return bool
	 **/
	private function is_debugging() {

		// TODO Move this to core?

		if ( !isset( $_GET["debug"] ) ) {
			return false;
		}

		$debug_mode = htmlspecialchars( $_GET["debug"] );

		return ( $debug_mode == 'on' );

	}

	/**
	 * Field has HTML wrapper
	 *
	 * @return bool
	 **/
	private function needs_html_wrapper() {

		return ( 0 == $this->nesting_level && !$this->clone );

	}

	/**
	 * Get HTML for title in field code block
	 *
	 * @return string
	 **/
	private function get_field_html_title() {

		ob_start();

		if ( $this->is_debugging() ) { 

			echo htmlspecialchars('<h3>Debug: '. $this->label .'</h3>');

		} else {

			// Echo the code block title as pseudo content to avoid selection 
?>
<span class="acftc-field-meta__title" data-type="<?php echo $this->type; ?>" data-pseudo-content="<?php echo $this->label; ?>"></span>
<?php
		}

		return ob_get_clean();

	}

	/**
	 * Get the HTML for the body of the field's code block
	 *
	 * @return string 
	 **/
	protected function get_field_html_body() {

		ob_start();

		if ( file_exists( $this->render_partial ) ) {

			include( $this->render_partial );

		} elseif ( in_array( $this->type, ACFTC_Core::$field_types_all_tc_pro ) ) {

			echo $this->indent . htmlspecialchars( "<?php // Upgrade to ACF Theme Code Pro for " . $this->type . " field support. ?>" ) . "\n";
			echo $this->indent . htmlspecialchars( "<?php // Visit http://www.hookturn.io for more information. ?>" ) . "\n";
			
		} else {
			
			echo $this->indent . htmlspecialchars( "<?php // The " . $this->type  . " field type is not supported in this version of the plugin. ?>" ) . "\n";
			echo $this->indent . htmlspecialchars( "<?php // Contact http://www.hookturn.io to request support for this field type. ?>" ) . "\n";

		}

		return ob_get_clean();

	}

	/**
	 * Get the HTML for the field
	 *
	 * @return string HTML for the field
	 **/
	public function get_field_html() {
		
		if ( empty( $this->type ) || $this->is_ignored_field_type( $this->type ) ) {
			return;
		}

		ob_start();

		if ( $this->needs_html_wrapper() && !$this->exclude_html_wrappers ) { 

?>
	<div class="acftc-field-meta">
		<?php echo $this->get_field_html_title();?>
	</div>
	<div class="acftc-field-code" id="acftc-<?php echo $this->quick_link_id; ?>">
		<a href="#" class="acftc-field__copy acf-js-tooltip" title="Copy to clipboard"></a>
		<pre class="line-numbers"><code class="language-php"><?php echo $this->get_field_html_body(); ?></code></pre>
	</div>
	
<?php
		} else {

			echo $this->get_field_html_body();

		}

		return ob_get_clean();

	}

}
