<?php
/**
 * Built-in plugin data for BikePress (non-demo reference rows).
 *
 * @package BikePress
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ensures required plugin data exists after schema create/upgrade.
 */
class BikePress_Plugin_Data {

	/**
	 * Core status labels seeded on activation (insert-if-missing).
	 *
	 * @return string[]
	 */
	public static function core_status_names() {
		return array( 'Active', 'Retired', 'Building' );
	}

	/**
	 * Core type labels seeded on activation (insert-if-missing).
	 *
	 * @return string[]
	 */
	public static function core_type_names() {
		return array( 'Road', 'Mountain', 'Gravel', 'Commuter', 'eBike' );
	}

	/**
	 * Ensure each core status exists; return map of name => id.
	 *
	 * @return array<string,int>
	 */
	public static function ensure_core_statuses() {
		global $wpdb;

		$status_table = bikepress_status_table();
		$now          = current_time( 'mysql' );
		$ids          = array();

		foreach ( self::core_status_names() as $name ) {
			$existing_id = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$status_table} WHERE bike_status = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$name
				)
			);

			if ( $existing_id > 0 ) {
				$ids[ $name ] = $existing_id;
				continue;
			}

			$wpdb->insert(
				$status_table,
				array(
					'last_update' => $now,
					'bike_status' => $name,
				)
			);
			$ids[ $name ] = (int) $wpdb->insert_id;
		}

		return $ids;
	}

	/**
	 * Ensure each core type exists; return map of name => id.
	 *
	 * @return array<string,int>
	 */
	public static function ensure_core_types() {
		global $wpdb;

		$type_table = bikepress_type_table();
		$now        = current_time( 'mysql' );
		$ids        = array();

		foreach ( self::core_type_names() as $name ) {
			$existing_id = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$type_table} WHERE bike_type = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$name
				)
			);

			if ( $existing_id > 0 ) {
				$ids[ $name ] = $existing_id;
				continue;
			}

			$wpdb->insert(
				$type_table,
				array(
					'last_update' => $now,
					'bike_type'   => $name,
				)
			);
			$ids[ $name ] = (int) $wpdb->insert_id;
		}

		return $ids;
	}

	/**
	 * Resolve a status ID by exact name, or Unknown if that status is missing.
	 *
	 * @param string $name Status label.
	 * @return int
	 */
	public static function resolve_status_id_or_unknown( $name ) {
		global $wpdb;

		$name = (string) $name;
		if ( '' === $name ) {
			return function_exists( 'bikepress_get_or_create_unknown_status_id' )
				? bikepress_get_or_create_unknown_status_id()
				: 0;
		}

		$status_table = bikepress_status_table();
		$existing_id  = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$status_table} WHERE bike_status = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$name
			)
		);

		if ( $existing_id > 0 ) {
			return $existing_id;
		}

		return function_exists( 'bikepress_get_or_create_unknown_status_id' )
			? bikepress_get_or_create_unknown_status_id()
			: 0;
	}

	/**
	 * Resolve a type ID by exact name, or Unknown if that type is missing.
	 *
	 * @param string $name Type label.
	 * @return int
	 */
	public static function resolve_type_id_or_unknown( $name ) {
		global $wpdb;

		$name = (string) $name;
		if ( '' === $name ) {
			return function_exists( 'bikepress_get_or_create_unknown_type_id' )
				? bikepress_get_or_create_unknown_type_id()
				: 0;
		}

		$type_table  = bikepress_type_table();
		$existing_id = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$type_table} WHERE bike_type = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$name
			)
		);

		if ( $existing_id > 0 ) {
			return $existing_id;
		}

		return function_exists( 'bikepress_get_or_create_unknown_type_id' )
			? bikepress_get_or_create_unknown_type_id()
			: 0;
	}
}
