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
 * Defines activation behavior: schema create/upgrade, plugin data, and optional demo data.
 *
 * @since      1.0.0
 * @package    BikePress
 * @subpackage BikePress/includes
 * @author     John S Weeks <steve@offthekitchen.com>
 */
class BikePress_Activator {

	const DB_VERSION = '1.2';

	/**
	 * Create or upgrade tables; seed plugin data; optionally load demo data.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		self::install_or_upgrade_schema();
		self::seed_plugin_data();
		self::backfill_missing_bike_types();

		if ( bikepress_should_load_demo() ) {
			require_once plugin_dir_path( __FILE__ ) . 'test-data.php';
			Test_Data::insert_test_data();
			delete_option( 'bikepress_show_demo_notice' );
		} else {
			update_option( 'bikepress_show_demo_notice', '1' );
		}

		delete_option( 'steves_bike_plugin_db_version' );
		update_option( 'bikepress_db_version', self::DB_VERSION );
	}

	/**
	 * Run schema/plugin-data upgrade when an older install is loaded.
	 */
	public static function maybe_upgrade() {
		$current = get_option( 'bikepress_db_version', '' );
		if ( self::DB_VERSION === (string) $current ) {
			return;
		}

		self::install_or_upgrade_schema();
		self::seed_plugin_data();
		self::backfill_missing_bike_types();
		update_option( 'bikepress_db_version', self::DB_VERSION );
	}

	/**
	 * Create/upgrade tables via dbDelta.
	 */
	public static function install_or_upgrade_schema() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-bikepress-tables.php';

		$charset_collate = $wpdb->get_charset_collate();

		$bikes_table       = bikepress_bikes_table();
		$maintenance_table = bikepress_maintenance_table();
		$specs_table       = bikepress_specs_table();
		$status_table      = bikepress_status_table();
		$type_table        = bikepress_type_table();

		$sql = "CREATE TABLE $bikes_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bike_image_id mediumint(9) DEFAULT 0 NOT NULL,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			bike_name tinytext NOT NULL,
			bike_desc text NOT NULL,
			bike_make varchar(15) DEFAULT '' NOT NULL,
			bike_model varchar(50) DEFAULT '' NOT NULL,
			serial_number varchar(50) DEFAULT '' NOT NULL,
			purchase_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			bike_status_id mediumint(9) NOT NULL,
			bike_type_id mediumint(9) DEFAULT 0 NOT NULL,
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

		$sql = "CREATE TABLE $type_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			last_update datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			bike_type varchar(50) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );
	}

	/**
	 * Seed core statuses and types.
	 */
	public static function seed_plugin_data() {
		require_once plugin_dir_path( __FILE__ ) . 'class-bikepress-plugin-data.php';
		BikePress_Plugin_Data::ensure_core_statuses();
		BikePress_Plugin_Data::ensure_core_types();
	}

	/**
	 * Assign Unknown type to bikes missing a type id (e.g. upgraded installs).
	 */
	public static function backfill_missing_bike_types() {
		global $wpdb;

		if ( ! function_exists( 'bikepress_get_or_create_unknown_type_id' ) ) {
			return;
		}

		$unknown_id = bikepress_get_or_create_unknown_type_id();
		if ( $unknown_id <= 0 ) {
			return;
		}

		$bikes_table = bikepress_bikes_table();
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$bikes_table} SET bike_type_id = %d WHERE bike_type_id = 0 OR bike_type_id IS NULL", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$unknown_id
			)
		);
	}
}
