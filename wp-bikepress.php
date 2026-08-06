<?php

/**
 *
 * @link              http://www.offthekitchen.com
 * @since             1.0.0
 * @package           BikePress
 *
 * @wordpress-plugin
 * Plugin Name:       BikePress
 * Plugin URI:        http://www.offthekitchen.com
 * Description:       Track bicycles, specifications, and maintenance records.
 * Version:           1.1.0
 * Author:            Off the Kitchen
 * Author URI:        http://www.offthekitchen.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bikepress
 * Domain Path:       /languages
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BIKEPRESS_VERSION', '1.1.0' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-bikepress-tables.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-bikepress-import-export.php';

/**
 * Legacy-style table constants for existing includes/partials.
 * Values use the current $wpdb prefix.
 */
define( 'BIKES_TABLE', bikepress_bikes_table() );
define( 'MAINTENANCE_TABLE', bikepress_maintenance_table() );
define( 'SPECS_TABLE', bikepress_specs_table() );
define( 'STATUS_TABLE', bikepress_status_table() );

add_action( 'admin_menu', 'bike_maintenance_setup_menu' );
add_action( 'admin_enqueue_scripts', 'bikepress_enqueue_admin_assets' );
add_action( 'admin_head', 'bikepress_hide_hub_only_submenu_items' );
add_action( 'admin_post_bikepress_save_bike', 'bikepress_handle_save_bike' );
add_action( 'admin_post_bikepress_delete_bike', 'bikepress_handle_delete_bike' );
add_action( 'admin_post_bikepress_save_spec', 'bikepress_handle_save_spec' );
add_action( 'admin_post_bikepress_delete_spec', 'bikepress_handle_delete_spec' );
add_action( 'admin_post_bikepress_save_maintenance', 'bikepress_handle_save_maintenance' );
add_action( 'admin_post_bikepress_delete_maintenance', 'bikepress_handle_delete_maintenance' );
add_action( 'admin_post_bikepress_save_status', 'bikepress_handle_save_status' );
add_action( 'admin_post_bikepress_delete_status', 'bikepress_handle_delete_status' );
add_action( 'admin_post_bikepress_export_data', 'bikepress_handle_export_data' );
add_action( 'admin_post_bikepress_import_data', 'bikepress_handle_import_data' );

/**
 * Enqueue BikePress admin CSS/fonts on plugin screens; media JS on bikes-admin only.
 *
 * @param string $hook Current admin page hook.
 */
function bikepress_enqueue_admin_assets( $hook ) {
	if ( ! bikepress_is_plugin_admin_screen( $hook ) ) {
		return;
	}

	wp_enqueue_style(
		'bikepress-admin',
		plugins_url( 'admin/css/bikepress-admin.css', __FILE__ ),
		array(),
		BIKEPRESS_VERSION
	);
	wp_enqueue_style(
		'bikepress-google-fonts',
		'https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;700&display=swap',
		array(),
		null
	);

	if ( 'my-bikes_page_bikes-admin' === $hook ) {
		wp_enqueue_media();
		wp_enqueue_script(
			'bikepress-admin-script',
			plugins_url( '/js/admin.js', __FILE__ ),
			array( 'jquery' ),
			BIKEPRESS_VERSION,
			true
		);
	}
}

/**
 * Keep hub-only pages registered for access, but hide them from the left submenu.
 */
function bikepress_hide_hub_only_submenu_items() {
	echo '<style id="bikepress-hide-hub-menus">#toplevel_page_my-bikes .wp-submenu a[href*="page=status-admin"],#toplevel_page_my-bikes .wp-submenu a[href*="page=import-export-admin"],#toplevel_page_my-bikes .wp-submenu a[href*="page=data-admin"]{display:none!important;}</style>';
}

/**
 * Establish the Bike Admin menu.
 */
