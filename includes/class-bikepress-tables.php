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
 * Default ON when the constant is not defined (pre-CRUD learning default).
 *
 * @return bool
 */
function bikepress_should_load_demo() {
	return ! defined( 'BIKEPRESS_LOAD_DEMO' ) || BIKEPRESS_LOAD_DEMO;
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
		'my-bikes_page_data-admin',
	);
	return in_array( $hook, $screens, true );
}
