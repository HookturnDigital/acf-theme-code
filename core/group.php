<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class for field group functionality
 */
class ACFTC_Group {

	// field group id (used to query for fields)
	private $id;

	/**
	 * array of all fields in field group
	 *
	 * if using postmeta table this will be an array of post meta objects
	 * if using posts table this will be an array of Post objects
	 * if using repeater add on, this will be an array of fields stored in arrays
	 */
	public $fields;

	/**
	 * nesting level
	 *
	 * 0 = not nested inside another field
	 * 1 = nested one level deep inside another field eg. repeater
	 * 2 = nested two levels deep inside other fields etc
	 */
	public $nesting_level;

	// theme code indent for the field group
	public $indent_count;

	private $exclude_html_wrappers = false;

	// if the field group is a clone
	public $clone = false;
	public $clone_parent_acftc_group = null;

	public $location_rule_param; // eg. 'block'

	/**
	 * Constructor for field group
	 * 
	 * @param array $args Array of arguments.
	 */
	function __construct( $args = array() ) {

		$default_args = array(
			'field_group_id' => null,
			'fields' => null,
			'nesting_level' => 0,
			'indent_count' => 0,
			'location_rule_param' => '',
			'clone_parent_acftc_group' => null,
			'exclude_html_wrappers' => false // Change to true for debug
		);

		$args = array_merge( $default_args, $args ); 

		// Constructor requires either fields or a field group id
		if ( empty( $args['field_group_id'] ) && empty( $args['fields'] ) ) {
			return false;
		}

		// Use fields provided (repeater add on in use)
		if ( !empty( $args['fields'] ) && is_array( $args['fields'] ) ) {
			$this->fields = $args['fields'];
		}
		// Get fields by field group id
		elseif ( !empty( $args['field_group_id'] ) ) {
			$this->id = $args['field_group_id'];
			$this->fields = $this->get_fields();
		}

		$this->nesting_level = $args['nesting_level'];
		$this->indent_count = $args['indent_count'];
		$this->location_rule_param = $args['location_rule_param'];
		$this->clone_parent_acftc_group = $args['clone_parent_acftc_group'];
		$this->exclude_html_wrappers = $args['exclude_html_wrappers'];

	}


	/**
	* Get all the fields in the field group.
	*
	* @return array of all fields (post objects) in the field group
	*/
	private function get_fields() {

		if ( 'postmeta' == ACFTC_Core::$db_table ) { // ACF
			return $this->get_fields_from_postmeta_table();
		 } elseif ( 'posts' == ACFTC_Core::$db_table ) { // ACF PRO
			return $this->get_fields_from_posts_table();
		}

	}


	/**
	* Get fields from postmeta table
	*
	* @return array of all fields (post meta objects) in the field group
	*/
	private function get_fields_from_postmeta_table() {

		global $wpdb;

		// get table prefix
		$postmeta_table_name = $wpdb->prefix . 'postmeta';

		// query postmeta table for fields in this field group
		$fields = $wpdb->get_results( "SELECT * FROM " . $postmeta_table_name . " WHERE post_id = " . $this->id . " AND meta_key LIKE 'field_%'" );

		return $fields;

	}


	/**
	* Get fields from posts table
	*
	* @return array of all fields (post objects) in the field group
	*/
	private function get_fields_from_posts_table() {

		// wp query args for all ACF fields for this field group
		$query_args = array(
			'post_type' => 'acf-field',
			'post_parent' => $this->id,
			'posts_per_page' => '-1',
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		$fields_query = new WP_Query( $query_args );

		return $fields_query->posts;

	}


	/**
	 * Get HTML for field group
	 *
	 * @return string
	 **/
	public function get_field_group_html() {
		
		// TODO: Is sorting necessary for sub fields of repeater created with
		// the repeater add on? They do have order_no data.
		
		$field_group_html = '';

		// ACF - create, sort and render fields
		if ( 'postmeta' == ACFTC_Core::$db_table ) {

			// create an array of ACFTC field objects
			$acftc_fields = array();

			foreach ( $this->fields as $field ) {

				$args = array(
					'nesting_level' => $this->nesting_level,
					'indent_count' => $this->indent_count,
					'location_rule_param' => $this->location_rule_param,
					'field_data_obj' => $field,
					'exclude_html_wrappers' => $this->exclude_html_wrappers
				);

				$field_class_name = ACFTC_Core::$class_prefix . 'Field';
				$acftc_field = new $field_class_name( $args );

				array_push( $acftc_fields, $acftc_field );

			}

			// sort fields
			usort( $acftc_fields, array( $this, "compare_field_order") );

			// render fields
			foreach ( $acftc_fields as $acftc_field ) {
				$field_group_html .= $acftc_field->get_field_html();
			}

		}

		// ACF PRO - create and render fields (no sorting required)
		elseif ( 'posts' == ACFTC_Core::$db_table ) {

			// create and render ACFTC field objects
			foreach ( $this->fields as $field_post_obj ) {

				$args = array(
					'nesting_level' => $this->nesting_level,
					'indent_count' => $this->indent_count,
					'location_rule_param' => $this->location_rule_param, 
					'field_data_obj' => $field_post_obj,
					'clone_parent_acftc_field' => $this->clone_parent_acftc_group, // TODO: Add this clone bit to the postmeta table func above?
					'exclude_html_wrappers' => $this->exclude_html_wrappers
				);

				$field_class_name = ACFTC_Core::$class_prefix . 'Field';
				$acftc_field = new $field_class_name( $args );

				$field_group_html .= $acftc_field->get_field_html();

			}

		}

		return $field_group_html;

	}

	/**
	 * Field order number comparion, used by usort() in get_field_group_html()
	 */
	private function compare_field_order( $a, $b ) {

		return $a->settings['order_no'] > $b->settings['order_no'];

	}

}