function bike_maintenance_setup_menu() {
	$bikes_icon = plugins_url( 'img/bikes-admin-icon-16.png', __FILE__ );

	add_menu_page(
		__( 'My Bikes', 'bikepress' ),
		__( 'My Bikes', 'bikepress' ),
		'manage_options',
		'my-bikes',
		'my_bikes',
		$bikes_icon
	);

	add_submenu_page( 'my-bikes', __( 'Manage Bikes', 'bikepress' ), __( 'Manage Bikes', 'bikepress' ), 'manage_options', 'bikes-admin', 'bikes_admin' );
	add_submenu_page( 'my-bikes', __( 'Manage Specs', 'bikepress' ), __( 'Manage Specs', 'bikepress' ), 'manage_options', 'specs-admin', 'specs_admin' );
	add_submenu_page( 'my-bikes', __( 'Manage Maintenance Records', 'bikepress' ), __( 'Manage Maintenance Records', 'bikepress' ), 'manage_options', 'maint-admin', 'maint_admin' );
	add_submenu_page( 'my-bikes', __( 'Manage Supporting Data', 'bikepress' ), __( 'Manage Supporting Data', 'bikepress' ), 'manage_options', 'supporting-data-admin', 'supporting_data_admin' );

	// Hub-only pages: keep registered under My Bikes for capability checks, hide via CSS.
	add_submenu_page( 'my-bikes', __( 'Manage Statuses', 'bikepress' ), __( 'Manage Statuses', 'bikepress' ), 'manage_options', 'status-admin', 'status_admin' );
	add_submenu_page( 'my-bikes', __( 'Import / Export Data', 'bikepress' ), __( 'Import / Export Data', 'bikepress' ), 'manage_options', 'import-export-admin', 'import_export_admin' );

	// Legacy slug redirect (hidden via CSS).
	add_submenu_page( 'my-bikes', __( 'Manage Data', 'bikepress' ), __( 'Manage Data', 'bikepress' ), 'manage_options', 'data-admin', 'bikepress_legacy_data_admin_redirect' );
}

/**
 * Redirect helper for Manage Bikes with a notice query arg.
 *
 * @param string $notice Notice slug.
 * @param array  $extra  Extra query args.
 */
function bikepress_redirect_bikes_admin( $notice = '', $extra = array() ) {
	$args = array( 'page' => 'bikes-admin' );
	if ( $notice ) {
		$args['bikepress_notice'] = $notice;
	}
	$args = array_merge( $args, $extra );
	wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
	exit;
}

/**
 * Save (insert/update) a bike.
 */
function bikepress_handle_save_bike() {
	if ( ! isset( $_POST['bikepress_bike_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_bike_nonce'] ) ), 'bikepress_save_bike' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$bike_id         = isset( $_POST['bike_id'] ) ? absint( $_POST['bike_id'] ) : 0;
	$bike_name       = isset( $_POST['bike_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bike_name'] ) ) : '';
	$bike_make       = isset( $_POST['bike_make'] ) ? sanitize_text_field( wp_unslash( $_POST['bike_make'] ) ) : '';
	$bike_model      = isset( $_POST['bike_model'] ) ? sanitize_text_field( wp_unslash( $_POST['bike_model'] ) ) : '';
	$serial_number   = isset( $_POST['serial_number'] ) ? sanitize_text_field( wp_unslash( $_POST['serial_number'] ) ) : '';
	$bike_status_id  = isset( $_POST['bike_status_id'] ) ? absint( $_POST['bike_status_id'] ) : 0;
	$bike_desc       = isset( $_POST['bike_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['bike_desc'] ) ) : '';
	$bike_image_id   = isset( $_POST['bike_image_id'] ) ? absint( $_POST['bike_image_id'] ) : 0;
	$purchase_raw    = isset( $_POST['purchase_date'] ) ? sanitize_text_field( wp_unslash( $_POST['purchase_date'] ) ) : '';

	if ( '' === $bike_name ) {
		bikepress_redirect_bikes_admin( 'bike_name_required', array( 'action' => $bike_id ? 'edit' : 'new', 'bike_id' => $bike_id ) );
	}

	if ( $bike_status_id <= 0 ) {
		bikepress_redirect_bikes_admin( 'bike_status_required', array( 'action' => $bike_id ? 'edit' : 'new', 'bike_id' => $bike_id ) );
	}

	$purchase_date = '0000-00-00 00:00:00';
	if ( $purchase_raw && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $purchase_raw ) ) {
		$purchase_date = $purchase_raw . ' 00:00:00';
	}

	$row = array(
		'last_update'    => current_time( 'mysql' ),
		'bike_name'      => $bike_name,
		'bike_make'      => $bike_make,
		'bike_model'     => $bike_model,
		'serial_number'  => $serial_number,
		'bike_status_id' => $bike_status_id,
		'bike_desc'      => $bike_desc,
		'bike_image_id'  => $bike_image_id,
		'purchase_date'  => $purchase_date,
	);

	$formats = array( '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s' );

	if ( $bike_id > 0 ) {
		$updated = $wpdb->update(
			BIKES_TABLE,
			$row,
			array( 'id' => $bike_id ),
			$formats,
			array( '%d' )
		);
		if ( false === $updated ) {
			bikepress_redirect_bikes_admin( 'bike_save_error', array( 'action' => 'edit', 'bike_id' => $bike_id ) );
		}
		bikepress_redirect_bikes_admin( 'bike_updated' );
	}

	$inserted = $wpdb->insert( BIKES_TABLE, $row, $formats );
	if ( false === $inserted ) {
		bikepress_redirect_bikes_admin( 'bike_save_error', array( 'action' => 'new' ) );
	}
	bikepress_redirect_bikes_admin( 'bike_created' );
}

