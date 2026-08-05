<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 * 
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/includes
 */

/**
 *
 * @since      1.0.0
 * @package    BikePress
 * @subpackage BikePress/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class BikePress_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bikepress',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
