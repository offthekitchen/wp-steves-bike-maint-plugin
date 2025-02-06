<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Steves_Bike_Maintenance
 * @subpackage Steves_Bike_Maintenance/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class Steves_Bike_Maintenance_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		delete_option( 'steves_bike_plugin_db_version');
		
		//Remove shortcodes
		remove_shortcode('sbm-bike-list', 'sbm_bike_list');

	}

}
