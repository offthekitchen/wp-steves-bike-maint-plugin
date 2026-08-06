<?php
/**
 * Fired during plugin activation.
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/includes
 */

/**
 * Defines activation behavior: schema create/upgrade and optional demo data.
 *
 * @since      1.0.0
 * @package    BikePress
 * @subpackage BikePress/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class BikePress_Activator {

	/**
	 * Create or upgrade tables; optionally load demo data.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-bikepress-tables.php';

		$db_version      = '1.0';
		$charset_collate = $wpdb->get_charset_collate();

		$bikes_table       = bikepress_bikes_table();
		$maintenance_table = bikepress_maintenance_table();
		$specs_table       = bikepress_specs_table();
		$status_table      = bikepress_status_table();

		$sql = "CREATE TABLE $bikes_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bike_image_id mediumint(9) DEFAULT 0 NOT NULL,
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
		dbDelta( $sql );

		$sql = "CREATE TABLE $maintenance_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bike_id mediumint(9) NOT NULL,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			maintenance_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			maintenance_desc varchar(255) DEFAULT '' NOT NULL,
			bike_miles mediumint(9) DEFAULT 0 NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE $specs_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bike_id mediumint(9) NOT NULL,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			spec_name varchar(50) DEFAULT '' NOT NULL,
			spec_desc varchar(50) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE $status_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			bike_status varchar(50) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );

		if ( bikepress_should_load_demo() ) {
			require_once plugin_dir_path( __FILE__ ) . 'test-data.php';
			Test_Data::insert_test_data();
		}

		delete_option( 'steves_bike_plugin_db_version' );
		update_option( 'bikepress_db_version', $db_version );
	}
}
