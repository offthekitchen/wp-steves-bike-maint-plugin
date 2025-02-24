<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://www.offthekitchen.com
 *
 * @package    STEVES_BIKE_MAINTENANCE
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$bikes_table_name = $wpdb->prefix . "bikes"; 
$maintenance_table_name = $wpdb->prefix . "bike_maintenance"; 
$specs_table_name = $wpdb->prefix . "bike_specs"; 
$status_table_name = $wpdb->prefix . "bike_status"; 

// UNCOMMENT AFTER TESTING COMPLETE
 
$sql = "DROP TABLE IF EXISTS $bikes_table_name";

$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS $maintenance_table_name";

$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS $specs_table_name";

$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS $status_table_name";

$wpdb->query($sql);

