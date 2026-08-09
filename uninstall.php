<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://www.offthekitchen.com
 * @package    BikePress
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$bikes_table       = $wpdb->prefix . 'bikes';
$maintenance_table = $wpdb->prefix . 'bike_maintenance';
$specs_table       = $wpdb->prefix . 'bike_specs';
$status_table      = $wpdb->prefix . 'bike_status';

// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table names are prefixed identifiers.
$wpdb->query( "DROP TABLE IF EXISTS {$bikes_table}" );
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$maintenance_table}" );
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$specs_table}" );
// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$status_table}" );

delete_option( 'bikepress_db_version' );
delete_option( 'steves_bike_plugin_db_version' );
delete_option( 'bikepress_show_demo_notice' );
delete_option( 'bike_name' );
delete_option( 'bike_make' );
