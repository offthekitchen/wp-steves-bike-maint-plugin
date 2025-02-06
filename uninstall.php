<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
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

