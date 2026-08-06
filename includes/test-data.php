<?php
/**
 * Demo / test data for BikePress activation.
 *
 * @link       http://www.offthekitchen.com
 * @since      1.0.0
 *
 * @package    BikePress
 * @subpackage BikePress/includes
 */

/**
 * Inserts a small demo dataset when enabled.
 *
 * @since 1.0.0
 * @package BikePress
 */
class Test_Data {

	/**
	 * Insert simplified demo statuses, bikes, maintenance, and specs.
	 *
	 * @since 1.0.0
	 */
	public static function insert_test_data() {
		global $wpdb;

		$bikes_table        = bikepress_bikes_table();
		$maintenance_table  = bikepress_maintenance_table();
		$specs_table        = bikepress_specs_table();
		$status_table       = bikepress_status_table();
		$now                = current_time( 'mysql' );

		// Statuses first so bikes can reference real IDs.
		$wpdb->insert(
			$status_table,
			array(
				'last_update' => $now,
				'bike_status' => 'Active',
			)
		);
		$status_active = (int) $wpdb->insert_id;

		$wpdb->insert(
			$status_table,
			array(
				'last_update' => $now,
				'bike_status' => 'Retired',
			)
		);
		$status_retired = (int) $wpdb->insert_id;

		$wpdb->insert(
			$status_table,
			array(
				'last_update' => $now,
				'bike_status' => 'Building',
			)
		);
		$status_building = (int) $wpdb->insert_id;

		// Bike 1
		$wpdb->insert(
			$bikes_table,
			array(
				'last_update'     => $now,
				'bike_image_id'   => 0,
				'bike_name'       => 'Trail Rider',
				'bike_desc'       => 'A reliable trail bike for weekend rides.',
				'bike_make'       => 'Specialized',
				'bike_model'      => 'Rockhopper',
				'purchase_date'   => '2018-05-12 00:00:00',
				'serial_number'   => 'DEMO-ROCK-001',
				'bike_status_id'  => $status_active,
			)
		);
		$bike1_id = (int) $wpdb->insert_id;

		// Bike 2
		$wpdb->insert(
			$bikes_table,
			array(
				'last_update'     => $now,
				'bike_image_id'   => 0,
				'bike_name'       => 'Commuter',
				'bike_desc'       => 'Everyday city and path commuting bike.',
				'bike_make'       => 'Trek',
				'bike_model'      => 'FX 2',
				'purchase_date'   => '2020-03-01 00:00:00',
				'serial_number'   => 'DEMO-TREK-002',
				'bike_status_id'  => $status_active,
			)
		);
		$bike2_id = (int) $wpdb->insert_id;

		// Bike 3
		$wpdb->insert(
			$bikes_table,
			array(
				'last_update'     => $now,
				'bike_image_id'   => 0,
				'bike_name'       => 'Project Frame',
				'bike_desc'       => 'Build in progress — demo building status.',
				'bike_make'       => 'Surly',
				'bike_model'      => 'Karate Monkey',
				'purchase_date'   => '2024-01-15 00:00:00',
				'serial_number'   => 'DEMO-SURLY-003',
				'bike_status_id'  => $status_building ? $status_building : $status_retired,
			)
		);
		$bike3_id = (int) $wpdb->insert_id;

		// Maintenance (a few rows)
		$wpdb->insert(
			$maintenance_table,
			array(
				'last_update'       => $now,
				'maintenance_date'  => '2024-06-01 00:00:00',
				'bike_id'           => $bike1_id,
				'maintenance_desc'  => 'New chain and cassette',
				'bike_miles'        => 1200,
			)
		);
		$wpdb->insert(
			$maintenance_table,
			array(
				'last_update'       => $now,
				'maintenance_date'  => '2025-02-10 00:00:00',
				'bike_id'           => $bike1_id,
				'maintenance_desc'  => 'Brake pads replaced',
				'bike_miles'        => 1850,
			)
		);
		$wpdb->insert(
			$maintenance_table,
			array(
				'last_update'       => $now,
				'maintenance_date'  => '2024-11-20 00:00:00',
				'bike_id'           => $bike2_id,
				'maintenance_desc'  => 'Flat tire repair',
				'bike_miles'        => 640,
			)
		);

		// Specs (a few rows)
		$wpdb->insert(
			$specs_table,
			array(
				'last_update' => $now,
				'bike_id'     => $bike1_id,
				'spec_name'   => 'Wheel size',
				'spec_desc'   => '29"',
			)
		);
		$wpdb->insert(
			$specs_table,
			array(
				'last_update' => $now,
				'bike_id'     => $bike1_id,
				'spec_name'   => 'Drivetrain',
				'spec_desc'   => '1x12',
			)
		);
		$wpdb->insert(
			$specs_table,
			array(
				'last_update' => $now,
				'bike_id'     => $bike2_id,
				'spec_name'   => 'Tire size',
				'spec_desc'   => '700x35c',
			)
		);
		$wpdb->insert(
			$specs_table,
			array(
				'last_update' => $now,
				'bike_id'     => $bike3_id,
				'spec_name'   => 'Frame material',
				'spec_desc'   => 'Steel',
			)
		);
	}
}
