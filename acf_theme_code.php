<?php
/*
Plugin Name: Advanced Custom Fields: Theme Code
Plugin URI: http://www.hookturn.io
Description: Generates theme code for ACF field groups to speed up development.
Version: 1.4.0
Author: hookturn
Author URI: http://www.hookturn.io
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Define the version number
define( 'ACFTC_VERSION', '1.4.0' );

// Check for dashboard or admin panel
if ( is_admin() ) {

	/**
	 * Classes
	 */
	include('core/core.php');
	include('core/group.php');
	include('core/field.php');

	/**
	 * Single function for accessing plugin core instance
	 *
	 * @return ACFTC_Core
	 */
	function acftc()
	{
		static $instance;
		if ( !$instance )
			$instance = new ACFTC_Core( plugin_dir_path( __FILE__ ), plugin_dir_url( __FILE__ ), plugin_basename( __FILE__ ), ACFTC_VERSION );
		return $instance;
	}

	acftc(); // kickoff

}
