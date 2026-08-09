<?php
/**
 * Shared table-name helpers for BikePress.
 *
 * @package BikePress
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Return the bikes table name with the current $wpdb prefix.
 *
 * @return string
 */
function bikepress_bikes_table() {
	global $wpdb;
	return $wpdb->prefix . 'bikes';
}

/**
 * @return string
 */
function bikepress_maintenance_table() {
	global $wpdb;
	return $wpdb->prefix . 'bike_maintenance';
}

/**
 * @return string
 */
function bikepress_specs_table() {
	global $wpdb;
	return $wpdb->prefix . 'bike_specs';
}

/**
 * @return string
 */
function bikepress_status_table() {
	global $wpdb;
	return $wpdb->prefix . 'bike_status';
}

/**
 * Whether demo/test data should be loaded on activation.
 * Default OFF; set BIKEPRESS_LOAD_DEMO to true in wp-config to seed on activate.
 *
 * @return bool
 */
function bikepress_should_load_demo() {
	return defined( 'BIKEPRESS_LOAD_DEMO' ) && BIKEPRESS_LOAD_DEMO;
}

/**
 * Whether bikes or statuses already exist (blocks demo re-import).
 *
 * @return bool
 */
function bikepress_has_existing_data() {
	global $wpdb;

	$bikes_table  = bikepress_bikes_table();
	$status_table = bikepress_status_table();

	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table names are prefixed identifiers.
	$bike_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$bikes_table}" );
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$status_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$status_table}" );

	return ( $bike_count > 0 || $status_count > 0 );
}

/**
 * Whether the current admin screen belongs to BikePress.
 *
 * @param string $hook Current admin hook suffix.
 * @return bool
 */
function bikepress_is_plugin_admin_screen( $hook ) {
	$screens = array(
		'toplevel_page_my-bikes',
		'my-bikes_page_bikes-admin',
		'my-bikes_page_specs-admin',
		'my-bikes_page_maint-admin',
		'my-bikes_page_supporting-data-admin',
		'my-bikes_page_status-admin',
		'my-bikes_page_import-export-admin',
		'my-bikes_page_bikepress-privacy',
		'my-bikes_page_bikepress-terms',
		'my-bikes_page_bikepress-about',
		'my-bikes_page_data-admin',
	);
	if ( in_array( $hook, $screens, true ) ) {
		return true;
	}

	// Fallback: some hosts/contexts pass an unexpected $hook; match on page slug.
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$pages = array(
		'my-bikes',
		'bikes-admin',
		'specs-admin',
		'maint-admin',
		'supporting-data-admin',
		'status-admin',
		'import-export-admin',
		'bikepress-privacy',
		'bikepress-terms',
		'bikepress-about',
		'data-admin',
	);
	return in_array( $page, $pages, true );
}
