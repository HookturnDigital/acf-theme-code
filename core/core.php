<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ACFTC_Core {

	public static $plugin_path = '';
	public static $plugin_url = '';
	public static $db_table = '';
	public static $indent_repeater = 2;
	public static $indent_flexible_content = 3;
	public static $basic_types = array(
		'text',
		'textarea',
		'number',
		'email',
		'url',
		'color_picker',
		'wysiwyg',
		'oembed',
		'radio'
	);

	// Extended functionality types
	public static $extended_types = array(
		'flexible_content',
		'repeater',
		'gallery',
		'font-awesome',
		'google_font_selector'
	);

	/**
	 * ACFTC_Core constructor
	 */
	public function __construct( $plugin_path, $plugin_url ) {

		// Paths and URLs
		self::$plugin_path = $plugin_path;
		self::$plugin_url = $plugin_url;

		// Hooks
		add_action( 'admin_init', array($this, 'set_db_table') );
		add_action( 'add_meta_boxes', array($this, 'register_meta_boxes') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );

	}


	/**
	 * Check if ACF plugin is free or pro version
	 */
	public function set_db_table() {

		/**
		 * If fields are created with ACF and then ACF PRO is installed and
		 * activated, the ACF fields are moved to the ACF PRO database structure.
		 * If ACF PRO is then deactivated and ACF is reactivated the fields
		 * won't appear in the admin.
		 *
		 * If fields are created with ACF PRO and then ACF is activated
		 * as well, only the fields created in ACF PRO will be visible
		 * in the the admin.
		 *
		 * If fields are created with ACF after activating and
		 * deactivating ACF PRO, they will only appear while ACF is
		 * activated and ACF PRO is deactivated. ACF PRO doesn't appear to
		 * convert fields to the new database structure more than once.
		 *
		 * Hence the following logic order is used:
		 */

		if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			self::$db_table = 'posts';
		} elseif  ( is_plugin_active('advanced-custom-fields/acf.php' ) ) {
			self::$db_table = 'postmeta';
		}

	}


	/**
	 * Register meta box
	 */
	public function register_meta_boxes() {

		add_meta_box(
			'acftc-meta-box',
			__( 'Template Code', 'textdomain' ),
			array( $this, 'display_callback'),
			array( 'acf', 'acf-field-group' ) // same meta box used for ACF and ACF PRO
		);

	}


	/**
	 * Meta box display callback
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function display_callback( $post ) {

		if ( self::$db_table == 'postmeta') {

			$parent_field_group = new ACFTC_Group( $post->ID );

		} elseif ( self::$db_table == 'posts') {

			$field_group_location = $this->get_field_group_locations( $post );
			$parent_field_group = new ACFTC_Group( $post->ID, 0 , 0 , $field_group_location);

		}

		$parent_field_group->render_field_group();
	}


	/**
	 * Get field group locations
	 *
	 * @param WP_Post $post Current post object.
	 */
	private function get_field_group_locations( $post ) {

		// get field group locations from field group post content
		if ( $post->post_content ) {

			$field_group_location_content = unserialize( $post->post_content );

			foreach ( $field_group_location_content['location']  as $location_condition_group ) {

				foreach ( $location_condition_group as $location_condition ) {

					// TODO: currently this function only supports "options_page".

					if ( "options_page" == $location_condition['param'] &&
						 "==" == $location_condition['operator'] ) {

						return "options_page";

					}

				}

			}

		}

	}


	// load scripts and styles
	public function enqueue( $hook ) {

		// grab the post type
		global $post_type;

		// if post type is an ACF field group
		if( 'acf-field-group' == $post_type || 'acf' == $post_type ) {

			// Plugin styles
			wp_enqueue_style( 'acftc_css', self::$plugin_url . 'assets/acf-theme-code.css');

			// Prism (code formatting)
			wp_enqueue_style( 'acftc_prism_css', self::$plugin_url . 'assets/prism.css');
			wp_enqueue_script( 'acftc_prism_js', self::$plugin_url . 'assets/prism.js' );

			// Clipboard
			wp_enqueue_script( 'acftc_clipboard_js', self::$plugin_url . 'assets/clipboard.js' );

			// Plugin js
			wp_enqueue_script( 'acftc_js', self::$plugin_url . 'assets/acf-theme-code.js', array( 'acftc_clipboard_js' ), '', true );

		}

	}

}
