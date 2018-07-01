<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ACFTC_Core {

	public static $plugin_path = '';
	public static $plugin_url = '';
	public static $plugin_basename = '';
	public static $plugin_version = '';
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
		'radio',
		'range'
	);

	// Field types supported by TC Pro
	public static $tc_pro_field_types = array(
		'flexible_content',
		'repeater',
		'gallery',
		'clone',
		'font-awesome',
		'google_font_selector',
		'rgba_color',
		'image_crop',
		'markdown',
		'nav_menu',
		'smart_button',
		'sidebar_selector',
		'tablepress_field',
		'table'
	);

	/**
	 * ACFTC_Core constructor
	 */
	public function __construct( $plugin_path, $plugin_url, $plugin_basename, $plugin_version ) {

		// Paths and URLs
		self::$plugin_path = $plugin_path;
		self::$plugin_url = $plugin_url;
		self::$plugin_basename = $plugin_basename;
		self::$plugin_version = $plugin_version;

		// Hooks
		add_action( 'admin_init', array($this, 'acf_theme_code_pro_check') );
		add_action( 'admin_init', array($this, 'set_db_table') );
		add_action( 'add_meta_boxes', array($this, 'register_meta_boxes') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );

	}

	/**
	 * Check if ACF Theme Code Pro is activated
	 */
	public function acf_theme_code_pro_check() {

		// If Theme Code Pro is activated, disable Theme Code (free) and display notice
		if ( is_plugin_active( 'acf-theme-code-pro/acf_theme_code_pro.php' ) ) {
			deactivate_plugins( self::$plugin_basename );
			add_action( 'admin_notices', array( $this, 'disabled_notice' ) );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}

	}

	/**
	 * ACF Theme Code (free) disabled notice
	 */
	public function disabled_notice() {
		echo '<div class="notice notice-success is-dismissible">';
			echo '<p>Plugin <strong>Advanced Custom Fields: Theme Code Pro</strong> is activated so plugin <strong>Advanced Custom Fields: Theme Code</strong> has been disabled.</p>';
		echo '</div>';
	}


	/**
	 * Check if ACF plugin is free or pro version
	 */
	public function set_db_table() {

		// If we can't detect ACF
		if ( ! class_exists( 'acf' )  ) {

			// bail early
			return;

		 }

		// Check for the function acf_get_setting - this came in version 5
		if ( function_exists( 'acf_get_setting' ) ) {

			// Get the version to be sure
			// This will return a srting of the version eg '5.0.0'
			$version = acf_get_setting( 'version' );

		} else {

			// Use the version 4 logic to get the version
			// This will return a string if the plugin is active eg '4.4.11'
			// This will retrn the string 'version' if the plugin is not active
			$version = apply_filters( 'acf/get_info', 'version' );

		}

		// Get only the major version from the version string (the first character)
		$major_version = substr( $version, 0 , 1 );

		// If the major version is 5
		if( $major_version == '5' ) {

			// Set the db table to posts
			self::$db_table = 'posts';

		// If the major version is 4
		} elseif( $major_version == '4' ) {

			// Set the db table to postmeta
			self::$db_table = 'postmeta';

		}

	}


	/**
	 * Register meta box
	 */
	public function register_meta_boxes() {

		add_meta_box(
			'acftc-meta-box',
			__( 'Theme Code', 'textdomain' ),
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

			// Setup a counter for location rules
			$location_rules_count = '';

			global $wpdb;

			// Prepend table prefix
			$table = $wpdb->prefix . 'postmeta';

			// Query postmeta table for location rules associated with this field group
			$query_results = $wpdb->get_results( "SELECT * FROM " . $table . " WHERE post_id = " . $post->ID . " AND meta_key LIKE 'rule'" );

			// count the results
			$location_rules_count = count( $query_results );

			// if we have more than 1 location show the notice
			if( $location_rules_count >= 2 ) {
				echo '<div class="acftc-pro-notice acftc-pro-notice--top"><a class="acftc-pro-notice__link" href="https://hookturn.io/downloads/acf-theme-code-pro/?utm_source=acftclocation" target="_blank">Generate code for each of your Location rules with <strong>ACF Theme Code Pro</strong></a></div>';
			}

			// render the field group
			$parent_field_group = new ACFTC_Group( $post->ID );


		} elseif ( self::$db_table == 'posts') {

			// count them
			$field_group_location_array_count = $this->get_field_group_locations( $post );

			// if we have more than 1 location show the notice
			if( $field_group_location_array_count >= 2 ) {
				echo '<div class="acftc-pro-notice acftc-pro-notice--top"><a class="acftc-pro-notice__link" href="https://hookturn.io/downloads/acf-theme-code-pro/?utm_source=acftclocation" target="_blank">Generate code for each of your Location rules with <strong>ACF Theme Code Pro</strong></a></div>';
			}

			// render the field group
			$parent_field_group = new ACFTC_Group( $post->ID, 0 , 0 , '');

		}

		if ( !empty( $parent_field_group->fields ) ) {

			$parent_field_group->render_field_group();

			// Upgrade to TC Pro notice
			echo '<div class="acftc-pro-notice"><a class="acftc-pro-notice__link" href="https://hookturn.io/downloads/acf-theme-code-pro/?utm_source=acftcfree" target="_blank">Upgrade to <strong>ACF Theme Code Pro</strong>.</a></div>';

		} else {

			echo '<div class="acftc-intro-notice"><p>Create some fields and publish the field group to generate theme code.</p></div>';

		}

	}


	/**
	 * Get field group locations
	 *
	 * @param WP_Post $post Current post object.
	 */

	private function get_field_group_locations( $post ) {

		// acf field group global
		global $field_group;

		// count the location array
		$location_array_count = count( $field_group['location'] );

		// return the count
		return $location_array_count;

	}


	// load scripts and styles
	public function enqueue( $hook ) {

		// grab the post type
		global $post_type;

		// if post type is an ACF field group
		if( 'acf-field-group' == $post_type || 'acf' == $post_type ) {

			// Plugin styles
			wp_enqueue_style( 'acftc_css', self::$plugin_url . 'assets/acf-theme-code.css', '' , self::$plugin_version);

			// Prism (code formatting)
			wp_enqueue_style( 'acftc_prism_css', self::$plugin_url . 'assets/prism.css', '' , self::$plugin_version);
			wp_enqueue_script( 'acftc_prism_js', self::$plugin_url . 'assets/prism.js', '' , self::$plugin_version);

			// Clipboard
			wp_enqueue_script( 'acftc_clipboard_js', self::$plugin_url . 'assets/clipboard.min.js', '' , self::$plugin_version);

			// Plugin js
			wp_enqueue_script( 'acftc_js', self::$plugin_url . 'assets/acf-theme-code.js', array( 'acftc_clipboard_js' ), self::$plugin_version, 'true' );

		}

	}

}