/**
 * Delete a bike and its related specs and maintenance rows.
 */
function bikepress_handle_delete_bike() {
	if ( ! isset( $_POST['bikepress_delete_bike_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_delete_bike_nonce'] ) ), 'bikepress_delete_bike' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$bike_id = isset( $_POST['bike_id'] ) ? absint( $_POST['bike_id'] ) : 0;
	if ( $bike_id <= 0 ) {
		bikepress_redirect_bikes_admin( 'bike_delete_error' );
	}

	$wpdb->delete( SPECS_TABLE, array( 'bike_id' => $bike_id ), array( '%d' ) );
	$wpdb->delete( MAINTENANCE_TABLE, array( 'bike_id' => $bike_id ), array( '%d' ) );
	$deleted = $wpdb->delete( BIKES_TABLE, array( 'id' => $bike_id ), array( '%d' ) );

	if ( false === $deleted || 0 === $deleted ) {
		bikepress_redirect_bikes_admin( 'bike_delete_error' );
	}

	bikepress_redirect_bikes_admin( 'bike_deleted' );
}

/**
 * Redirect helper for a BikePress admin page.
 *
 * @param string $page   Admin page slug.
 * @param string $notice Notice slug.
 * @param array  $extra  Extra query args.
 */
function bikepress_redirect_admin_page( $page, $notice = '', $extra = array() ) {
	$args = array( 'page' => $page );
	if ( $notice ) {
		$args['bikepress_notice'] = $notice;
	}
	$args = array_merge( $args, $extra );
	wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
	exit;
}

/**
 * Whether a bike ID exists.
 *
 * @param int $bike_id Bike ID.
 * @return bool
 */
function bikepress_bike_exists( $bike_id ) {
	global $wpdb;
	$bike_id = absint( $bike_id );
	if ( $bike_id <= 0 ) {
		return false;
	}
	$found = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . BIKES_TABLE . ' WHERE id = %d', $bike_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return ! empty( $found );
}

/**
 * Save (insert/update) a spec.
 */
function bikepress_handle_save_spec() {
	if ( ! isset( $_POST['bikepress_spec_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_spec_nonce'] ) ), 'bikepress_save_spec' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$spec_id   = isset( $_POST['spec_id'] ) ? absint( $_POST['spec_id'] ) : 0;
	$bike_id   = isset( $_POST['bike_id'] ) ? absint( $_POST['bike_id'] ) : 0;
	$spec_name = isset( $_POST['spec_name'] ) ? sanitize_text_field( wp_unslash( $_POST['spec_name'] ) ) : '';
	$spec_desc = isset( $_POST['spec_desc'] ) ? sanitize_text_field( wp_unslash( $_POST['spec_desc'] ) ) : '';
	$filter_id = isset( $_POST['filter_bike_id'] ) ? absint( $_POST['filter_bike_id'] ) : 0;

	$extra = array();
	if ( $filter_id > 0 ) {
		$extra['bike_id'] = $filter_id;
	}

	if ( ! bikepress_bike_exists( $bike_id ) ) {
		bikepress_redirect_admin_page( 'specs-admin', 'spec_bike_required', array_merge( $extra, array( 'action' => $spec_id ? 'edit' : 'new', 'spec_id' => $spec_id ) ) );
	}
	if ( '' === $spec_name ) {
		bikepress_redirect_admin_page( 'specs-admin', 'spec_name_required', array_merge( $extra, array( 'action' => $spec_id ? 'edit' : 'new', 'spec_id' => $spec_id, 'bike_id' => $bike_id ) ) );
	}

	$row = array(
		'last_update' => current_time( 'mysql' ),
		'bike_id'     => $bike_id,
		'spec_name'   => $spec_name,
		'spec_desc'   => $spec_desc,
	);
	$formats = array( '%s', '%d', '%s', '%s' );

	if ( $spec_id > 0 ) {
		$updated = $wpdb->update( SPECS_TABLE, $row, array( 'id' => $spec_id ), $formats, array( '%d' ) );
		if ( false === $updated ) {
			bikepress_redirect_admin_page( 'specs-admin', 'spec_save_error', array_merge( $extra, array( 'action' => 'edit', 'spec_id' => $spec_id ) ) );
		}
		bikepress_redirect_admin_page( 'specs-admin', 'spec_updated', $extra );
	}

	$inserted = $wpdb->insert( SPECS_TABLE, $row, $formats );
	if ( false === $inserted ) {
		bikepress_redirect_admin_page( 'specs-admin', 'spec_save_error', array_merge( $extra, array( 'action' => 'new' ) ) );
	}
	bikepress_redirect_admin_page( 'specs-admin', 'spec_created', $extra );
}

