<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class ACFTC_Core {

	public static $class_prefix = 'ACFTC_';
	public static $acf_version = null;
	public static $db_table = '';
	public static $indent_repeater = 2;
	public static $indent_flexible_content = 3;

	public static $ignored_field_types = array(
		'tab',
		'message',
		'accordion',
		'enhanced_message',
		'row',
		'page', // Advanced Forms Pro (used for multi-step forms)
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
		'acf_cf7',
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
		'rgba_color',
		'sidebar_selector',
		'smart_button',
		'table',
		'tablepress_field',
		'address',
		'number_slider',
		'posttype_select',
		'acf_code_field',
		'youtubepicker',
		'focal_point',
		'color_palette', 
		'forms', // Gravityforms and Ninjaforms
		'icon-picker', // Icon Selector
		'svg_icon',
		'swatch',
		'image_aspect_ratio_crop',
		'audio_video_player',

		// TC Pro basic types (see above)
		'extended-color-picker', 
		// ACF RGBA Color Picker (requires ACF PRO)
		// https://wordpress.org/plugins/acf-rgba-color-picker/
		// https://github.com/constlab/acf-rgba-color-field)
		'star_rating_field',
		// ACF Star Rating Field
		// https://github.com/kevinruscoe/acf-star-rating-field
		'acf_cf7',
		// ACF Field For Contact Form 7
		// https://wordpress.org/plugins/acf-field-for-contact-form-7/

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

		add_action( 'admin_init', array($this, 'set_acf_version') );
		add_action( 'admin_init', array($this, 'set_db_table') );
		add_action( 'add_meta_boxes', array($this, 'register_meta_boxes') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );

		add_filter( 'postbox_classes_acf-field-group_acftc-meta-box' , array($this, 'add_metabox_classes') );

		if ( ACFTC_IS_PRO ) {

			add_action( 'admin_init', array($this, 'load_textdomain') );
			add_action( 'acf/include_admin_tools' , array($this, 'add_location_registration_tool') );

		} 
		
	}


	/**
	 * Set ACF version
	 * 
	 * The ACF_VERSION constant wasn't introduced until ACF 5.5.13 so it's not utilised here.
	 */
	public function set_acf_version() {

		// Check for the function `acf_get_setting`. This was added in version 5 of ACF.
		if (function_exists('acf_get_setting')) {

			self::$acf_version = acf_get_setting('version'); // This will return a string of the version eg '5.0.0'

		} else {

			// Use the version 4 logic to get the version.
			// This will return a string if the plugin is active eg '4.4.11'.
			// Else it will return the string 'version' if the plugin is not active.
			self::$acf_version = apply_filters('acf/get_info', 'version');

		}

	}


	/**
	 * Set the DB Table used by ACF. 
	 * 
	 * This changed from `postmeta` to `posts in version 5 of ACF and ACF Pro.
	 */
	public function set_db_table()
	{
		self::$db_table = version_compare(self::$acf_version, '5', '<') ? 'postmeta' : 'posts';
	}



	/**
	 * Register meta box
	 */
	public function register_meta_boxes() {

		add_meta_box(
			'acftc-meta-box',
			__( 'Theme Code', 'acf-theme-code' ), // Previously `textdomain`
			array( $this, 'display_callback'),
			array( 'acf', 'acf-field-group' )
		);

	}


	/**
	 * Add metabox classes
	 */
	public function add_metabox_classes( $classes ) {
		
		if ( ACFTC_IS_PRO ) {
			$classes[] = 'acftc-pro-meta-box';
		}
		
		return $classes;

	}


	/**
	 * Meta box display callback
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function display_callback( $field_group_post_obj ) {

		echo $this->get_meta_box_content_html($field_group_post_obj);
	
	}


	/**
	 * Get meta box content 
	 */
	public function get_meta_box_content_html($field_group_post_obj) {

		$field_group_ui_html = ACFTC_Field_Group_UI::get_field_group_ui_html($field_group_post_obj);

		return $field_group_ui_html; 

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


	/**
	 * Get stylesheet filename
	 * 
	 * If ACF version is less than 6 use legacy stylesheet
	 */
	private function get_stylesheet_filename()
	{
		return (self::$acf_version < 6) ? 'acf-theme-code-legacy' : 'acf-theme-code';
	}


	// load scripts and styles
	public function enqueue($hook)
	{

		global $post_type, $plugin_page;

		$current_screen = get_current_screen();

		if (
			'acf-field-group' == $post_type
			|| 'acf' == $post_type
			|| 'acf-tools' == $plugin_page // ACF Tools page
		) {


			// Plugin styles
			$plugin_stylesheet_filename = $this->get_stylesheet_filename();

			wp_enqueue_style('acftc', ACFTC_PLUGIN_DIR_URL . "assets/{$plugin_stylesheet_filename}.css", array(), ACFTC_PLUGIN_VERSION);

			// Prism (code formatting)
			wp_enqueue_style('acftc-prism', ACFTC_PLUGIN_DIR_URL . 'assets/prism.css', array(), ACFTC_PLUGIN_VERSION);
			
			wp_enqueue_script('acftc-prism', ACFTC_PLUGIN_DIR_URL . 'assets/prism.js', array(), ACFTC_PLUGIN_VERSION);

			// Clipboard
			wp_enqueue_script('acftc-clipboard', ACFTC_PLUGIN_DIR_URL . 'assets/clipboard.min.js', array(), '1.7.1');

			// Plugin JS
			wp_enqueue_script('acftc', ACFTC_PLUGIN_DIR_URL . 'assets/acf-theme-code.js', array('wp-i18n', 'jquery', 'acftc-clipboard'), ACFTC_PLUGIN_VERSION, true);
			
			// i18n
			// The third argument for `wp_set_script_translations()` is an optional path to the directory containing translation files.
			// This is only needed if your plugin or theme is not hosted on WordPress.org, which provides these translation files automatically.
			$translation_dir_file_path = (ACFTC_IS_PRO) ? (ACFTC_PLUGIN_DIR_PATH . 'pro/languages') : null;
			wp_set_script_translations('acftc', 'acf-theme-code', $translation_dir_file_path);

			if (ACFTC_IS_PRO) {
				wp_enqueue_script('acftc-pro', ACFTC_PLUGIN_DIR_URL . 'pro/assets/acf-theme-code-pro.js', array('jquery', 'acftc'), ACFTC_PLUGIN_VERSION, true);
			}
		}

		if (ACFTC_IS_PRO && 'settings_page_theme-code-pro-license' == $current_screen->id) {
			wp_enqueue_style('acftcp-license-page', ACFTC_PLUGIN_DIR_URL . 'pro/assets/acftcp-license-page.css', '', ACFTC_PLUGIN_VERSION);
		}

	}


	/**
	 * Load translated strings for ACFTC Pro (ACFTC uses wordpress.org for translations).
	 */
	public function load_textdomain() {
		
		load_plugin_textdomain( 'acf-theme-code', false, dirname( ACFTC_PLUGIN_BASENAME ) . '/pro/languages' ); 

	}

}
