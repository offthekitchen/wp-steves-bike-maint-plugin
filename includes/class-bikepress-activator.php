<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    BikePress
 * @subpackage BikePress/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class BikePress_Activator
{

	/**
	 * Activation
	 *
	 * This function establishes all the necessary setup during the activation of the plufgin.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{

		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$bikepress_db_version = '1.0';

		$charset_collate = $wpdb->get_charset_collate();

		// Create bike table
		$sql = "CREATE TABLE IF NOT EXISTS " . BIKES_TABLE . " (
			 id mediumint(9) NOT NULL AUTO_INCREMENT,
			 bike_image_id mediumint(9) default 0 NOT NULL,
			 last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			 bike_name tinytext NOT NULL,
			 bike_desc varchar(255) DEFAULT '' NOT NULL,
 			 bike_make varchar(15) DEFAULT '' NOT NULL,
  			 bike_model varchar(15) DEFAULT '' NOT NULL,
			 serial_number varchar(30) DEFAULT '' NOT NULL,
			 purchase_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			 bike_status_id mediumint(9) NOT NULL,
			 PRIMARY KEY  (id)
		   ) $charset_collate;";

		dbDelta($sql);

		// Create maintenance table
		$sql = "CREATE TABLE IF NOT EXISTS " . MAINTENANCE_TABLE . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bike_id mediumint(9) NOT NULL,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			maintenance_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			maintenance_desc varchar(255) DEFAULT '' NOT NULL,
			bike_miles mediumint(9) DEFAULT 0 NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

		dbDelta($sql);

		// Create specs table
		$sql = "CREATE TABLE IF NOT EXISTS " . SPECS_TABLE . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bike_id mediumint(9) NOT NULL,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			spec_name varchar(50) DEFAULT '' NOT NULL,
			spec_desc varchar(50) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

		dbDelta($sql);

		// Create status table
		$sql = "CREATE TABLE IF NOT EXISTS " . STATUS_TABLE . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			bike_status varchar(50) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

		dbDelta($sql);

		// TEST DATA
		require_once(plugin_dir_path( __FILE__ ) . '/test-data.php');
		Test_Data::insert_test_data();

		// Prefer BikePress option key; remove legacy key if present.
		delete_option( 'steves_bike_plugin_db_version' );
		add_option( 'bikepress_db_version', $bikepress_db_version );

	}
	

}