/**
 * Delete a single spec row.
 */
function bikepress_handle_delete_spec() {
	if ( ! isset( $_POST['bikepress_delete_spec_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_delete_spec_nonce'] ) ), 'bikepress_delete_spec' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$spec_id   = isset( $_POST['spec_id'] ) ? absint( $_POST['spec_id'] ) : 0;
	$filter_id = isset( $_POST['filter_bike_id'] ) ? absint( $_POST['filter_bike_id'] ) : 0;
	$extra     = $filter_id > 0 ? array( 'bike_id' => $filter_id ) : array();

	if ( $spec_id <= 0 ) {
		bikepress_redirect_admin_page( 'specs-admin', 'spec_delete_error', $extra );
	}

	$deleted = $wpdb->delete( SPECS_TABLE, array( 'id' => $spec_id ), array( '%d' ) );
	if ( false === $deleted || 0 === $deleted ) {
		bikepress_redirect_admin_page( 'specs-admin', 'spec_delete_error', $extra );
	}
	bikepress_redirect_admin_page( 'specs-admin', 'spec_deleted', $extra );
}

/**
 * Save (insert/update) a maintenance record.
 */
function bikepress_handle_save_maintenance() {
	if ( ! isset( $_POST['bikepress_maint_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_maint_nonce'] ) ), 'bikepress_save_maintenance' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$maint_id  = isset( $_POST['maint_id'] ) ? absint( $_POST['maint_id'] ) : 0;
	$bike_id   = isset( $_POST['bike_id'] ) ? absint( $_POST['bike_id'] ) : 0;
	$desc      = isset( $_POST['maintenance_desc'] ) ? sanitize_text_field( wp_unslash( $_POST['maintenance_desc'] ) ) : '';
	$miles     = isset( $_POST['bike_miles'] ) ? absint( $_POST['bike_miles'] ) : 0;
	$date_raw  = isset( $_POST['maintenance_date'] ) ? sanitize_text_field( wp_unslash( $_POST['maintenance_date'] ) ) : '';
	$filter_id = isset( $_POST['filter_bike_id'] ) ? absint( $_POST['filter_bike_id'] ) : 0;

	$extra = array();
	if ( $filter_id > 0 ) {
		$extra['bike_id'] = $filter_id;
	}

	if ( ! bikepress_bike_exists( $bike_id ) ) {
		bikepress_redirect_admin_page( 'maint-admin', 'maint_bike_required', array_merge( $extra, array( 'action' => $maint_id ? 'edit' : 'new', 'maint_id' => $maint_id ) ) );
	}
	if ( '' === $desc ) {
		bikepress_redirect_admin_page( 'maint-admin', 'maint_desc_required', array_merge( $extra, array( 'action' => $maint_id ? 'edit' : 'new', 'maint_id' => $maint_id, 'bike_id' => $bike_id ) ) );
	}

	$maintenance_date = '0000-00-00 00:00:00';
	if ( $date_raw && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date_raw ) ) {
		$maintenance_date = $date_raw . ' 00:00:00';
	} else {
		bikepress_redirect_admin_page( 'maint-admin', 'maint_date_required', array_merge( $extra, array( 'action' => $maint_id ? 'edit' : 'new', 'maint_id' => $maint_id, 'bike_id' => $bike_id ) ) );
	}

	$row = array(
		'last_update'       => current_time( 'mysql' ),
		'bike_id'           => $bike_id,
		'maintenance_date'  => $maintenance_date,
		'maintenance_desc'  => $desc,
		'bike_miles'        => $miles,
	);
	$formats = array( '%s', '%d', '%s', '%s', '%d' );

	if ( $maint_id > 0 ) {
		$updated = $wpdb->update( MAINTENANCE_TABLE, $row, array( 'id' => $maint_id ), $formats, array( '%d' ) );
		if ( false === $updated ) {
			bikepress_redirect_admin_page( 'maint-admin', 'maint_save_error', array_merge( $extra, array( 'action' => 'edit', 'maint_id' => $maint_id ) ) );
		}
		bikepress_redirect_admin_page( 'maint-admin', 'maint_updated', $extra );
	}

	$inserted = $wpdb->insert( MAINTENANCE_TABLE, $row, $formats );
	if ( false === $inserted ) {
		bikepress_redirect_admin_page( 'maint-admin', 'maint_save_error', array_merge( $extra, array( 'action' => 'new' ) ) );
	}
	bikepress_redirect_admin_page( 'maint-admin', 'maint_created', $extra );
}

/**
 * Delete a single maintenance row.
 */
