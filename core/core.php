<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class ACFTC_Core {

	public static $class_prefix = 'ACFTC_';
	public static $db_table = '';
	public static $indent_repeater = 2;
	public static $indent_flexible_content = 3;

	public static $ignored_field_types = array(
		'tab',
		'message',
		'accordion',
		'enhanced_message',
		'row'
	);

	// Basic field types supported by TC Free and TC Pro
	public static $field_types_basic = array(
		'text',
		'textarea',
		'number',
		'range',
		'email',
		'url',
		'wysiwyg',
		'oembed',
		'date_picker',
		'date_time_picker',
		'time_picker',
		'color_picker',
	);

	/**
	 * Basic field types only suppported by TC Pro.
	 * 
	 * Note: These field types should also appear in the the TC Pro
	 * field types array below
	 */
	private static $field_types_basic_pro = array(
		'extended-color-picker',
		'star_rating_field',
		'qtranslate_text',
		'qtranslate_textarea',
		'qtranslate_wysiwyg',
	);

	/** 
	 * Field types supported by TC Pro.
	 * 
	 * Used for used for locating render partials and also
	 * displaying appropriate upgrade suggestions.
	 * 
	 * Note: This array also includes TC Pro basic field types.
	 */
	public static $field_types_all_tc_pro = array(
		// ACF Pro
		'repeater',
		'flexible_content',
		'gallery',
		'clone',

		// 3rd party
		'font-awesome',
		'google_font_selector',
		'image_crop',
		'markdown',
		'rgba_color',
		'sidebar_selector',
		'smart_button',
		'table',
		'tablepress_field',
		'address',
		'number_slider',
		'posttype_select',
		'acf_code_field',
		'link_picker',
		'youtubepicker',
		'focal_point',
		'color_palette', // Color Palette
		'forms', // Gravityforms and Ninjaforms
		'icon-picker', // Icon Selector
		'svg_icon',
		'swatch', // Color Palette
		'image_aspect_ratio_crop',
		'qtranslate_file', // qTranslate
		'qtranslate_image', // qTranslate
		'nav_menu',

		// TC Pro basic types (see above)
		'extended-color-picker', // RGBA Color Picker (https://github.com/constlab/acf-rgba-color-field)
		'star_rating_field', // Star Rating
		'qtranslate_text',
		'qtranslate_textarea',
		'qtranslate_wysiwyg',
	);

	/**
	 * ACFTC_Core constructor
	 */
	public function __construct() {

		$this->set_class_prefix();
		$this->set_basic_field_types();
		$this->add_core_actions();

	}

    /**
	 * Set class prefix
	 */
	private function set_class_prefix() {

		if ( ACFTC_IS_PRO ) {
			self::$class_prefix .= 'Pro_';
		}

	}

    /**
	 * Set extra basic field types
	 */
	private function set_basic_field_types() {

		if ( ACFTC_IS_PRO ) {
			self::$field_types_basic = array_merge( self::$field_types_basic, self::$field_types_basic_pro );
		}

	}

	/**
	 * Add plugin actions
	 **/
	private function add_core_actions() {

		add_action( 'admin_init', array($this, 'set_db_table') );
		add_action( 'add_meta_boxes', array($this, 'register_meta_boxes') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );

		if ( ACFTC_IS_PRO ) {

			add_action( 'acf/include_admin_tools' , array($this, 'add_location_registration_tool') );

		} 
		
	}

	/**
	 * Set the DB Table (as this changes between version 4 and 5)
	 * So we need to check if we're using version 4 or version 5 of ACF
	 * This includes ACF 5 in a theme or ACF 4 or 5 installed via a plugin
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
			__( 'Theme Code', 'acftc-textdomain' ), // Previously `textdomain`
			array( $this, 'display_callback'),
			array( 'acf', 'acf-field-group' )
		);

	}


	/**
	 * Meta box display callback
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function display_callback( $field_group_post_obj ) {

		$locations_class_name = self::$class_prefix . 'Locations';
		$locations_ui = new $locations_class_name( $field_group_post_obj );

        echo $locations_ui->get_locations_html();

	}


	/**
	 * Add meta box for Location Registration Tool to ACF "Tools" page
	 */
	public function add_location_registration_tool() {
		
		include( ACFTC_PLUGIN_DIR_PATH . 'pro/location-registration/ACFTC_Location_Registration.php'); // TODO update this path and filename

		if( function_exists('acf_register_admin_tool') ) {
			acf_register_admin_tool( 'ACFTC_Location_Registration' );
		}

	}


	// load scripts and styles
	public function enqueue( $hook ) {

		global $post_type;

		$page = $GLOBALS['plugin_page'];

		// if post type is an ACF field group
		if( 'acf-field-group' == $post_type || 'acf' == $post_type || 'acf-tools' == $page ) {

			// Plugin styles
			wp_enqueue_style( 'acftc_css', ACFTC_PLUGIN_DIR_URL . 'assets/acf-theme-code.css', '' , ACFTC_PLUGIN_VERSION);

			// Prism (code formatting)
			wp_enqueue_style( 'acftc_prism_css', ACFTC_PLUGIN_DIR_URL . 'assets/prism.css', '' , ACFTC_PLUGIN_VERSION);
			wp_enqueue_script( 'acftc_prism_js', ACFTC_PLUGIN_DIR_URL . 'assets/prism.js', '' , ACFTC_PLUGIN_VERSION);

			// Clipboard
			wp_enqueue_script( 'acftc_clipboard_js', ACFTC_PLUGIN_DIR_URL . 'assets/clipboard.min.js', '' , ACFTC_PLUGIN_VERSION);

			// Plugin js
			wp_enqueue_script( 'acftc_js', ACFTC_PLUGIN_DIR_URL . 'assets/acf-theme-code.js', array( 'acftc_clipboard_js' ), '', ACFTC_PLUGIN_VERSION, true );

			if ( ACFTC_IS_PRO ) {
				wp_enqueue_script( 'acftc_pro_js', ACFTC_PLUGIN_DIR_URL . 'pro/assets/acf-theme-code-pro.js', array( 'acftc_js' ), '', ACFTC_PLUGIN_VERSION, true );
			}

		}

	}

}
