<?php
/**
 * Import / export BikePress table data as versioned JSON.
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Build export payloads and apply imports (id-based upsert).
 */
class BikePress_Import_Export {

	const FORMAT = 'bikepress-export';

	/**
	 * Current schema version from options (fallback to activator default).
	 *
	 * @return string
	 */
	public static function current_db_version() {
		$version = get_option( 'bikepress_db_version', '' );
		return $version ? (string) $version : '1.0';
	}

	/**
	 * Build the export document array.
	 *
	 * @return array
	 */
	public static function build_export_document() {
		global $wpdb;

		return array(
			'format'      => self::FORMAT,
			'plugin'      => 'bikepress',
			'db_version'  => self::current_db_version(),
			'exported_at' => current_time( 'mysql' ),
			'data'        => array(
				'statuses'     => self::table_rows( STATUS_TABLE ),
				'bikes'        => self::table_rows( BIKES_TABLE ),
				'specs'        => self::table_rows( SPECS_TABLE ),
				'maintenance'  => self::table_rows( MAINTENANCE_TABLE ),
			),
		);
	}

	/**
	 * Fetch all rows from a table as associative arrays.
	 *
	 * @param string $table Table name.
	 * @return array
	 */
	private static function table_rows( $table ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- trusted prefixed table name.
		$rows = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY id ASC", ARRAY_A );
		return is_array( $rows ) ? $rows : array();
	}

	/**
	 * Validate and import a decoded export document.
	 *
	 * @param array $doc Decoded JSON.
	 * @return array|\WP_Error Stats on success, WP_Error on failure.
	 */
	public static function import_document( $doc ) {
		if ( ! is_array( $doc ) ) {
			return new WP_Error( 'invalid_json', __( 'Import file is not valid JSON.', 'bikepress' ) );
		}

		if ( empty( $doc['format'] ) || self::FORMAT !== $doc['format'] ) {
			return new WP_Error( 'invalid_format', __( 'Import file is not a BikePress export.', 'bikepress' ) );
		}

		$file_version = isset( $doc['db_version'] ) ? (string) $doc['db_version'] : '';
		$current      = self::current_db_version();
		if ( '' === $file_version || $file_version !== $current ) {
			return new WP_Error(
				'db_version_mismatch',
				sprintf(
					/* translators: 1: file db version, 2: current db version */
					__( 'Import blocked: file db_version “%1$s” does not match this site (“%2$s”).', 'bikepress' ),
					$file_version ? $file_version : __( '(missing)', 'bikepress' ),
					$current
				)
			);
		}

		if ( empty( $doc['data'] ) || ! is_array( $doc['data'] ) ) {
			return new WP_Error( 'missing_data', __( 'Import file has no data section.', 'bikepress' ) );
		}

		$data = $doc['data'];
		$stats = array(
			'statuses'    => array( 'inserted' => 0, 'updated' => 0, 'errors' => 0 ),
			'bikes'       => array( 'inserted' => 0, 'updated' => 0, 'errors' => 0 ),
			'specs'       => array( 'inserted' => 0, 'updated' => 0, 'errors' => 0 ),
			'maintenance' => array( 'inserted' => 0, 'updated' => 0, 'errors' => 0 ),
		);

		$stats['statuses']    = self::upsert_collection( STATUS_TABLE, isset( $data['statuses'] ) ? $data['statuses'] : array(), array( 'last_update', 'bike_status' ), 'statuses' );
		$stats['bikes']       = self::upsert_bikes( isset( $data['bikes'] ) ? $data['bikes'] : array() );
		$stats['specs']       = self::upsert_collection( SPECS_TABLE, isset( $data['specs'] ) ? $data['specs'] : array(), array( 'last_update', 'bike_id', 'spec_name', 'spec_desc' ), 'specs' );
		$stats['maintenance'] = self::upsert_collection( MAINTENANCE_TABLE, isset( $data['maintenance'] ) ? $data['maintenance'] : array(), array( 'last_update', 'bike_id', 'maintenance_date', 'maintenance_desc', 'bike_miles' ), 'maintenance' );

		self::fix_auto_increment( STATUS_TABLE );
		self::fix_auto_increment( BIKES_TABLE );
		self::fix_auto_increment( SPECS_TABLE );
		self::fix_auto_increment( MAINTENANCE_TABLE );

		return $stats;
	}