function bikepress_handle_delete_maintenance() {
	if ( ! isset( $_POST['bikepress_delete_maint_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_delete_maint_nonce'] ) ), 'bikepress_delete_maintenance' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$maint_id  = isset( $_POST['maint_id'] ) ? absint( $_POST['maint_id'] ) : 0;
	$filter_id = isset( $_POST['filter_bike_id'] ) ? absint( $_POST['filter_bike_id'] ) : 0;
	$extra     = $filter_id > 0 ? array( 'bike_id' => $filter_id ) : array();

	if ( $maint_id <= 0 ) {
		bikepress_redirect_admin_page( 'maint-admin', 'maint_delete_error', $extra );
	}

	$deleted = $wpdb->delete( MAINTENANCE_TABLE, array( 'id' => $maint_id ), array( '%d' ) );
	if ( false === $deleted || 0 === $deleted ) {
		bikepress_redirect_admin_page( 'maint-admin', 'maint_delete_error', $extra );
	}
	bikepress_redirect_admin_page( 'maint-admin', 'maint_deleted', $extra );
}

/**
 * Get the Unknown status ID, creating the row if it does not exist.
 *
 * @return int Status ID, or 0 on failure.
 */
function bikepress_get_or_create_unknown_status_id() {
	global $wpdb;

	$existing = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT id FROM ' . STATUS_TABLE . ' WHERE bike_status = %s LIMIT 1',
			'Unknown'
		)
	); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	if ( $existing ) {
		return absint( $existing );
	}

	$inserted = $wpdb->insert(
		STATUS_TABLE,
		array(
			'last_update' => current_time( 'mysql' ),
			'bike_status' => 'Unknown',
		),
		array( '%s', '%s' )
	);

	if ( false === $inserted ) {
		return 0;
	}

	return absint( $wpdb->insert_id );
}

/**
 * Whether a status row is the Unknown status.
 *
 * @param int $status_id Status ID.
 * @return bool
 */
function bikepress_is_unknown_status( $status_id ) {
	global $wpdb;

	$status_id = absint( $status_id );
	if ( $status_id <= 0 ) {
		return false;
	}

	$name = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT bike_status FROM ' . STATUS_TABLE . ' WHERE id = %d',
			$status_id
		)
	); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	return 'Unknown' === $name;
}

/**
 * Save (insert/update) a status.
 */
function bikepress_handle_save_status() {
	if ( ! isset( $_POST['bikepress_status_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_status_nonce'] ) ), 'bikepress_save_status' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$status_id   = isset( $_POST['status_id'] ) ? absint( $_POST['status_id'] ) : 0;
	$bike_status = isset( $_POST['bike_status'] ) ? sanitize_text_field( wp_unslash( $_POST['bike_status'] ) ) : '';

	if ( '' === $bike_status ) {
		bikepress_redirect_admin_page( 'status-admin', 'status_name_required', array( 'action' => $status_id ? 'edit' : 'new', 'status_id' => $status_id ) );
	}

	$row = array(
		'last_update' => current_time( 'mysql' ),
		'bike_status' => $bike_status,
	);
	$formats = array( '%s', '%s' );

	if ( $status_id > 0 ) {
		$updated = $wpdb->update( STATUS_TABLE, $row, array( 'id' => $status_id ), $formats, array( '%d' ) );
		if ( false === $updated ) {
			bikepress_redirect_admin_page( 'status-admin', 'status_save_error', array( 'action' => 'edit', 'status_id' => $status_id ) );
		}
		bikepress_redirect_admin_page( 'status-admin', 'status_updated' );
	}

	$inserted = $wpdb->insert( STATUS_TABLE, $row, $formats );
	if ( false === $inserted ) {
		bikepress_redirect_admin_page( 'status-admin', 'status_save_error', array( 'action' => 'new' ) );
	}
	bikepress_redirect_admin_page( 'status-admin', 'status_created' );
}

/**
 * Delete a status, reassigning bikes to Unknown.
 */
function bikepress_handle_delete_status() {
	if ( ! isset( $_POST['bikepress_delete_status_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_delete_status_nonce'] ) ), 'bikepress_delete_status' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	global $wpdb;

	$status_id = isset( $_POST['status_id'] ) ? absint( $_POST['status_id'] ) : 0;
	if ( $status_id <= 0 ) {
		bikepress_redirect_admin_page( 'status-admin', 'status_delete_error' );
	}

	if ( bikepress_is_unknown_status( $status_id ) ) {
		bikepress_redirect_admin_page( 'status-admin', 'status_unknown_protected' );
	}

	$unknown_id = bikepress_get_or_create_unknown_status_id();
	if ( $unknown_id <= 0 ) {
		bikepress_redirect_admin_page( 'status-admin', 'status_delete_error' );
	}

	$wpdb->update(
		BIKES_TABLE,
		array( 'bike_status_id' => $unknown_id ),
		array( 'bike_status_id' => $status_id ),
		array( '%d' ),
		array( '%d' )
	);

	$deleted = $wpdb->delete( STATUS_TABLE, array( 'id' => $status_id ), array( '%d' ) );
	if ( false === $deleted || 0 === $deleted ) {
		bikepress_redirect_admin_page( 'status-admin', 'status_delete_error' );
	}
	bikepress_redirect_admin_page( 'status-admin', 'status_deleted' );
}

/**
 * Download a JSON export of all BikePress data.
 */
function bikepress_handle_export_data() {
	if ( ! isset( $_POST['bikepress_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_export_nonce'] ) ), 'bikepress_export_data' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	$doc  = BikePress_Import_Export::build_export_document();
	$json = wp_json_encode( $doc, JSON_PRETTY_PRINT );
	if ( false === $json ) {
		wp_die( esc_html__( 'Could not build export file.', 'bikepress' ) );
	}

	$filename = 'bikepress-export-' . gmdate( 'Y-m-d-His' ) . '.json';

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'Content-Length: ' . strlen( $json ) );
	echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- raw JSON download.
	exit;
}

/**
 * Import a BikePress JSON export (id-based upsert).
 */
function bikepress_handle_import_data() {
	if ( ! isset( $_POST['bikepress_import_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bikepress_import_nonce'] ) ), 'bikepress_import_data' ) ) {
		wp_die( esc_html__( 'Security check failed', 'bikepress' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bikepress' ) );
	}

	if ( empty( $_FILES['bikepress_import_file']['tmp_name'] ) || ! is_uploaded_file( $_FILES['bikepress_import_file']['tmp_name'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		bikepress_redirect_admin_page( 'import-export-admin', 'import_upload_error' );
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$raw = file_get_contents( $_FILES['bikepress_import_file']['tmp_name'] );
	if ( false === $raw || '' === $raw ) {
		bikepress_redirect_admin_page( 'import-export-admin', 'import_upload_error' );
	}

	$doc = json_decode( $raw, true );
	if ( null === $doc && JSON_ERROR_NONE !== json_last_error() ) {
		bikepress_redirect_admin_page( 'import-export-admin', 'import_invalid_json' );
	}

	$result = BikePress_Import_Export::import_document( $doc );
	if ( is_wp_error( $result ) ) {
		$code = $result->get_error_code();
		$map  = array(
			'invalid_json'       => 'import_invalid_json',
			'invalid_format'     => 'import_invalid_format',
			'db_version_mismatch' => 'import_db_mismatch',
			'missing_data'       => 'import_missing_data',
		);
		$notice = isset( $map[ $code ] ) ? $map[ $code ] : 'import_error';
		set_transient(
			'bikepress_import_flash_' . get_current_user_id(),
			array(
				'notice'  => $notice,
				'message' => $result->get_error_message(),
			),
			60
		);
		bikepress_redirect_admin_page( 'import-export-admin', $notice );
	}

	set_transient(
		'bikepress_import_flash_' . get_current_user_id(),
		array(
			'notice' => 'import_ok',
			'stats'  => $result,
		),
		60
	);
	bikepress_redirect_admin_page( 'import-export-admin', 'import_ok' );
}

/**
 * Admin: My Bikes hub.
 */
function my_bikes() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/my-bikes-page.php';
}

/**
 * Admin: manage bikes CRUD.
 */
function bikes_admin() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/bikes-admin-page.php';
}

/**
 * Admin: specs CRUD.
 */
function specs_admin() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/specs-admin-page.php';
}

/**
 * Admin: maintenance CRUD.
 */
function maint_admin() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/maint-admin-page.php';
}

/**
 * Admin: Supporting Data hub.
 */
function supporting_data_admin() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/supporting-data-admin-page.php';
}

/**
 * Admin: statuses CRUD (hub-only; not shown in left submenu).
 */
function status_admin() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/status-admin-page.php';
}

/**
 * Admin: import / export (hub-only).
 */
function import_export_admin() {
	include plugin_dir_path( __FILE__ ) . 'admin/partials/import-export-admin-page.php';
}

/**
 * Redirect legacy Manage Data slug to Supporting Data hub.
 */
function bikepress_legacy_data_admin_redirect() {
	wp_safe_redirect( admin_url( 'admin.php?page=supporting-data-admin' ) );
	exit;
}

/**
 * Activation callback.
 */
function activate_bikepress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bikepress-activator.php';
	BikePress_Activator::activate();
}

/**
 * Deactivation callback.
 */
function deactivate_bikepress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bikepress-deactivator.php';
	BikePress_Deactivator::deactivate();
}