	/**
	 * Upsert bike rows; clear bike_image_id when attachment missing.
	 *
	 * @param array $rows Row arrays.
	 * @return array Stats.
	 */
	private static function upsert_bikes( $rows ) {
		$stats = array( 'inserted' => 0, 'updated' => 0, 'errors' => 0 );
		if ( ! is_array( $rows ) ) {
			return $stats;
		}

		$columns = array( 'bike_image_id', 'last_update', 'bike_name', 'bike_desc', 'bike_make', 'bike_model', 'serial_number', 'purchase_date', 'bike_status_id' );

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) || empty( $row['id'] ) ) {
				$stats['errors']++;
				continue;
			}

			$id = absint( $row['id'] );
			if ( $id <= 0 ) {
				$stats['errors']++;
				continue;
			}

			$payload = self::pick_columns( $row, $columns );
			$image_id = isset( $payload['bike_image_id'] ) ? absint( $payload['bike_image_id'] ) : 0;
			if ( $image_id > 0 && ! wp_attachment_is_image( $image_id ) ) {
				$payload['bike_image_id'] = 0;
			}

			$result = self::upsert_row( BIKES_TABLE, $id, $payload );
			if ( is_wp_error( $result ) ) {
				$stats['errors']++;
			} else {
				$stats[ $result ]++;
			}
		}

		return $stats;
	}

	/**
	 * Upsert a collection of rows for a simple table.
	 *
	 * @param string $table   Table name.
	 * @param array  $rows    Rows.
	 * @param array  $columns Columns to write (excluding id).
	 * @param string $label   Unused label for clarity.
	 * @return array Stats.
	 */
	private static function upsert_collection( $table, $rows, $columns, $label = '' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$stats = array( 'inserted' => 0, 'updated' => 0, 'errors' => 0 );
		if ( ! is_array( $rows ) ) {
			return $stats;
		}

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) || empty( $row['id'] ) ) {
				$stats['errors']++;
				continue;
			}

			$id = absint( $row['id'] );
			if ( $id <= 0 ) {
				$stats['errors']++;
				continue;
			}

			$payload = self::pick_columns( $row, $columns );
			$result  = self::upsert_row( $table, $id, $payload );
			if ( is_wp_error( $result ) ) {
				$stats['errors']++;
			} else {
				$stats[ $result ]++;
			}
		}

		return $stats;
	}

	/**
	 * Pick and coerce selected columns from a row.
	 *
	 * @param array $row     Source row.
	 * @param array $columns Allowed columns.
	 * @return array
	 */
	private static function pick_columns( $row, $columns ) {
		$out = array();
		foreach ( $columns as $col ) {
			if ( ! array_key_exists( $col, $row ) ) {
				continue;
			}
			$value = $row[ $col ];
			if ( in_array( $col, array( 'bike_id', 'bike_status_id', 'bike_image_id', 'bike_miles' ), true ) ) {
				$out[ $col ] = absint( $value );
			} else {
				$out[ $col ] = is_string( $value ) ? $value : (string) $value;
			}
		}
		return $out;
	}

	/**
	 * Insert or update one row by id.
	 *
	 * @param string $table   Table.
	 * @param int    $id      Row id.
	 * @param array  $payload Column values without id.
	 * @return string|\WP_Error 'inserted'|'updated' or error.
	 */
	private static function upsert_row( $table, $id, $payload ) {
		global $wpdb;

		$exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE id = %d", $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( $exists ) {
			$updated = $wpdb->update( $table, $payload, array( 'id' => $id ) );
			if ( false === $updated ) {
				return new WP_Error( 'update_failed', 'update failed' );
			}
			return 'updated';
		}

		$insert            = $payload;
		$insert['id']      = $id;
		$inserted          = $wpdb->insert( $table, $insert );
		if ( false === $inserted ) {
			return new WP_Error( 'insert_failed', 'insert failed' );
		}
		return 'inserted';
	}

	/**
	 * Bump AUTO_INCREMENT past the current max id.
	 *
	 * @param string $table Table name.
	 */
	private static function fix_auto_increment( $table ) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$max = (int) $wpdb->get_var( "SELECT MAX(id) FROM {$table}" );
		$next = $max > 0 ? $max + 1 : 1;
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query( "ALTER TABLE {$table} AUTO_INCREMENT = {$next}" );
	}
}