/**
 * Shortcode: interactive bike list.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function bikepress_bike_list( $atts ) {
	global $wpdb;

	$bike_plugin_url = plugin_dir_url( __FILE__ );
	$bikes_table     = BIKES_TABLE;
	$status_table    = STATUS_TABLE;
	$maint_table     = MAINTENANCE_TABLE;
	$specs_table     = SPECS_TABLE;

	// Ensure public assets load when the shortcode renders.
	wp_enqueue_style( 'bikepress' );
	wp_enqueue_script( 'bikepress' );

	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table names are trusted prefixed identifiers.
	$aBikes = $wpdb->get_results(
		"SELECT {$bikes_table}.*, {$status_table}.bike_status
		FROM {$bikes_table}
		INNER JOIN {$status_table}
			ON {$bikes_table}.bike_status_id = {$status_table}.id"
	);

	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$aMaintenance = $wpdb->get_results( "SELECT * FROM {$maint_table}" );
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$aSpecs = $wpdb->get_results( "SELECT * FROM {$specs_table}" );

	$last_bike_id = 0;
	if ( ! empty( $aBikes ) ) {
		$last_bike    = end( $aBikes );
		$last_bike_id = isset( $last_bike->id ) ? absint( $last_bike->id ) : 0;
		reset( $aBikes );
	}

	$default_img = esc_url( $bike_plugin_url . 'img/default-bike.jpg' );
	$icon_maint  = esc_url( $bike_plugin_url . 'img/icon-maint.png' );
	$icon_specs  = esc_url( $bike_plugin_url . 'img/icon-specs.png' );
	$icon_bikes  = esc_url( $bike_plugin_url . 'img/icon-bikes.png' );

	$Content  = '<section id="bike-list" class="bike-section fade-in">';
	$Content .= '<h1 class="bike-list-title">BIKES</h1>';

	foreach ( $aBikes as $oBike ) {
		$bike_id   = absint( $oBike->id );
		$bike_name = esc_html( $oBike->bike_name );
		$bike_js   = esc_js( $oBike->bike_name );

		$Content .= '<article class="bike bike-data">';

		$image_attributes = wp_get_attachment_image_src( absint( $oBike->bike_image_id ) );
		if ( $image_attributes ) {
			$Content .= sprintf(
				'<img src="%s" width="%d" height="%d" class="bike-image" alt="%s" />',
				esc_url( $image_attributes[0] ),
				absint( $image_attributes[1] ),
				absint( $image_attributes[2] ),
				$bike_name
			);
		} else {
			$Content .= '<img src="' . $default_img . '" class="bike-image" alt="' . $bike_name . '" />';
		}

		$Content .= '<div class="bike-info">';
		$Content .= '<div class="bike-header">';
		$Content .= '<h2 class="bike-title">' . $bike_name . '</h2>';
		$Content .= '<div class="icons-wrapper">';
		$Content .= '<a href="javascript:void(0);" onclick="showSection(\'maintenance\', ' . $bike_id . ', \'' . $bike_js . '\');">';
		$Content .= '<img src="' . $icon_maint . '" class="bike-icon" alt="" />';
		$Content .= '<p class="icon-subtext">Maintenance</p>';
		$Content .= '</a>';
		$Content .= '<a href="javascript:void(0);" onclick="showSection(\'specs\', ' . $bike_id . ', \'' . $bike_js . '\');">';
		$Content .= '<img src="' . $icon_specs . '" class="bike-icon" alt="" />';
		$Content .= '<p class="icon-subtext">Specs</p>';
		$Content .= '</a>';
		$Content .= '</div></div>';
		$Content .= '<div class="bike-specs">';
		$Content .= '<div class="bike-make bike-detail"><b>MAKE:</b> ' . esc_html( $oBike->bike_make ) . '</div>';
		$Content .= '<div class="bike-model bike-detail"><b>MODEL:</b> ' . esc_html( $oBike->bike_model ) . '</div>';
		$Content .= '<div class="bike-status bike-detail"><b>STATUS:</b> ' . esc_html( $oBike->bike_status ) . '</div>';
		$Content .= '</div>';
		$Content .= '<div class="bike-data"><div class="bike-desc">' . esc_html( $oBike->bike_desc ) . '</div></div>';
		$Content .= '</div></article>';
	}
	$Content .= '</section>';

	// Maintenance section.
	$Content .= '<section id="maintenance-log" class="bike-section fade-in">';
	$Content .= '<h1 class="maintenance-log-title">MAINTENANCE LOG</h1>';
	$Content .= '<h2 id="maintenance-log-bike-name"></h2>';
	$Content .= '<div class="maintenance-header bike-section-header"><div class="icons-wrapper">';
	$Content .= '<a href="javascript:void(0);" onclick="showSection(\'bikes\', 0, \'\')">';
	$Content .= '<img src="' . $icon_bikes . '" class="bike-icon" alt="" /><p class="icon-subtext">Bike List</p></a>';
	$Content .= '<a href="javascript:void(0);" onclick="showSection(\'specs\', ' . $last_bike_id . ', bikeName)" id="maint-to-specs-link">';
	$Content .= '<img src="' . $icon_specs . '" class="bike-icon" alt="" /><p class="icon-subtext">Specs</p></a>';
	$Content .= '</div></div>';

	$Content .= '<div class="maintenance-entries bike-entries">';
	$Content .= '<header class="maintenance-entry-headers entry-headers"><div class="col">DATE</div><div class="col">MAINT</div><div class="col">MILES</div></header>';

	foreach ( $aBikes as $oBike ) {
		$date_array     = date_parse( $oBike->purchase_date );
		$formatted_date = esc_html( sprintf( '%d-%d-%d', $date_array['year'], $date_array['month'], $date_array['day'] ) );
		$Content       .= '<div class="entry-record maintenance-entry row bike-' . absint( $oBike->id ) . '">';
		$Content       .= '<div class="maint-date col">' . $formatted_date . '</div>';
		$Content       .= '<div class="maint-desc col">Purchased</div>';
		$Content       .= '<div class="bike-miles col">0</div></div>';
	}

	foreach ( $aMaintenance as $oMaintRecord ) {
		$date_array     = date_parse( $oMaintRecord->maintenance_date );
		$formatted_date = esc_html( sprintf( '%d-%d-%d', $date_array['year'], $date_array['month'], $date_array['day'] ) );
		$Content       .= '<div class="entry-record maintenance-entry row bike-' . absint( $oMaintRecord->bike_id ) . '">';
		$Content       .= '<div class="maint-date col">' . $formatted_date . '</div>';
		$Content       .= '<div class="maint-desc col">' . esc_html( $oMaintRecord->maintenance_desc ) . '</div>';
		$Content       .= '<div class="bike-miles col">' . esc_html( (string) $oMaintRecord->bike_miles ) . '</div></div>';
	}

	$Content .= '<div class="no-records">NO RECORDS FOUND</div></div></section>';

	// Specs section.
	$Content .= '<section id="specs-list" class="bike-section fade-in">';
	$Content .= '<h1 class="specs-list-title">SPECIFICATIONS</h1>';
	$Content .= '<h2 id="specs-list-bike-name"></h2>';
	$Content .= '<div class="specs-header bike-section-header"><div class="icons-wrapper">';
	$Content .= '<a href="javascript:void(0);" onclick="showSection(\'bikes\', 0, \'\');">';
	$Content .= '<img src="' . $icon_bikes . '" class="bike-icon" alt="" /><p class="icon-subtext">Bike List</p></a>';
	$Content .= '<a href="javascript:void(0);" onclick="showSection(\'maintenance\', ' . $last_bike_id . ', bikeName);" id="specs-to-maint-link">';
	$Content .= '<img src="' . $icon_maint . '" class="bike-icon" alt="" /><p class="icon-subtext">Maintenance</p></a>';
	$Content .= '</div></div>';

	$Content .= '<div class="spec-entries bike-entries">';
	$Content .= '<header class="spec-entry-headers entry-headers"><div class="col">NAME</div><div class="col">DESC</div></header>';

	foreach ( $aBikes as $oBike ) {
		$Content .= '<div class="entry-record spec-entry row bike-' . absint( $oBike->id ) . '">';
		$Content .= '<div class="spec-name col">Serial Number</div>';
		$Content .= '<div class="spec-desc col">' . esc_html( $oBike->serial_number ) . '</div></div>';
	}

	foreach ( $aSpecs as $oSpecRecord ) {
		$Content .= '<div class="entry-record spec-entry row bike-' . absint( $oSpecRecord->bike_id ) . '">';
		$Content .= '<div class="spec-name col">' . esc_html( $oSpecRecord->spec_name ) . '</div>';
		$Content .= '<div class="spec-desc col">' . esc_html( $oSpecRecord->spec_desc ) . '</div></div>';
	}

	$Content .= '</div><div class="no-records">NO RECORDS FOUND</div></section>';

	return $Content;
}

add_shortcode( 'bikepress-bike-list', 'bikepress_bike_list' );

register_activation_hook( __FILE__, 'activate_bikepress' );
register_deactivation_hook( __FILE__, 'deactivate_bikepress' );

require plugin_dir_path( __FILE__ ) . 'includes/class-bikepress.php';

/**
 * Boot the plugin.
 */
function run_bikepress() {
	$plugin = new BikePress();
	$plugin->run();
}

run_bikepress();
